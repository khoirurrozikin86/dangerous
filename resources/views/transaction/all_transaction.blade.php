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
                                    <nav class="page-breadcrumb">
                                        <ol class="breadcrumb">
                                            <a href="{{ route('add.transaction') }}" class="btn btn-primary mx-1 btn-sm"><i
                                                    class="feather-16" data-feather="file-plus"></i> &nbsp;Add</a>
                                            <a href="{{ route('export.transaction') }}" class="btn btn-success mx-1 btn-sm"><i
                                                    class="feather-16" data-feather="file-minus"></i> &nbsp;Excel</a>
                                  
                                            <a href="{{ route('pdf.transaction') }}" class="btn btn-danger mx-1 btn-sm"><i
                                                    class="feather-16" data-feather="file-minus"></i> &nbsp;pdf</a>
                                        </ol>
                                    </nav>
                                </div>

                                <div class="col">
                                    <h6 class="card-title text-center">Transaction</h6>

                                </div>
                                <div class="col">
                                    <h6 class="card-title text-center"></h6>
                                </div>
                            </div>

                        </div>




                        <div class="table-responsive">
                            <table id="dataTableExample" class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No TRX</th>
                                        <th>NIK</th>
                                        <th>NAME</th>
                                        <th>DEPT.</th>
                                        <th>TYPE 1</th>
                                        <th>DATE IN</th>
                                        <th>TYPE 2</th>
                                        <th>DATE OUT</th>
                                        <th>REMARK</th>
                                  
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>


                                    @foreach ($transaction as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->no_trx }}</td>
                                            <td>{{ $item->employee->nik }}</td>
                                            <td>{{ $item->employee->name }}</td>
                                            <td>{{ $item->employee->department }}</td>
                                            <td>{{ $item->type1 }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->type2 }}</td>
                                            <td>{{ $item->updated_at != $item->created_at ? $item->updated_at : '' }}</td>

                                            <td>{{ $item->remark }}</td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                    <div class="d-flex align-items-center">

                                                        <div class="d-flex align-items-center">
                                                            <div class="actions dropdown">
                                                                <a href="#" data-bs-toggle="dropdown"> <i
                                                                        data-feather="more-horizontal"></i></a>
                                                                <div class="dropdown-menu" role="menu">




                                                                    {{-- <a href="{{ route('delete.productin', $item->id) }}" --}}
                                                                    <a href="#" class="dropdown-item text-danger" data-id="{{$item->id}}"
                                                                        id="deletes"><i class="feather-16"
                                                                            data-feather="trash-2"></i>
                                                                        &nbsp; Delete</a>


                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                       


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>



    <script>
        $('body').on('click', '#deletes', function() {



            var id_trans = $(this).data("id");

            console.log(id_trans);

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
                        url: "/delete/transaction/" + id_trans,
                        success: function(data) {
                            // table.ajax.reload(null, false);

                            swalWithBootstrapButtons.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success',
                            )
                            window.location.reload();
                        },
                        error: function(data) {
                            console.log('Error:', data);

                            swalWithBootstrapButtons.fire(
                                'Cancelled',
                                `'There is relation data'.${data.responseJSON.message}`,
                                'error'
                            )

                        }
                    });


                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Your file is safe :)',
                        'error'
                    )
                }
            })

        });
    </script>
@endsection
