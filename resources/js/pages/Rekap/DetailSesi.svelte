<script>
    export let sesi;
    export let mahasiswas = [];

    let editingId = null;

    async function updateStatus(userId, status) {
        try {
            await fetch('/kehadiran/update-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content,
                },
                body: JSON.stringify({
                    user_id: userId,
                    sesi_presensi_id: sesi.id,
                    mata_kuliah_id: sesi.mata_kuliah_id,
                    status: status,
                }),
            });

            location.reload();

        } catch (error) {
            console.error(error);
            alert('Gagal mengubah status.');
        }
    }
</script>

<div class="page-wrap">

    <!-- HEADER -->
    <div class="animate-fade-in" style="margin-bottom: 32px;">

        <a
            href={`/rekap/${sesi.mata_kuliah_id}`}
            class="qr-btn qr-btn-outline qr-btn-sm"
            style="margin-bottom:20px; display:inline-flex;"
        >
            ← Kembali
        </a>

        <h1 class="qr-page-title">
            Detail Kehadiran
        </h1>

        <p class="qr-page-subtitle">
            Pertemuan pada
            {new Date(sesi.started_at).toLocaleString('id-ID')}
        </p>

    </div>

    <!-- TABLE -->
    <div class="qr-card animate-slide-up" style="overflow:hidden;">

        <div style="overflow-x:auto;">

            <table class="qr-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Waktu Scan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    {#if mahasiswas.length > 0}

                        {#each mahasiswas as item, index}

                            <tr>

                                <td>{index + 1}</td>

                                <td>{item.nim}</td>

                                <td style="font-weight:600;">
                                    {item.name}
                                </td>

                                <td>

                                    {#if editingId === item.id}

                                        <select
                                            class="status-select"
                                            value={item.status}
                                            on:change={(e) => {
                                                updateStatus(
                                                    item.id,
                                                    e.target.value
                                                );
                                                editingId = null;
                                            }}
                                        >
                                            <option value="hadir">Hadir</option>
                                            <option value="telat">Telat</option>
                                            <option value="izin">Izin</option>
                                            <option value="alpha">Alpha</option>
                                        </select>

                                    {:else}

                                        <span class="qr-badge
                                            {item.status === 'hadir'
                                                ? 'qr-badge-success'
                                                : item.status === 'telat'
                                                ? 'qr-badge-warning'
                                                : item.status === 'izin'
                                                ? 'qr-badge-info'
                                                : 'qr-badge-danger'}">

                                            {item.status}

                                        </span>

                                    {/if}

                                </td>

                                <td style="color: var(--color-text-secondary);">

                                    {#if item.waktu_scan}
                                        {new Date(item.waktu_scan)
                                            .toLocaleString('id-ID')}
                                    {:else}
                                        -
                                    {/if}

                                </td>

                                <td>

                                    <button
                                        class="qr-btn qr-btn-outline qr-btn-sm"
                                        on:click={() => editingId = item.id}
                                    >
                                        ✏️ Edit
                                    </button>

                                </td>

                            </tr>

                        {/each}

                    {:else}

                        <tr>
                            <td colspan="6">

                                <div class="qr-empty">

                                    <div class="qr-empty-icon">
                                        📋
                                    </div>

                                    <div class="qr-empty-text">
                                        Belum ada mahasiswa.
                                    </div>

                                </div>

                            </td>
                        </tr>

                    {/if}

                </tbody>

            </table>

        </div>

    </div>

</div>

<style>
.page-wrap {
    min-height: 100vh;
    background: var(--color-bg);
    padding: 32px 24px;
    max-width: 1200px;
    margin: 0 auto;
}

.status-select {
    padding: 6px 10px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: white;
    cursor: pointer;
}

.status-select:focus {
    outline: none;
    border-color: #2563eb;
}
</style>