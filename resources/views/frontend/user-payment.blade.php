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
                            <div class="row">
                                <div class="col-lg-6">
                                    @if (session('success'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                    @if (session('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            {{ session('error') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif

                                    <!-- Payment Details -->
                                    <div class="mb-3">
                                        <h5 class="fw-semibold">Payment Details</h5>
                                        <br>
                                        <table class="table table-bordered">
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
                                                <td>Piliyandala</td>
                                            </tr>
                                        </table>
                                        <br>
                                        <h6><strong>Note:</strong> It is mandatory to mention your National Identity Card number on your bank receipt when paying the registration fee.</h6>
                                        <hr>
                                    </div>

                                    <!-- Payment History -->
                                    @if (isset($payments) && $payments->isNotEmpty())
                                        <h5 class="fw-semibold mb-3">Payment History</h5>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Status</th>
                                                        <th>Slip</th>
                                                        <th>Uploaded At</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($payments as $payment)
                                                        <tr>
                                                            <td>{{ $payment->status }}</td>
                                                            <td>
                                                                @if ($payment->payment_slip)
                                                                    <a href="{{ Storage::url($payment->payment_slip) }}" target="_blank" class="btn btn-sm btn-info">
                                                                        View Slip
                                                                    </a>
                                                                @else
                                                                    Not uploaded
                                                                @endif
                                                            </td>
                                                            <td>{{ $payment->created_at }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        <hr>
                                    @endif

                                    <form class="needs-validation" novalidate method="POST" action="{{ route('payment.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <!-- Upload Payment Slip -->
                                        <div class="mb-3">
                                            <label class="form-label">Upload Payment Slip</label>
                                            <input type="file" class="form-control" name="payment_slip" accept=".pdf,.png,.jpg,.jpeg" required>
                                            <small class="form-text text-muted">Max Size 4MB - Accepted formats: PDF, PNG, JPG</small>
                                            <x-input-error :messages="$errors->get('payment_slip')" class="mt-2" />
                                            <div class="invalid-feedback">Please upload a payment slip.</div>
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