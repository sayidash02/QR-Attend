<script>
    import QRCode from "qrcode";
    import { onMount } from "svelte";

    export let user;
    export let activeSesi = null;
    export let totalMahasiswa = 0;
    export let totalSesi = 0;
    export let kehadiranRate = 95;

    let qrDataUrl = "";
    let sidebarOpen = false;

    async function generateQR(token) {
        qrDataUrl = await QRCode.toDataURL(token, {
            width: 256,
            margin: 2,
            color: {
                dark: "#0A3068",
                light: "#FFFFFF",
            },
        });
    }

    onMount(async () => {
        if (activeSesi) {
            await generateQR(activeSesi.token);
        }
    });

    async function logout() {
        await fetch("/logout", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "Content-Type": "application/json",
            },
        });
        window.location.href = "/login";
    }

    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
    }
</script>

<div class="min-h-screen bg-[var(--color-bg)] flex font-[var(--font-sans)]">
    <!-- MOBILE OVERLAY -->
    {#if sidebarOpen}
        <div
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden"
            on:click={toggleSidebar}
            on:keydown={(e) => e.key === "Escape" && toggleSidebar()}
            role="button"
            tabindex="-1"
            aria-label="Tutup sidebar"
        ></div>
    {/if}

    <!-- SIDEBAR -->
    <aside
        class="
        fixed lg:sticky top-0 left-0 h-screen w-72 bg-white border-r border-[var(--color-border)] p-6 flex flex-col z-50
        transition-transform duration-300 ease-in-out
        {sidebarOpen ? 'translate-x-0' : '-translate-x-full'} lg:translate-x-0
    "
    >
        <!-- Close button mobile -->
        <button
            class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-slate-100 text-slate-500 cursor-pointer border-0 bg-transparent"
            on:click={toggleSidebar}
            aria-label="Tutup sidebar"
        >
            <svg
                class="w-5 h-5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                stroke-width="2"><path d="M6 18L18 6M6 6l12 12" /></svg
            >
        </button>

        <div class="mb-10">
            <h1 class="text-2xl font-bold text-[var(--color-primary)]">
                QR-Attend
            </h1>
            <p class="text-xs text-[var(--color-text-muted)] mt-1">
                Panel Dosen
            </p>
        </div>

        <nav class="space-y-1.5 flex-1">
            <a
                href="/dosen/dashboard"
                class="flex items-center gap-3 bg-[var(--color-primary-light)] text-[var(--color-primary)] px-4 py-3 rounded-xl font-semibold text-sm transition"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><rect x="3" y="3" width="7" height="7" rx="1" /><rect
                        x="14"
                        y="3"
                        width="7"
                        height="7"
                        rx="1"
                    /><rect x="3" y="14" width="7" height="7" rx="1" /><rect
                        x="14"
                        y="14"
                        width="7"
                        height="7"
                        rx="1"
                    /></svg
                >
                Dashboard
            </a>
            <a
                href="/mahasiswa"
                class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" /><circle
                        cx="9"
                        cy="7"
                        r="4"
                    /><path d="M23 21v-2a4 4 0 00-3-3.87" /><path
                        d="M16 3.13a4 4 0 010 7.75"
                    /></svg
                >
                Data Mahasiswa
            </a>
            <a
                href="/presensi"
                class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><rect x="3" y="3" width="18" height="18" rx="2" /><path
                        d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"
                    /></svg
                >
                Sesi Presensi
            </a>
            <a
                href="/rekap"
                class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                    /></svg
                >
                Rekap Kehadiran
            </a>
        </nav>

        <div class="space-y-3">
            <a
                href="/akun"
                class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><circle cx="12" cy="12" r="3" /><path
                        d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z"
                    /></svg
                >
                Pengaturan
            </a>
            <button
                on:click={logout}
                class="w-full flex items-center gap-3 text-[var(--color-error)] hover:bg-[var(--color-error-light)] px-4 py-3 rounded-xl text-sm font-medium transition cursor-pointer border-0 bg-transparent"
            >
                <svg
                    class="w-5 h-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    ><path
                        d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"
                    /></svg
                >
                Logout
            </button>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6 lg:p-10 overflow-auto min-w-0">
        <!-- MOBILE TOPBAR -->
        <div class="flex justify-between items-center mb-8 animate-fade-in">
            <div class="flex items-center gap-3">
                <button
                    class="lg:hidden p-2 rounded-xl hover:bg-slate-100 text-[var(--color-text-secondary)] cursor-pointer border-0 bg-transparent"
                    on:click={toggleSidebar}
                    aria-label="Buka sidebar"
                >
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        ><path d="M4 6h16M4 12h16M4 18h16" /></svg
                    >
                </button>
                <div>
                    <h1
                        class="text-2xl lg:text-3xl font-bold text-[var(--color-text-primary)]"
                    >
                        Dashboard Dosen
                    </h1>
                    <p class="text-sm text-[var(--color-text-muted)] mt-0.5">
                        Kelola presensi mahasiswa dan sesi QR.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <h2
                        class="font-semibold text-[var(--color-text-primary)] text-sm"
                    >
                        {user.name}
                    </h2>
                    <p
                        class="text-xs text-[var(--color-text-muted)] capitalize"
                    >
                        {user.role}
                    </p>
                </div>
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-muted)] flex items-center justify-center text-white font-bold text-sm"
                >
                    {user.name.charAt(0).toUpperCase()}
                </div>
            </div>
        </div>

        <!-- HERO / SESI AKTIF -->
        <div
            class="bg-white rounded-2xl shadow-[var(--shadow-sm)] p-6 lg:p-10 mb-8 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8 border border-[var(--color-border)] animate-slide-up"
        >
            <div class="flex-1 min-w-0">
                {#if activeSesi}
                    <div class="flex items-center gap-3 mb-5">
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"
                            ></span>
                            <span
                                class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"
                            ></span>
                        </span>
                        <span
                            class="bg-[var(--color-success-light)] text-[var(--color-success)] px-3 py-1 rounded-lg text-xs font-semibold"
                        >
                            Sesi Sedang Berjalan
                        </span>
                    </div>

                    <h2
                        class="text-2xl lg:text-3xl font-bold text-[var(--color-text-primary)] mb-3"
                    >
                        {activeSesi.mata_kuliah?.nama_matkul || "Mata Kuliah"}
                    </h2>

                    <p
                        class="text-[var(--color-text-secondary)] text-sm leading-relaxed mb-6 max-w-lg"
                    >
                        Dosen sedang membuka sesi presensi secara realtime.
                        Mahasiswa dapat memindai QR Code untuk mencatat
                        kehadiran.
                    </p>

                    <a
                        href="/presensi"
                        class="inline-flex items-center gap-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-200 shadow-sm hover:shadow-md"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            ><rect
                                x="3"
                                y="3"
                                width="18"
                                height="18"
                                rx="2"
                            /><path
                                d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"
                            /></svg
                        >
                        Kelola Sesi Presensi
                    </a>
                {:else}
                    <span
                        class="bg-slate-100 text-[var(--color-text-muted)] px-3 py-1 rounded-lg text-xs font-semibold"
                    >
                        Sesi Tidak Aktif
                    </span>

                    <h2
                        class="text-2xl lg:text-3xl font-bold text-[var(--color-text-primary)] mt-4 mb-3"
                    >
                        Belum Ada Sesi Aktif
                    </h2>

                    <p
                        class="text-[var(--color-text-secondary)] text-sm leading-relaxed mb-6 max-w-lg"
                    >
                        Mulai kelas Anda dengan membuat QR Code presensi
                        mahasiswa secara realtime.
                    </p>

                    <a
                        href="/presensi"
                        class="inline-flex items-center gap-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white px-6 py-3 rounded-xl font-semibold text-sm transition-all duration-200 shadow-sm hover:shadow-md"
                    >
                        <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="2"><path d="M12 4v16m8-8H4" /></svg
                        >
                        Buka Sesi Baru
                    </a>
                {/if}
            </div>

            <div class="flex flex-col items-center gap-4 shrink-0">
                {#if activeSesi && qrDataUrl}
                    <div
                        class="bg-white p-3 rounded-2xl shadow-[var(--shadow-lg)] border-2 border-[var(--color-primary)]/20"
                    >
                        <img
                            src={qrDataUrl}
                            alt="QR Code Presensi"
                            class="w-48 h-48 lg:w-56 lg:h-56"
                        />
                    </div>
                {:else}
                    <div
                        class="w-48 h-48 lg:w-56 lg:h-56 border-2 border-dashed border-[var(--color-border)] rounded-2xl flex items-center justify-center text-center text-[var(--color-text-muted)] text-sm bg-[var(--color-bg)]"
                    >
                        <div>
                            <svg
                                class="w-10 h-10 mx-auto mb-2 opacity-30"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="1"
                                ><rect
                                    x="3"
                                    y="3"
                                    width="18"
                                    height="18"
                                    rx="2"
                                /><path
                                    d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"
                                /></svg
                            >
                            QR Code
                        </div>
                    </div>
                {/if}
            </div>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 stagger">
            <div
                class="bg-white p-6 rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] animate-slide-up hover:shadow-[var(--shadow-md)] transition-shadow duration-200"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-4"
                >
                    <svg
                        class="w-5 h-5 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        ><path
                            d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"
                        /><circle cx="9" cy="7" r="4" /><path
                            d="M23 21v-2a4 4 0 00-3-3.87"
                        /><path d="M16 3.13a4 4 0 010 7.75" /></svg
                    >
                </div>
                <h3
                    class="text-3xl font-bold text-[var(--color-text-primary)] mb-1"
                >
                    {totalMahasiswa}
                </h3>
                <p class="text-sm text-[var(--color-text-muted)]">
                    Total Mahasiswa
                </p>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] animate-slide-up hover:shadow-[var(--shadow-md)] transition-shadow duration-200"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center mb-4"
                >
                    <svg
                        class="w-5 h-5 text-purple-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        ><rect x="3" y="3" width="18" height="18" rx="2" /><path
                            d="M7 7h.01M7 12h.01M7 17h.01M12 7h.01M12 12h.01M12 17h.01M17 7h.01M17 12h.01M17 17h.01"
                        /></svg
                    >
                </div>
                <h3
                    class="text-3xl font-bold text-[var(--color-text-primary)] mb-1"
                >
                    {totalSesi}
                </h3>
                <p class="text-sm text-[var(--color-text-muted)]">
                    Total Sesi Kelas
                </p>
            </div>

            <div
                class="bg-white p-6 rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] animate-slide-up hover:shadow-[var(--shadow-md)] transition-shadow duration-200"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center mb-4"
                >
                    <svg
                        class="w-5 h-5 text-emerald-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        ><path
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                        /></svg
                    >
                </div>
                <h3
                    class="text-3xl font-bold text-[var(--color-text-primary)] mb-1"
                >
                    {kehadiranRate}%
                </h3>
                <p class="text-sm text-[var(--color-text-muted)]">
                    Rata-rata Kehadiran
                </p>
            </div>
        </div>
    </main>
</div>
