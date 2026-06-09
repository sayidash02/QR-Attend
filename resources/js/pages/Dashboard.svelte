<script>
    import { onMount } from 'svelte'

    export let user
    export let kehadirans = []

    let totalHadir = 0
    let totalTelat = 0
    let totalIzin = 0
    let totalAlpha = 0
    let sidebarOpen = false

    async function logout() {
        await fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
        window.location.href = '/login'
    }

    onMount(() => {
        if (Array.isArray(kehadirans)) {
            totalHadir = kehadirans.filter(k => k.status === 'hadir').length
            totalTelat = kehadirans.filter(k => k.status === 'telat').length
            totalIzin = kehadirans.filter(k => k.status === 'izin').length
            totalAlpha = kehadirans.filter(k => k.status === 'alpha').length
        }
    })

    function toggleSidebar() { sidebarOpen = !sidebarOpen }
</script>

<div class="min-h-screen bg-[var(--color-bg)] flex font-[var(--font-sans)]">

    {#if sidebarOpen}
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden" on:click={toggleSidebar} on:keydown={(e) => e.key === 'Escape' && toggleSidebar()} role="button" tabindex="-1" aria-label="Tutup sidebar"></div>
    {/if}

    <aside class="fixed lg:sticky top-0 left-0 h-screen w-72 bg-white border-r border-[var(--color-border)] p-6 flex flex-col z-50 transition-transform duration-300 ease-in-out {sidebarOpen ? 'translate-x-0' : '-translate-x-full'} lg:translate-x-0">
        <button class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-slate-100 text-slate-500 cursor-pointer border-0 bg-transparent" on:click={toggleSidebar} aria-label="Tutup sidebar">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="mb-10">
            <h1 class="text-2xl font-bold text-[var(--color-primary)]">QR-Attend</h1>
            <p class="text-xs text-[var(--color-text-muted)] mt-1">Panel Mahasiswa</p>
        </div>
        <nav class="space-y-1.5 flex-1">
            <a href="/mahasiswa/dashboard" class="flex items-center gap-3 bg-[var(--color-primary-light)] text-[var(--color-primary)] px-4 py-3 rounded-xl font-semibold text-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                Dashboard
            </a>
            <a href="/presensi" class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M15 10l-4 4l6 6l4-16l-18 7l4 2l2 6l3-4"/></svg>
                Scan Presensi
            </a>
            <a href="/riwayat-absensi" class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Riwayat Absensi
            </a>
        </nav>
        <div class="space-y-3">
            <a href="/akun" class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
                Pengaturan
            </a>
            <button on:click={logout} class="w-full flex items-center gap-3 text-[var(--color-error)] hover:bg-[var(--color-error-light)] px-4 py-3 rounded-xl text-sm font-medium transition cursor-pointer border-0 bg-transparent">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                Logout
            </button>
        </div>
    </aside>

    <main class="flex-1 p-6 lg:p-10 overflow-auto min-w-0">

        <div class="flex justify-between items-center mb-8 animate-fade-in">
            <div class="flex items-center gap-3">
                <button class="lg:hidden p-2 rounded-xl hover:bg-slate-100 text-[var(--color-text-secondary)] cursor-pointer border-0 bg-transparent" on:click={toggleSidebar} aria-label="Buka sidebar">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-2xl lg:text-3xl font-bold text-[var(--color-text-primary)]">Dashboard Mahasiswa</h1>
                    <p class="text-sm text-[var(--color-text-muted)] mt-0.5">Pantau kehadiran Anda dan scan QR Code kelas.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <h2 class="font-semibold text-[var(--color-text-primary)] text-sm">{user.name}</h2>
                    <p class="text-xs text-[var(--color-text-muted)]">{user.nim}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-muted)] flex items-center justify-center text-white font-bold text-sm">
                    {user.name.charAt(0).toUpperCase()}
                </div>
            </div>
        </div>

        <!-- PROFILE CARD -->
        <div class="bg-white rounded-2xl shadow-[var(--shadow-sm)] p-6 lg:p-8 mb-8 border border-[var(--color-border)] animate-slide-up">
            <div class="flex items-center gap-5">
                <div class="w-16 h-16 lg:w-20 lg:h-20 bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-muted)] rounded-2xl flex items-center justify-center text-3xl lg:text-4xl text-white font-bold shrink-0">
                    {user.name.charAt(0).toUpperCase()}
                </div>
                <div class="min-w-0">
                    <h2 class="text-xl lg:text-2xl font-bold text-[var(--color-text-primary)] mb-1">{user.name}</h2>
                    <p class="text-sm text-[var(--color-text-secondary)]"><span class="font-semibold">NIM:</span> {user.nim || '-'}</p>
                    <p class="text-sm text-[var(--color-text-secondary)]"><span class="font-semibold">Email:</span> {user.email}</p>
                </div>
            </div>
        </div>

        <!-- ACTIONS -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 stagger">
            <a href="/presensi" class="bg-white rounded-2xl shadow-[var(--shadow-sm)] p-6 border border-[var(--color-border)] hover:shadow-[var(--shadow-md)] hover:border-[var(--color-primary)]/30 transition-all duration-200 group animate-slide-up">
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4 group-hover:bg-blue-100 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[var(--color-text-primary)] mb-1">Scan Presensi</h3>
                <p class="text-sm text-[var(--color-text-muted)] leading-relaxed mb-4">Arahkan kamera ke QR Code untuk mencatat kehadiran.</p>
                <span class="inline-flex items-center gap-1.5 bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg text-xs font-semibold group-hover:bg-[var(--color-primary-hover)] transition-colors">
                    Buka Scanner
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </span>
            </a>

            <a href="/riwayat-absensi" class="bg-white rounded-2xl shadow-[var(--shadow-sm)] p-6 border border-[var(--color-border)] hover:shadow-[var(--shadow-md)] hover:border-[var(--color-primary)]/30 transition-all duration-200 group animate-slide-up">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition-colors">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-[var(--color-text-primary)] mb-1">Riwayat Absensi</h3>
                <p class="text-sm text-[var(--color-text-muted)] leading-relaxed mb-4">Lihat riwayat lengkap kehadiran Anda di semua kelas.</p>
                <span class="inline-flex items-center gap-1.5 bg-[var(--color-primary)] text-white px-4 py-2 rounded-lg text-xs font-semibold group-hover:bg-[var(--color-primary-hover)] transition-colors">
                    Lihat Detail
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </span>
            </a>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 stagger">
            <div class="bg-white p-5 rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] animate-slide-up hover:shadow-[var(--shadow-md)] transition-shadow">
                <div class="w-9 h-9 rounded-lg bg-[var(--color-success-light)] flex items-center justify-center mb-3">
                    <svg class="w-4.5 h-4.5 text-[var(--color-success)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-[var(--color-text-primary)] mb-0.5">{totalHadir}</h3>
                <p class="text-xs text-[var(--color-text-muted)]">Hadir</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] animate-slide-up hover:shadow-[var(--shadow-md)] transition-shadow">
                <div class="w-9 h-9 rounded-lg bg-[var(--color-warning-light)] flex items-center justify-center mb-3">
                    <svg class="w-4.5 h-4.5 text-[var(--color-warning)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-[var(--color-text-primary)] mb-0.5">{totalTelat}</h3>
                <p class="text-xs text-[var(--color-text-muted)]">Telat</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] animate-slide-up hover:shadow-[var(--shadow-md)] transition-shadow">
                <div class="w-9 h-9 rounded-lg bg-[var(--color-info-light)] flex items-center justify-center mb-3">
                    <svg class="w-4.5 h-4.5 text-[var(--color-info)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-[var(--color-text-primary)] mb-0.5">{totalIzin}</h3>
                <p class="text-xs text-[var(--color-text-muted)]">Izin</p>
            </div>
            <div class="bg-white p-5 rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] animate-slide-up hover:shadow-[var(--shadow-md)] transition-shadow">
                <div class="w-9 h-9 rounded-lg bg-[var(--color-error-light)] flex items-center justify-center mb-3">
                    <svg class="w-4.5 h-4.5 text-[var(--color-error)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h3 class="text-2xl font-bold text-[var(--color-text-primary)] mb-0.5">{totalAlpha}</h3>
                <p class="text-xs text-[var(--color-text-muted)]">Alpha</p>
            </div>
        </div>

    </main>
</div>