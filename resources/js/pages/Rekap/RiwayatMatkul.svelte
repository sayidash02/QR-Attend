<script>
    export let riwayat = [];
    export let matkul;
</script>

<div class="page-wrap">

    <div class="animate-fade-in" style="margin-bottom:32px;">

        <a
            href="/riwayat-absensi"
            class="qr-btn qr-btn-outline qr-btn-sm"
            style="margin-bottom:20px; display:inline-flex;"
        >
            ← Kembali
        </a>

        <h1 class="qr-page-title">
            {matkul.nama_matkul}
        </h1>

        <p class="qr-page-subtitle">
            Riwayat Kehadiran Mata Kuliah
        </p>

    </div>

    <div class="grid">

        {#if riwayat.length > 0}

            {#each riwayat as item, index}

                <div class="qr-card pertemuan-card">

                    <div>

                        <div class="badge-pertemuan">
                            Pertemuan {index + 1}
                        </div>

                        <h2>
                            Status Kehadiran
                        </h2>

                        <p>
                            Waktu Scan:
                            {new Date(item.waktu_scan)
                                .toLocaleString('id-ID')}
                        </p>

                    </div>

                    <div>

                        <span
                            class="qr-badge
                            {item.status === 'hadir'
                                ? 'qr-badge-success'
                                : item.status === 'telat'
                                ? 'qr-badge-warning'
                                : item.status === 'izin'
                                ? 'qr-badge-info'
                                : 'qr-badge-danger'}"
                        >
                            {item.status}
                        </span>

                    </div>

                </div>

            {/each}

        {:else}

            <div class="qr-card">

                <div class="qr-empty">

                    <div class="qr-empty-icon">
                        📋
                    </div>

                    <div class="qr-empty-text">
                        Belum ada riwayat kehadiran.
                    </div>

                </div>

            </div>

        {/if}

    </div>

</div>

<style>

.page-wrap {
    min-height: 100vh;
    background: var(--color-bg);
    padding: 32px 24px;
    max-width: 1200px;
    margin: auto;
}

.grid {
    display: grid;
    gap: 20px;
}

.pertemuan-card {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-left: 5px solid #2563eb;
    transition: .2s;
}

.pertemuan-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,.08);
}

.badge-pertemuan {
    display: inline-block;
    background: #dbeafe;
    color: #1d4ed8;
    padding: 6px 12px;
    border-radius: 9999px;
    font-size: .85rem;
    font-weight: 600;
    margin-bottom: 12px;
}

.pertemuan-card h2 {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.pertemuan-card p {
    color: var(--color-text-secondary);
}

@media(max-width:768px){

    .pertemuan-card{
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
    }

}

</style>