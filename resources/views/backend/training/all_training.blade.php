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
                                    <h6 class="card-title text-center">TRAINING All</h6>
                                </div>

                                <hr />

                            </div>

                        </div>

                        <div class="row">
                            <div class="col">

                                <div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                                    {{-- <a href="javascript:void(0)" class="btn btn-primary" id="btn-create-training"><i
                                            class="feather-10" data-feather="plus"></i> &nbsp;Add</a> --}}
                                    {{-- <a href="{{ route('print.training') }}" class="btn btn-primary"><i class="feather-10"
                                            data-feather="printer"></i> &nbsp;Print</a> --}}

                                    <a href="{{ route('export.training') }}" class="btn btn-primary"><i class="feather-10"
                                            data-feather="download"></i> &nbsp;Export</a>
                                </div>
                                <hr>
                            </div>
                        </div>

                        <div class="table-responsive">

                            <table id="trainingTable" class="table table-sm" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>TRAINING NO</th>
                                        <th>DATE</th>
                                        <th>NIK</th>
                                        <th>NAME</th>
                                        <th>LINE</th>
                                        <th>OP CODE</th>
                                        <th>OP Name</th>

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

                    <form id="trainingForm" name="trainingForm">

                        <div class="alert alert-danger print-error-msg" style="display:none">

                            <ul></ul>

                        </div>

                        <input type="hidden" name="training_id" id="training_id">
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




            var table = $('#trainingTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.training') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'training_no',
                        name: 'training_no'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data, type, row) {
                            // Convert the data to a Date object
                            const date = new Date(data);

                            // Format the date to your preferred format (YYYY-MM-DD HH:mm:ss)
                            const formattedDate = date.toISOString().slice(0, 19).replace('T', ' ');

                            return formattedDate; // Return the formatted date
                        }
                    },
                    {
                        data: 'employee.nik',
                        name: 'employee.nik'
                    },
                    {
                        data: 'employee.name',
                        name: 'employee.name'
                    },
                    {
                        data: 'employee.posisi',
                        name: 'employee.posisi'
                    },
                    {
                        data: 'basicoperation.op_code',
                        name: 'basicoperation.op_code'
                    },
                    {
                        data: 'basicoperation.op_name',
                        name: 'basicoperation.op_name'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],

            });




            /*------------------------------------------

            --------------------------------------------

            Click to Button

            --------------------------------------------

            --------------------------------------------*/

            $('#btn-create-training').click(function() {

                $('#saveBtn').html("save");
                $(this).find('form').trigger('reset');
                $('#trainingForm').find(".print-error-msg").find("ul").find("li").remove();
                $('#trainingForm').find(".print-error-msg").css('display', 'none');

                $('#saveBtn').val("create-training");
                $('#trainingForm').trigger("reset");
                $('#exampleModalLabel').html("Create New training");
                $('#training_id').val('');
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

                    data: $('#trainingForm').serialize(),

                    url: "{{ route('store.training') }}",

                    type: "POST",

                    dataType: 'json',

                    success: function(data) {

                        $('#trainingForm').trigger("reset");
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

                        $('#trainingForm').find(".print-error-msg").find("ul").html('');
                        $('#trainingForm').find(".print-error-msg").css('display',
                            'block');
                        $.each(data.responseJSON.errors, function(key, value) {
                            $('#trainingForm').find(".print-error-msg").find("ul")
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


            $('body').on('click', '.edittraining', function() {

                var training_id = $(this).data('id');
                console.log(training_id);


                $.get("/edit/training/" + training_id, function(
                    data) {
                    $('#exampleModalLabel').html("Edit training");
                    $('#saveBtn').html("edit");
                    $('#modal-create').modal('show');
                    $('#training_id').val(data.id);
                    $('#op_code').val(data.op_code);
                    $('#op_name').val(data.op_name);
                    $('#op_type').val(data.op_type);
                    $('#remark').val(data.remark);

                    $('#op_code').attr("readonly", true)

                    $('#trainingForm').find(".print-error-msg").find("ul").find("li")
                        .remove();
                    $('#trainingForm').find(".print-error-msg").css('display', 'none');

                })

            });

            /*------------------------------------------

            --------------------------------------------

            Delete Product Code

            --------------------------------------------

            --------------------------------------------*/


            $('body').on('click', '.deletetraining', function() {



                var training_id = $(this).data("id");

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
                            url: "/delete/training/" + training_id,
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
