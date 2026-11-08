<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SMKS Muhammadiyah 2 Genteng</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef6ff',
                            100: '#d9ebff',
                            500: '#1d68bd',
                            600: '#13519c',
                            700: '#0e3d7a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-800 font-sans min-h-screen flex items-center justify-center p-4 antialiased selection:bg-brand-500 selection:text-white">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8 text-center relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-brand-50 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-50 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-brand-50 text-brand-600 mb-6 shadow-sm border border-brand-100/60">
                @yield('icon')
            </div>

            <div class="text-xs font-black tracking-widest uppercase text-brand-600 mb-1">
                @yield('code')
            </div>

            <h1 class="text-2xl font-black text-slate-900 tracking-tight mb-3">
                @yield('heading')
            </h1>

            <p class="text-sm text-slate-500 leading-relaxed mb-8">
                @yield('message')
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold text-sm text-white bg-brand-600 hover:bg-brand-500 active:scale-95 transition-all shadow-md shadow-brand-600/20">
                    Kembali ke Beranda
                </a>
                <button onclick="window.history.back()" class="inline-flex items-center justify-center px-6 py-3 rounded-xl font-bold text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 active:scale-95 transition-all">
                    Halaman Sebelumnya
                </button>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-[11px] text-slate-400 font-medium">
            SMKS Muhammadiyah 2 Genteng (STEMDA)
        </div>
    </div>
</body>
</html>
