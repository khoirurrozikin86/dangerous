@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content mt-5">



        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <div>
                            <div class="row">
                              

                                <div class="col">
                                    <h6 class="card-title text-center">Serah Terima All</h6>
                                </div>
                              
                            </div>

                        </div>

                        <div class="row">
                            <div class="col">
                                

                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" class="btn btn-primary" id="btn-create-serahterima"><i class="feather-10" data-feather="plus"></i>  &nbsp;Add</a>
                                  
                                    <a href="{{ route('import.serahterimas') }}"  class="btn btn-primary"><i class="feather-10" data-feather="upload"></i>  &nbsp;Import</a>
                                    <a href="{{ route('export.serahterima') }}"  class="btn btn-primary"><i class="feather-10" data-feather="download"></i>  &nbsp;Export</a>
                                  </div>
                            </div>
                        </div>


                        <div class="table-responsive">

                            <table id="serahterimaTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>TRX</th>
                                        <th>NIK</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>Remark</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <br />
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>






    <!-- Modal -->
    <div class="modal fade" id="modal-create" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>

                </div>
                <div class="modal-body">

                    <form id="serahterimaForm" name="serahterimaForm">

                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>
                    
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK:</label>
                                    <input type="text" class="form-control" id="nik" name="nik" autofocus>
                                    <input type="hidden" class="form-control" id="serahterima_id" name="serahterima_id">
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name:</label>
                                    <input type="text" class="form-control" id="name" name="name">
                                </div>
                                <div class="mb-3">
                                    <label for="department" class="form-label">Department:</label>
                                    <input type="text" class="form-control" id="department" name="department">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="item_code" class="form-label">Item Code:</label>
                                    <input type="text" class="form-control" id="item_code" name="item_code">
                                </div>
                                <div class="mb-3">
                                    <label for="item_name" class="form-label">Item Name:</label>
                                    <input type="text" class="form-control" id="item_name" name="item_name">
                                </div>
                                <div class="mb-3">
                                    <label for="remark" class="form-label">Remark:</label>
                                    <input type="text" class="form-control" id="remark" name="remark">
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save</button>
                </div>
            </div>
        </div>
    </div>




    <script>
        $(function() {

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }

            });

            $('#modal-create').on('shown.bs.modal', function() {
                $(this).find('[autofocus]').focus();
            });




            var table = $('#serahterimaTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.serahterima') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'

                    },
                    {
                        data: 'no_trx',
                        name: 'no_trx'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'department',
                        name: 'department'
                    },
                    {
                        data: 'item_code',
                        name: 'item_code'
                    },
                    {
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'remark',
                        name: 'remark'
                    },


                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                
            });




            /*------------------------------------------

            --------------------------------------------

            Click to Button

            --------------------------------------------

            --------------------------------------------*/

            $('#btn-create-serahterima').click(function() {

                $('#saveBtn').html("save");
                $(this).find('form').trigger('reset');
                $('#serahterimaForm').find(".print-error-msg").find("ul").find("li").remove();
                $('#serahterimaForm').find(".print-error-msg").css('display', 'none');

                $('#saveBtn').val("create-serahterima");
                $('#serahterimaForm').trigger("reset");
                $('#exampleModalLabel').html("Create New serahterima");
                $('#serahterima_id').val('');
                $('#modal-create').modal('show');
                $('#nik').attr("readonly", false)
                $(this).find('[autofocus]').focus();

            });


            /*------------------------------------------

            --------------------------------------------

            Create Product Code

            --------------------------------------------

            --------------------------------------------*/

            $('#saveBtn').click(function(e) {

                e.preventDefault();

                $(this).html('Sending..');



                $.ajax({

                    data: $('#serahterimaForm').serialize(),

                    url: "{{ route('store.serahterima') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function(data) {

                        $('#serahterimaForm').trigger("reset");
                        $('#modal-create').modal('hide');
                        table.ajax.reload(null, false);
                        $('#saveBtn').html('SIMPAN');

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                        });

                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        })
                    },

                    error: function(data) {

                        $('#serahterimaForm').find(".print-error-msg").find("ul").html('');
                        $('#serahterimaForm').find(".print-error-msg").css('display', 'block');
                        $.each(data.responseJSON.errors, function(key, value) {
                            $('#serahterimaForm').find(".print-error-msg").find("ul")
                                .append(
                                    '<li>' + value + '</li>');
                        });
                        $('#saveBtn').html('SIMPAN');
                    }

                    // error: function(data) {
                    //     console.log('Error:', data);
                    //     $('#saveBtn').html('Save Changes');
                    // }

                });

            });

            /*------------------------------------------

            --------------------------------------------

            Click to Edit Button

            --------------------------------------------

            --------------------------------------------*/


            $('body').on('click', '.editserahterima', function() {

                var serahterima_id = $(this).data('id');
                console.log(serahterima_id);


                $.get("/edit/serahterima/" + serahterima_id, function(
                    data) {
                    $('#exampleModalLabel').html("Edit serahterima");
                    $('#saveBtn').html("edit");
                    $('#modal-create').modal('show');
                    $('#serahterima_id').val(data.id);
                    $('#nik').val(data.nik);
                    $('#name').val(data.name);
                    $('#department').val(data.department);
                    $('#item_code').val(data.item_code);
                    $('#item_name').val(data.item_name);
                    $('#remark').val(data.remark);

                    // $('#nik').attr("readonly", true)

                    $('#serahterimaForm').find(".print-error-msg").find("ul").find("li").remove();
                    $('#serahterimaForm').find(".print-error-msg").css('display', 'none');

                })

            });

            /*------------------------------------------

            --------------------------------------------

            Delete Product Code

            --------------------------------------------

            --------------------------------------------*/


            $('body').on('click', '.deleteserahterima', function() {



                var serahterima_id = $(this).data("id");

                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger me-2'
                    },
                    buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            type: "GET",
                            url: "/delete/serahterima/" + serahterima_id,
                            success: function(data) {
                                table.ajax.reload(null, false);

                                swalWithBootstrapButtons.fire({
                                    title: 'Deleted!',
                                    text: 'Your file has been deleted.',
                                    icon: 'success',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        // Optional: Add any additional actions you want to perform after the alert closes
                                    }
                                })
                            },
                            error: function(data) {
                                console.log('Error:', data);

                                swalWithBootstrapButtons.fire({
                                    title: 'Cancelled!',
                                    text: `'There is relation data'.${data.responseJSON.message}`,
                                    icon: 'error',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        // Optional: Add any additional actions you want to perform after the alert closes
                                    }
                                })



                            }
                        });


                    } else if (
                        // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire({
                            title: 'Cancelled!',
                            text: 'Your file is safe :)',
                            icon: 'error',
                            timer: 2000,
                            timerProgressBar: true,
                            willClose: () => {
                                // Optional: Add any additional actions you want to perform after the alert closes
                            }
                        })
                    }
                })

            });




        });
    </script>
@endsection
