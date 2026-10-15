<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Eye, EyeOff, Lock, Mail } from 'lucide-vue-next';
import { computed, ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const showPassword = ref(false);

const title = computed(() => 'Masuk Admin');
</script>

<template>
    <Head :title="title" />

    <div class="fixed inset-0 bg-center bg-cover bg-[url('/public/storage/images/stm1.png')]">
        <span class="absolute inset-0 bg-gradient-to-tl from-zinc-800 to-zinc-700 opacity-60"></span>
    </div>

    <main class="relative min-h-screen overflow-hidden bg-transparent flex flex-col items-center justify-center px-4">
        <section class="w-full flex flex-col items-center justify-center flex-1">
            <div class="w-full max-w-lg mb-12 text-center text-white">
                <h1 class="text-4xl font-black mb-3">Selamat Datang STEMDA</h1>
                <p class="text-lg">Gunakan akun admin untuk mengelola konten website secara cepat dan aman.</p>
            </div>

            <div class="w-full max-w-lg">
                <div class="relative login-card-anim">
                    <div aria-hidden="true" class="login-glow pointer-events-none absolute -inset-1 rounded-3xl opacity-70 blur-2xl"></div>
                    <div class="relative z-10 flex flex-col min-w-0 break-words bg-white/95 border-0 shadow-xl rounded-2xl bg-clip-border text-slate-700 ring-1 ring-black/5 backdrop-blur-sm">
                        <div class="p-6 mb-0 text-center bg-transparent border-b-0 rounded-t-2xl">
                            <h5 class="mb-1 text-slate-800 text-xl font-extrabold tracking-tight">Login Admin</h5>
                            <p class="mb-0 text-sm text-slate-500">Masuk untuk mengelola konten website sekolah.</p>
                        </div>
                        <div class="flex-auto p-6 pt-4">
                            <form role="form text-left" @submit.prevent="submit">
                                <div class="mb-4">
                                    <label for="email" class="block mb-2 text-sm font-semibold text-slate-600">Email</label>
                                    <div class="relative">
                                        <Mail class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                                        <input
                                            id="email"
                                            type="email"
                                            v-model="form.email"
                                            required
                                            autofocus
                                            autocomplete="username"
                                            class="w-full rounded-lg border border-solid border-gray-300 bg-white py-3 pr-3 pl-10 text-sm text-slate-700 transition-all duration-150 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                            placeholder="admin@smkm2genteng.sch.id"
                                        />
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>
                                <div class="mb-4">
                                    <label for="password" class="block mb-2 text-sm font-semibold text-slate-600">Password</label>
                                    <div class="relative">
                                        <Lock class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" />
                                        <input
                                            id="password"
                                            :type="showPassword ? 'text' : 'password'"
                                            v-model="form.password"
                                            required
                                            autocomplete="current-password"
                                            class="w-full rounded-lg border border-solid border-gray-300 bg-white py-3 pr-10 pl-10 text-sm text-slate-700 transition-all duration-150 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                            placeholder="••••••••"
                                        />
                                        <button
                                            type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                                            :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                                            @click="showPassword = !showPassword"
                                        >
                                            <EyeOff v-if="showPassword" class="h-4 w-4" />
                                            <Eye v-else class="h-4 w-4" />
                                        </button>
                                    </div>
                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>
                                <div class="flex items-center justify-between mb-4">
                                    <label class="flex items-center gap-2 text-sm text-slate-600">
                                        <Checkbox name="remember" v-model:checked="form.remember" />
                                        Ingat saya
                                    </label>
                                </div>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="inline-block w-full px-5 py-3.5 mb-2 font-bold text-white uppercase transition-all duration-150 ease-in-out rounded-lg bg-gradient-to-tl from-zinc-900 to-zinc-700 hover:-translate-y-px hover:shadow-lg active:translate-y-0 disabled:opacity-70 disabled:hover:translate-y-0"
                                >
                                    Masuk Admin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</template>

<style scoped>
.login-card-anim {
    animation: loginCardIn 700ms cubic-bezier(0.2, 0.9, 0.2, 1) both,
        loginCardFloat 7s ease-in-out 900ms infinite;
    transform-origin: 50% 70%;
}

.login-glow {
    background: radial-gradient(60% 60% at 30% 20%, rgba(59, 130, 246, 0.55), transparent 60%),
        radial-gradient(60% 60% at 70% 30%, rgba(139, 92, 246, 0.45), transparent 60%),
        radial-gradient(60% 60% at 55% 80%, rgba(244, 63, 94, 0.35), transparent 60%);
    animation: loginGlow 10s ease-in-out infinite;
}

@keyframes loginCardIn {
    from {
        opacity: 0;
        transform: translateY(18px) scale(0.985);
        filter: saturate(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: saturate(1);
    }
}

@keyframes loginCardFloat {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-6px);
    }
}

@keyframes loginGlow {
    0%,
    100% {
        transform: translate3d(0, 0, 0) scale(1);
        opacity: 0.7;
    }
    50% {
        transform: translate3d(12px, -10px, 0) scale(1.05);
        opacity: 0.9;
    }
}

@media (prefers-reduced-motion: reduce) {
    .login-card-anim,
    .login-glow {
        animation: none;
    }
}
</style>
