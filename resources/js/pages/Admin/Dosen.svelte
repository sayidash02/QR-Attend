<script>
    import { toast } from '../../stores/toastStore.js'
    import { router } from '@inertiajs/svelte'

    export let dosen = []

    let name = ''
    let nidn = ''
    let matkul = ''
    let email = ''
    let password = ''
    let loading = false

    let editingId = null
    let editingMatkulVal = ''

    async function tambahDosen() {
        if (!name || !email || !password) {
            toast.warning('Nama, email, dan password wajib diisi!')
            return
        }
        loading = true
        try {
            const response = await fetch('/admin/dosen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ name, nidn, matkul, email, password }),
            })
            if (response.ok) {
                toast.success(`Dosen ${name} berhasil ditambahkan!`)
                name = ''; nidn = ''; matkul = ''; email = ''; password = ''
                router.reload({ only: ['dosen'] })
            } else {
                const data = await response.json().catch(() => ({}))
                toast.error(data.message || 'Gagal menambahkan dosen')
            }
        } catch (error) {
            toast.error('Terjadi kesalahan jaringan')
        } finally {
            loading = false
        }
    }

    async function hapusDosen(id, name) {
        if (!confirm(`Apakah Anda yakin ingin menghapus dosen ${name}?`)) return
        try {
            const response = await fetch(`/admin/dosen/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            })
            if (response.ok) {
                toast.success('Dosen berhasil dihapus!')
                router.reload({ only: ['dosen'] })
            } else {
                toast.error('Gagal menghapus dosen')
            }
        } catch (error) {
            toast.error('Terjadi kesalahan jaringan')
        }
    }

    async function editMatkul(id) {
        try {
            const response = await fetch(`/admin/dosen/${id}/matkul`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ matkul: editingMatkulVal }),
            })
            if (response.ok) {
                toast.success('Mata kuliah berhasil diperbarui!')
                editingId = null
                router.reload({ only: ['dosen'] })
            } else {
                toast.error('Gagal memperbarui mata kuliah')
            }
        } catch (error) {
            toast.error('Terjadi kesalahan jaringan')
        }
    }

    function startEdit(d) { editingId = d.id; editingMatkulVal = d.matkul || '' }
    function cancelEdit() { editingId = null }
</script>

<div class="min-h-screen bg-[var(--color-bg)] text-[var(--color-text-primary)] flex flex-col font-[var(--font-sans)]">

    <!-- TOP BAR -->
    <header class="bg-white/80 backdrop-blur-md border-b border-[var(--color-border)] sticky top-0 z-10 px-6 lg:px-8 py-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <p class="text-xs font-semibold text-[var(--color-primary)] uppercase tracking-widest mb-0.5 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Admin Portal
                </p>
                <h1 class="text-xl lg:text-2xl font-bold text-[var(--color-text-primary)]">Kelola Data Dosen</h1>
            </div>
            <a href="/admin/dashboard" class="flex items-center gap-2 bg-white hover:bg-slate-50 text-[var(--color-text-secondary)] font-medium px-4 py-2.5 rounded-xl transition shadow-sm border border-[var(--color-border)] hover:border-slate-300 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Dashboard
            </a>
        </div>
    </header>

    <!-- MAIN -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 lg:px-6 py-8 grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- FORM -->
        <section class="lg:col-span-1 animate-slide-in-left">
            <div class="bg-white border border-[var(--color-border)] rounded-2xl p-6 shadow-[var(--shadow-sm)] sticky top-24">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-[var(--color-text-primary)]">Tambah Dosen</h2>
                        <p class="text-xs text-[var(--color-text-muted)]">Masukkan detail akun dosen baru</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="dosen-name" class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                        <input id="dosen-name" bind:value={name} type="text" placeholder="Dr. Budi Santoso" class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition" />
                    </div>
                    <div>
                        <label for="dosen-nidn" class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5">NIDN</label>
                        <input id="dosen-nidn" bind:value={nidn} type="text" placeholder="0412088501" class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition" />
                    </div>
                    <div>
                        <label for="dosen-matkul" class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5">Mata Kuliah</label>
                        <textarea id="dosen-matkul" bind:value={matkul} placeholder="Pemrograman Web, Basis Data, AI" class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition h-20 resize-none"></textarea>
                    </div>
                    <div>
                        <label for="dosen-email" class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5">Email</label>
                        <input id="dosen-email" bind:value={email} type="email" placeholder="dosen@universitas.ac.id" class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition" />
                    </div>
                    <div>
                        <label for="dosen-password" class="block text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider mb-1.5">Password</label>
                        <input id="dosen-password" bind:value={password} type="password" placeholder="••••••••" class="w-full bg-[var(--color-bg)] border border-[var(--color-border)] focus:border-[var(--color-accent)] focus:ring-2 focus:ring-[var(--color-accent)]/20 rounded-xl px-4 py-3 text-sm placeholder-[var(--color-text-muted)] outline-none transition" />
                    </div>
                    <button on:click={tambahDosen} disabled={loading} class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-hover)] active:bg-[var(--color-primary-active)] text-white font-semibold py-3 px-4 rounded-xl transition-all shadow-sm hover:shadow-md mt-2 cursor-pointer disabled:opacity-50 flex items-center justify-center gap-2 border-0 text-sm">
                        {#if loading}
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Menyimpan...
                        {:else}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                            Daftarkan Dosen
                        {/if}
                    </button>
                </div>
            </div>
        </section>

        <!-- TABLE -->
        <section class="lg:col-span-2 animate-slide-up">
            <div class="bg-white border border-[var(--color-border)] rounded-2xl p-6 shadow-[var(--shadow-sm)]">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-[var(--color-text-primary)]">Daftar Dosen Aktif</h2>
                        <p class="text-xs text-[var(--color-text-muted)]">Total {dosen.length} dosen terdaftar</p>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-xl border border-[var(--color-border)]">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[var(--color-bg)] border-b border-[var(--color-border)]">
                                <th class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider">Info Dosen</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider">Mata Kuliah</th>
                                <th class="text-left py-3 px-4 text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--color-border-light)]">
                            {#if dosen.length === 0}
                                <tr>
                                    <td colspan="3" class="text-center py-12">
                                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <p class="text-sm text-[var(--color-text-muted)]">Belum ada data dosen terdaftar.</p>
                                    </td>
                                </tr>
                            {:else}
                                {#each dosen as d}
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="py-3.5 px-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[var(--color-primary)] to-[var(--color-primary-muted)] text-white flex items-center justify-center font-bold text-xs shrink-0">
                                                    {d.name.charAt(0).toUpperCase()}
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-semibold text-sm text-[var(--color-text-primary)] truncate">{d.name}</div>
                                                    <div class="text-xs text-[var(--color-text-muted)] flex items-center gap-1.5 mt-0.5">
                                                        <span>ID: {d.nidn || '-'}</span>
                                                        <span class="text-slate-300">•</span>
                                                        <span class="truncate">{d.email}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3.5 px-4">
                                            {#if editingId === d.id}
                                                <div class="flex items-center gap-2 max-w-sm">
                                                    <input bind:value={editingMatkulVal} class="bg-white border border-[var(--color-border)] focus:border-[var(--color-accent)] rounded-lg px-2.5 py-1.5 text-xs outline-none w-full transition" placeholder="Pisahkan dengan koma" />
                                                    <button on:click={() => editMatkul(d.id)} class="bg-[var(--color-success)] hover:bg-emerald-700 text-white p-2 rounded-lg text-xs cursor-pointer border-0" title="Simpan">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                                                    </button>
                                                    <button on:click={cancelEdit} class="bg-slate-100 hover:bg-slate-200 text-[var(--color-text-secondary)] p-2 rounded-lg text-xs cursor-pointer border-0" title="Batal">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            {:else}
                                                <div class="flex items-center gap-2 group">
                                                    <div class="flex flex-wrap gap-1">
                                                        {#if d.matkul}
                                                            {#each d.matkul.split(',') as subject}
                                                                <span class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 text-[10px] font-semibold border border-blue-100/50">{subject.trim()}</span>
                                                            {/each}
                                                        {:else}
                                                            <span class="text-xs text-[var(--color-text-muted)] italic">Belum ada</span>
                                                        {/if}
                                                    </div>
                                                    <button on:click={() => startEdit(d)} class="opacity-0 group-hover:opacity-100 text-xs text-blue-600 hover:text-blue-700 bg-slate-100 hover:bg-slate-200 px-2 py-1 rounded-lg transition cursor-pointer border-0 ml-auto flex items-center gap-1 shrink-0">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                                        Edit
                                                    </button>
                                                </div>
                                            {/if}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <button on:click={() => hapusDosen(d.id, d.name)} class="flex items-center gap-1.5 text-[var(--color-error)] hover:bg-[var(--color-error-light)] px-3 py-2 rounded-lg transition text-xs font-semibold cursor-pointer border-0 bg-transparent">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/></svg>
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