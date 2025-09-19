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
                                    <h6 class="card-title text-center">DANGEOURS TOOL</h6>

                                </div>

                            </div>

                        </div>

                        <div class="row">
                            <form id="filter-form">

                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="start_date">Tanggal Mulai:</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control"
                                                required>
                                        </div>

                                    </div>
                                    <div class="col-6">

                                        <div class="form-group">
                                            <label for="end_date">Tanggal Akhir:</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control"
                                                required>
                                        </div>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-danger mt-2" id="tampilkan">Tampilkan</button>

                                <button class="btn btn-primary mt-2 btn-sm" id="export-excel"><i class="feather-10"
                                        data-feather="download"></i> &nbsp;Export</button>

                            </form>
                        </div>

                        <div class="tampils" id="tampils" style="display: none">

                            <div class="table-responsive">
                                <table id="dataTableExamplex" class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>TRX OUT</th>
                                            <th>DATE IN</th>
                                            <th>NIK</th>
                                            <th>NAME</th>
                                            <th>DEPT.</th>
                                            <th>TRX RETURN</th>
                                            <th>SKU</th>
                                            <th>NAME</th>
                                            <th>DATE OUT</th>
                                            <th>REMARK</th>

                                            <th>Action</th>

                                        </tr>
                                    </thead>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#filter-form').submit(function(event) {
                event.preventDefault(); // Prevent the form from submitting

                cari_data();


            });


            // Event handler for the export button
            $('#export-excel').click(function() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                window.location.href = '{{ route('export.peminjaman') }}?start_date=' + startDate +
                    '&end_date=' + endDate; // Redirect to the server-side export route
            });



        });



        function cari_data() {
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            $('#tampils').show(); // Show the DataTable container

            // Initialize DataTable
            var table = $('#dataTableExamplex').DataTable({
                "processing": true, // Show processing indicator
                "serverSide": true, // Enable server-side processing
                "destroy": true,
                "ajax": {
                    "url": '{{ route('get.peminjaman') }}',
                    "type": "GET",
                    "data": {
                        start_date: startDate,
                        end_date: endDate,
                        _token: '{{ csrf_token() }}'
                    }
                },
                "columnDefs": [{
                    "targets": [2, 9], // Index of the 'created_at' and 'updated_at' columns (zero-based)
                    "render": function(data, type, row) {
                        // Format the date and time
                        var dateTime = new Date(data);
                        if (isNaN(dateTime)) {
                            return ''; // Return empty string for invalid dates
                        }
                        var formattedDateTime = dateTime.toLocaleDateString('id-ID') + ' ' + dateTime
                            .toLocaleTimeString('id-ID');
                        return formattedDateTime;
                    }
                }],
                "columns": [{
                        "data": "DT_RowIndex",
                        "name": "DT_RowIndex"
                    },
                    {
                        "data": "no_trx_out",
                        "name": "no_trx_out"
                    },
                    {
                        "data": "created_at",
                        "name": "created_at"
                    },
                    {
                        "data": "employee.nik",
                        "name": "employee.nik"
                    },
                    {
                        "data": "employee.name",
                        "name": "employee.name"
                    },
                    {
                        "data": "employee.department",
                        "name": "employee.department"
                    },
                    {
                        "data": "no_trx_return",
                        "name": "no_trx_return"
                    },
                    {
                        "data": "item.code",
                        "name": "item.code"
                    },
                    {
                        "data": "item.name",
                        "name": "item.name"
                    },
                    {
                        "data": "updated_at",
                        "name": "updated_at"
                    },
                    {
                        "data": "remark",
                        "name": "remark"
                    },
                    {
                        "data": "action",
                        "name": "action"
                    }
                ],

            });



            $('body').on('click', '.deletePeminjaman', function() {



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
                            url: "/delete/peminjaman/" + item_id,
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
