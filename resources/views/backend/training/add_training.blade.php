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

    <title>E - TRAINING</title>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="https://unpkg.com/@zxing/library@latest"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        /* style jam digital */
        .time-now {
            font-size: 40px;
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

        .pinjam-now {
            font-size: 30px;
            font-weight: 600;
            color: rgb(197, 197, 197);
            text-align: center;
            padding: 0px 0px 0px 0px;
        }

        .area-datetime {
            margin-top: 0px;
            padding: 0px;
            width: 100%;
            background-color: black;
        }

        .area-input {
            margin-top: 0px;
            padding: 0px;
            width: 100%;
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
            font-size: 30px;
            text-align: center;
            color: rgb(255, 17, 17);
        }

        .hidden-input {
            display: none;
        }

        .badge {
            cursor: pointer;
            transition: background-color 0.3s ease;
            /* Efek transisi */
        }

        /* Hover effect untuk badge */
        .badge:hover {
            background-color: #ff5c5c;
            /* Ubah warna saat di-hover */
            color: white;
            /* Ubah warna teks jika perlu */
        }
    </style>

</head>

<body class="bg-white" id="content-scan">

    <div class="p-1">

        <!-- partial -->
        <div class="page-wrapper">
            <div class="">
                <div class="row">
                    <div class="col-12 col-xl-12 stretch-card">
                        <div class="area-datetime  text-center">
   
    							
                            <a href="{{ route('all.training') }}" class="time-now text-center">TRAINING</a>
                        </div>
                    </div>
                </div>

                <div class="row px-3 px-md-6 area-input mt-5">
                    <form id="TransactionForm" name="TransactionForm" class="w-100">
                        <div class="alert alert-danger print-error-msg" style="display:none">
                            <ul></ul>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6 mb-3">
                                <input type="text" class="form-control hidden-input" id="remark" name="remark">
                                <input type="text" class="form-control hidden-input" id="training_id" name="training_id">

                                <div class="input-group">
                                    <input type="text" class="form-control" id="nik" name="nik" placeholder="NIK" autofocus required>
                                    <button type="button" id="scan-button" class="btn btn-primary">Scan NIK</button>
                                </div>

                                <input type="text" class="form-control hidden-input" id="employee_id" name="employee_id" required>
                                <p class="txt-counts" id="txt-name">-</p>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <div class="input-group mb-3">
                                    <select class="form-control" id="sku" name="sku" required>
                                        <option value="" disabled selected>Select Operation</option>
                                    </select>
                                    <input type="text" class="form-control hidden-input" id="basicoperation_id" name="basicoperation_id" required>
                                    <button type="button" id="save-button" class="btn btn-success" style="display: none;">Simpan</button>
                                </div>
                                <p class="txt-counts mt-3" id="txt-name-item">-</p>
                            </div>
                        </div>

                        <!-- Add video modal for scanning -->
                        <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="videoModalLabel">Scan NIK</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <video id="video" class="w-100" autoplay></video>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="row py-2 px-3 px-md-6 table-pinjam mt-1">
                    <div class="table-responsive">
                        <table id="dataTableExamplex" class="table bg-white mt-2">
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
                            <tbody id="transaction-table-body">
                                <!-- Rows will be populated dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

    </div>


    <script type="text/javascript">
        let codeReader;
        const videoElement = document.getElementById('video');

        function startCamera() {
      // Check for mediaDevices API support
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        alert('getUserMedia is not supported in this browser');
        return;
    }

    // Initialize the QR code reader
    if (!codeReader) {
        codeReader = new ZXing.BrowserQRCodeReader();
    }

    const videoElement = document.getElementById('video');
    const videoModalElement = document.getElementById('videoModal');
    const videoModal = new bootstrap.Modal(videoModalElement);
    videoModal.show();

    // Access the camera using getUserMedia
    navigator.mediaDevices.getUserMedia({ video: true })
        .then((stream) => {
            videoElement.srcObject = stream;
            videoElement.play();

            // Start decoding the QR code from the video stream
            codeReader.decodeFromVideoDevice(null, videoElement, (result, err) => {
                if (result) {
                    console.log("QR Code Scanned: ", result.text); // Log scanned NIK
                    document.getElementById('nik').value = result.text;
                    $('#nik').change(); // Trigger the change event to validate NIK automatically

                    stopCamera(); // Stop the camera after successful scan
                    videoModal.hide(); // Hide the modal
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                }
            });
        })
        .catch((error) => {
            console.error("Unable to access the camera: ", error);
        });
        }

        function stopCamera() {
            console.log("Stopping camera..."); // Tambahkan log untuk debugging
            if (codeReader) {
                codeReader.reset(); // Stop the camera
                const videoElement = document.getElementById('video');
                videoElement.srcObject = null; // Clear the video source
                console.log("Camera stopped.");
            } else {
                console.log("Camera already stopped or not initialized.");
            }
        }

        // Event listener for the scan button
        document.getElementById('scan-button').addEventListener('click', () => {
            startCamera(); // Start the camera when the button is clicked
        });

        $('#videoModal').on('hidden.bs.modal', function() {
            stopCamera();
        });

        // AJAX call to fetch employee data based on NIK
        $("#nik").on('change', function() { // Change event listener
            var nik = $(this).val().trim(); // Get the NIK value and trim spaces
            console.log("Input NIK: ", nik); // Log the input NIK

            if (nik.length > 0) { // Proceed only if NIK is not empty
                $.ajax({
                    url: "{{ route('check.employee') }}",
                    method: "POST",
                    data: {
                        nik: nik,
                        _token: '{{ csrf_token() }}' // Include CSRF token
                    },
                    success: function(response) {
                        console.log("AJAX Response: ", response); // Log the AJAX response
                        if (response.id) {
                            $("#employee_id").val(response.id);
                            var truncatedName = response.name.substring(0, 15);
                            $("#txt-name").html(truncatedName);
                            $('#sku').prop('disabled', false); // Enable SKU field
                            $('#sku').focus();
                        } else {
                            const Toast = Swal.mixin({
                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                            });

                            Toast.fire({
                                icon: 'error',
                                title: 'NIK tidak ditemukan!'
                            });

                            $('#employee_id').val(''); // Clear employee ID if not found
                            $('#sku').val('');
                            $('#txt-name').html('-');
                            $('#sku').prop('disabled', true); // Keep SKU field disabled
                        }
                    },
                    error: function(xhr) {
                        console.error("AJAX Error: ", xhr); // Log any errors from the AJAX call
                    }
                });
            } else {
                // Clear fields if NIK is empty
                $('#employee_id').val('');
                $('#txt-name').html('-');
                $('#sku').val('');
                $('#sku').prop('disabled', true); // Keep SKU field disabled
            }
        });



        $("#nik").on('keyup', function(e) {
            if (e.keyCode === 13) {
                var nik = $(this).val();

                $.ajax({
                    url: "{{ route('check.employee') }}",
                    method: "POST",
                    data: {
                        nik: nik,
                        _token: '{{ csrf_token() }}' // Include CSRF token
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
                                timer: 1000,
                                timerProgressBar: true,
                            });

                            Toast.fire({
                                icon: 'error',
                                title: 'NIK tidak ditemukan!'
                            });

                            $('#nik').val('');
                            $('#sku').val('');
                            $('#txt-name').html('-');
                            $('#sku').prop('disabled', true); // Keep SKU field disabled
                        }
                    }
                });
            }
        });





        window.addEventListener('beforeunload', stopCamera); // Stop scanning when leaving the page








        $(document).ready(function() {
            // ... existing code ...

            // Fetch basic operations and populate the dropdown
            fetchBasicOperations();

            // Event for SKU dropdown change
            $("#sku").on('change', function() {
                var selectedOption = $(this).find("option:selected");
                var basicOperationId = selectedOption.val();
                var basicOperationName = selectedOption.text();

                $("#basicoperation_id").val(basicOperationId);
                $("#txt-name-item").text(basicOperationName);

                const selectedOperation = $(this).val();
                if (selectedOperation) {
                    // Tampilkan tombol simpan jika opsi dipilih
                    $('#save-button').show();
                } else {
                    // Sembunyikan tombol simpan jika tidak ada opsi yang dipilih
                    $('#save-button').hide();
                }




            });
        });

        // Function to fetch basic operations
        function fetchBasicOperations() {
            $.ajax({
                url: "{{ route('get.basicoperationdata') }}", // Update with your route
                method: "GET",
                success: function(response) {
                    var skuDropdown = $('#sku');
                    skuDropdown.empty().append(
                        '<option value="" disabled selected>Select Operation</option>'); // Reset dropdown

                    response.forEach(function(operation) {
                        skuDropdown.append(
                            `<option value="${operation.id}">${operation.op_name}</option>`);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching basic operations:", error);
                }
            });
        }

        $(document).ready(function() {
            // Load 100 data saat halaman dibuka
            loadTrainings();
            clear_input();

            function clear_input() {
                $('#nik').val('');
                $('#employee_id').val('');
                $('#sku').val('');
                $('#txt-name').text('-');
                $('#txt-name-item').text('-');
                $('#sku').prop('disabled', true); // Disable SKU field

            }

            function loadTrainings() {
                $.ajax({
                    url: "{{ route('get.traininglimit') }}", // Pastikan route ke controller benar
                    method: 'GET',
                    success: function(response) {
                        console.log(response); // Cek data respons untuk debugging
                        let rows = '';
                        $.each(response, function(index, training) {
                            rows += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${training.training_no}</td>
                            <td>${new Date(training.created_at).toLocaleDateString()}</td> <!-- Format tanggal -->
                            <td>${training.employee.nik}</td>
                            <td>${training.employee.name}</td>
                            <td>${training.employee.posisi}</td>
                            <td>${training.basicoperation.op_code}</td>
                            <td>${training.basicoperation.op_name}</td>
                            
                            <td>
                                
                        
                                <a class="badge bg-secondary delete-button" data-id="${training.id}">Delete</a>
                            </td>
                        </tr>`;
                        });
                        $('#transaction-table-body').html(rows); // Masukkan data ke tabel
                    },
                    error: function(xhr) {
                        alert('Error fetching data');
                    }
                });
            }


            // Submit form menggunakan AJAX (seperti pada kode sebelumnya)
            $('#save-button').click(function(e) {
                e.preventDefault();

                let formData = {
                    nik: $('#nik').val(),
                    employee_id: $('#employee_id').val(),
                    sku: $('#sku').val(),
                    basicoperation_id: $('#basicoperation_id').val(),
                    remark: $('#remark').val(),
                    _token: '{{ csrf_token() }}' // Laravel CSRF Token
                };

                $.ajax({
                    url: "{{ route('store.training') }}",
                    method: 'POST',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Transaksi berhasil disimpan!',

                                showConfirmButton: false,
                                timer: 1000,
                                timerProgressBar: true,
                            });

                            // Perbarui jumlah IN

                            loadTrainings();

                            clear_input();

                            // clear_input();
                            $('#nik').focus();
                            $('#save-button').hide();

                        }
                    },
                    error: function(xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '';
                        $.each(errors, function(key, value) {
                            errorMessage += value + '\n';
                        });
                        alert(errorMessage);
                    }
                });
            });



            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();
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
                            url: "/delete/training/" + item_id,
                            success: function(data) {

                                $(`a[data-id="${item_id}"]`).closest('tr').remove();
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

</body>

</html>

<script src="{{ asset('backend/assets/vendors/feather-icons/feather.min.js') }}"></script>
