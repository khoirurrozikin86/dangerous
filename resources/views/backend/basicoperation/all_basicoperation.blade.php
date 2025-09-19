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
                                    <h6 class="card-title text-center">Basic Operatiion All</h6>
                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <div class="col">
                                {{-- <nav class="page-breadcrumb">
                                    <ol class="breadcrumb">
                                        <a href="javascript:void(0)" class="btn btn-sm btn-primary mx-1"
                                            id="btn-create-basicoperation"><i class="feather-16" data-feather="file-plus"></i>
                                            &nbsp;Add</a>
                                        <a href="{{ route('print.basicoperation') }}" class="btn btn-sm btn-danger mx-1"
                                            ><i class="feather-16" data-feather="printer"></i>
                                            &nbsp;Print</a>
                                        <a href="{{ route('import.basicoperations') }}" class="btn btn-sm btn-success mx-1"
                                            ><i class="feather-16" data-feather="file-plus"></i>
                                            &nbsp;Import</a>

                                    </ol>   
                                </nav> --}}

                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" class="btn btn-primary" id="btn-create-basicoperation"><i
                                            class="feather-10" data-feather="plus"></i> &nbsp;Add</a>
                                    <a href="{{ route('print.basicoperation') }}" class="btn btn-primary"><i
                                            class="feather-10" data-feather="printer"></i> &nbsp;Print</a>
                                    {{-- <a href="{{ route('import.basicoperations') }}" class="btn btn-primary"><i
                                            class="feather-10" data-feather="upload"></i> &nbsp;Import</a> --}}
                                    <a href="{{ route('export.basicoperation') }}" class="btn btn-primary"><i
                                            class="feather-10" data-feather="download"></i> &nbsp;Export</a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">

                            <table id="basicoperationTable" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>OP CODE</th>
                                        <th>OP Name</th>
                                        <th>OP TYPE</th>
                                        <th>remark</th>
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

                    <form id="basicoperationForm" name="basicoperationForm">

                        <div class="alert alert-danger print-error-msg" style="display:none">

                            <ul></ul>

                        </div>

                        <input type="hidden" name="basicoperation_id" id="basicoperation_id">
                        <div class="mb-3">
                            <label for="op_code" class="form-label">OP Code:</label>
                            <input type="text" class="form-control" id="op_code" name="op_code" autofocus>

                        </div>
                        <div class="mb-3">
                            <label for="op_name" class="form-label">OP Name:</label>
                            <input type="text" class="form-control" id="op_name" name="op_name">
                        </div>
                        <div class="mb-3">
                            <label for="op_type" class="form-label">OP Type:</label>
                            <input type="text" class="form-control" id="op_type" name="op_type">
                        </div>

                        <div class="mb-3">
                            <label for="remark" class="form-label">Reamrk:</label>
                            <input type="text" class="form-control" id="remark" name="remark">
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




            var table = $('#basicoperationTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.basicoperation') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'

                    },
                    {
                        data: 'op_code',
                        name: 'op_code'
                    },
                    {
                        data: 'op_name',
                        name: 'op_name'
                    },
                    {
                        data: 'op_type',
                        name: 'op_type'
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

            $('#btn-create-basicoperation').click(function() {

                $('#saveBtn').html("save");
                $(this).find('form').trigger('reset');
                $('#basicoperationForm').find(".print-error-msg").find("ul").find("li").remove();
                $('#basicoperationForm').find(".print-error-msg").css('display', 'none');

                $('#saveBtn').val("create-basicoperation");
                $('#basicoperationForm').trigger("reset");
                $('#exampleModalLabel').html("Create New basicoperation");
                $('#basicoperation_id').val('');
                $('#modal-create').modal('show');
                $('#op_code').attr("readonly", false)
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

                    data: $('#basicoperationForm').serialize(),

                    url: "{{ route('store.basicoperation') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function(data) {

                        $('#basicoperationForm').trigger("reset");
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

                        $('#basicoperationForm').find(".print-error-msg").find("ul").html('');
                        $('#basicoperationForm').find(".print-error-msg").css('display',
                            'block');
                        $.each(data.responseJSON.errors, function(key, value) {
                            $('#basicoperationForm').find(".print-error-msg").find("ul")
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


            $('body').on('click', '.editbasicoperation', function() {

                var basicoperation_id = $(this).data('id');
                console.log(basicoperation_id);


                $.get("/edit/basicoperation/" + basicoperation_id, function(
                    data) {
                    $('#exampleModalLabel').html("Edit basicoperation");
                    $('#saveBtn').html("edit");
                    $('#modal-create').modal('show');
                    $('#basicoperation_id').val(data.id);
                    $('#op_code').val(data.op_code);
                    $('#op_name').val(data.op_name);
                    $('#op_type').val(data.op_type);
                    $('#remark').val(data.remark);

                    $('#op_code').attr("readonly", true)

                    $('#basicoperationForm').find(".print-error-msg").find("ul").find("li")
                        .remove();
                    $('#basicoperationForm').find(".print-error-msg").css('display', 'none');

                })

            });

            /*------------------------------------------

            --------------------------------------------

            Delete Product Code

            --------------------------------------------

            --------------------------------------------*/


            $('body').on('click', '.deletebasicoperation', function() {



                var basicoperation_id = $(this).data("id");

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
                            url: "/delete/basicoperation/" + basicoperation_id,
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
