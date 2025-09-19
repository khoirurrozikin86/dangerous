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
                                    <h6 class="card-title text-center">Item All</h6>
                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" class="btn btn-primary" id="btn-create-item"><i class="feather-10" data-feather="plus"></i>  &nbsp;Add</a>
                                    <a href="{{ route('print.item') }}" class="btn btn-primary"><i class="feather-10" data-feather="printer"></i>  &nbsp;Print</a>  
                                    <a href="{{ route('import.items') }}"  class="btn btn-primary"><i class="feather-10" data-feather="upload"></i>  &nbsp;Import</a>
                                    <a href="{{ route('export.item') }}"  class="btn btn-primary"><i class="feather-10" data-feather="download"></i>  &nbsp;Export</a>
                                  </div>
                            </div>
                        </div>


                        <div class="table-responsive">  

                            <table id="itemTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>SKU</th>
                                        <th>Name</th>
                                        <th>Posisi</th>
                                        <th>Category</th>
                                        <th>Unit</th>
                                        <th>Status</th>
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

                    <form id="itemForm" name="itemForm">

                        <div class="alert alert-danger print-error-msg" style="display:none">

                            <ul></ul>

                        </div>

                        <input type="hidden" name="item_id" id="item_id">
                        <div class="mb-3">
                            <label for="code" class="form-label">code:</label>
                            <input type="text" class="form-control" id="code" name="code" autofocus>

                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>

                        <div class="mb-3">
                            <label for="posisi" class="form-label">Posisi:</label>
                            <input type="text" class="form-control" id="posisi" name="posisi">
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select id="category" name="category" class="js-example-basic-single form-select" style="width: 100%">
                            

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="unit" class="form-label">Unit:</label>
                            <input type="text" class="form-control" id="unit" name="unit">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status:  isikan 0 (ready)</label>
                            <input type="number" class="form-control" id="status" name="status">
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

            $('#status').val('0');




            var table = $('#itemTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.item') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'

                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'posisi',
                        name: 'posisi'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },


                    {
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ]
            });




            /*------------------------------------------

            --------------------------------------------

            Click to Button

            --------------------------------------------

            --------------------------------------------*/

            $('#btn-create-item').click(function() {

                $('#saveBtn').html("save");
                $(this).find('form').trigger('reset');
                $('#itemForm').find(".print-error-msg").find("ul").find("li").remove();
                $('#itemForm').find(".print-error-msg").css('display', 'none');

                $('#saveBtn').val("create-item");
                $('#itemForm').trigger("reset");
                $('#exampleModalLabel').html("Create New item");
                $('#item_id').val('');
                $('#modal-create').modal('show');
                $('#code').attr("readonly", false)
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

                    data: $('#itemForm').serialize(),

                    url: "{{ route('store.item') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function(data) {

                        $('#itemForm').trigger("reset");
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

                        $('#itemForm').find(".print-error-msg").find("ul").html('');
                        $('#itemForm').find(".print-error-msg").css('display', 'block');
                        $.each(data.responseJSON.errors, function(key, value) {
                            $('#itemForm').find(".print-error-msg").find("ul")
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


            $('body').on('click', '.editItem', function() {

                var item_id = $(this).data('id');
                console.log(item_id);


                $.get("/edit/item/" + item_id, function(
                    data) {
                    $('#exampleModalLabel').html("Edit item");
                    $('#saveBtn').html("edit");
                    $('#modal-create').modal('show');
                    $('#item_id').val(data.id);
                    $('#code').val(data.code);
                    $('#name').val(data.name);
                    $('#category').val(data.category);
                    $('#posisi').val(data.posisi);
                    $('#unit').val(data.unit);
                    $('#status').val(data.status);

                    // $('#category').val(data.category_id).trigger('change');

                    $('#code').attr("readonly", true)

                    $('#itemForm').find(".print-error-msg").find("ul").find("li").remove();
                    $('#itemForm').find(".print-error-msg").css('display', 'none');

                })

            });

            /*------------------------------------------

            --------------------------------------------

            Delete Product Code

            --------------------------------------------

            --------------------------------------------*/


            $('body').on('click', '.deleteItem', function() {



                var item_id = $(this).data("id");

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
                            url: "/delete/item/" + item_id,
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


        $(document).ready(function() {
            // Fetch categories and update the dropdown
            $.ajax({
                url: "{{ route('get.categoryglobal') }}",
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var select = $('#category');
                    select.empty(); // Clear existing options
                    data.forEach(function(category) {
                        select.append($('<option>', {
                            value: category.name,
                            text: category.name
                        }));
                    });
                }
            });
        });
    </script>
@endsection
