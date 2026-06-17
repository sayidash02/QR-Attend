<script>
    export let sesis = [];
    export let matkul;

    console.log('sesis', sesis);
    console.log('matkul', matkul);
</script>


<div class="page-wrap">

    <!-- HEADER -->
    <div class="animate-fade-in" style="margin-bottom: 32px;">

        <a
            href="/rekap"
            class="qr-btn qr-btn-outline qr-btn-sm"
            style="margin-bottom: 20px; display: inline-flex;"
        >
            ← Kembali
        </a>

        <div class="header-top">

            <div>
                <h1 class="qr-page-title">
                    {matkul.nama_matkul}
                </h1>

                <p class="qr-page-subtitle">
                    Daftar Pertemuan Presensi
                </p>
            </div>

            <a
                href={`/rekap/${matkul.id}/export`}
                class="qr-btn export-btn"
            >
                📥 Download Excel
            </a>

        </div>

    </div>

    <!-- CARD PERTEMUAN -->
    <div class="grid">

        {#if sesis.length > 0}

            {#each sesis as sesi, index}

                <div class="qr-card pertemuan-card animate-slide-up">

                    <div class="card-left">

                        <div class="badge-pertemuan">
                            Pertemuan {index + 1}
                        </div>

                        <h2 class="card-title">
                            Sesi Presensi
                        </h2>

                        <div class="card-info">

                            <p>
                                📅 Dibuka:
                                <strong>
                                    {new Date(sesi.started_at)
                                        .toLocaleString('id-ID')}
                                </strong>
                            </p>

                            <p>
                                ⏰ Berakhir:
                                <strong>
                                    {new Date(sesi.expired_at)
                                        .toLocaleString('id-ID')}
                                </strong>
                            </p>

                        </div>

                    </div>

                    <div class="card-right">

                        <a
                            href={`/rekap/sesi/${sesi.id}`}
                            class="qr-btn"
                        >
                            📋 Lihat Kehadiran
                        </a>

                    </div>

                </div>

            {/each}

        {:else}

            <div class="qr-card">

                <div class="qr-empty">

                    <div class="qr-empty-icon">
                        📚
                    </div>

                    <div class="qr-empty-text">
                        Belum ada sesi presensi.
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
        margin: 0 auto;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .export-btn {
        background: #22c55e;
        color: white;
        padding: 12px 20px;      /* memperbesar tombol */
        font-size: 1rem;         /* ukuran teks */
        font-weight: 600;        /* teks lebih tebal */
        border-radius: 12px;     /* sudut lebih bulat */
    }

    .export-btn:hover {
        background: #16a34a;
    }

    .export-btn:hover {
        background: #16a34a;
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
        border-left: 5px solid #22c55e;
        transition: all 0.2s ease;
    }

    .pertemuan-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .card-left {
        flex: 1;
    }

    .badge-pertemuan {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 6px 12px;
        border-radius: 9999px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 14px;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--color-text-primary);
        margin-bottom: 12px;
    }

    .card-info p {
        color: var(--color-text-secondary);
        margin: 6px 0;
        font-size: 0.95rem;
    }

    .card-right {
        min-width: 200px;
        display: flex;
        justify-content: flex-end;
    }

    @media (max-width: 768px) {

        .header-top {
            flex-direction: column;
            align-items: stretch;
        }

        .export-btn {
            width: 100%;
            text-align: center;
        }

        .pertemuan-card {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .card-right {
            width: 100%;
        }

        .card-right .qr-btn {
            width: 100%;
        }

    }
</style>