<?php

namespace App\Http\Controllers;

use App\Models\PhotoReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use DataTables;
use App\Exports\PhotoReturnExport;
use Maatwebsite\Excel\Facades\Excel;


class PhotoReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function Allphotoreturn(){
        return view('photo.all_photo');
    }
    public function Addphotoreturn(){
        return view('photo.add_photo');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function showForm()
    {
        return view('return-photo-form');
    }

    public function Storephotoreturn(Request $request)
    {
        $request->validate([
            'photo1' => 'required|image|mimes:jpeg,png,jpg,gif',
            'photo2' => 'image|mimes:jpeg,png,jpg,gif',
            'photo3' => 'image|mimes:jpeg,png,jpg,gif',
            'photo4' => 'image|mimes:jpeg,png,jpg,gif',
            'photo5' => 'image|mimes:jpeg,png,jpg,gif',
            'department' => 'required|string|max:255',
            'remark' => 'required|string|max:255',
        ]);

        $photoReturn = new PhotoReturn();
        $photoReturn->creator = Auth::user()->name;
        $photoReturn->department = $request->department;
        $photoReturn->remark = $request->remark;

        foreach (['photo1', 'photo2', 'photo3', 'photo4', 'photo5'] as $photo) {
            if ($request->hasFile($photo)) {
                $image = $request->file($photo);
                $filename = time() . '_' . $image->getClientOriginalName();

                // Kompres gambar
                $imageResized = Image::make($image->getRealPath());
                $imageResized->resize(800, 600, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(storage_path('app/public/' . $filename), 75); // Kompres dengan kualitas 75%

                $photoReturn->{$photo} =  $filename;
            }
        }

        $photoReturn->save();

        return redirect()->back()->with('success', 'Photos uploaded successfully!');
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
    public function show(PhotoReturn $photoReturn)
    {
        //
    }

    public function getphotoreturn(Request $request)
    {
        if ($request->ajax()) {
            $data = PhotoReturn::whereBetween('created_at', [$request->start_date, $request->end_date])
                    ->latest()
                    ->get();
            return Datatables::of($data)
      
                    ->addIndexColumn()
                    ->addColumn('photo1', function($row) {
                        return '<img src="'.asset('storage/'.$row->photo1).'" width="50">';
                    })
                    ->addColumn('photo2', function($row) {
                        return $row->photo2 ? '<img src="'.asset('storage/'.$row->photo2).'" width="50">' : 'N/A';
                    })
                    ->addColumn('photo3', function($row) {
                        return $row->photo3 ? '<img src="'.asset('storage/'.$row->photo3).'" width="50">' : 'N/A';
                    })
                    ->addColumn('photo4', function($row) {
                        return $row->photo4 ? '<img src="'.asset('storage/'.$row->photo4).'" width="50">' : 'N/A';
                    })
                    ->addColumn('photo5', function($row) {
                        return $row->photo5 ? '<img src="'.asset('storage/'.$row->photo5).'" width="50">' : 'N/A';
                    })
                  
                    ->addColumn('action', function($row) {
                        return   '<div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center">
                          
                            <div class="d-flex align-items-center">
                                <div class="actions dropdown">
                                    <a href="#" data-bs-toggle="dropdown"> ••• </a>
                                    <div class="dropdown-menu" role="menu">
                                      
                                    
       
                                            <a href="javascript:void(0)"
                                                class="dropdown-item text-danger deletePhoto"
                                             data-id="'.$row->id.'"> &nbsp; Delete</a>
                                     
  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                    })
                    ->rawColumns(['photo1', 'photo2', 'photo3', 'photo4', 'photo5', 'action'])
                    ->make(true);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhotoReturn $photoReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PhotoReturn $photoReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function Deletephotoreturn($id)
    {
   
            $photo = PhotoReturn::findOrFail($id);

            // Hapus foto dari penyimpanan
            $photoPaths = [
                '/' . $photo->photo1,
                '/' . $photo->photo2,
                '/' . $photo->photo3,
                '/' . $photo->photo4,
                '/' . $photo->photo5,
            ];

            foreach ($photoPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            // Hapus entri dari database
            $photo->delete();
      return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus'], 200);
    
    }


    public function Exportphotoreturn(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Ambil data dari database berdasarkan rentang tanggal
   
        $data = PhotoReturn::whereBetween('created_at', [$startDate, $endDate])->get();

        // Ekspor data ke Excel menggunakan class PeminjamanExport
        return Excel::download(new PhotoReturnExport($data), 'photoreturn.xlsx');
    }
}
