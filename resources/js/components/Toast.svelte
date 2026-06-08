<script>

    import { toasts, dismissToast } from '../stores/toastStore.js'

    const icons = {

        success: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,

        error: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,

        warning: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`,

        info: `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,

    }

</script>

{#if $toasts.length > 0}

    <div class="toast-container" aria-live="polite">

        {#each $toasts as t (t.id)}

            <div
                class="toast toast-{t.type}"
                class:toast-exit={!t.visible}
            >

                <!-- GLOW ACCENT -->
                <div class="toast-glow toast-glow-{t.type}"></div>

                <!-- ICON -->
                <div class="toast-icon toast-icon-{t.type}">

                    {@html icons[t.type]}

                </div>

                <!-- MESSAGE -->
                <p class="toast-message">{t.message}</p>

                <!-- CLOSE -->
                <button
                    class="toast-close"
                    on:click={() => dismissToast(t.id)}
                    aria-label="Tutup notifikasi"
                >

                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>

                </button>

                <!-- PROGRESS BAR -->
                <div
                    class="toast-progress toast-progress-{t.type}"
                    style="animation-duration: {t.duration}ms"
                ></div>

            </div>

        {/each}

    </div>

{/if}

<style>

    .toast-container {
        position: fixed;
        top: 28px;
        right: 28px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 14px;
        pointer-events: none;
    }

    /* ---- TOAST BASE ---- */
    .toast {
        position: relative;
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 360px;
        max-width: 480px;
        padding: 18px 20px 18px 18px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.92);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.6);
        box-shadow:
            0 8px 32px rgba(6, 43, 102, 0.12),
            0 2px 8px rgba(0, 0, 0, 0.06),
            inset 0 1px 0 rgba(255, 255, 255, 0.8);
        pointer-events: all;
        overflow: hidden;
        animation: toastSlideIn 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    .toast-exit {
        animation: toastSlideOut 0.35s cubic-bezier(0.55, 0, 1, 0.45) forwards;
    }

    /* ---- GLOW EFFECT ---- */
    .toast-glow {
        position: absolute;
        top: -20px;
        left: -20px;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        filter: blur(30px);
        opacity: 0.4;
        pointer-events: none;
    }

    .toast-glow-success { background: #10b981; }
    .toast-glow-error { background: #ef4444; }
    .toast-glow-warning { background: #f59e0b; }
    .toast-glow-info { background: #062B66; }

    /* ---- ICON ---- */
    .toast-icon {
        flex-shrink: 0;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        animation: iconPop 0.5s cubic-bezier(0.16, 1, 0.3, 1) 0.15s both;
    }

    .toast-icon-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #059669;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    .toast-icon-error {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
    }

    .toast-icon-warning {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #d97706;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
    }

    .toast-icon-info {
        background: linear-gradient(135deg, #dbeafe, #bfdbfe);
        color: #062B66;
        box-shadow: 0 4px 12px rgba(6, 43, 102, 0.25);
    }

    /* ---- MESSAGE ---- */
    .toast-message {
        flex: 1;
        margin: 0;
        font-size: 14.5px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.45;
        letter-spacing: -0.01em;
    }

    /* ---- CLOSE BUTTON ---- */
    .toast-close {
        flex-shrink: 0;
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        padding: 6px;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .toast-close:hover {
        background: rgba(148, 163, 184, 0.15);
        color: #475569;
        transform: scale(1.1);
    }

    /* ---- PROGRESS BAR ---- */
    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        border-radius: 0 0 16px 16px;
        animation: progressShrink linear forwards;
    }

    .toast-progress-success {
        background: linear-gradient(90deg, #10b981, #34d399);
    }

    .toast-progress-error {
        background: linear-gradient(90deg, #ef4444, #f87171);
    }

    .toast-progress-warning {
        background: linear-gradient(90deg, #f59e0b, #fbbf24);
    }

    .toast-progress-info {
        background: linear-gradient(90deg, #062B66, #3b82f6);
    }

    /* ---- ANIMATIONS ---- */
    @keyframes toastSlideIn {
        0% {
            opacity: 0;
            transform: translateX(100%) scale(0.8);
        }
        100% {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
    }

    @keyframes toastSlideOut {
        0% {
            opacity: 1;
            transform: translateX(0) scale(1);
        }
        100% {
            opacity: 0;
            transform: translateX(120%) scale(0.8);
        }
    }

    @keyframes iconPop {
        0% {
            opacity: 0;
            transform: scale(0);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes progressShrink {
        0% {
            width: 100%;
        }
        100% {
            width: 0%;
        }
    }

    /* ---- TYPE-SPECIFIC BORDER ---- */
    .toast-success { border-left: 4px solid #10b981; }
    .toast-error { border-left: 4px solid #ef4444; }
    .toast-warning { border-left: 4px solid #f59e0b; }
    .toast-info { border-left: 4px solid #062B66; }

    /* ---- RESPONSIVE ---- */
    @media (max-width: 640px) {

        .toast-container {
            top: 16px;
            right: 16px;
            left: 16px;
        }

        .toast {
            min-width: auto;
            max-width: 100%;
        }

    }

</style>
