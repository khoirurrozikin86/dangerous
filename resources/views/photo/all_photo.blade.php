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
                                    <h6 class="card-title text-center">Photo All</h6>

                                </div>

                            </div>

                        </div>

                        <div class="row mb-3 py-3 px-6">
                            <div class="col-md-4">
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" class="form-control">
                            </div>
                            <div class="col-md-4 align-self-end mt-2">
                                <button id="filterBtn" class="btn btn-primary btn-sm">Filter</button>
                                <button class="btn btn-success btn-sm" id="export-excel">Export</button>

                            </div>

                        </div>

                        <div class="table-responsive">
                            <table id="dataTableExamplex" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>department</th>
                                        <th>remark</th>
                                        <th>creator</th>
                                        <th>Photo1</th>
                                        <th>Photo2</th>
                                        <th>Photo3</th>
                                        <th>Photo4</th>
                                        <th>Photo5</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('#filterBtn').on('click', function() {
                cari_data();
            });

        });

        // Event handler for the export button
        $('#export-excel').click(function() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            window.location.href = '{{ route('export.photoreturn') }}?start_date=' + startDate +
                '&end_date=' + endDate; // Redirect to the server-side export route
        });




        function cari_data() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            // $('#tampils').show(); 

            // Initialize DataTable
            var table = $('#dataTableExamplex').DataTable({
                "processing": true, // Show processing indicator
                "serverSide": true, // Enable server-side processing
                "destroy": true,
                "ajax": {
                    "url": "{{ route('get.photoreturn') }}",
                    "type": "GET",
                    "data": {
                        start_date: startDate,
                        end_date: endDate,
                        _token: '{{ csrf_token() }}'
                    }
                },

                "columns": [{
                        "data": "DT_RowIndex",
                        "name": "DT_RowIndex"
                    },
                    {
                        "data": "department",
                        "name": "department"
                    },
                    {
                        "data": "remark",
                        "name": "remark"
                    },
                    {
                        "data": "creator",
                        "name": "creator"
                    },
                    {
                        "data": "photo1",
                        "name": "photo1"
                    },
                    {
                        "data": "photo2",
                        "name": "photo2"
                    },
                    {
                        "data": "photo3",
                        "name": "photo3"
                    },
                    {
                        "data": "photo4",
                        "name": "photo4"
                    },
                    {
                        "data": "photo5",
                        "name": "photo5"
                    },

                    {
                        "data": "action",
                        "name": "action"
                    }
                ],

            });



            $('body').on('click', '.deletePhoto', function() {



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
                            url: "/delete/photoreturn/" + item_id,
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
        }
    </script>
@endsection
