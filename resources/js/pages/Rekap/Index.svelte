<script>
    export let matkuls = [];
</script>

<div class="page-wrap">

    <div class="animate-fade-in" style="margin-bottom: 32px;">

        <a href="/dosen/dashboard"
           class="qr-btn qr-btn-outline qr-btn-sm"
           style="margin-bottom: 20px; display: inline-flex;">
            ← Kembali ke Dashboard
        </a>

        <h1 class="qr-page-title">
            Rekap Kehadiran
        </h1>

        <p class="qr-page-subtitle">
            Mata kuliah yang Anda ampu.
        </p>

    </div>

    <div class="matkul-grid">

        {#if matkuls.length > 0}

            {#each matkuls as matkul}

                <div class="qr-card matkul-card">

                    <div class="badge">
                        Mata Kuliah
                    </div>

                    <h2>{matkul.nama_matkul}</h2>

                    <p>
                        Total Kehadiran:
                        <strong>{matkul.kehadirans_count}</strong>
                    </p>

                    <a
                        href={`/rekap/${matkul.id}`}
                        class="qr-btn"
                        style="width: 100%; margin-top: 20px;"
                    >
                        Lihat Rekap
                    </a>

                </div>

            {/each}

        {:else}

            <div class="qr-empty">
                <div class="qr-empty-icon">📚</div>
                <div class="qr-empty-text">
                    Belum ada mata kuliah.
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

    .matkul-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    .matkul-card {
        padding: 24px;
    }

    .matkul-card h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin: 12px 0;
    }

    .badge {
        display: inline-block;
        background: #dbeafe;
        color: #1d4ed8;
        padding: 6px 12px;
        border-radius: 9999px;
        font-size: 0.875rem;
    }
</style>