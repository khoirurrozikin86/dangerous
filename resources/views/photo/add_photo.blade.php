@extends('admin.admin_dashboard')

@section('admin')
    <div class="page-content">

        <div class="row profile-body">

            <!-- left wrapper end -->
            <!-- middle wrapper start -->
            <div class="col-md-12 col-xl-12 middle-wrapper">
                <div class="row">
                    <div class="card">
                        <div class="card-body">

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            <h6 class="card-title">Add Photo</h6>

                            <form action="{{ route('store.photoreturn') }}" method="POST" enctype="multipart/form-data"
                                id="adminForm">
                                @csrf

                                <div class="mb-3 row">
                                    <label for="department" class="col-sm-3 col-form-label">Department:</label>
                                    <div class="col-sm-9">
                                        <select class="js-example-basic-single form-select" name="department"
                                            id="department" required>
                                            <option value="">Select Department</option>
                                            <option value="sewing">Sewing</option>
                                            <option value="cutting">Cutting</option>
                                            <option value="warehouse">Warehouse</option>
                                            <option value="finishing">Finishing</option>
                                            <option value="folding">Folding</option>
                                            <option value="packing">Packing</option>
                                            <option value="sample">Sample</option>
                                            <option value="fabric">Fabric</option>
                                            <option value="qc">QC</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3 row">
                                    <label for="remark" class="col-sm-3 col-form-label">Remark:</label>
                                    <div class="col-sm-9">
                                        <select class="js-example-basic-single form-select" name="remark" id="remark"
                                            required>
                                            <option value="">Select Remark</option>
                                            <option value="istirahat">Istirahat</option>
                                            <option value="pulang">Pulang</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="photo1" class="col-sm-3 col-form-label">Photo 1:</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="photo1" id="photo1" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="photo2" class="col-sm-3 col-form-label">Photo 2:</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="photo2" id="photo2">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="photo3" class="col-sm-3 col-form-label">Photo 3:</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="photo3" id="photo3">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="photo4" class="col-sm-3 col-form-label">Photo 4:</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="photo4" id="photo4">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="photo5" class="col-sm-3 col-form-label">Photo 5:</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" name="photo5" id="photo5">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                        <div id="loadingSpinner" class="spinner-border text-primary" style="display: none;"
                                            role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
            <!-- middle wrapper end -->
            <!-- right wrapper start -->

            <!-- right wrapper end -->
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('#adminForm').on('submit', function() {
                $('#submitBtn').prop('disabled', true);
                $('#loadingSpinner').show();
            });
        });
    </script>
@endsection
