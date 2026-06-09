<script>
    import QRCode from "qrcode";
    import { onMount, onDestroy } from "svelte";
    import { router } from "@inertiajs/svelte";
    import { toast } from "../stores/toastStore.js";

    export let user;
    export let mataKuliahs = [];
    export let activeSesi = null;

    let selectedMatkul = "";
    let durasi = 10;
    let qrDataUrl = "";
    let countdown = "";
    let countdownInterval = null;
    let pollingInterval = null;
    let loading = false;
    let kehadiranList = activeSesi?.kehadirans || [];
    let sidebarOpen = false;

    // Mengubah string token UUID dari backend menjadi gambar QR Code Base64 menggunakan library 'qrcode'
    async function generateQR(token) {
        qrDataUrl = await QRCode.toDataURL(token, {
            width: 320,
            margin: 2,
            color: { dark: "#0A3068", light: "#FFFFFF" },
        });
    }

    function startCountdown() {
        if (!activeSesi) return;
        countdownInterval = setInterval(() => {
            const now = new Date();
            const expiry = new Date(activeSesi.expired_at);
            const diff = expiry - now;
            if (diff <= 0) {
                countdown = "00:00";
                clearInterval(countdownInterval);
                clearInterval(pollingInterval);
                activeSesi = null;
                qrDataUrl = "";
                return;
            }
            const min = Math.floor(diff / 60000);
            const sec = Math.floor((diff % 60000) / 1000);
            countdown = `${String(min).padStart(2, "0")}:${String(sec).padStart(2, "0")}`;
        }, 1000);
    }

    function startPolling() {
        if (!activeSesi) return;
        pollingInterval = setInterval(async () => {
            try {
                const res = await fetch(`/presensi/${activeSesi.id}/status`);
                const data = await res.json();
                kehadiranList = data.kehadirans || [];
                if (!data.is_active) {
                    clearInterval(pollingInterval);
                    clearInterval(countdownInterval);
                    activeSesi = null;
                    qrDataUrl = "";
                    countdown = "";
                }
            } catch (e) {
                console.error("Polling error:", e);
            }
        }, 3000);
    }

    function bukaSesi() {
        if (!selectedMatkul) {
            toast.warning("Pilih mata kuliah terlebih dahulu!");
            return;
        }
        loading = true;
        router.post(
            "/presensi",
            {
                mata_kuliah_id: selectedMatkul,
                durasi_menit: durasi,
            },
            {
                onFinish: () => {
                    loading = false;
                },
            },
        );
    }

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

    let csrfToken = "";

    onMount(async () => {
        csrfToken =
            document.querySelector('meta[name="csrf-token"]')?.content || "";
        if (activeSesi) {
            await generateQR(activeSesi.token);
            kehadiranList = activeSesi.kehadirans || [];
            startCountdown();
            startPolling();
        }
    });

    onDestroy(() => {
        if (countdownInterval) clearInterval(countdownInterval);
        if (pollingInterval) clearInterval(pollingInterval);
    });

    function toggleSidebar() {
        sidebarOpen = !sidebarOpen;
    }
</script>

<div class="min-h-screen bg-[var(--color-bg)] flex font-[var(--font-sans)]">
    <!-- MOBILE OVERLAY -->
    {#if sidebarOpen}
        <div
            class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden"
            onclick={toggleSidebar}
            onkeydown={(e) => e.key === "Escape" && toggleSidebar()}
            role="button"
            tabindex="-1"
            aria-label="Tutup sidebar"
        ></div>
    {/if}

    <!-- SIDEBAR -->
    <aside
        class="fixed lg:sticky top-0 left-0 h-screen w-72 bg-white border-r border-[var(--color-border)] p-6 flex flex-col z-50 transition-transform duration-300 ease-in-out {sidebarOpen
            ? 'translate-x-0'
            : '-translate-x-full'} lg:translate-x-0"
    >
        <button
            class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-slate-100 text-slate-500 cursor-pointer border-0 bg-transparent"
            onclick={toggleSidebar}
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
                    /><path
                        d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                    /></svg
                >
                Data Mahasiswa
            </a>
            <a
                href="/presensi"
                class="flex items-center gap-3 bg-[var(--color-primary-light)] text-[var(--color-primary)] px-4 py-3 rounded-xl font-semibold text-sm transition"
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

        <button
            onclick={logout}
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
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6 lg:p-10 overflow-auto min-w-0">
        <!-- TOPBAR -->
        <div class="flex justify-between items-center mb-8 animate-fade-in">
            <div class="flex items-center gap-3">
                <button
                    class="lg:hidden p-2 rounded-xl hover:bg-slate-100 text-[var(--color-text-secondary)] cursor-pointer border-0 bg-transparent"
                    onclick={toggleSidebar}
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
                        Sesi Presensi
                    </h1>
                    <p class="text-sm text-[var(--color-text-muted)] mt-0.5">
                        Buka sesi absensi dan tampilkan QR Code untuk mahasiswa.
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

        {#if activeSesi}
            <!-- SESI AKTIF -->
            <div
                class="bg-white rounded-2xl shadow-[var(--shadow-sm)] p-6 lg:p-10 mb-8 border border-[var(--color-border)] animate-slide-up"
            >
                <div
                    class="flex flex-col lg:flex-row items-start justify-between gap-8"
                >
                    <div class="flex-1 min-w-0">
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
                                >Sesi Aktif</span
                            >
                        </div>

                        <h2
                            class="text-2xl lg:text-3xl font-bold text-[var(--color-text-primary)] mb-3"
                        >
                            {activeSesi.mata_kuliah?.nama_matkul ||
                                "Mata Kuliah"}
                        </h2>

                        <p
                            class="text-[var(--color-text-secondary)] text-sm mb-6"
                        >
                            Minta mahasiswa memindai QR Code di samping untuk
                            mencatat kehadiran.
                        </p>

                        <!-- COUNTDOWN -->
                        <div
                            class="bg-[var(--color-bg)] rounded-xl p-5 mb-5 border border-[var(--color-border)]"
                        >
                            <p
                                class="text-[var(--color-text-muted)] text-xs mb-1"
                            >
                                Sisa Waktu
                            </p>
                            <p
                                class="text-3xl lg:text-4xl font-bold text-[var(--color-primary)] font-mono"
                            >
                                {countdown || "--:--"}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <div
                                class="bg-blue-50 rounded-xl px-5 py-3 border border-blue-100"
                            >
                                <p class="text-blue-600 text-xs">Durasi</p>
                                <p class="text-lg font-bold text-blue-700">
                                    {activeSesi.durasi_menit} menit
                                </p>
                            </div>
                            <div
                                class="bg-emerald-50 rounded-xl px-5 py-3 border border-emerald-100"
                            >
                                <p class="text-emerald-600 text-xs">
                                    Sudah Scan
                                </p>
                                <p class="text-lg font-bold text-emerald-700">
                                    {kehadiranList.length} orang
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- QR CODE -->
                    <div class="flex flex-col items-center gap-4 shrink-0">
                        {#if qrDataUrl}
                            <div
                                class="bg-white p-3 rounded-2xl shadow-[var(--shadow-lg)] border-2 border-[var(--color-primary)]/20"
                            >
                                <img
                                    src={qrDataUrl}
                                    alt="QR Code Presensi"
                                    class="w-56 h-56 lg:w-64 lg:h-64"
                                />
                            </div>
                        {:else}
                            <div
                                class="w-56 h-56 lg:w-64 lg:h-64 border-2 border-dashed border-[var(--color-border)] rounded-2xl flex items-center justify-center text-[var(--color-text-muted)] text-sm bg-[var(--color-bg)]"
                            >
                                Generating QR...
                            </div>
                        {/if}

                        <form
                            action="/presensi/{activeSesi.id}/close"
                            method="POST"
                            class="w-full"
                        >
                            <input
                                type="hidden"
                                name="_token"
                                value={csrfToken}
                            />
                            <button
                                type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-[var(--color-error)] hover:bg-red-700 text-white px-6 py-3 rounded-xl font-semibold text-sm transition-colors cursor-pointer border-0"
                            >
                                <svg
                                    class="w-4 h-4"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    ><path d="M6 18L18 6M6 6l12 12" /></svg
                                >
                                Tutup Sesi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- DAFTAR KEHADIRAN -->
            <div
                class="bg-white rounded-2xl shadow-[var(--shadow-sm)] p-6 lg:p-8 border border-[var(--color-border)] animate-slide-up"
            >
                <h3
                    class="text-lg font-bold text-[var(--color-text-primary)] mb-5"
                >
                    Daftar Kehadiran ({kehadiranList.length})
                </h3>

                {#if kehadiranList.length === 0}
                    <div class="text-center py-12">
                        <svg
                            class="w-12 h-12 mx-auto mb-3 text-slate-200"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1"
                            ><path
                                d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"
                            /><circle cx="9" cy="7" r="4" /><path
                                d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"
                            /></svg
                        >
                        <p class="text-[var(--color-text-muted)] text-sm">
                            Belum ada mahasiswa yang melakukan scan.
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
                                        class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >#</th
                                    >
                                    <th
                                        class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >Nama</th
                                    >
                                    <th
                                        class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >NIM</th
                                    >
                                    <th
                                        class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >Waktu</th
                                    >
                                    <th
                                        class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider"
                                        >Status</th
                                    >
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-[var(--color-border-light)]"
                            >
                                {#each kehadiranList as item, i}
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td
                                            class="py-3 px-4 text-sm text-[var(--color-text-muted)]"
                                            >{i + 1}</td
                                        >
                                        <td
                                            class="py-3 px-4 text-sm font-medium text-[var(--color-text-primary)]"
                                            >{item.user_name ||
                                                item.user?.name}</td
                                        >
                                        <td
                                            class="py-3 px-4 text-sm text-[var(--color-text-secondary)]"
                                            >{item.user_nim ||
                                                item.user?.nim ||
                                                "-"}</td
                                        >
                                        <td
                                            class="py-3 px-4 text-sm text-[var(--color-text-secondary)]"
                                            >{new Date(
                                                item.waktu_scan,
                                            ).toLocaleTimeString("id-ID")}</td
                                        >
                                        <td class="py-3 px-4">
                                            {#if item.status === "hadir"}
                                                <span
                                                    class="inline-flex items-center gap-1 bg-[var(--color-success-light)] text-[var(--color-success)] px-2.5 py-1 rounded-lg text-xs font-semibold"
                                                >
                                                    <svg
                                                        class="w-3 h-3"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="2"
                                                        ><path
                                                            d="M5 13l4 4L19 7"
                                                        /></svg
                                                    >
                                                    Hadir
                                                </span>
                                            {:else if item.status === "telat"}
                                                <span
                                                    class="inline-flex items-center gap-1 bg-[var(--color-warning-light)] text-[var(--color-warning)] px-2.5 py-1 rounded-lg text-xs font-semibold"
                                                >
                                                    <svg
                                                        class="w-3 h-3"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="2"
                                                        ><circle
                                                            cx="12"
                                                            cy="12"
                                                            r="10"
                                                        /><path
                                                            d="M12 6v6l4 2"
                                                        /></svg
                                                    >
                                                    Telat
                                                </span>
                                            {:else}
                                                <span
                                                    class="bg-slate-100 text-[var(--color-text-secondary)] px-2.5 py-1 rounded-lg text-xs font-semibold"
                                                    >{item.status}</span
                                                >
                                            {/if}
                                        </td>
                                    </tr>
                                {/each}
                            </tbody>
                        </table>
                    </div>
                {/if}
            </div>
        {:else}
            <!-- FORM BUKA SESI -->
            <div
                class="bg-white rounded-2xl shadow-[var(--shadow-sm)] p-6 lg:p-10 border border-[var(--color-border)] max-w-2xl animate-slide-up"
            >
                <h2
                    class="text-xl lg:text-2xl font-bold text-[var(--color-text-primary)] mb-2"
                >
                    Buka Sesi Baru
                </h2>
                <p class="text-sm text-[var(--color-text-muted)] mb-8">
                    Pilih mata kuliah dan atur durasi sesi absensi (maks. 15
                    menit).
                </p>

                <!-- MATA KULIAH -->
                <label
                    for="select-matkul"
                    class="block mb-2 text-sm font-semibold text-[var(--color-text-primary)]"
                    >Mata Kuliah</label
                >
                <select
                    id="select-matkul"
                    bind:value={selectedMatkul}
                    class="w-full border border-[var(--color-border)] p-3.5 rounded-xl mb-6 bg-white text-sm text-[var(--color-text-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--color-accent)]/20 focus:border-[var(--color-accent)] transition"
                >
                    <option value="">— Pilih Mata Kuliah —</option>
                    {#each mataKuliahs as mk}
                        <option value={mk.id}>{mk.nama_matkul}</option>
                    {/each}
                </select>

                <!-- DURASI -->
                <label
                    for="range-durasi"
                    class="block mb-2 text-sm font-semibold text-[var(--color-text-primary)]"
                >
                    Durasi Sesi: <span class="text-[var(--color-primary)]"
                        >{durasi} menit</span
                    >
                </label>
                <input
                    id="range-durasi"
                    type="range"
                    bind:value={durasi}
                    min="1"
                    max="15"
                    class="w-full mb-2 accent-[var(--color-primary)]"
                />
                <div
                    class="flex justify-between text-xs text-[var(--color-text-muted)] mb-6"
                >
                    <span>1 menit</span>
                    <span>15 menit</span>
                </div>

                <p
                    class="text-xs text-[var(--color-text-muted)] mb-6 flex items-center gap-1.5"
                >
                    <svg
                        class="w-4 h-4 text-[var(--color-warning)]"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        ><circle cx="12" cy="12" r="10" /><path
                            d="M12 6v6l4 2"
                        /></svg
                    >
                    Mahasiswa yang scan setelah {durasi} menit akan dicatat sebagai
                    <strong class="text-[var(--color-warning)]">Telat</strong>.
                </p>

                <button
                    onclick={bukaSesi}
                    disabled={loading}
                    class="w-full flex items-center justify-center gap-2 bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] text-white px-8 py-3.5 rounded-xl font-semibold text-sm transition-all disabled:opacity-50 cursor-pointer border-0 shadow-sm hover:shadow-md"
                >
                    {#if loading}
                        <svg
                            class="animate-spin w-4 h-4"
                            fill="none"
                            viewBox="0 0 24 24"
                            ><circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            ></circle><path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                            ></path></svg
                        >
                        Membuka Sesi...
                    {:else}
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
                        Buka Sesi Presensi
                    {/if}
                </button>
            </div>
        {/if}
    </main>
</div>
