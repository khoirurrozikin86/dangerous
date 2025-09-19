<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemsImport;
use App\Exports\ItemsExport;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function Allitem()
    {
        return view('backend.item.all_item');
    }


    public function Getitem(Request $request){

        if ($request->ajax()) {     
            $data = Item::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('status', function($row) {
                        $status = $row->status;
                        $class = $status == '0' ? 'badge bg-success' : 'badge bg-danger';
                        $statusok = $status == '0' ? 'READY' : 'DIPINJAM';
                        return '<span class="'.$class.'">'.$statusok.'</span>';
                    })
                    ->addColumn('action', function($row){


                      return   '<div class="d-flex align-items-center justify-content-between flex-wrap">
                      <div class="d-flex align-items-center">
                        
                          <div class="d-flex align-items-center">
                              <div class="actions dropdown">
                                  <a href="#" data-bs-toggle="dropdown"> ••• </a>
                                  <div class="dropdown-menu" role="menu">
                                    
                                  
                                          <a href="javascript:void(0)"
                                              class="dropdown-item editItem" data-id="'.$row->id.'"> &nbsp; Edit</a>
                                   
                          
                                          <a href="javascript:void(0)"
                                              class="dropdown-item text-danger deleteItem"
                                           data-id="'.$row->id.'"> &nbsp; Delete</a>
                                   

                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>';

                    })

                    // ->addColumn('qr_code', function($row){ return QrCode::size(30)->generate($row->code);})

                    ->rawColumns(['status','action'])

                    ->make(true);
                 

        }

    }


    public function GetItemCount()
    {
        // Hitung jumlah total karyawan
        $itemCount = Item::count();

        // Return the total item count as a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Total item count retrieved successfully',
            'data' => [
                'item_count' => $itemCount
            ]
        ]);
    }


    public function Checkitem(Request $request)

    {

        $id = $request->input('sku');
        $user = Item::where('code',$id)->first();

        if ($user) {                
            return response()->json($user);
        } else {
            return response()->json(['nama' => 'SKU tidak ditemukan']);
        }

        // return response()->json($user);

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

    public function StoreItem(Request $request)
    {
        if( $request->item_id == ""){

            $request->validate([
                'code' => 'required|unique:items',
                'name' => 'required',
                'category' => 'required',
                'posisi' => 'required',
                'unit' => 'required',
    
            ]);

            $post = item::updateOrCreate([

   
                'id' => $request->item_id
        
                 ],[
                    'code' => $request->code,
                    'name' => $request->name,
                    'category' => $request->category, 
                    'posisi' => $request->posisi,
                    'unit' => $request->unit,
                    'status' => $request->status,
        
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
                'code' => 'required',
                'name' => 'required',
                'category' => 'required',
                'posisi' => 'required',
                'unit' => 'required',
    
            ]);

            $post = item::updateOrCreate([

   
                'id' => $request->item_id
        
                 ],[
                    'code' => $request->code,
                    'name' => $request->name,
                    'category' => $request->category,
                    'posisi' => $request->posisi,
                    'unit' => $request->unit,
                    'status' => $request->status,
        
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
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    public function Edititem($id)
    {
        $items = item::find($id);
        return response()->json($items);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }

    public function DeleteItem($id)
    {
        Item::findOrFail($id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data Post Berhasil Dihapus!.',
        ]);
    }

    public function Printitem()
    {
     
        return view('backend.item.print');
    }

    
    public function GetPosisi(Request $request){

        // $posisi= $request->input('posisi');

          // Fetch employee positions for the specified department
          $data = Item::distinct()->pluck('posisi');

          return response()->json($data);

        

    }


    public function exportPDF(Request $request)
    {

           // Validate the input
           $request->validate([
            'posisi' => 'required|string'
        ]);

         // Get the position from the request
         $position = $request->input('posisi');

         // Fetch employees with the specified position
         $items = Item::where('posisi', $position)->get();


        foreach ($items as $item) {
            $qrCode = QrCode::size(100)->generate($item->code);
            $item->qr_code = $qrCode;

        }

        return view('backend.item.pdf', compact('items'));
    }


    public function Importitems()
    {
        return view('backend.item.import_item');
    }

    public function Importitem(Request $request)
    {
        $cek = Excel::import(new itemsImport, $request->file('import_file'));

    
            $notification = array(
                'message' => 'Import Successfully',
                'alert-type' => 'success'
            );
   
    
        return redirect()->route('all.item')->with($notification);
    } //end method


    public function Exportitem()
    {
        return Excel::download(new ItemsExport, 'items.xlsx');
    }




    
}
