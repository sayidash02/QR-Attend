<script>
    export let mahasiswa = [];
    let sidebarOpen = false;
    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
    }
</script>

<div class="min-h-screen bg-[var(--color-bg)] flex font-[var(--font-sans)]">
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

    <aside
        class="fixed lg:sticky top-0 left-0 h-screen w-72 bg-white border-r border-[var(--color-border)] p-6 flex flex-col z-50 transition-transform duration-300 ease-in-out {sidebarOpen
            ? 'translate-x-0'
            : '-translate-x-full'} lg:translate-x-0"
    >
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
                class="flex items-center gap-3 text-[var(--color-text-secondary)] hover:bg-slate-50 px-4 py-3 rounded-xl text-sm transition"
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
                class="flex items-center gap-3 bg-[var(--color-primary-light)] text-[var(--color-primary)] px-4 py-3 rounded-xl font-semibold text-sm transition"
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
                    /><path
                        d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
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
    </aside>

    <main class="flex-1 p-6 lg:p-10 overflow-auto min-w-0">
        <div class="flex items-center gap-3 mb-8 animate-fade-in">
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
                    stroke-width="2"><path d="M4 6h16M4 12h16M4 18h16" /></svg
                >
            </button>
            <div>
                <h1
                    class="text-2xl lg:text-3xl font-bold text-[var(--color-text-primary)]"
                >
                    Data Mahasiswa
                </h1>
                <p class="text-sm text-[var(--color-text-muted)] mt-0.5">
                    Daftar mahasiswa di kelas Anda
                </p>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl shadow-[var(--shadow-sm)] border border-[var(--color-border)] animate-slide-up"
        >
            <div class="p-6 lg:p-8">
                <h2
                    class="text-lg font-bold text-[var(--color-text-primary)] mb-5"
                >
                    Daftar Mahasiswa
                    <span
                        class="text-sm font-normal text-[var(--color-text-muted)] ml-2"
                        >({mahasiswa.length})</span
                    >
                </h2>

                {#if mahasiswa.length === 0}
                    <div class="text-center py-12">
                        <svg
                            class="w-12 h-12 mx-auto mb-3 text-slate-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1"
                            ><path
                                d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"
                            /><circle cx="9" cy="7" r="4" /></svg
                        >
                        <p class="text-[var(--color-text-muted)] text-sm">
                            Belum ada data mahasiswa
                        </p>
                    </div>
                {:else}
                    <div
                        class="overflow-x-auto rounded-xl border border-[var(--color-border)]"
                    >
                        <table class="w-full">
                            <thead>
                                <tr
                                    class="bg-[var(--color-bg)] border-b border-[var(--color-border)]"
                                >
                                    <th
                                        class="p-3.5 text-left text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >No</th
                                    >
                                    <th
                                        class="p-3.5 text-left text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >NIM</th
                                    >
                                    <th
                                        class="p-3.5 text-left text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >Nama</th
                                    >
                                    <th
                                        class="p-3.5 text-left text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >Email</th
                                    >
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-[var(--color-border-light)]"
                            >
                                {#each mahasiswa as m, index}
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td
                                            class="p-3.5 text-sm text-[var(--color-text-muted)]"
                                            >{index + 1}</td
                                        >
                                        <td
                                            class="p-3.5 text-sm font-medium text-[var(--color-text-primary)]"
                                            >{m.nim}</td
                                        >
                                        <td
                                            class="p-3.5 text-sm text-[var(--color-text-secondary)]"
                                            >{m.name}</td
                                        >
                                        <td
                                            class="p-3.5 text-sm text-[var(--color-text-muted)]"
                                            >{m.email}</td
                                        >
                                    </tr>
                                {/each}
                            </tbody>
                        </table>
                    </div>
                {/if}
            </div>
        </div>
    </main>
</div>
