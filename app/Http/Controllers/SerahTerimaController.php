<?php

namespace App\Http\Controllers;

use App\Models\SerahTerima;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SerahTerimaImport;
use App\Exports\SerahTerimaExport;

class SerahTerimaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function Allserahterima(){
        return view('serahterima.all_serahterima');
    }

    public function Getserahterima(Request $request){

        if ($request->ajax()) {
            $data = SerahTerima::latest()->get();
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
                                              class="dropdown-item editserahterima" data-id="'.$row->id.'"> &nbsp; Edit</a>
                                   
                          
                                          <a href="javascript:void(0)"
                                              class="dropdown-item text-danger deleteserahterima"
                                           data-id="'.$row->id.'"> &nbsp; Delete</a>
                                   

                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>';

                    })

                    // ->addColumn('qr_code', function($row){ return QrCode::size(30)->generate($row->nik);})

                    ->rawColumns(['action'])

                    ->make(true);
                 

        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function Storeserahterima(Request $request)
    {

         // Generate no_trx_out
              $lastTransactionOut = SerahTerima::whereNotNull('no_trx')
                ->orderBy('created_at', 'desc')
                ->first();
            if ($lastTransactionOut) {
                $lastNoTrxOut = $lastTransactionOut->no_trx;
                $lastNoTrxOutNum = intval(substr($lastNoTrxOut, 12));
                $nextNoTrxOutNum = $lastNoTrxOutNum + 1;
            } else {
                $nextNoTrxOutNum = 1;
            }
            $currentYear = date('Y'); // Get the current year
            $noTrxOut = 'TRX-ST' . $currentYear . sprintf('%010d', $nextNoTrxOutNum);
           


        if( $request->serahterima_id == ""){

            $request->validate([
                'nik' => 'required',
                'name' => 'required',
                'department' => 'required',
                'item_code' => 'required',
                'remark' => 'required',
    
            ]);

            $post = SerahTerima::updateOrCreate( [
                'id' => $request->serahterima_id
            ],[
                    'no_trx' => $noTrxOut,
                    'nik' => $request->nik,
                    'name' => $request->name,
                    'department' => $request->department, 
                    'item_code' => $request->item_code,
                    'item_name' => $request->item_name,
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
                'nik' => 'required',
                'name' => 'required',
                'department' => 'required',
                'item_code' => 'required',
                'item_name' => 'required',
                'remark' => 'required',
    
            ]);

            $post = SerahTerima::updateOrCreate(
                [
                    'id' => $request->serahterima_id
                ],
                [
                    'nik' => $request->nik,
                    'name' => $request->name,
                    'department' => $request->department,
                    'item_code' => $request->item_code,
                    'item_name' => $request->item_name,
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SerahTerima $serahTerima)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SerahTerima $serahTerima)
    {
        //
    }

    public function Editserahterima($id)
    {
        $serahterima = SerahTerima::find($id);
        return response()->json($serahterima);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SerahTerima $serahTerima)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SerahTerima $serahTerima)
    {
        //
    }

    public function Deleteserahterima($id)
    {
        SerahTerima::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Post Berhasil Dihapus!.',
        ]);
    }

    public function Exportserahterima(){
        
        return Excel::download(new SerahTerimaExport, 'serahterima.xlsx');

    }

    
    public function Importserahterimas()
    {
        return view('serahterima.import_serahterima');
    }

    public function Importserahterima(Request $request)
    {
        $cek = Excel::import(new SerahTerimaImport, $request->file('import_file'));

    
            $notification = array(
                'message' => 'Import Successfully',
                'alert-type' => 'success'
            );
   
    
        return redirect()->route('all.serahterima')->with($notification);
    } //end method


    

}
