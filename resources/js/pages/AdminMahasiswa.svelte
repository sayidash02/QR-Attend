<script>
    import { toast } from "../stores/toastStore.js";
    import { router } from "@inertiajs/svelte";

    export let mahasiswa = [];

    let name = "";
    let nim = "";
    let email = "";
    let password = "";
    let loading = false;

    async function tambahMahasiswa() {
        if (!name || !nim || !email || !password) {
            toast.warning("Semua field wajib diisi!");
            return;
        }
        loading = true;
        try {
            const response = await fetch("/admin/mahasiswa", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
                body: JSON.stringify({ name, nim, email, password }),
            });
            if (response.ok) {
                toast.success(`Mahasiswa ${name} berhasil ditambahkan!`);
                name = "";
                nim = "";
                email = "";
                password = "";
                router.reload({ only: ["mahasiswa"] });
            } else {
                const data = await response.json().catch(() => ({}));
                toast.error(data.message || "Gagal menambahkan mahasiswa");
            }
        } catch (error) {
            toast.error("Terjadi kesalahan jaringan");
        } finally {
            loading = false;
        }
    }

    async function hapusMahasiswa(id, name) {
        if (!confirm(`Apakah Anda yakin ingin menghapus mahasiswa ${name}?`))
            return;
        try {
            const response = await fetch(`/mahasiswa/${id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
            });
            if (response.ok) {
                toast.success("Mahasiswa berhasil dihapus!");
                router.reload({ only: ["mahasiswa"] });
            } else {
                toast.error("Gagal menghapus mahasiswa");
            }
        } catch (error) {
            toast.error("Terjadi kesalahan jaringan");
        }
    }
</script>

<div
    class="min-h-screen bg-[var(--color-bg)] text-[var(--color-text-primary)] flex flex-col font-[var(--font-sans)]"
>
    <header
        class="bg-white/80 backdrop-blur-md border-b border-[var(--color-border)] sticky top-0 z-10 px-6 lg:px-8 py-4"
    >
        <div
            class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3"
        >
            <div>
                <p
                    class="text-xs font-semibold text-[var(--color-primary)] uppercase tracking-widest mb-0.5 flex items-center gap-1.5"
                >
                    <svg
                        class="w-3.5 h-3.5"
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
                    class="text-xl lg:text-2xl font-bold text-[var(--color-text-primary)]"
                >
                    Kelola Data Mahasiswa
                </h1>
            </div>
            <a
                href="/admin/dashboard"
                class="flex items-center gap-2 bg-white hover:bg-slate-50 text-[var(--color-text-secondary)] font-medium px-4 py-2.5 rounded-xl transition shadow-sm border border-[var(--color-border)] hover:border-slate-300 text-sm"
            >
                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7" /></svg
                >
                Dashboard
            </a>
        </div>
    </header>

    <main
        class="flex-1 max-w-7xl w-full mx-auto px-4 lg:px-6 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6"
    >
        <section class="lg:col-span-1 animate-slide-in-left">
            <div
                class="bg-white border border-[var(--color-border)] rounded-2xl p-6 shadow-[var(--shadow-sm)] sticky top-24"
            >
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center"
                    >
                        <svg
                            class="w-5 h-5 text-emerald-600"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            ><path
                                d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"
                            /><circle cx="8.5" cy="7" r="4" /><line
                                x1="20"
                                y1="8"
                                x2="20"
                                y2="14"
                            /><line x1="23" y1="11" x2="17" y2="11" /></svg
                        >
                    </div>
                    <div>
                        <h2
                            class="text-lg font-bold text-[var(--color-text-primary)]"
                        >
                            Tambah Mahasiswa
                        </h2>
                        <p class="text-xs text-[var(--color-text-muted)]">
                            Masukkan detail akun mahasiswa baru
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label
                            for="mhs-name"
                            class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5"
                            >Nama Lengkap</label
                        >
                        <input
                            id="mhs-name"
                            bind:value={name}
                            type="text"
                            placeholder="Wildan Pratama"
                            class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition"
                        />
                    </div>
                    <div>
                        <label
                            for="mhs-nim"
                            class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5"
                            >NIM</label
                        >
                        <input
                            id="mhs-nim"
                            bind:value={nim}
                            type="text"
                            placeholder="231110027"
                            class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition"
                        />
                    </div>
                    <div>
                        <label
                            for="mhs-email"
                            class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5"
                            >Email</label
                        >
                        <input
                            id="mhs-email"
                            bind:value={email}
                            type="email"
                            placeholder="mahasiswa@universitas.ac.id"
                            class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition"
                        />
                    </div>
                    <div>
                        <label
                            for="mhs-password"
                            class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5"
                            >Password</label
                        >
                        <input
                            id="mhs-password"
                            bind:value={password}
                            type="password"
                            placeholder="••••••••"
                            class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition"
                        />
                    </div>
                    <button
                        on:click={tambahMahasiswa}
                        disabled={loading}
                        class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] active:bg-[var(--color-primary-active)] text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-sm hover:shadow-md mt-2 cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2 border-0 text-sm"
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
                            Menyimpan...
                        {:else}
                            <svg
                                class="w-4 h-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                stroke-width="2"
                                ><path
                                    d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"
                                /><circle cx="8.5" cy="7" r="4" /><line
                                    x1="20"
                                    y1="8"
                                    x2="20"
                                    y2="14"
                                /><line x1="23" y1="11" x2="17" y2="11" /></svg
                            >
                            Daftarkan Mahasiswa
                        {/if}
                    </button>
                </div>
            </div>
        </section>

        <section class="lg:col-span-2 animate-slide-up">
            <div
                class="bg-white border border-[var(--color-border)] rounded-2xl p-6 shadow-[var(--shadow-sm)]"
            >
                <div class="mb-5">
                    <h2
                        class="text-lg font-bold text-[var(--color-text-primary)]"
                    >
                        Daftar Mahasiswa Aktif
                    </h2>
                    <p class="text-xs text-[var(--color-text-muted)]">
                        Total {mahasiswa.length} mahasiswa terdaftar
                    </p>
                </div>

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
                                    >Info Mahasiswa</th
                                >
                                <th
                                    class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider w-28"
                                    >Aksi</th
                                >
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-[var(--color-border-light)]"
                        >
                            {#if mahasiswa.length === 0}
                                <tr>
                                    <td colspan="2" class="text-center py-12">
                                        <svg
                                            class="w-12 h-12 mx-auto mb-3 text-slate-200"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                            stroke-width="1"
                                            ><path
                                                d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"
                                            /><circle
                                                cx="9"
                                                cy="7"
                                                r="4"
                                            /></svg
                                        >
                                        <p
                                            class="text-sm text-[var(--color-text-muted)]"
                                        >
                                            Belum ada data mahasiswa terdaftar.
                                        </p>
                                    </td>
                                </tr>
                            {:else}
                                {#each mahasiswa as m}
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="py-3.5 px-4">
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <div
                                                    class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-700 text-white flex items-center justify-center font-bold text-xs shrink-0"
                                                >
                                                    {m.name
                                                        .charAt(0)
                                                        .toUpperCase()}
                                                </div>
                                                <div class="min-w-0">
                                                    <div
                                                        class="font-semibold text-sm text-[var(--color-text-primary)] truncate"
                                                    >
                                                        {m.name}
                                                    </div>
                                                    <div
                                                        class="text-xs text-[var(--color-text-muted)] flex items-center gap-1.5 mt-0.5"
                                                    >
                                                        <span
                                                            >NIM: {m.nim ||
                                                                "-"}</span
                                                        >
                                                        <span
                                                            class="text-slate-300"
                                                            >•</span
                                                        >
                                                        <span class="truncate"
                                                            >{m.email}</span
                                                        >
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <button
                                                on:click={() =>
                                                    hapusMahasiswa(
                                                        m.id,
                                                        m.name,
                                                    )}
                                                class="flex items-center gap-1.5 text-[var(--color-error)] hover:bg-[var(--color-error-light)] px-3 py-2 rounded-lg transition text-xs font-semibold cursor-pointer border-0 bg-transparent"
                                            >
                                                <svg
                                                    class="w-3.5 h-3.5"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="2"
                                                    ><path
                                                        d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"
                                                    /></svg
                                                >
                                                Hapus
                                            </button>
                                        </td>
                                    </tr>
                                {/each}
                            {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>
</div>
