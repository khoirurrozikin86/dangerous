<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\TrainingExport;

class TrainingController extends Controller
{
    public function Alltraining()
    {
        return view('backend.training.all_training');
    }

    public function Addtraining()
    {
        return view('backend.training.add_training');
    }
 
    public function Gettraining(Request $request){
 
        if ($request->ajax()) {
            $data = Training::with('employee', 'basicoperation')->latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
 
 
                      return   '<div class="d-flex align-items-center justify-content-between flex-wrap">
                      <div class="d-flex align-items-center">
                        
                          <div class="d-flex align-items-center">
                              <div class="actions dropdown">
                                  <a href="#" data-bs-toggle="dropdown"> ••• </a>
                                  <div class="dropdown-menu" role="menu">
                                    
                                  
                          
                                          <a href="javascript:void(0)"
                                              class="dropdown-item text-danger deletetraining"
                                           data-id="'.$row->id.'"> &nbsp; Delete</a>
                                   
 
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>';
 
                    })
 
                    // ->addColumn('qr_code', function($row){ return QrCode::size(30)->generate($row->op_code);})
 
                    ->rawColumns(['action'])
 
                    ->make(true);
                 
 
        }
 
    }


    public function Gettraininglimit(){
        $today = Carbon::today();
           $transactions = Training::with('employee', 'basicoperation')
              ->whereDate('created_at', $today)
               ->orderBy('created_at', 'desc')
               ->limit(10)
               ->get(); 
   
           return response()->json($transactions);
       }
 
    public function GetPosisi(Request $request){
 
        // $remark= $request->input('remark');
 
          // Fetch training positions for the specified op_type
          $data = training::distinct()->pluck('remark');
 
          return response()->json($data);
 
        
 
    }
 
    public function Checktraining(Request $request)
 
    {
 
        $op_code = $request->input('op_code');
        $user = training::where('op_code',$op_code)->first();
 
        if ($user) {        
            return response()->json($user);
        } else {
            return response()->json(['nama' => 'NIK tidak ditemukan']);
        }
 
        // return response()->json($user);
 
    }
 
    public function GettrainingCount()
    {
        // Hitung jumlah total karyawan
        $trainingCount = training::count();
 
        // Return the total training count as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Total training count retrieved successfully',
            'data' => [
                'training_count' => $trainingCount
            ]
        ]);
    }
 
 
 
    public function Storetraining(Request $request)
    {
        if( $request->training_id == ""){
 
            $request->validate([
                'employee_id' => 'required',
                'basicoperation_id' => 'required',
 
    
            ]);
 
          // Cek jika training_id tidak diisi (untuk create baru)
                if ($request->training_id == "") {

                    // Generate training_no otomatis
                    $year = date('Y');
                    $latestTraining = Training::whereYear('created_at', $year)->orderBy('id', 'desc')->first();

                    if ($latestTraining) {
                        // Ambil nomor urut dari training terakhir
                        $lastNumber = (int) substr($latestTraining->training_no, -7); // Ambil 7 digit terakhir dari training_no
                        $newNumber = str_pad($lastNumber + 1, 7, '0', STR_PAD_LEFT); // Tambahkan 1 dan pad dengan 0
                    } else {
                        // Jika belum ada data di tahun ini, mulai dari 0000001
                        $newNumber = '0000001';
                    }

                    // Format nomor training
                    $training_no = 'TR' . $year . $newNumber;

                    // Simpan data baru
                    $post = training::create([
                        'training_no' => $training_no,
                        'employee_id' => $request->employee_id,
                        'basicoperation_id' => $request->basicoperation_id,
                        'remark' => '',
                        'user_id' => Auth::id(),
                    ]);

                } else {
                    // Untuk update data berdasarkan training_id yang sudah ada
                    $post = training::updateOrCreate(
                        ['id' => $request->training_id],
                        [
                            'employee_id' => $request->employee_id,
                            'basicoperation_id' => $request->basicoperation_id,
                            'remark' => '',
                            'user_id' => Auth::id(),
                        ]
                    );
                }

                // Kembalikan response JSON
                return response()->json([
                    'success' => true,
                    'message' => 'Data Berhasil Disimpan!',
                    'data' => $post,
                    'alert-type' => 'success'
                ]);
            }
      
    }
 
    public function Importtrainings()
    {
        return view('backend.training.import_training');
    }
 
 
    public function Importtraining(Request $request)
    {
        $cek = Excel::import(new trainingsImport, $request->file('import_file'));
 
    
            $notification = array(
                'message' => 'Import Successfully',
                'alert-type' => 'success'
            );
   
    
        return redirect()->route('all.training')->with($notification);
    } //end method
 
    
    public function Edittraining($id)
    {
        $trainings = training::find($id);
        return response()->json($trainings);
    }
 
  
 
     public function Deletetraining($id)
     {
         training::findOrFail($id)->delete();
         return response()->json([
             'success' => true,
             'message' => 'Data Post Berhasil Dihapus!.',
         ]);
     }
 
 
    // public function Exporttraining(){
        
    //         return Excel::download(new trainingsExport, 'trainings.xlsx');
    
    // }
 
 
 
 
 
    public function exportPDF(Request $request)
    {
 
           // Validate the input
           $request->validate([
            'remark' => 'required|string'
        ]);
 
         // Get the position from the request
         $position = $request->input('remark');
 
         // Fetch trainings with the specified position
         $trainings = training::where('remark', $position)->get();
 
 
        foreach ($trainings as $training) {
            $qrCode = QrCode::size(100)->generate($training->op_code);
            $training->qr_code = $qrCode;
 
        }
 
        return view('backend.training.pdf', compact('trainings'));
    }
 
    public function Printtraining()
    {
     
        return view('backend.training.print');
    }

   public function Exporttraining(){
    return Excel::download(new TrainingExport, 'training_data.xlsx');
   }
}
