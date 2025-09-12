@extends('frontend.layouts.master')

@section('title', 'Payment Verification')

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Payment Verification</h4>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->

            <!-- General Form -->
            <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                {{-- Flash Messages --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif

                <!-- Payment Details Row -->
                <div class="row">
                    <!-- Left Side (Diploma Payment) -->
                    <div class="col-lg-6 mb-3">
                        
                        <table class="table table-bordered mt-3">
                            <tr class="table-primary text-center">
                                <th colspan="2">Diploma Payment Account</th>
                            </tr>
                            <tr>
                                <td><strong>Account Number:</strong></td>
                                <td>359-100-170-007-439</td>
                            </tr>
                            <tr>
                                <td><strong>Account Holder Name:</strong></td>
                                <td>E.I.I for Higher Education</td>
                            </tr>
                            <tr>
                                <td><strong>Bank Name:</strong></td>
                                <td>People's Bank</td>
                            </tr>
                            <tr>
                                <td><strong>Bank Branch:</strong></td>
                                <td>Piliyandala City</td>
                            </tr>
                        </table>
                        <h6 class="mt-2 text-danger">
    <strong>Diploma in Psychology:</strong> Registration fee Rs. 4,000
</h6>
<h6 class="mt-2 text-danger">
    <strong>Diploma in Buddhist Counselling:</strong> Registration fee Rs. 4,000
</h6>
<h6 class="mt-2 text-danger">
    <strong>Diploma and Other Program:</strong> Registration fee Rs. 3,000
</h6>

                    </div>

                    <!-- Right Side (Course Payment) -->
                    <div class="col-lg-6 mb-3">
                        
                        <table class="table table-bordered mt-3">
                            <tr class="table-info text-center">
                                <th colspan="2">Bachelor/ PgD/ Master Program Payment Account</th>
                            </tr>
                            <tr>
                                <td><strong>Account Number:</strong></td>
                                <td>0090823540</td>
                            </tr>
                            <tr>
                                <td><strong>Account Holder Name:</strong></td>
                                <td>Eurasian Campus (Pvt) Ltd</td>
                            </tr>
                            <tr>
                                <td><strong>Bank Name:</strong></td>
                                <td>Bank of Ceylon</td>
                            </tr>
                            <tr>
                                <td><strong>Bank Branch:</strong></td>
                                <td>kesbewa</td>
                            </tr>
                        </table>
                        <h6 class="mt-2 text-danger">
    <strong>Bachelor/ PgD/ Master Program:</strong> Registration fee Rs. 5,000
</h6>
                    </div>
                </div>

                <!-- Note + Form in Half Page -->
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Note -->
                        <h6 class="mt-2">
                            <strong>Note:</strong> It is mandatory to mention your National Identity Card
                            number on your bank receipt when paying the registration fee.
                        </h6>
                        <hr>

                        <!-- Payment Verification Form -->
                        <form class="needs-validation" novalidate method="POST" 
                              action="{{ route('payment.store') }}" enctype="multipart/form-data">
                            @csrf

                            <!-- Payment Type -->
                            <div class="mb-3">
                                <label class="form-label">Payment Type <span class="text-danger">*</span></label>
                                <select class="form-select" name="payment_type" required>
                                    <option value="">-- Select Payment Type --</option>
                                    <option value="registration">Registration Payment</option>
                                    <option value="course">Course Payment</option>
                                </select>
                                <x-input-error :messages="$errors->get('payment_type')" class="mt-2" />
                                <div class="invalid-feedback">Please select a payment type.</div>
                            </div>

                            <!-- Upload Payment Slip -->
                            <div class="mb-3">
                                <label class="form-label">Upload Payment Slip</label>
                                <input type="file" class="form-control" name="payment_slip"
                                       accept=".pdf,.png,.jpg,.jpeg" required>
                                <small class="form-text text-muted">
                                    Max Size 4MB - Accepted formats: PDF, PNG, JPG
                                </small>
                                <x-input-error :messages="$errors->get('payment_slip')" class="mt-2" />
                                <div class="invalid-feedback">Please upload a payment slip.</div>
                            </div>

                            <!-- Remark -->
                            <div class="mb-3">
    <label class="form-label">Remark (NIC Number) <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="remark" placeholder="Enter your NIC Number" required>
    <x-input-error :messages="$errors->get('remark')" class="mt-2" />
    <div class="invalid-feedback">Please enter your NIC Number.</div>
</div>

                            <!-- Submit Button -->
                            <button class="btn btn-primary" type="submit">Submit Payment Slip</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

        </div>
    </div>
    </div>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Bootstrap Validation
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.needs-validation');

            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            }, false);
        });
    </script>
@endsection