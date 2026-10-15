<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BackupController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:admin.backup.view')->only(['index']);
        $this->middleware('permission:admin.backup.export')->only(['export']);
        $this->middleware('permission:admin.backup.import')->only(['import']);
    }

    public function index()
    {
        return view('admin.backup.index');
    }

    public function export()
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            return redirect()->back()->with('error', 'Export SQL hanya didukung untuk database MySQL.');
        }

        $tables = $this->exportableTables();

        $filename = 'backup-admin-' . now()->format('Ymd_His') . '-' . Str::random(6) . '.sql';
        $pdo = DB::connection()->getPdo();

        Log::info('admin.backup.export', [
            'user_id' => Auth::id(),
            'email' => Auth::user()?->email,
            'ip' => request()->ip(),
            'tables_count' => count($tables),
        ]);

        return response()->streamDownload(
            function () use ($tables, $pdo) {
                $app = (string) config('app.name');
                $generatedAt = now()->toIso8601String();

                echo "-- SMK_BACKUP_SQL_V1\n";
                echo "-- App: " . str_replace(["\r", "\n"], ' ', $app) . "\n";
                echo "-- Generated at: " . $generatedAt . "\n";
                echo "-- Data-only dump (schema must exist via migrations)\n\n";

                echo "SET NAMES utf8mb4;\n";
                echo "SET FOREIGN_KEY_CHECKS=0;\n\n";

                foreach ($tables as $table) {
                    echo "TRUNCATE TABLE `" . str_replace('`', '``', $table) . "`;\n";
                }

                echo "\n";

                foreach ($tables as $table) {
                    $columns = Schema::getColumnListing($table);
                    if (count($columns) === 0) {
                        continue;
                    }

                    $escapedColumns = array_map(fn ($c) => '`' . str_replace('`', '``', $c) . '`', $columns);
                    $prefix = "INSERT INTO `" . str_replace('`', '``', $table) . "` (" . implode(',', $escapedColumns) . ") VALUES\n";

                    $rows = [];
                    $chunkSize = 200;

                    foreach (DB::table($table)->select($columns)->cursor() as $row) {
                        $row = (array) $row;

                        if ($table === 'system_information_menus' && isset($row['url']) && preg_match('#/admin/access/(users|roles)#', $row['url'])) {
                            continue;
                        }

                        $values = [];

                        foreach ($columns as $col) {
                            $values[] = $this->sqlValue($pdo, $row[$col] ?? null);
                        }

                        $rows[] = '(' . implode(',', $values) . ')';

                        if (count($rows) >= $chunkSize) {
                            echo $prefix . implode(",\n", $rows) . ";\n\n";
                            $rows = [];
                        }
                    }

                    if (count($rows) > 0) {
                        echo $prefix . implode(",\n", $rows) . ";\n\n";
                    }
                }

                echo "SET FOREIGN_KEY_CHECKS=1;\n";
            },
            $filename,
            ['Content-Type' => 'application/sql; charset=utf-8']
        );
    }

    public function import(Request $request)
    {
        $data = $request->validate([
            'backup_file' => ['required', 'file', 'max:51200'],
            'confirm' => ['required', 'string', 'in:IMPORT'],
        ]);

        $contents = file_get_contents($data['backup_file']->getRealPath());
        if ($contents === false) {
            return redirect()->back()->with('error', 'Gagal membaca file backup');
        }

        if (!str_contains(substr($contents, 0, 200), 'SMK_BACKUP_SQL_V1')) {
            return redirect()->back()->with('error', 'File backup tidak valid / bukan hasil Export Backup dari sistem ini');
        }

        if (DB::connection()->getDriverName() !== 'mysql') {
            return redirect()->back()->with('error', 'Import SQL hanya didukung untuk database MySQL.');
        }

        $allowedTables = collect($this->exportableTables())->flip();
        $statements = $this->splitSqlStatements($contents);
        if (count($statements) === 0) {
            return redirect()->back()->with('error', 'File backup tidak berisi query yang bisa dieksekusi');
        }

        $tablesToReplace = [];
        $executableStatements = [];
        $skippedSensitiveTables = [];
        $skippedUnavailableTables = [];

        foreach ($statements as $stmt) {
            $normalized = ltrim($stmt);
            if ($normalized === '') {
                continue;
            }

            $verb = strtoupper(strtok($normalized, " \t\r\n"));
            if (!in_array($verb, ['SET', 'TRUNCATE', 'INSERT'], true)) {
                return redirect()->back()->with('error', 'Import gagal: Query tidak diizinkan di file backup: ' . $verb);
            }

            if ($verb === 'SET') {
                $allowedSet = preg_match('/^SET\s+NAMES\s+utf8mb4\s*$/i', rtrim($normalized, " \t\r\n;"))
                    || preg_match('/^SET\s+FOREIGN_KEY_CHECKS\s*=\s*[01]\s*$/i', rtrim($normalized, " \t\r\n;"));

                if (!$allowedSet) {
                    return redirect()->back()->with('error', 'Import gagal: Statement SET tidak diizinkan di file backup');
                }
            }

            $table = $this->extractTableName($normalized);
            if ($table !== null && $this->isSensitiveTable($table)) {
                $skippedSensitiveTables[$table] = true;
                continue;
            }

            if ($table !== null && !$allowedTables->has($table)) {
                $skippedUnavailableTables[$table] = true;
                continue;
            }

            if ($verb === 'TRUNCATE' && $table !== null) {
                $tablesToReplace[] = $table;
                continue;
            }

            if ($verb === 'SET' && preg_match('/^SET\s+FOREIGN_KEY_CHECKS\s*=\s*[01]\s*$/i', rtrim($normalized, " \t\r\n;"))) {
                continue;
            }

            $executableStatements[] = $stmt;
        }

        if (count($tablesToReplace) === 0 && count($executableStatements) === 0) {
            return redirect()->back()->with('error', 'File backup tidak berisi tabel konten yang diizinkan untuk diimport');
        }

        DB::beginTransaction();
        try {
            Schema::disableForeignKeyConstraints();

            foreach (array_values(array_unique($tablesToReplace)) as $table) {
                DB::table($table)->delete();
            }

            foreach ($executableStatements as $stmt) {
                DB::unprepared($stmt);
            }

            Schema::enableForeignKeyConstraints();
            DB::commit();
        } catch (\Throwable $e) {
            try {
                Schema::enableForeignKeyConstraints();
            } catch (\Throwable) {
            }

            DB::rollBack();
            return redirect()->back()->with('error', 'Import gagal: ' . $e->getMessage());
        }

        Log::warning('admin.backup.import', [
            'user_id' => Auth::id(),
            'email' => Auth::user()?->email,
            'ip' => request()->ip(),
            'statements_count' => count($statements),
            'skipped_sensitive_tables' => array_values(array_keys($skippedSensitiveTables)),
            'skipped_unavailable_tables' => array_values(array_keys($skippedUnavailableTables)),
        ]);

        $successMessage = 'Backup berhasil di-import';
        if (count($skippedSensitiveTables) > 0) {
            $successMessage .= '. Tabel sensitif diabaikan: ' . implode(', ', array_keys($skippedSensitiveTables));
        }
        if (count($skippedUnavailableTables) > 0) {
            $successMessage .= '. Tabel legacy/tidak tersedia diabaikan: ' . implode(', ', array_keys($skippedUnavailableTables));
        }

        return redirect()->route('admin.backup.index')->with('success', $successMessage);
    }

    private function sqlValue(\PDO $pdo, mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return $pdo->quote($value->format('Y-m-d H:i:s'));
        }

        return $pdo->quote((string) $value);
    }

    private function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $buffer = '';
        $inSingle = false;
        $inDouble = false;
        $inBacktick = false;
        $len = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $ch = $sql[$i];
            $next = $i + 1 < $len ? $sql[$i + 1] : '';

            if (!$inSingle && !$inDouble && !$inBacktick) {
                if ($ch === '-' && $next === '-' && ($i === 0 || $sql[$i - 1] === "\n")) {
                    while ($i < $len && $sql[$i] !== "\n") {
                        $i++;
                    }
                    continue;
                }

                if ($ch === '#' && ($i === 0 || $sql[$i - 1] === "\n")) {
                    while ($i < $len && $sql[$i] !== "\n") {
                        $i++;
                    }
                    continue;
                }

                if ($ch === '/' && $next === '*') {
                    $i += 2;
                    while ($i < $len - 1) {
                        if ($sql[$i] === '*' && $sql[$i + 1] === '/') {
                            $i++;
                            break;
                        }
                        $i++;
                    }
                    continue;
                }
            }

            if ($ch === "'" && !$inDouble && !$inBacktick) {
                $escaped = $i > 0 && $sql[$i - 1] === '\\';
                if (!$escaped) {
                    $inSingle = !$inSingle;
                }
            } elseif ($ch === '"' && !$inSingle && !$inBacktick) {
                $escaped = $i > 0 && $sql[$i - 1] === '\\';
                if (!$escaped) {
                    $inDouble = !$inDouble;
                }
            } elseif ($ch === '`' && !$inSingle && !$inDouble) {
                $inBacktick = !$inBacktick;
            }

            if ($ch === ';' && !$inSingle && !$inDouble && !$inBacktick) {
                $stmt = trim($buffer);
                if ($stmt !== '') {
                    $statements[] = $stmt;
                }
                $buffer = '';
                continue;
            }

            $buffer .= $ch;
        }

        $tail = trim($buffer);
        if ($tail !== '') {
            $statements[] = $tail;
        }

        return $statements;
    }

    private function extractTableName(string $statement): ?string
    {
        $statement = ltrim($statement);

        if (preg_match('/^TRUNCATE\s+TABLE\s+`?([a-zA-Z0-9_]+)`?/i', $statement, $m)) {
            return $m[1];
        }

        if (preg_match('/^INSERT\s+INTO\s+`?([a-zA-Z0-9_]+)`?/i', $statement, $m)) {
            return $m[1];
        }

        return null;
    }

    private function exportableTables(): array
    {
        $excluded = array_merge($this->technicalExcludedTables(), $this->sensitiveTables());

        $rows = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
        $tables = [];

        foreach ($rows as $row) {
            $arr = (array) $row;
            unset($arr['Table_type']);
            $name = array_values($arr)[0] ?? null;
            if (!is_string($name) || $name === '') {
                continue;
            }

            if (in_array($name, $excluded, true)) {
                continue;
            }

            $tables[] = $name;
        }

        sort($tables);
        return $tables;
    }

    private function technicalExcludedTables(): array
    {
        return [
            'cache',
            'cache_locks',
            'failed_jobs',
            'job_batches',
            'jobs',
            'migrations',
            'password_reset_tokens',
            'sessions',
            'telescope_entries',
            'telescope_entries_tags',
            'telescope_monitoring',
        ];
    }

    private function sensitiveTables(): array
    {
        return [
            'users',
            'permissions',
            'roles',
            'model_has_permissions',
            'model_has_roles',
            'role_has_permissions',
        ];
    }

    private function isSensitiveTable(string $table): bool
    {
        return in_array($table, $this->sensitiveTables(), true);
    }
}
