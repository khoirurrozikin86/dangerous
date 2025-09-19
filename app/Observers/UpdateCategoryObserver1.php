<?php

namespace App\Observers;

use App\Models\Peminjaman;
use App\Events\CategoryUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class UpdateCategoryObserver
{
    /**
     * Handle the Peminjaman "created" event.
     */
    public function created(Peminjaman $peminjaman): void
    {
        //
    }

    /**
     * Handle the Peminjaman "updated" event.
     */
    public function updated(Peminjaman $peminjaman): void
    {
        //
    }

    /**
     * Handle the Peminjaman "deleted" event.
     */
    public function deleted(Peminjaman $peminjaman): void
    {
        //
    }

    /**
     * Handle the Peminjaman "restored" event.
     */
    public function restored(Peminjaman $peminjaman): void
    {
        //
    }

    /**
     * Handle the Peminjaman "force deleted" event.
     */
    public function forceDeleted(Peminjaman $peminjaman): void
    {
        //
    }

    public function UpdatePeminjamanHariIni()

    {
        // Ambil tanggal hari ini
        $today = Carbon::today();

        // Tanggal kemarin
        $yesterday = Carbon::yesterday();
    
        // Kategori yang akan dihitung secara spesifik
        $specificCategories = ['SEW', 'QC', 'PACK', 'CUTT', 'MKN', 'SPL', 'WH', 'FOLD', 'PRNT', 'IRON'];
        $categories = [
            'SEW' => 'SEW%',
            'QC' => 'QC%',
            'PACK' => 'PACK%',
            'CUTT' => 'CUTT%',
            'MKN' => 'MKN%',
            'SPL' => 'SPL%',
            'WH' => 'WH%',
            'FOLD' => 'FOLD%',
            'PRNT' => 'PRNT%',
            'IRON' => 'IRON%'
        ];
        $counts = [];
    
        foreach ($categories as $key => $pattern) {
            $counts[$key] = Peminjaman::with(['employee', 'item'])
                                        ->whereDate('created_at', $today)
                                        ->where('remark', 'PINJAM')
                                        ->whereHas('item', function($query) use ($pattern) {
                                            $query->where('code', 'like', $pattern);
                                        })
                                        ->count();
        }
    
        // Hitung jumlah kategori 'OTHER'
        $counts['OTHER'] = Peminjaman::with(['employee', 'item'])
                                        ->whereDate('created_at', $today)
                                        ->where('remark', 'PINJAM')
                                        ->whereHas('item', function($query) use ($specificCategories) {
                                            $query->whereNotIn('code', $specificCategories);
                                        })
                                        ->count();

         // Hitung jumlah peminjaman yang belum dikembalikan hari ini dan yang masih dipinjam kemarin
         $counts['NOT_RETURN'] = Peminjaman::where(function($query) use ($today, $yesterday) {
                                            $query->whereDate('created_at', $yesterday)
                                             ->where('remark', 'PINJAM')
                                                ->where('no_trx_return','');
                                            })
                                           
                                            ->count();


        Cache::forever('category', $counts);

        event(new CategoryUpdated($counts));
    
        // Return the counts as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Counts retrieved successfully',
            'data' => $counts
        ]);
    }
}
