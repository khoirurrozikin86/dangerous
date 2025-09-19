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
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

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
                        $up = $row->updated_at;
                        $cr = $row->created_at;
                        return $up != $cr ? $up : '';
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
                    ->rawColumns(['remark', 'action'])
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

   public function getpeminjaman_today()
{
    $today = Carbon::today();
    $yesterday = Carbon::yesterday();

    $itemCount = Item::count();
    $employeeCount = Peminjaman::whereDate('created_at', $today)
                                ->where('remark', 'PINJAM')
                                ->distinct('employee_id')
                                ->count('employee_id');
    $peminjamanCount = Peminjaman::whereDate('created_at', $today)
                                 ->where('remark', 'PINJAM')
                                 ->count();
    $itemOutCount = Item::where('status', 1)
                        ->count();

    // Kembalikan data langsung tanpa cache
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
    $today = Carbon::today();
    $yesterday = Carbon::yesterday();

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

    $counts['NOT_RETURN'] = Peminjaman::where(function($query) use ($today, $yesterday) {
                                    $query->whereDate('created_at','<=', $yesterday)
                                         ->where('remark', 'PINJAM')
                                         ->where('no_trx_return','');
                                })
                                ->count();

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
