<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Models\Item;
use App\Events\PeminjamanUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PeminjamanObserver
{
     /**
     * Handle the Peminjaman "created" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function created(Peminjaman $peminjaman)
    {
        $this->updateTotalPeminjaman();
    }

    /**
     * Handle the Peminjaman "updated" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function updated(Peminjaman $peminjaman)
    {
        $this->updateTotalPeminjaman();
    }

    /**
     * Handle the Peminjaman "deleted" event.
     *
     * @param  \App\Models\Peminjaman  $peminjaman
     * @return void
     */
    public function deleted(Peminjaman $peminjaman)
    {
        $this->updateTotalPeminjaman();
    }

    /**
     * Update cache and fire event.
     */
    protected function updateTotalPeminjaman()
    {
        // Ambil tanggal hari ini
        $today = Carbon::today();

        // Menghitung jumlah total karyawan
        $itemCount = Item::count();

        // Menghitung jumlah peminjaman hari ini untuk karyawan yang meminjam
        $employeeCount = Peminjaman::whereDate('created_at', $today)
                                   ->where('remark', 'PINJAM')
                                   ->distinct('employee_id')
                                   ->count('employee_id');

        // Menghitung total peminjaman hari ini
        $peminjamanCount = Peminjaman::whereDate('created_at', $today)
                                     ->where('remark', 'PINJAM')
                                     ->count();

        // Menghitung jumlah barang yang masih dipinjam hari ini
        $itemOutCount = Item::where('status', 1)->count();

        // Simpan semua nilai dalam satu array
        $cacheData = [
            'ITEM' => $itemCount,
            'EMPLOYEE_BORROW' => $employeeCount,
            'PEMINJAMAN' => $peminjamanCount,
            'ITEM_OUT' => $itemOutCount
        ];

        // Simpan array sebagai nilai untuk satu kunci dalam cache
        Cache::forever('peminjaman_today_data', $cacheData);

        // Trigger the event
        event(new PeminjamanUpdated($itemCount, $employeeCount, $peminjamanCount, $itemOutCount));
    }
   
}
