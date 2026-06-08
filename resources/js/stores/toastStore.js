import { writable } from 'svelte/store'

export const toasts = writable([])

let toastId = 0

/**
 * Menambahkan notifikasi toast baru
 * @param {string} message - Pesan notifikasi
 * @param {'success'|'error'|'warning'|'info'} type - Tipe notifikasi
 * @param {number} duration - Durasi dalam ms (default 4000)
 */
export function addToast(message, type = 'success', duration = 4000) {

    const id = ++toastId

    toasts.update(all => [

        ...all,

        { id, message, type, duration, visible: true },

    ])

    // Auto dismiss
    setTimeout(() => {

        dismissToast(id)

    }, duration)

    return id

}

/**
 * Menghapus notifikasi toast
 * @param {number} id - ID toast yang akan dihapus
 */
export function dismissToast(id) {

    toasts.update(all =>

        all.map(t =>

            t.id === id ? { ...t, visible: false } : t

        )

    )

    // Hapus dari DOM setelah animasi selesai
    setTimeout(() => {

        toasts.update(all => all.filter(t => t.id !== id))

    }, 400)

}

// Shortcut functions
export const toast = {

    success: (msg, duration) => addToast(msg, 'success', duration),

    error: (msg, duration) => addToast(msg, 'error', duration),

    warning: (msg, duration) => addToast(msg, 'warning', duration),

    info: (msg, duration) => addToast(msg, 'info', duration),

}
