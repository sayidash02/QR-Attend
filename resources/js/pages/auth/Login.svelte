<script>
    import { router } from "@inertiajs/svelte";
    import { toast } from "../../stores/toastStore.js";

    let email = "";
    let password = "";
    let loading = false;

    function login() {
        if (!email || !password) {
            toast.warning("Email dan password harus diisi!");
            return;
        }

        loading = true;

        router.post(
            "/login",
            {
                email,
                password,
            },
            {
                onSuccess: () => {
                    toast.success("Login berhasil! Mengarahkan...");
                },
                onError: (errors) => {
                    toast.error(errors.email || "Email atau password salah!");
                },
                onFinish: () => {
                    loading = false;
                },
            },
        );
    }
</script>

<div
    class="min-h-screen bg-[var(--color-bg)] flex overflow-hidden font-[var(--font-sans)]"
>
    <!-- LEFT BRANDING PANEL — visible on large screens -->
    <div
        class="hidden lg:flex w-1/2 bg-gradient-to-br from-[var(--color-primary)] to-[#0E4A9E] text-white items-center justify-center p-16 relative overflow-hidden"
    >
        <!-- Decorative circles -->
        <div
            class="absolute -top-24 -left-24 w-96 h-96 bg-white/5 rounded-full"
        ></div>
        <div
            class="absolute -bottom-32 -right-32 w-[500px] h-[500px] bg-white/5 rounded-full"
        ></div>
        <div
            class="absolute top-1/3 right-16 w-20 h-20 bg-white/10 rounded-2xl rotate-12"
        ></div>

        <div class="max-w-md text-center relative z-10 animate-fade-in">
            <!-- SVG Logo -->
            <div
                class="w-24 h-24 mx-auto mb-8 bg-white/10 backdrop-blur-sm rounded-3xl flex items-center justify-center border border-white/20"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-12 h-12 text-white"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <rect x="3" y="3" width="18" height="18" rx="2" />
                    <path
                        d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"
                    />
                </svg>
            </div>

            <h1 class="text-5xl font-extrabold mb-4 tracking-tight">
                QR-Attend
            </h1>

            <p class="text-lg text-white/70 leading-relaxed">
                Sistem Presensi Cerdas berbasis QR Code untuk pengelolaan
                kehadiran dosen dan mahasiswa secara modern.
            </p>

            <div
                class="mt-10 flex items-center justify-center gap-6 text-white/40 text-sm"
            >
                <span class="flex items-center gap-1.5">
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        ><path
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        /></svg
                    >
                    Aman
                </span>
                <span class="flex items-center gap-1.5">
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        ><path d="M13 10V3L4 14h7v7l9-11h-7z" /></svg
                    >
                    Realtime
                </span>
                <span class="flex items-center gap-1.5">
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        ><path
                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"
                        /></svg
                    >
                    Mobile
                </span>
            </div>
        </div>
    </div>

    <!-- RIGHT LOGIN FORM -->
    <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-md animate-slide-up">
            <!-- Mobile branding (visible only on small screens) -->
            <div class="lg:hidden text-center mb-8">
                <div
                    class="w-14 h-14 mx-auto mb-4 bg-[var(--color-primary)] rounded-2xl flex items-center justify-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7 text-white"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path
                            d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"
                        />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-[var(--color-primary)]">
                    QR-Attend
                </h2>
            </div>

            <!-- Card -->
            <div
                class="bg-white rounded-2xl shadow-[var(--shadow-lg)] border border-[var(--color-border)] p-8 sm:p-10"
            >
                <div class="mb-8">
                    <h1
                        class="text-3xl font-bold text-[var(--color-text-primary)] mb-2"
                    >
                        Masuk ke Akun
                    </h1>
                    <p class="text-[var(--color-text-secondary)] text-sm">
                        Silakan login menggunakan akun yang telah didaftarkan.
                    </p>
                </div>

                <form on:submit|preventDefault={login} class="space-y-5">
                    <!-- EMAIL -->
                    <div>
                        <label
                            for="login-email"
                            class="block text-sm font-semibold text-[var(--color-text-primary)] mb-1.5"
                        >
                            Email
                        </label>
                        <input
                            id="login-email"
                            bind:value={email}
                            type="email"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-3 text-sm text-[var(--color-text-primary)] placeholder-[var(--color-text-muted)] bg-[var(--color-bg)] transition duration-200 focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 focus:bg-white outline-none"
                        />
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label
                            for="login-password"
                            class="block text-sm font-semibold text-[var(--color-text-primary)] mb-1.5"
                        >
                            Password
                        </label>
                        <input
                            id="login-password"
                            bind:value={password}
                            type="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            class="w-full border border-[var(--color-border)] rounded-xl px-4 py-3 text-sm text-[var(--color-text-primary)] placeholder-[var(--color-text-muted)] bg-[var(--color-bg)] transition duration-200 focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 focus:bg-white outline-none"
                        />
                    </div>

                    <!-- SUBMIT -->
                    <button
                        type="submit"
                        disabled={loading}
                        class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] active:bg-[var(--color-primary-active)] text-white py-3.5 rounded-xl text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2 cursor-pointer border-0"
                    >
                        {#if loading}
                            <svg
                                class="animate-spin w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <circle
                                    class="opacity-25"
                                    cx="12"
                                    cy="12"
                                    r="10"
                                    stroke="currentColor"
                                    stroke-width="4"
                                ></circle>
                                <path
                                    class="opacity-75"
                                    fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                ></path>
                            </svg>
                            Memproses...
                        {:else}
                            Masuk
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                ><path d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg
                            >
                        {/if}
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-[var(--color-text-muted)] mt-6">
                QR-Attend SILT &middot; Svelte · Inertia · Laravel · Tailwind
            </p>
        </div>
    </div>
</div>
