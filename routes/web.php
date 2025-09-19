<?php


use Illuminate\Support\Facades\Route;
use Spatie\Permission\Contracts\Permission;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ColorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PhotoReturnController;
use App\Http\Controllers\SerahTerimaController;
use App\Http\Controllers\BasicOperationController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Exports\PeminjamanExport;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


require __DIR__ . '/auth.php';

Route::get('/login', function () {

    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif (auth()->user()->role === 'agent') {
            return redirect()->route('agent.dashboard');
        } elseif (auth()->user()->role === 'user') {
            return redirect()->route('user.dashboard');
        }
    }
    return view('admin.admin_login');
});


Route::get('/admin/login', [AdminController::class, 'store'])->name('admin.login');

//admin group middleware
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Route::get('/', [AdminController::class, 'AdminDashboard'])->name('admin.login');
    Route::get('/admin/dashboard', [AdminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [AdminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [AdminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [AdminController::class, 'AdminProfileStore'])->name('admin.profile.store');
    Route::get('/admin/change/password', [AdminController::class, 'AdminChangePassword'])->name('admin.change.password');
    Route::post('/admin/update/password', [AdminController::class, 'AdminUpdatePassword'])->name('admin.update.password');
});

Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/dashboard', [AgentController::class, 'AgentDashboard'])->name('agent.dashboard');
});



Route::middleware(['auth', 'role:admin'])->group(function () {

    //permission all route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/permission', 'AllPermission')->name('all.permission')->middleware('can:add.permission');
        Route::get('/add/permission', 'AddPermission')->name('add.permission')->middleware('can:add.permission');
        Route::post('/store/permission', 'StorePermission')->name('store.permission');
        Route::get('/edit/permission/{id}', 'EditPermission')->name('edit.permission')->middleware('can:add.permission');
        Route::post('/update/permission', 'UpdatePermission')->name('update.permission');
        Route::get('/delete/permission/{id}', 'DeletePermission')->name('delete.permission')->middleware('can:delete.permission');
        Route::get('/import/permission', 'ImportPermission')->name('import.permission');
        Route::get('/export', 'Export')->name('export');
        Route::post('/import', 'Import')->name('import');
    });

    //role all route
    Route::controller(RoleController::class)->group(function () {
        Route::get('/all/roles', 'AllRoles')->name('all.roles')->middleware('can:all.roles');
        Route::get('/add/roles', 'AddRoles')->name('add.roles')->middleware('can:add.roles');
        Route::post('/store/roles', 'StoreRoles')->name('store.roles');
        Route::get('/edit/roles/{id}', 'EditRoles')->name('edit.roles')->middleware('can:edit.roles');
        Route::post('/update/roles', 'UpdateRoles')->name('update.roles');
        Route::get('/delete/roles/{id}', 'DeleteRoles')->name('delete.roles')->middleware('can:delete.roles');


        Route::get('/add/roles/permission', 'AddRolesPermission')->name('add.roles.permission')->middleware('can:add.roles.permission');
        Route::post('/add/permission/store', 'RolesPermissionStore')->name('roles.permission.store');
        Route::get('/all/roles/permission', 'AllRolesPermission')->name('all.roles.permission')->middleware('can:all.roles.permission');
        Route::get('/admin/edit/roles/{id}', 'AdminEditRoles')->name('admin.edit.roles')->middleware('can:admin.edit.roles');
        Route::post('/admin/roles/update/{id}', 'AdminRolesUpdate')->name('admin.roles.update');
        Route::get('/admin/delete/roles/{id}', 'AdminDeleteRoles')->name('admin.delete.roles')->middleware('can:admin.delete.roles');
    });


    //Admin User All Route
    Route::controller(AdminController::class)->group(function () {
        Route::get('/all/admin', 'AllAdmin')->name('all.admin')->middleware('can:all.admin');
        Route::get('/add/admin', 'AddAdmin')->name('add.admin')->middleware('can:add.admin');
        Route::post('/store/admin', 'StoreAdmin')->name('store.admin');
        Route::get('/edit/admin/{id}', 'EditAdmin')->name('edit.admin')->middleware('can:edit.admin');
        Route::post('/update/admin/{id}', 'UpdateAdmin')->name('update.admin');
        Route::get('/delete/admin/{id}', 'DeleteAdmin')->name('delete.admin')->middleware('can:delete.admin');
    });

    

      //color User All Color
      Route::controller(ColorController::class)->group(function () {
        Route::get('/all/color', 'AllColor')->name('all.color')->middleware('can:all.color');
        Route::get('/get/color', 'GetColor')->name('get.color')->middleware('can:all.color');
        Route::get('/get/colorglobal', 'GetColorGlobal')->name('get.colorGlobal');
        Route::get('/add/color', 'AddColor')->name('add.color')->middleware('can:add.color');
      
        Route::post('/store/color', 'StoreColor')->name('store.color');
        Route::get('/edit/color/{id}', 'EditColor')->name('edit.color')->middleware('can:edit.color');
        Route::post('/update/color/{id}', 'UpdateColor')->name('update.color');
        Route::get('/delete/color/{id}', 'DeleteColor')->name('delete.color');
        Route::get('/export/color', 'ExportColor')->name('export.color');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/category', 'Allcategory')->name('all.category')->middleware('can:all.category');
        Route::get('/get/category', 'Getcategory')->name('get.category')->middleware('can:get.category');
        Route::get('/get/categoryglobal', 'GetCategoryGlobal')->name('get.categoryglobal');
        Route::get('/add/category', 'Addcategory')->name('add.category')->middleware('can:add.category');
      
        Route::post('/store/category', 'Storecategory')->name('store.category')->middleware('can:add.category');
        Route::get('/edit/category/{id}', 'Editcategory')->name('edit.category')->middleware('can:edit.category');
        Route::post('/update/category/{id}', 'Updatecategory')->name('update.category')->middleware('can:edit.category');
        Route::get('/delete/category/{id}', 'Deletecategory')->name('delete.category')->middleware('can:delete.category');
        Route::get('/export/category', 'Exportcategory')->name('export.category');
    });


     

    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/all/employee', 'AllEmployee')->name('all.employee')->middleware('can:all.employee');
        Route::get('/add/employee', 'AddEmployee')->name('add.employee')->middleware('can:add.employee');
        Route::post('/store/employee', 'StoreEmployee')->name('store.employee');
        Route::get('/edit/employee/{id}', 'EditEmployee')->name('edit.employee')->middleware('can:edit.employee');
        Route::post('/update/employee/{id}', 'UpdateEmployee')->name('update.employee')->middleware('can:edit.employee');
        Route::get('/delete/employee/{id}', 'DeleteEmployee')->name('delete.employee')->middleware('can:delete.employee');
        Route::get('/export/employee', 'ExportEmployee')->name('export.employee')->middleware('can:export.employee');;
        Route::get('/get/employee', 'Getemployee')->name('get.employee')->middleware('can:all.employee');
        Route::get('/get/employeecount', 'GetEmployeeCount')->name('get.employeecount');
        Route::get('/get/posisiemployee', 'GetPosisi')->name('get.posisiemployee');
        Route::post('/check/employee', 'CheckEmployee')->name('check.employee');
        Route::get('/print/employee', 'PrintEmployee')->name('print.employee')->middleware('can:export.employee');
        Route::post('/pdf/employee', 'exportPDF')->name('pdf.employee')->middleware('can:export.employee');
        Route::post('/import/employee', 'ImportEmployee')->name('import.employee')->middleware('can:import.employee');
        Route::get('/import/employees', 'Importemployees')->name('import.employees')->middleware('can:import.employee');
      
    });


    Route::controller(BasicOperationController::class)->group(function () {
        Route::get('/all/basicoperation', 'AllBasicOperation')->name('all.basicoperation')->middleware('can:all.basicoperation');
        Route::get('/add/basicoperation', 'AddBasicOperation')->name('add.basicoperation')->middleware('can:add.basicoperation');
        Route::post('/store/basicoperation', 'StoreBasicOperation')->name('store.basicoperation');
        Route::get('/edit/basicoperation/{id}', 'EditBasicOperation')->name('edit.basicoperation')->middleware('can:edit.basicoperation');
        Route::post('/update/basicoperation/{id}', 'UpdateBasicOperation')->name('update.basicoperation')->middleware('can:edit.basicoperation');
        Route::get('/delete/basicoperation/{id}', 'DeleteBasicOperation')->name('delete.basicoperation')->middleware('can:delete.basicoperation');
        Route::get('/export/basicoperation', 'ExportBasicOperation')->name('export.basicoperation')->middleware('can:export.basicoperation');;
        Route::get('/get/basicoperation', 'Getbasicoperation')->name('get.basicoperation')->middleware('can:all.basicoperation');
        Route::get('/get/basicoperationcount', 'GetBasicOperationCount')->name('get.basicoperationcount');
        Route::get('/get/posisibasicoperation', 'GetPosisi')->name('get.posisibasicoperation');
        Route::post('/check/basicoperation', 'CheckBasicOperation')->name('check.basicoperation');
        Route::get('/print/basicoperation', 'PrintBasicOperation')->name('print.basicoperation')->middleware('can:export.basicoperation');
        Route::post('/pdf/basicoperation', 'exportPDF')->name('pdf.basicoperation')->middleware('can:export.basicoperation');
        Route::post('/import/basicoperation', 'ImportBasicOperation')->name('import.basicoperation')->middleware('can:import.basicoperation');
        Route::get('/import/basicoperations', 'Importbasicoperations')->name('import.basicoperations')->middleware('can:import.basicoperation');
        Route::get('/get/basicoperationdata', 'Getbasicoperationdata')->name('get.basicoperationdata')->middleware('can:all.basicoperation');
      
    });



    
    Route::controller(ItemController::class)->group(function () {
        Route::get('/all/item', 'Allitem')->name('all.item')->middleware('can:all.item');
        Route::get('/add/item', 'Additem')->name('add.item')->middleware('can:add.item');
        Route::post('/store/item', 'Storeitem')->name('store.item');
        Route::get('/edit/item/{id}', 'Edititem')->name('edit.item')->middleware('can:edit.item');
        Route::post('/update/item/{id}', 'Updateitem')->name('update.item');
        Route::get('/delete/item/{id}', 'DeleteItem')->name('delete.item')->middleware('can:delete.item');
        Route::get('/export/item', 'Exportitem')->name('export.item')->middleware('can:export.item');
        Route::get('/get/item', 'Getitem')->name('get.item')->middleware('can:all.item');
        Route::get('/get/itemcount', 'GetitemCount')->name('get.itemcount');
        Route::get('/get/posisi', 'GetPosisi')->name('get.posisi');
        Route::post('/check/item', 'Checkitem')->name('check.item');
        Route::get('/print/item', 'Printitem')->name('print.item')->middleware('can:export.item');
        Route::post('/pdf/item', 'exportPDF')->name('pdf.item')->middleware('can:export.item');
        Route::post('/import/item', 'Importitem')->name('import.item')->middleware('can:import.item');
        Route::get('/import/items', 'Importitems')->name('import.items')->middleware('can:import.item');
      
    });








    Route::controller(TransactionController::class)->group(function () {
       
        Route::get('/all/transaction', 'Alltransaction')->name('all.transaction')->middleware('can:all.transaction');
        Route::get('/add/transaction', 'Addtransaction')->name('add.transaction')->middleware('can:add.transaction');
        Route::get('/add/transactionout', 'Addtransactionout')->name('add.transactionout')->middleware('can:add.transactionout');
        Route::post('/store/transaction', 'Storetransaction')->name('store.transaction');
        Route::get('/edit/transaction/{id}', 'Edittransaction')->name('edit.transaction')->middleware('can:edit.transaction');
        Route::post('/update/transaction/{id}', 'Updatetransaction')->name('update.transaction');
        Route::get('/delete/transaction/{id}', 'Deletetransaction')->name('delete.transaction');
        Route::get('/export/transaction', 'Exporttransaction')->name('export.transaction');
        Route::get('/get/transaction', 'Gettransaction')->name('get.transaction')->middleware('can:all.transaction');
        Route::get('/get/transactionin', 'GettransactionIN')->name('get.transactionin');
        Route::get('/get/transactionout', 'GettransactionOUT')->name('get.transactionout');
        Route::get('/get/transactionstay', 'GettransactionSTAY')->name('get.transactionstay');
        Route::get('/pdf/transaction', 'exportPdf')->name('pdf.transaction');
      
    });

    
    Route::controller(PeminjamanController::class)->group(function () {
       
        Route::get('/all/peminjaman', 'Allpeminjaman')->name('all.peminjaman')->middleware('can:all.peminjaman');
        Route::get('/get/peminjaman', 'getpeminjaman')->name('get.peminjaman')->middleware('can:get.peminjaman');
        Route::get('/get/peminjaman_today', 'getpeminjaman_today')->name('get.peminjaman_today');
        Route::get('/get/peminjaman_department', 'GetPeminjamanHariIni')->name('get.peminjaman_department');
        Route::get('/get/peminjaman_employee', 'getpeminjaman_employee')->name('get.peminjaman_employee');
        Route::get('/add/peminjaman', 'Addpeminjaman')->name('add.peminjaman')->middleware('can:add.peminjaman');
        Route::get('/add/peminjamanrt', 'Addpeminjamanrt')->name('add.peminjamanrt')->middleware('can:add.peminjaman');
        Route::post('/store/peminjaman', 'StorePeminjaman')->name('store.peminjaman');
        Route::get('/edit/peminjaman/{id}', 'Editpeminjaman')->name('edit.peminjaman')->middleware('can:edit.peminjaman');
        Route::post('/update/peminjaman/{id}', 'Updatepeminjaman')->name('update.peminjaman');
        Route::get('/delete/peminjaman/{id}', 'Deletepeminjaman')->name('delete.peminjaman')->middleware('can:delete.peminjaman');                  
        Route::get('/export/peminjaman', 'export')->name('export.peminjaman')->middleware('can:export.peminjaman');;
        Route::get('/get/peminjamanlimit', 'Getpeminjamanlimit')->name('get.peminjamanlimit')->middleware('can:all.peminjaman');
        Route::get('/get/peminjamanrtlimit', 'Getpeminjamanrtlimit')->name('get.peminjamanrtlimit')->middleware('can:all.peminjaman');
        Route::get('/get/peminjamanin', 'GetpeminjamanIN')->name('get.peminjamanin');
        Route::get('/get/peminjamanrt', 'Getpeminjamanrt')->name('get.peminjamanrt');
        Route::get('/get/peminjamanstay', 'GetpeminjamanSTAY')->name('get.peminjamanstay');
        Route::get('/pdf/peminjaman', 'exportPdf')->name('pdf.peminjaman');
      
    });

      //color User All Color
      Route::controller(PhotoReturnController::class)->group(function () {
        Route::get('/all/photoreturn', 'Allphotoreturn')->name('all.photoreturn')->middleware('can:all.photoreturn');
        Route::get('/get/photoreturn', 'Getphotoreturn')->name('get.photoreturn')->middleware('can:all.photoreturn');
        Route::get('/get/photoreturnglobal', 'GetphotoreturnGlobal')->name('get.photoreturnGlobal');
        Route::get('/add/photoreturn', 'Addphotoreturn')->name('add.photoreturn')->middleware('can:add.photoreturn');
      
        Route::post('/store/photoreturn', 'Storephotoreturn')->name('store.photoreturn');
        Route::get('/edit/photoreturn/{id}', 'Editphotoreturn')->name('edit.photoreturn')->middleware('can:edit.photoreturn');
        Route::post('/update/photoreturn/{id}', 'Updatephotoreturn')->name('update.photoreturn');
        Route::get('/delete/photoreturn/{id}', 'Deletephotoreturn')->name('delete.photoreturn')->middleware('can:delete.photoreturn');;
        Route::get('/export/photoreturn', 'Exportphotoreturn')->name('export.photoreturn');
    });
      Route::controller(SerahTerimaController::class)->group(function () {
        Route::get('/all/serahterima', 'Allserahterima')->name('all.serahterima')->middleware('can:all.serahterima');
        Route::get('/get/serahterima', 'Getserahterima')->name('get.serahterima')->middleware('can:all.serahterima');
        Route::get('/get/serahterimaglobal', 'GetserahterimaGlobal')->name('get.serahterimaGlobal');
        Route::get('/add/serahterima', 'Addserahterima')->name('add.serahterima')->middleware('can:add.serahterima');
      
        Route::post('/store/serahterima', 'Storeserahterima')->name('store.serahterima')->middleware('can:add.serahterima');
        Route::get('/edit/serahterima/{id}', 'Editserahterima')->name('edit.serahterima')->middleware('can:edit.serahterima');
        Route::post('/update/serahterima/{id}', 'Updateserahterima')->name('update.serahterima');
        Route::get('/delete/serahterima/{id}', 'Deleteserahterima')->name('delete.serahterima')->middleware('can:delete.serahterima');;
        Route::get('/export/serahterima', 'Exportserahterima')->name('export.serahterima');
        Route::post('/import/serahterima', 'Importserahterima')->name('import.serahterima')->middleware('can:import.serahterima');
        Route::get('/import/serahterimas', 'Importserahterimas')->name('import.serahterimas')->middleware('can:import.serahterima');
    });
 



    Route::controller(TrainingController::class)->group(function () {
        Route::get('/all/training', 'AllTraining')->name('all.training')->middleware('can:all.training');
        Route::get('/add/training', 'AddTraining')->name('add.training')->middleware('can:add.training');
        Route::post('/store/training', 'StoreTraining')->name('store.training');
        Route::get('/edit/training/{id}', 'EditTraining')->name('edit.training')->middleware('can:edit.training');
        Route::post('/update/training/{id}', 'UpdateTraining')->name('update.training')->middleware('can:edit.training');
        Route::get('/delete/training/{id}', 'DeleteTraining')->name('delete.training')->middleware('can:delete.training');
        Route::get('/export/training', 'Exporttraining')->name('export.training')->middleware('can:export.training');;
        Route::get('/get/training', 'Gettraining')->name('get.training')->middleware('can:all.training');
        Route::get('/get/traininglimit', 'Gettraininglimit')->name('get.traininglimit')->middleware('can:all.training');
        Route::get('/get/trainingcount', 'GetTrainingCount')->name('get.trainingcount');
        Route::get('/get/posisitraining', 'GetPosisi')->name('get.posisitraining');
        Route::post('/check/training', 'CheckTraining')->name('check.training');
        Route::get('/print/training', 'PrintTraining')->name('print.training')->middleware('can:export.training');
        Route::post('/pdf/training', 'exportPDF')->name('pdf.training')->middleware('can:export.training');
        Route::post('/import/training', 'ImportTraining')->name('import.training')->middleware('can:import.training');
        Route::get('/import/trainings', 'Importtrainings')->name('import.trainings')->middleware('can:import.training');
      
    });

    
   



}); 

//end admin middleware
