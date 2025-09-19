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





                            </form>
                        </div>



                        <div class="" id="table-container" style="display: none">


                            <div class="row mt-3">
                                <div class="col">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button class="btn btn-primary" id="export-excel"><i class="feather-10"
                                                data-feather="download"></i> &nbsp;Export</button>
                                    </div>
                                </div>
                            </div>


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
            $('#tampilkan').click(function() {
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();
                console.log(startDate);

                // Check if both start date and end date are provided
                if (startDate && endDate) {
                    $.ajax({
                        url: '{{ route('get.peminjamanoke') }}',
                        type: 'GET',
                        data: {
                            start_date: startDate,
                            end_date: endDate
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(data) {
                            console.log('Data received:', data);
                            $('#table-container').show();
                            $('#dataTableExamplex').html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error('Error occurred while fetching data:', error);
                            alert('Error occurred while fetching data. Please try again.');
                        }
                    });
                } else {
                    // Display an alert if either start date or end date is missing
                    alert('Please provide both start date and end date.');
                }
            });
        });
    </script>
@endsection

