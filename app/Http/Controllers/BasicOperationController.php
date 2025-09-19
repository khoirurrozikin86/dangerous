<?php

namespace App\Http\Controllers;

use App\Models\BasicOperation;
use Illuminate\Http\Request;
use DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Maatwebsite\Excel\Facades\Excel;

class BasicOperationController extends Controller
{
  
   public function Allbasicoperation()
   {
       return view('backend.basicoperation.all_basicoperation');
   }

   public function Getbasicoperation(Request $request){

       if ($request->ajax()) {
           $data = basicoperation::latest()->get();
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
                                             class="dropdown-item editbasicoperation" data-id="'.$row->id.'"> &nbsp; Edit</a>
                                  
                         
                                         <a href="javascript:void(0)"
                                             class="dropdown-item text-danger deletebasicoperation"
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

   public function GetPosisi(Request $request){

       // $remark= $request->input('remark');

         // Fetch basicoperation positions for the specified op_type
         $data = basicoperation::distinct()->pluck('remark');

         return response()->json($data);

       

   }

   public function Checkbasicoperation(Request $request)

   {

       $op_code = $request->input('op_code');
       $user = basicoperation::where('op_code',$op_code)->first();

       if ($user) {        
           return response()->json($user);
       } else {
           return response()->json(['nama' => 'NIK tidak ditemukan']);
       }

       // return response()->json($user);

   }

   public function GetbasicoperationCount()
   {
       // Hitung jumlah total karyawan
       $basicoperationCount = basicoperation::count();

       // Return the total basicoperation count as a JSON response
       return response()->json([
           'success' => true,
           'message' => 'Total basicoperation count retrieved successfully',
           'data' => [
               'basicoperation_count' => $basicoperationCount
           ]
       ]);
   }



   public function Storebasicoperation(Request $request)
   {
       if( $request->basicoperation_id == ""){

           $request->validate([
               'op_code' => 'required',
               'op_name' => 'required',
               'op_type' => 'required',
               'remark' => 'required',
   
           ]);

           $post = basicoperation::updateOrCreate([

  
               'id' => $request->basicoperation_id
       
                ],[
                   'op_code' => $request->op_code,
                   'op_name' => $request->op_name,
                   'op_type' => $request->op_type, 
                   'remark' => $request->remark,
       
               ]);
       
       
               //return response
               return response()->json([
                   'success' => true,
                   'message' => 'Data Berhasil Disimpan!',
                   'data'    => $post,
                   'alert-type' => 'success'  
               ]);
   

       }
       else{
           $request->validate([
               'op_code' => 'required',
               'op_name' => 'required',
               'op_type' => 'required',
               'remark' => 'required',
   
           ]);

           $post = basicoperation::updateOrCreate([

  
               'id' => $request->basicoperation_id
       
                ],[
                   'op_code' => $request->op_code,
                   'op_name' => $request->op_name,
                   'op_type' => $request->op_type,
                   'remark' => $request->remark,
       
               ]);
       
       
               //return response
               return response()->json([
                   'success' => true,
                   'message' => 'Data Berhasil Disimpan!',
                   'data'    => $post,
                   'alert-type' => 'success'  
               ]);
   
       }
     
   }

   public function Importbasicoperations()
   {
       return view('backend.basicoperation.import_basicoperation');
   }


   public function Importbasicoperation(Request $request)
   {
       $cek = Excel::import(new basicoperationsImport, $request->file('import_file'));

   
           $notification = array(
               'message' => 'Import Successfully',
               'alert-type' => 'success'
           );
  
   
       return redirect()->route('all.basicoperation')->with($notification);
   } //end method

   
   public function Editbasicoperation($id)
   {
       $basicoperations = basicoperation::find($id);
       return response()->json($basicoperations);
   }

 

    public function Deletebasicoperation($id)
    {
        basicoperation::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Post Berhasil Dihapus!.',
        ]);
    }


   public function Exportbasicoperation(){
       
           return Excel::download(new basicoperationsExport, 'basicoperations.xlsx');
   
   }





   public function exportPDF(Request $request)
   {

          // Validate the input
          $request->validate([
           'remark' => 'required|string'
       ]);

        // Get the position from the request
        $position = $request->input('remark');

        // Fetch basicoperations with the specified position
        $basicoperations = basicoperation::where('remark', $position)->get();


       foreach ($basicoperations as $basicoperation) {
           $qrCode = QrCode::size(100)->generate($basicoperation->op_code);
           $basicoperation->qr_code = $qrCode;

       }

       return view('backend.basicoperation.pdf', compact('basicoperations'));
   }

   public function Printbasicoperation()
   {
    
       return view('backend.basicoperation.print');
   }

   public function Getbasicoperationdata(){

       $data = BasicOperation::all();
       return response()->json($data);
   }
}
