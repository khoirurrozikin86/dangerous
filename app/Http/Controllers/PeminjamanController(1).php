<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Item;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;
use App\Exports\PeminjamanExport;
use App\Events\PeminjamanUpdated;
use App\Events\CategoryUpdated;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Broadcast;


class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    // public function updateTotalPeminjaman()
    // {
    //     $today = Carbon::today();
    //     $totalPeminjaman = Peminjaman::whereDate('created_at', $today)
    //         ->where('remark', 'PINJAM')
    //         ->count();


    //     Cache::forever('total_peminjaman', $totalPeminjaman);

    //     // PeminjamanUpdated::dispatch($totalPeminjaman);


    //     // Broadcast event untuk memberi tahu perubahan total peminjaman
    //     event(new PeminjamanUpdated($totalPeminjaman));
    // }


    public function Allpeminjaman(Request $request)
    {   


        return view('dangerous.all_transaction');
    } 

    public function getpeminjaman(Request $request)
    {
        if ($request->ajax()) {
            $data = Peminjaman::with(['employee', 'item'])
                    ->whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->latest()
                    ->get();
            return Datatables::of($data)
      
                    ->addIndexColumn()
                    ->addColumn('remark', function($row) {
                        $remark = $row->remark;
                        $class = $remark == 'PINJAM' ? 'badge bg-danger' : 'badge bg-success';
                        return '<span class="'.$class.'">'.$remark.'</span>';
                    })
           		   ->addColumn('updated_at', function($row) {      
                        // Menggunakan operator ternary untuk memeriksa apakah updated_at sama dengan created_at

                       $up = $row->updated_at;
                       $cr = $row->created_at ;
                       $res = $up != $cr ? $up : '';
                        return $res;
                    })   
                    ->addColumn('action', function($row) {
                        return   '<div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                          
                            <div class="d-flex align-items-center">
                                <div class="actions dropdown">
                                    <a href="#" data-bs-toggle="dropdown"> ••• </a>
                                    <div class="dropdown-menu" role="menu">
                                      
                                    
       
                                            <a href="javascript:void(0)"
                                                class="dropdown-item text-danger deletePeminjaman"
                                             data-id="'.$row->id.'"> &nbsp; Delete</a>
                                     
  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                    })
                    ->rawColumns(['remark','action'])
                    ->make(true);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function GetPeminjamanlimit(){
     $today = Carbon::today();
        $transactions = Peminjaman::with('employee', 'item')
           ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($transactions);
    }

    public function GetPeminjamanrtlimit(){
     $today = Carbon::today();
        $transactions = Peminjaman::with('employee', 'item')
           ->whereDate('created_at', $today)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($transactions);
    }


    


    public function getpeminjaman_today(){
        
           // Ambil tanggal hari ini
           $today = Carbon::today();
        
           // Tanggal kemarin
           $yesterday = Carbon::yesterday();
   
           // Menghitung jumlah peminjaman pada hari ini dengan remark 'pinjam' untuk setiap kategori
           // Hitung jumlah total karyawan
           $itemCount = Item::count();
   
           // Menghitung jumlah peminjaman hari ini untuk karyawan yang meminjam
           // Hitung jumlah unik karyawan yang meminjam barang pada hari ini berdasarkan nama
            $employeeCount = Peminjaman::whereDate('created_at', $today)
                                                ->where('remark', 'PINJAM')
                                                ->distinct('employee_id')
                                                ->count('employee_id');
   
           // Menghitung total peminjaman hari ini
           $peminjamanCount = Peminjaman::whereDate('created_at', $today)
                                         ->where('remark', 'PINJAM')
                                         ->count();
   
           // Menghitung jumlah barang yang masih dipinjam hari ini
           $itemOutCount = Item::where('status', 1)
                                      ->count();

            // Simpan semua nilai dalam satu array
            $cacheData = [
                'ITEM' => $itemCount,
                'EMPLOYEE_BORROW' => $employeeCount,
                'PEMINJAMAN' => $peminjamanCount,
                'ITEM_OUT' => $itemOutCount
            ];


         // Simpan array sebagai nilai untuk satu kunci dalam cache
           Cache::forever('peminjaman_today_data', $cacheData);

           event(new PeminjamanUpdated($itemCount, $employeeCount, $peminjamanCount, $itemOutCount));
   
           // Return the counts as a JSON response
           return response()->json([
               'success' => true,
               'message' => 'Counts retrieved successfully',
               'data' => [
                   'ITEM' => $itemCount,
                   'EMPLOYEE_BORROW' => $employeeCount,
                   'PEMINJAMAN' => $peminjamanCount,
                   'ITEM_OUT' => $itemOutCount
               ]
           ]);

    }
  
   

    public function GetPeminjamanHariIni()

    {
        // Ambil tanggal hari ini
        $today = Carbon::today();

        // Tanggal kemarin
        $yesterday = Carbon::yesterday();
    
        // Kategori yang akan dihitung secara spesifik
        $specificCategories = ['SEW%', '%QC%', 'PACK%', 'CUT%', 'MEK%', 'SPL%', 'WH%', 'FOLD%', 'PRINT%', 'IRON%'];
        $categories = [
            'SEW' => 'SEW%',
            'QC' => '%QC%',
            'PACK' => 'PACK%',
            'CUTT' => 'CUT%',
            'MEK' => 'MEK%',
            'SPL' => 'SPL%',
            'WH' => 'WH%',
            'FOLD' => 'FOLD%',
            'PRINT' => 'PRINT%',
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
                                        $query->where(function($query) use ($specificCategories) {
                                            foreach ($specificCategories as $patternx) {
                                                $query->wherenot('code', 'like', $patternx );
                                            }
                                        });
                                    })
                                    ->count();

         // Hitung jumlah peminjaman yang belum dikembalikan hari ini dan yang masih dipinjam kemarin
         $counts['NOT_RETURN'] = Peminjaman::where(function($query) use ($today, $yesterday) {
                                            $query->whereDate('created_at','<=', $yesterday)
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



    public function Addpeminjaman(){
        $this->getpeminjaman_today();
         $this->GetPeminjamanHariIni();

        return view('dangerous.peminjaman');
    }   
    public function Addpeminjamanrt(){
        $this->getpeminjaman_today();
         $this->GetPeminjamanHariIni();
        return view('dangerous.pengembalian');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }


    public function StorePeminjaman(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'item_id' => 'required',
        ]);

        // Generate no_trx_out
        $lastTransactionOut = Peminjaman::whereNotNull('no_trx_out')
            ->orderBy('created_at', 'desc')
            ->first();
        if ($lastTransactionOut) {
            $lastNoTrxOut = $lastTransactionOut->no_trx_out;
            $lastNoTrxOutNum = intval(substr($lastNoTrxOut, 13));
            $nextNoTrxOutNum = $lastNoTrxOutNum + 1;
        } else {
            $nextNoTrxOutNum = 1;
        }
        $currentYear = date('Y'); // Get the current year
        $noTrxOut = 'TRX-OUT-' . $currentYear . sprintf('%010d', $nextNoTrxOutNum);

        // Generate no_trx_return
        $lastTransactionReturn = Peminjaman::whereNotNull('no_trx_return')
            ->orderBy('no_trx_return', 'desc')
            ->first();
            // dd($lastTransactionReturn);
        if ($lastTransactionReturn) {
            $lastNoTrxReturn = $lastTransactionReturn->no_trx_return;
            $lastNoTrxReturnNum = intval(substr($lastNoTrxReturn, 13));
            $nextNoTrxReturnNum = $lastNoTrxReturnNum + 1;
        } else {
            $nextNoTrxReturnNum = 1;
        }
        $currentYear = date('Y'); // Get the current year
        $noTrxReturn = 'TRX-RTN-' . $currentYear. sprintf('%010d', $nextNoTrxReturnNum);
        

        if ($request->remark == "PINJAM") {
            // Check item status
            $item = Item::find($request->item_id);
            if ($item && $item->status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item sedang dipinjam!',
                    'alert-type' => 'error'
                ]);
            }

      

            // Create new transaction
            $transaction = Peminjaman::create([
                'no_trx_out' => $noTrxOut,
                'employee_id' => $request->employee_id,
                'item_id' => $request->item_id,
                'no_trx_return' => '',
                'remark' => 'PINJAM'
            ]);

           // Update item status to 1 (borrowed)
           if($transaction){

            if ($item) {
                $item->status = 1;
                $item->save();
            }

            $this->getpeminjaman_today();
             $this->GetPeminjamanHariIni();

           }
        

        } elseif ($request->remark == "KEMBALI") {
            // Find the last PINJAM transaction for this item and employee
            $transaction = Peminjaman::where('employee_id', $request->employee_id)
                ->where('item_id', $request->item_id)
                ->where('remark', 'PINJAM')
                ->latest('id')
                ->first();

            if ($transaction) {
                // Update the transaction with return details

            
                $transaction->update([
                    'no_trx_return' => $noTrxReturn,
                    'remark' => 'KEMBALI'
                ]);


                // Update item status to 0 (available)
                $item = Item::find($request->item_id);
                if ($item) {
                    $item->status = 0;
                    $item->save();
                }

                $this->getpeminjaman_today();
                 $this->GetPeminjamanHariIni();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada transaksi PINJAM yang sesuai ditemukan untuk dikembalikan!',
                    'alert-type' => 'error'
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan!',
            'data' => $transaction,
            'alert-type' => 'success'
        ]);
    }
    public function StorePeminjamanold1(Request $request)
    {
        $request->validate([
            'employee_id' => 'required', // Validasi bahwa employee_id diharapkan dari request
            'item_id' => 'required', // Validasi bahwa employee_id diharapkan dari request
        ]);

        // Membuat nomor transaksi
        $lastTransaction = Peminjaman::orderBy('created_at', 'desc')->first();
        if ($lastTransaction) {
            $lastNoTrx = $lastTransaction->no_trx_out;
            $lastNoTrxNum = intval(substr($lastNoTrx, 13)); // Get the numeric part of the last transaction number
            $nextNoTrxNum = $lastNoTrxNum + 1; // Increment the number
        } else {
            $nextNoTrxNum = 1; // Start with 1 if there are no transactions
        }
       $currentYear = date('Y'); // Get the current year
        $noTrxOut = 'TRX-OUT-' . $currentYear . sprintf('%010d', $nextNoTrxOutNum);

        $lastTransactionReturn = Peminjaman::orderBy('created_at', 'desc')->first();
        if ($lastTransactionReturn) {
            $lastNoTrxReturn = $lastTransactionReturn->no_trx_return;
            $lastNoTrxReturnNum = intval(substr($lastNoTrxReturn, 13)); // Get the numeric part of the last return transaction number
            $nextNoTrxReturnNum = $lastNoTrxReturnNum + 1; // Increment the number
        } else {
            $nextNoTrxReturnNum = 1; // Start with 1 if there are no transactions
        }
        $currentYear = date('Y'); // Get the current year
        $noTrxReturn = 'TRX-RTN-' . $currentYear. sprintf('%010d', $nextNoTrxReturnNum);
            


        
        if ($request->remark == "PINJAM") {

             // Check the status of the item
            $item = Item::find($request->item_id);
            if ($item && $item->status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status masih PINJAM!',
                    'alert-type' => 'error'
                ]);
            }


            // Periksa apakah sudah ada transaksi IN yang belum di-close dengan OUT untuk employee_id yang sama
            $existingTransaction = Peminjaman::where('employee_id', $request->employee_id)
                                            ->where('item_id', $request->item_id)
                                            ->where('remark', 'PINJAM')
                                            ->first();
            if ($existingTransaction) {
                // Jika ada, kirim response gagal untuk menghindari duplikasi
                return response()->json([
                    'success' => false,
                    'message' => 'Status masih PINJAM!',
                    'alert-type' => 'error'
                ]);
            }

            // Jika tidak ada transaksi IN yang terbuka, buat transaksi baru
            $transaction = Peminjaman::create([
                'no_trx_out' => $noTrx,
                'employee_id' => $request->employee_id,
                'item_id' => $request->item_id,
                'no_trx_return' => '',
                'remark' => 'PINJAM'
            ]);

                // Update item status to 0 (false)
            $item = Item::find($request->item_id);
            if ($item) {
                $item->update(['status' => 0]);
            }


        } elseif ($request->remark == "KEMBALI") {
            // Jika types adalah OUT, update transaksi terakhir dengan status IN dan employee_id yang sama
            $transaction = Peminjaman::where('employee_id', $request->employee_id)
                                    ->where('item_id', $request->item_id)
                                    ->where('remark', 'PINJAM')
                                    ->latest('id')
                                    ->first();

            if ($transaction) {
                // Update transaksi yang ditemukan dengan OUT
                $transaction->update([
                    'no_trx_return' => $noTrxReturn,
                    'remark' => 'KEMBALI'
                ]);


                    // Update item status to 1 (true) when returned
                $item = Item::find($request->item_id);
                if ($item) {
                    $item->update(['status' => 1]);
                }



            } else {
                // Jika tidak ditemukan transaksi yang bisa diupdate, kirim error response
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada transaksi IN yang sesuai ditemukan untuk diupdate!',
                    'alert-type' => 'error'
                ]);
            }
        }

        // Kirim response sukses jika semua proses di atas berjalan tanpa error
        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan!',
            'data' => $transaction,
            'alert-type' => 'success'
        ]);

    }
    

            
    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }

    public function Deletepeminjaman($id)
    {
        $del = Peminjaman::findOrFail($id);

        $item = Item::find($del->item_id);
    
        if ($item) {
            // Perbarui status item menjadi 0
            $item->status = 0;
            $item->save();
        }

        // Hapus transaksi
        $del->delete();

        $this->getpeminjaman_today();
         $this->GetPeminjamanHariIni();
       

        return response()->json([
            'success' => true,
            'message' => 'Data Post Berhasil Dihapus!.',
        ]);

    }

    public function export(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil data dari database berdasarkan rentang tanggal
        $data = Peminjaman::with(['employee', 'item']);
        $data = Peminjaman::whereBetween('created_at', [$startDate, $endDate])->get();

        // Ekspor data ke Excel menggunakan class PeminjamanExport
        return Excel::download(new PeminjamanExport($data), 'peminjaman.xlsx');
    }
}
