@extends('frontend.layouts.master')

@section('title', 'Update Payment')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Update Rejected Payment</h4>
                    </div>
                    
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Update Payment Details</h5>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('user.payment.update.post', $payment->id) }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Removed @method('PUT') -->
                            <div class="mb-3">
                                <label class="form-label">Payment Slip</label>
                                <input type="file" class="form-control" name="payment_slip" accept="image/*,application/pdf">
                                <small class="text-muted">Upload PDF, PNG, JPG, or JPEG (max 4MB). Leave blank to keep existing slip.</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Remark (NIC Number)<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="remark" rows="3" placeholder="Enter any additional remarks">{{ $payment->remark ?? '' }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Program<span class="text-danger">*</span></label>
                                <select class="form-select" name="program" required>
                                    <option value="" disabled {{ !$payment->program ? 'selected' : '' }}>Select Program</option>
                                    <option value="Diploma" {{ $payment->program === 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="Higher Diploma" {{ $payment->program === 'Higher Diploma' ? 'selected' : '' }}>Higher Diploma</option>
                                    <option value="Postgraduate Diploma" {{ $payment->program === 'Postgraduate Diploma' ? 'selected' : '' }}>Postgraduate Diploma</option>
                                    <option value="Degree" {{ $payment->program === 'Degree' ? 'selected' : '' }}>Degree</option>
                                    <option value="Master Program" {{ $payment->program === 'Master Program' ? 'selected' : '' }}>Master Program</option>
                                    <option value="PhD" {{ $payment->program === 'PhD' ? 'selected' : '' }}>PhD</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount (Rs.)<span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="amount" step="1" min="0" value="{{ $payment->amount ?? '' }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Payment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection