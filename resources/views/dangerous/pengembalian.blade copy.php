<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Responsive HTML Admin Dashboard Template based on Bootstrap 5">
    <meta name="author" content="needle">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="keywords"
        content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <title>E - DANGEROUS</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- End fonts -->

    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/core/core.css') }}">

    <link rel="stylesheet" href="{{ asset('backend/assets/fonts/feather-font/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/vendors/sweetalert2/sweetalert2.min.css') }}">

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/demo1/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">
    <!-- End layout styles -->

    <link rel="stylesheet" href="{{ asset('css/toastr.css') }}">

    {{-- <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" /> --}}

    <!-- javascript -->

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('js/pusher.min.js') }}"></script>

    <style>
        /* style jam digital */
        .time-now {
            font-size: 60px;
            font-weight: bold;
            text-align: center;
            color: rgb(255, 230, 0);
            padding: 0px 0px 0px 0px;
        }

        .date-now {
            font-size: 30px;
            font-weight: 600;
            color: rgb(197, 197, 197);
            text-align: center;
            padding: 0px 0px 0px 0px;
        }

        .menu-now {
            font-size: 10px;
            font-weight: 600;
            color: rgb(197, 197, 197);
            text-align: center;
            padding: 0px 0px 0px 0px;
        }

        .area-datetime {
            margin-top: 0px;
            padding: 0px;
            width: 100%;
            height: 150px;
            background-color: black;
        }

        /* .table-pinjam {
            margin-top: 0px;
            padding: 0px;
            width: 100%;
            height: 290px;

        } */
        .area-input {
            margin-top: 0px;
            padding: 0px;
            width: 100%;
            height: 130px;
        }

        .card {
            background-color: black;
        }

        .card-title {
            text-align: center;
            font-weight: 600;
            color: rgb(197, 197, 197);
        }

        .txt-count {
            font-weight: bold;
            text-align: center;
            color: rgb(255, 230, 0);
        }

        .txt-counts {
            font-weight: bold;
            font-size: 50px;
            text-align: center;
            color: rgb(255, 17, 17);
        }

        .hidden-input {
            display: none;
        }

        .menu-now {
            font-size: 10px;
            font-weight: 600;
            color: rgb(197, 197, 197);
            text-align: center;
            padding: 0px 0px 0px 0px;
        }

        .pinjam-now {
            font-size: 30px;
            font-weight: 600;
            color: rgb(197, 197, 197);
            text-align: center;
            padding: 0px 0px 0px 0px;
        }
    </style>

</head>

<body class="bg-black" id="content-scan" onclick="openFullscreen();">

    <div class="p-1">

        <!-- partial -->
        <div class="page-wrapper">
            <div class="">
                <div class="row">
                    <div class="col-12 col-xl-12 stretch-card">
                        <div class="area-datetime  align-items-center">
                            <div class="menu-now">PENGEMBALIAN</div>
                            <div class="time-now" id="timenow"></div>
                            <div class="date-now" id="datenow"></div>

                        </div>
                    </div>
                </div>

                <div class="row px-6 area-input">
                    <form id="TransactionForm" name="TransactionForm">
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <p class="txt-counts" id="txt-name">-</p>

                                <input type="text" class="form-control hidden-input" id="remark" name="remark">

                                <input type="text" class="form-control" id="nik" name="nik"
                                    placeholder="NIK" autofocus required>

                                <input type="text" class="form-control hidden-input" id="employee_id"
                                    name="employee_id" required>

                            </div>

                            <div class="col-6">
                                <p class="txt-counts" id="txt-name-item">-</p>

                                <input type="text" class="form-control" id="sku" name="sku"
                                    placeholder="SKU" autofocus required>

                                <input type="text" class="form-control hidden-input" id="item_id" name="item_id"
                                    required>

                            </div>

                        </div>

                    </form>

                </div>

                <div class="row py-2 px-6 table-pinjam">

                    <table id="dataTableExamplex" class="table bg-white">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NO TRX</th>
                                <th>DATE</th>
                                <th>EMP ID</th>
                                <th>EMP NAME</th>
                                <th>DEPT.</th>
                                <th>SKU</th>
                                <th>NAME</th>
                                <th>NO TRX RTN</th>
                                <th>DATE</th>
                                <th>REMARK</th>
                            </tr>
                        </thead>
                        <tbody id="transaction-table-body">

                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>



    <script type="text/javascript">
        $(document).ready(function() {



            $('#remark').val('KEMBALI');
            fetchTransactions();
            fetchTotalPeminjaman();

            clear_input();

            $("#sku").prop('disabled', true);


            $("#nik").on('keyup', function(e) {

                if (e.keyCode === 13) {

                    var nik = $(this).val();

                    $.ajax({
                        url: "{{ route('check.employee') }}",
                        method: "POST",
                        data: {
                            nik: nik,
                            _token: '{{ csrf_token() }}' // Sertakan token CSRF
                        },
                        success: function(response) {
                            if (response.id) {
                                $("#employee_id").val(response.id);
                                var truncatedName = response.name.substring(0, 15);
                                $("#txt-name").html(truncatedName);
                                $('#sku').prop('disabled', false); // Enable SKU field
                                $('#sku').focus();


                            } else {
                                const Toast = Swal.mixin({

                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });

                                Toast.fire({
                                    icon: 'error',
                                    title: 'NIK tidak ditemukan!'
                                })

                                $('#nik').val('');
                                $('#sku').val('');
                                $('#txt-name').html('-');
                                $('#sku').prop('disabled', true); // Keep SKU field disabled
                            }
                        }

                    });
                }
            });

            $("#sku").on('keyup', function(e) {
                if (e.keyCode === 13) {
                    var sku = $(this).val();
                    $.ajax({
                        url: "{{ route('check.item') }}", // URL endpoint to check SKU
                        method: "POST",
                        data: {
                            sku: sku,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.id) {
                                $("#item_id").val(response.id);
                                var truncatedItemName = response.name.substring(0, 15);
                                $("#txt-name-item").html(truncatedItemName);


                                saveTransaction();
                                clear_input();
                            } else {
                                const Toast = Swal.mixin({

                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });

                                Toast.fire({
                                    icon: 'error',
                                    title: 'SKU tidak ditemukan!'
                                })

                                $('#sku').val('');
                                $('#txt-name-item').text('-');
                            }
                        }
                    });
                }
            });




        });

        function clear_input() {
            $('#nik').val('');
            $('#employee_id').val('');
            $('#sku').val('');
            $('#txt-name').text('-');
            $('#txt-name-item').text('-');
            $('#sku').prop('disabled', true); // Disable SKU field

        }



        function saveTransaction() {

            var employee_id = $("#employee_id").val();
            var item_id = $("#item_id").val();
            var remark = $("#remark").val();

            $.ajax({
                url: "{{ route('store.peminjaman') }}",
                method: "POST",
                data: {
                    employee_id: employee_id,
                    item_id: item_id,
                    remark: remark,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Transaksi berhasil disimpan!',

                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        });

                        // Perbarui jumlah IN

                        fetchTransactions();
                        // fetchTotalPeminjaman();
                        clear_input();
                        $('#nik').focus();

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: response.message,

                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                        });

                        clear_input();
                        $('#nik').focus();

                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan!',

                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                }
            });
        }


        function fetchTotalPeminjaman() {
            $.ajax({
                url: "{{ route('get.peminjaman_today') }}",
                method: "GET",
                success: function(response) {
                    $('#pinjam-now').text(response.total);
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan saat mengambil total peminjaman:", error);
                }
            });
        }

        function formatDateTime(dateTimeString) {
            const date = new Date(dateTimeString);
            const options = {
                timeZone: 'Asia/Jakarta'
            };
            return date.toLocaleDateString('id-ID', options) + ' ' + date.toLocaleTimeString('id-ID', options).replace(
                '.000000Z', '');
        }


        function fetchTransactions() {
            $.ajax({
                url: "{{ route('get.peminjamanrtlimit') }}",
                method: "GET",
                success: function(response) {
                    var tbody = $('#transaction-table-body');
                    tbody.empty();
                    response.forEach(function(transaction, index) {

                        const createdAt = formatDateTime(transaction.created_at);
                        const updatedAt = transaction.updated_at !== transaction.created_at ?
                            formatDateTime(transaction.updated_at) : '';


                        var row = `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${transaction.no_trx_out}</td>
                                    <td>${createdAt}</td>
                                    <td>${transaction.employee.nik}</td>
                                    <td>${transaction.employee.name}</td>
                                    <td>${transaction.employee.department}</td>
                                    <td>${transaction.item.code}</td>
                                    <td>${transaction.item.name}</td>
                                    <td>${transaction.no_trx_return}</td>
                                    <td>${updatedAt}</td>
                                    <td>
                                        ${transaction.remark 
                                            ? (transaction.remark === 'KEMBALI' 
                                                ? `<span class="badge bg-success">${transaction.remark}</span>` 
                                                : `<span class="badge bg-danger">${transaction.remark}</span>`)
                                            : ''}
                                    </td>
                                </tr>
                            `;
                        tbody.append(row);
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan saat memuat data transaksi!',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                    });
                }
            });

        }



        function openFullscreen() {
            var elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.webkitRequestFullscreen) {
                /* Safari */
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                /* IE11 */
                elem.msRequestFullscreen();
            }

            $("#nik").focus();
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
        setInterval(refreshTime, 2000);
    </script>

</body>

</html>

<script src="{{ asset('backend/assets/vendors/feather-icons/feather.min.js') }}"></script>