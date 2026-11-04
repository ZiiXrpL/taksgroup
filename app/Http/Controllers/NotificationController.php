<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

/**
 * Endpoint notifikasi bersama untuk ketiga role (spesifikasi bagian 7 &
 * 31), diletakkan di luar prefix admin/guru/siswa - sama seperti
 * FileDownloadController - karena "notifikasi milik siapa" murni
 * ditentukan oleh notifiable_id/notifiable_type pada baris notifikasi itu
 * sendiri (dicek lewat findOrFailForUser()), bukan oleh role route.
 */
class NotificationController extends Controller
{
    /**
     * Daftar lengkap notifikasi milik user yang sedang login, terbaru
     * lebih dulu.
     */
    public function index(): View
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', ['notifications' => $notifications]);
    }

    /**
     * Tandai satu notifikasi sebagai dibaca, lalu arahkan ke URL tujuan
     * notifikasi tersebut (disimpan pada kolom data->url saat notifikasi
     * dibuat, lihat app/Notifications/*).
     */
    public function markRead(DatabaseNotification $notification): RedirectResponse
    {
        $this->authorizeOwnership($notification);

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return redirect($notification->data['url'] ?? url()->previous());
    }

    /**
     * Tandai seluruh notifikasi milik user sebagai dibaca (dipanggil dari
     * tombol "Tandai semua dibaca" pada dropdown notifikasi).
     */
    public function markAllRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }

    /**
     * Cegah user membaca/menandai notifikasi milik user lain lewat ID
     * langsung (spesifikasi bagian 25: "Prevent IDOR").
     */
    private function authorizeOwnership(DatabaseNotification $notification): void
    {
        abort_unless($notification->notifiable_id === auth()->id()
            && $notification->notifiable_type === auth()->user()::class, 403);
    }
}
