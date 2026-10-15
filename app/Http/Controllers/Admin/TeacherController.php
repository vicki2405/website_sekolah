<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolOrgNode;
use App\Models\Setting;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Traits\ImageCompression;

class TeacherController extends Controller
{
    use ImageCompression;

    private function clearCache(): void
    {
        $this->forgetPublicCaches('teachers');
    }

    public function __construct()
    {
        $this->middleware('permission:admin.teachers.view')->only(['index']);
        $this->middleware('permission:admin.teachers.create')->only(['create', 'store']);
        $this->middleware('permission:admin.teachers.edit')->only([
            'edit',
            'update',
            'orgImageUpload',
            'orgImageDelete',
            'orgNodeStore',
            'orgNodeUpdate',
            'orgNodeDestroy',
            'orgAssign',
            'orgUnassign',
            'orgBootstrap',
        ]);
        $this->middleware('permission:admin.teachers.delete')->only(['destroy']);
    }

    public function orgImageUpload(Request $request)
    {
        $request->validate([
            'org_image' => [
                'required',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
        ]);

        if ($request->hasFile('org_image')) {
            $path = $this->compressAndStore($request->file('org_image'), 'uploads/organization');
            $url = '/storage/' . $path;

            Setting::updateOrCreate(
                ['key' => 'org_structure_image'],
                ['value' => $url]
            );

            $this->clearCache();

            return back()->with('success', 'Gambar struktur organisasi berhasil diupload');
        }

        return back()->with('error', 'Gagal upload gambar');
    }

    public function orgImageDelete()
    {
        $setting = Setting::where('key', 'org_structure_image')->first();
        if ($setting) {
            $this->deletePublicFileIfNeeded($setting->value);
            $setting->delete();
        }

        $this->clearCache();

        return back()->with('success', 'Gambar struktur organisasi berhasil dihapus');
    }

    public function index()
    {
        $teachers = Teacher::query()->orderBy('order', 'asc')->orderBy('id', 'asc')
            ->get(['id', 'name', 'subject', 'position', 'photo', 'category', 'order', 'is_active', 'email', 'phone', 'bio']);
        $categories = \App\Models\TeacherCategory::orderBy('order')->get(['id', 'name']);
        $orgNodes = SchoolOrgNode::query()
            ->with(['teachers' => fn ($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();
        $orgImage = Setting::where('key', 'org_structure_image')->first();

        return view('admin.teachers', [
            'teachers' => $teachers,
            'categories' => $categories,
            'orgNodes' => $orgNodes,
            'orgFlat' => $this->flattenOrgNodes($orgNodes),
            'orgImage' => $orgImage,
        ]);
    }

    public function create()
    {
        $categories = \App\Models\TeacherCategory::orderBy('order')->get();
        return view('admin.teachers.create', compact('categories'));
    }

    public function edit(Teacher $teacher)
    {
        $categories = \App\Models\TeacherCategory::orderBy('order')->get();
        return view('admin.teachers.edit', compact('teacher', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'photo_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
        ]);

        $data['subject'] = $data['subject'] ?? $data['position'];
        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($request->has('is_active') ? $request->boolean('is_active') : true);

        if ($request->hasFile('photo_file')) {
            $path = $this->compressAndStore($request->file('photo_file'), 'uploads/teachers', 60, 600);
            $data['photo'] = '/storage/'.$path;
        }

        unset($data['photo_file']);

        Teacher::create($data);
        $this->clearCache();
        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil ditambahkan');
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string'],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'photo_file' => [
                'nullable',
                'file',
                'mimetypes:image/jpeg,image/png,image/gif,image/webp',
                'max:5120',
                'dimensions:max_width=6000,max_height=6000',
            ],
        ]);

        $data['subject'] = $data['subject'] ?? ($teacher->subject ?: $data['position']);
        $data['order'] = $data['order'] ?? ($teacher->order ?? 0);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('photo_file')) {
            $this->deletePublicFileIfNeeded($teacher->photo);
            $path = $this->compressAndStore($request->file('photo_file'), 'uploads/teachers', 60, 600);
            $data['photo'] = '/storage/'.$path;
        }

        unset($data['photo_file']);

        $teacher->update($data);
        $this->clearCache();
        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy(Teacher $teacher)
    {
        $this->deletePublicFileIfNeeded($teacher->photo);
        Teacher::destroy($teacher->id);
        $this->clearCache();
        return redirect()->route('admin.teachers.index')->with('success', 'Data guru berhasil dihapus');
    }

    public function orgNodeStore(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', Rule::exists('school_org_nodes', 'id')],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        SchoolOrgNode::create($data);
        $this->clearCache();
        return redirect()->route('admin.teachers.index');
    }

    public function orgNodeUpdate(Request $request, SchoolOrgNode $node)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('school_org_nodes', 'id')->where(fn ($q) => $q->where('id', '!=', $node->id)),
            ],
            'order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['order'] = $data['order'] ?? 0;
        $data['is_active'] = (bool) ($data['is_active'] ?? false);

        $node->update($data);
        $this->clearCache();
        return redirect()->route('admin.teachers.index');
    }

    public function orgNodeDestroy(SchoolOrgNode $node)
    {
        SchoolOrgNode::query()->where('parent_id', $node->id)->update(['parent_id' => $node->parent_id]);
        $node->teachers()->detach();
        $node->delete();
        $this->clearCache();
        return redirect()->route('admin.teachers.index');
    }

    public function orgAssign(Request $request)
    {
        $data = $request->validate([
            'node_id' => ['required', 'integer', Rule::exists('school_org_nodes', 'id')],
            'teacher_id' => ['required', 'integer', Rule::exists('teachers', 'id')],
            'label' => ['nullable', 'string', 'max:255'],
            'order' => ['nullable', 'integer'],
        ]);

        $node = SchoolOrgNode::findOrFail($data['node_id']);
        $node->teachers()->syncWithoutDetaching([
            $data['teacher_id'] => [
                'label' => $data['label'] ?? null,
                'order' => $data['order'] ?? 0,
            ],
        ]);
        $this->clearCache();
        return redirect()->route('admin.teachers.index');
    }

    public function orgUnassign(SchoolOrgNode $node, Teacher $teacher)
    {
        $node->teachers()->detach($teacher->id);
        $this->clearCache();
        return redirect()->route('admin.teachers.index');
    }

    public function orgBootstrap()
    {
        $root = SchoolOrgNode::query()->firstOrCreate(
            ['title' => 'Kepala Sekolah', 'parent_id' => null],
            ['order' => 1, 'is_active' => true],
        );

        $waka = SchoolOrgNode::query()->firstOrCreate(
            ['title' => 'Wakil Kepala Sekolah', 'parent_id' => $root->id],
            ['order' => 2, 'is_active' => true],
        );

        $defaults = [
            ['title' => 'Waka Kurikulum', 'parent_id' => $waka->id, 'order' => 1],
            ['title' => 'Waka Humanos & BKK', 'parent_id' => $waka->id, 'order' => 2],
            ['title' => 'Waka Kesiswaan', 'parent_id' => $waka->id, 'order' => 3],
            ['title' => 'Tata Usaha', 'parent_id' => $root->id, 'order' => 3],
            ['title' => 'Chairman Program TKR', 'parent_id' => $root->id, 'order' => 4],
            ['title' => 'Chairman Program TBSM', 'parent_id' => $root->id, 'order' => 5],
            ['title' => 'Chairman Program TEI', 'parent_id' => $root->id, 'order' => 6],
            ['title' => 'Chairman Program Animasi', 'parent_id' => $root->id, 'order' => 7],
            ['title' => 'Chairman Program Kuliner', 'parent_id' => $root->id, 'order' => 8],
        ];

        foreach ($defaults as $d) {
            SchoolOrgNode::query()->firstOrCreate(
                ['title' => $d['title'], 'parent_id' => $d['parent_id']],
                ['order' => $d['order'], 'is_active' => true],
            );
        }

        $this->clearCache();
        return redirect()->route('admin.teachers.index');
    }

    private function deletePublicFileIfNeeded(?string $url): void
    {
        if (!$url) return;
        if (!str_starts_with($url, '/storage/')) return;

        $relative = ltrim(substr($url, strlen('/storage/')), '/');
        if ($relative !== '') {
            Storage::disk('public')->delete($relative);
        }
    }

    private function flattenOrgNodes(iterable $nodes): array
    {
        $byParent = [];
        foreach ($nodes as $n) {
            $byParent[$n->parent_id ?? 0][] = $n;
        }

        foreach ($byParent as $parentId => $children) {
            usort($children, fn ($a, $b) => ($a->order <=> $b->order) ?: ($a->id <=> $b->id));
            $byParent[$parentId] = $children;
        }

        $out = [];
        $walk = function ($parentId, $depth) use (&$walk, &$out, $byParent) {
            foreach ($byParent[$parentId] ?? [] as $node) {
                $out[] = ['node' => $node, 'depth' => $depth];
                $walk($node->id, $depth + 1);
            }
        };

        $walk(0, 0);

        return $out;
    }
}
