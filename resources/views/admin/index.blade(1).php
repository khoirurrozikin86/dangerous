@extends('admin.admin_dashboard')

@section('admin')
    <style>
        .table1 tr th {
            background: #35A9DB;
            color: #fff;
            font-weight: normal;
        }

        td {
            text-align: center;
        }
    </style>
    <div class="page-content">

        <div class="align-items-center">
            <div>
                {{-- <h4 class="mb-3 mb-md-0 text-center">Dashboard Peminjaman </h4> --}}
                <h2 class="time-now text-center" id="timenow"></h2>
                <div class="date-now text-center" id="datenow"></div>
            </div>

        </div>

        <div class="row">
            <div class="col-12 col-xl-12 stretch-card">
                <div class="area-datetime  align-items-center">

                </div>

            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 col-xl-12 stretch-card">
                <div class="row flex-grow-1">
                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body bg-danger text-white">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">ITEM</h6>

                                </div>
                                <div class="row">
                                    <h1 class="txt-count mb-2" id="txt-count-item">0</h1>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body bg-primary text-white">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">Employee (BORROW) </h6>

                                </div>
                                <div class="row">
                                    <h1 class="txt-count mb-2" id="txt-count-emp">0</h1>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body bg-secondary text-white">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">PEMINJAMAN</h6>

                                </div>
                                <div class="row">
                                    <h1 class="txt-count mb-2" id="txt-count-out">0</h1>

                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body bg-info text-white">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <h6 class="card-title mb-0">ITEM (OUT)</h6>

                                </div>
                                <div class="row">
                                    <h1 class="txt-count mb-2" id="txt-count-stay">0</h1>

                                    <div class="col-6 col-md-12 col-xl-7">
                                        <div id="growthChart" class="mt-md-3 mt-xl-0"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- row -->

        <div class="row mb-3">
            <div class="col-12">
                <h6>Peminjaman Hari ini</h6>
                <div style="overflow-x:auto;">
                    <table class="table table1">
                        <tr>
                            <th>SEW</th>
                            <th>QC</th>
                            <th>PACK</th>
                            <th>CUTT</th>
                            <th>MEK</th>
                            <th>SPL</th>
                            <th>WH</th>
                            <th>FOLD</th>
                            <th>PRINT</th>
                            <th>IRON</th>
                            <th>OTHER</th>
                            <th>NOT RETURN</th>

                        </tr>
                        <tr>
                            <td id="put_sewing"></td>
                            <td id="put_qc"></td>
                            <td id="put_packing"></td>
                            <td id="put_cutting"></td>
                            <td id="put_mekanik"></td>
                            <td id="put_sample"></td>
                            <td id="put_wh"></td>
                            <td id="put_folding"></td>
                            <td id="put_print"></td>
                            <td id="put_iron"></td>
                            <td id="put_other"></td>
                            <td id="put_not_return"></td>

                        </tr>

                    </table>
                </div>

            </div>

        </div>

    </div>

    <script>
          Pusher.logToConsole = true;
        var pusher = new Pusher('fb483b4646ebb3e3a5a7', {
            cluster: 'ap1'
        });

        var channel = pusher.subscribe('peminjaman-channel');
        channel.bind('peminjaman-updated', function(data) {

            $('#txt-count-item').text(data.itemCount);
            $('#txt-count-emp').text(data.employeeCount);
            $('#txt-count-out').text(data.peminjamanCount);
            $('#txt-count-stay').text(data.itemOutCount);
        });

        var channelx = pusher.subscribe('category-channel');
        channelx.bind('category-updated', function(data) {
            console.log('Category updated data:', data); // Tambahkan log
            // Update HTML elements using jQuery
            $('#put_sewing').text(data.category.SEW);
            $('#put_qc').text(data.QC);
            $('#put_packing').text(data.PACK);
            $('#put_cutting').text(data.CUTT);
            $('#put_mekanik').text(data.MEK);
            $('#put_sample').text(data.SPL);
            $('#put_wh').text(data.WH);
            $('#put_folding').text(data.FOLD);
            $('#put_print').text(data.PRINT);
            $('#put_iron').text(data.IRON);
            $('#put_other').text(data.OTHER);
            $('#put_not_return').text(data.NOT_RETURN);
        });




        // A $( document ).ready() block.
        $(document).ready(function() {

            fetchTotalPeminjaman();
            fetchcountdepartment();


        });


        function fetchcountdepartment() {
            $.ajax({
                url: '/get/peminjaman_department',
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#put_sewing').text(response.data.SEW);
                        $('#put_qc').text(response.data.QC);
                        $('#put_packing').text(response.data.PACK);
                        $('#put_cutting').text(response.data.CUTT);
                        $('#put_mekanik').text(response.data.MEK);
                        $('#put_sample').text(response.data.SPL);
                        $('#put_wh').text(response.data.WH);
                        $('#put_folding').text(response.data.FOLD);
                        $('#put_print').text(response.data.PRINT);
                        $('#put_iron').text(response.data.IRON);
                        $('#put_other').text(response.data.OTHER);
                        $('#put_not_return').text(response.data.NOT_RETURN);
                    } else {
                        console.error('Error fetching data: ', response.message);
                    }
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        }

        function fetchTotalPeminjaman() {
            $.ajax({
                url: "{{ route('get.peminjaman_today') }}",
                method: "GET",
                success: function(response) {
                    $('#txt-count-item').text(response.data.ITEM);
                    $('#txt-count-emp').text(response.data.EMPLOYEE_BORROW);
                    $('#txt-count-out').text(response.data.PEMINJAMAN);
                    $('#txt-count-stay').text(response.data.ITEM_OUT);
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan saat mengambil total peminjaman:", error);
                }
            });
        }




        //intital tanggal dan waktu dari id
        var dateDisplay = document.getElementById("datenow");
        var timeDisplay = document.getElementById("timenow");
        //fungsi
        function refreshTime() {
            var dateString = new Date().toLocaleString("id-ID", {
                imeZone: "Asia/Jakarta"
            }); //gettime
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var todayy = dd + '/' + mm + '/' + yyyy;
            var formattedString = dateString.replace(",", "-");
            dateDisplay.innerHTML = todayy; // date 

            var splitarray = new Array();
            splitarray = formattedString.split(" ");
            var splitarraytime = new Array();
            splitarraytime = splitarray[1].split(".");
            timeDisplay.innerHTML = splitarraytime[0] + ':' + splitarraytime[1] + ':' +
                splitarraytime[2]; // time 
        }
        //panggil ulang otomatis fungsi 
        setInterval(refreshTime, 1000);
    </script>
@endsection
