<script>
    async function logout() {
        await fetch("/logout", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
        });
        window.location.href = "/login";
    }
</script>

<div
    class="min-h-screen bg-[var(--color-bg)] p-6 lg:p-10 font-[var(--font-sans)]"
>
    <!-- HEADER -->
    <div
        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10 gap-4 animate-fade-in"
    >
        <div>
            <p
                class="text-xs font-semibold text-[var(--color-primary)] uppercase tracking-widest mb-1 flex items-center gap-1.5"
            >
                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><path
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                    /></svg
                >
                Admin Portal
            </p>
            <h1
                class="text-2xl lg:text-3xl font-bold text-[var(--color-text-primary)]"
            >
                Dashboard Admin
            </h1>
            <p class="text-sm text-[var(--color-text-muted)] mt-1">
                Pilih menu pengelolaan sistem
            </p>
        </div>
        <button
            on:click={logout}
            class="flex items-center gap-2 bg-[var(--color-error)] hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors cursor-pointer border-0 shadow-sm"
        >
            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                stroke-width="2"
                ><path
                    d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"
                /></svg
            >
            Logout
        </button>
    </div>

    <!-- MENU -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 stagger">
        <!-- DOSEN -->
        <a
            href="/admin/dosen"
            class="bg-white rounded-2xl border border-[var(--color-border)] shadow-[var(--shadow-sm)] p-8 hover:shadow-[var(--shadow-lg)] hover:border-[var(--color-primary)]/20 transition-all duration-300 group animate-slide-up"
        >
            <div
                class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mb-6 group-hover:bg-blue-100 transition-colors"
            >
                <svg
                    class="w-7 h-7 text-blue-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2" /><circle
                        cx="12"
                        cy="7"
                        r="4"
                    /></svg
                >
            </div>
            <h2 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">
                Kelola Dosen
            </h2>
            <p
                class="text-sm text-[var(--color-text-muted)] leading-relaxed mb-6"
            >
                Tambah dosen, atur NIDN, dan kelola mata kuliah yang diampu.
            </p>
            <span
                class="inline-flex items-center gap-1.5 bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg text-xs font-semibold group-hover:bg-[var(--color-primary-hover)] transition-colors"
            >
                Masuk Menu Dosen
                <svg
                    class="w-3.5 h-3.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="2"><path d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg
                >
            </span>
        </a>

        <!-- MAHASISWA -->
        <a
            href="/admin/mahasiswa"
            class="bg-white rounded-2xl border border-[var(--color-border)] shadow-[var(--shadow-sm)] p-8 hover:shadow-[var(--shadow-lg)] hover:border-[var(--color-success)]/20 transition-all duration-300 group animate-slide-up"
        >
            <div
                class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mb-6 group-hover:bg-emerald-100 transition-colors"
            >
                <svg
                    class="w-7 h-7 text-emerald-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle
                        cx="9"
                        cy="7"
                        r="4"
                    /><path
                        d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                    /></svg
                >
            </div>
            <h2 class="text-xl font-bold text-[var(--color-text-primary)] mb-2">
                Kelola Mahasiswa
            </h2>
            <p
                class="text-sm text-[var(--color-text-muted)] leading-relaxed mb-6"
            >
                Tambah mahasiswa, hapus mahasiswa, dan kelola data akademik
                mahasiswa.
            </p>
            <span
                class="inline-flex items-center gap-1.5 bg-[var(--color-success)] text-white px-4 py-2 rounded-lg text-xs font-semibold group-hover:bg-emerald-700 transition-colors"
            >
                Masuk Menu Mahasiswa
                <svg
                    class="w-3.5 h-3.5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="2"><path d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg
                >
            </span>
        </a>
    </div>
</div>
