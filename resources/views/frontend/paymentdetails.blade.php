@extends('frontend.layouts.master')

@section('title', 'Payment Details')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Payment Details</h4>
                    </div>
                    <div>
                        <a href="{{ route('admin.payment.manage') }}" class="btn btn-secondary">Back to Payment List</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Payment Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Payment Details -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Payment Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p><strong>User Full Name:</strong> {{ $payment->user->application->full_name ?? 'N/A' }}</p>
                                                <p><strong>Uploaded At:</strong> {{ $payment->created_at ? $payment->created_at->format('F j, Y h:i A') : 'N/A' }}</p>
                                                <p><strong>Payment Type:</strong> {{ $payment->payment_type ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Remark:</strong> {{ $payment->remark ?? 'N/A' }}</p>
                                                <p><strong>Program:</strong> {{ $payment->program ?? 'N/A' }}</p>
                                                <p><strong>Amount (Rs.):</strong> {{ $payment->amount ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Payment Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Status:</strong> 
                                            <span class="badge {{ 
                                                $payment->status === 'Approved' ? 'bg-success-subtle text-success' : 
                                                ($payment->status === 'Pending' ? 'bg-warning-subtle text-warning' : 
                                                ($payment->status === 'Rejected' ? 'bg-danger-subtle text-danger' : 'bg-secondary-subtle text-secondary')) 
                                            }} fw-semibold">
                                                {{ $payment->status ?? 'Pending' }}
                                            </span>
                                        </p>
                                        @if ($payment->rejection_reason)
                                            <p><strong>Rejection Reason:</strong> {{ $payment->rejection_reason }}</p>
                                        @endif
                                        @if ($payment->updated_by && in_array($payment->status, ['Approved', 'Rejected']))
                                            <p><strong>Updated By:</strong> <span class="text-primary">{{ $payment->updatedBy->name ?? 'N/A' }}</span></p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Uploaded Documents (Payment Slip) -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Uploaded Documents</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            @if ($payment->payment_slip)
                                                <div class="col-md-4 mb-3">
                                                    <p><strong>Payment Slip:</strong></p>
                                                    <a href="#" data-bs-toggle="modal" data-bs-target="#documentModal" onclick="loadImage('{{ Storage::url($payment->payment_slip) }}', 'documentFrame')">
                                                        <button type="button" class="btn btn-primary">View Slip</button>
                                                    </a>
                                                </div>
                                            @else
                                                <div class="col-md-4 mb-3">
                                                    <p><strong>Payment Slip:</strong> <span class="text-muted">Not uploaded</span></p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

   <!-- Payment History -->
<div class="col-12">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="card-title mb-0">Payment History</h6>
        </div>
        <div class="card-body">
            @php
                $payments = \App\Models\Payment::where('user_id', $payment->user_id)->get();
            @endphp
            @if ($payments->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Type</th>
                                <th scope="col">Amount (Rs.)</th>
                                <th scope="col">Remark</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $historyPayment)
                                <tr>
                                    <td>{{ $historyPayment->created_at ?? 'N/A' }}</td>
                                    <td>{{ $historyPayment->payment_type ?? 'N/A' }}</td>
                                    <td>{{ $historyPayment->amount ?? 'N/A' }}</td>
                                    <td>{{ $historyPayment->remark ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $historyPayment->status === 'Approved' ? 'success' : 
                                            ($historyPayment->status === 'Pending' ? 'warning' : 
                                            ($historyPayment->status === 'Rejected' ? 'danger' : 'secondary')) 
                                        }} me-2">
                                            {{ $historyPayment->status ?? 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center" role="alert">
                    <h5 class="alert-heading">No Payments Done Yet</h5>
                    <p>No payment records found for this user.</p>
                </div>
            @endif
        </div>
    </div>
</div>
                    <div class="card-footer">
                        <form method="POST" action="{{ route('admin.payment.update', $payment->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <select name="status" class="form-select" required id="statusSelect">
                                        <option value="" disabled {{ !$payment->status ? 'selected' : '' }}>Select Status</option>
                                        <option value="Pending" {{ $payment->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Approved" {{ $payment->status === 'Approved' ? 'selected' : '' }}>Approve</option>
                                        <option value="Rejected" {{ $payment->status === 'Rejected' ? 'selected' : '' }}>Reject</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                </div>
                                <div class="col-md-6">
                                    <textarea name="rejection_reason" class="form-control" placeholder="Reason for rejection (required if Rejected)" id="reasonTextarea">{{ $payment->status === 'Rejected' ? $payment->rejection_reason : old('rejection_reason') }}</textarea>
                                    <x-input-error :messages="$errors->get('rejection_reason')" class="mt-2" />
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                                </div>
                            </div>
                        </form>
                        <script>
                            document.getElementById('statusSelect').addEventListener('change', function() {
                                var reasonTextarea = document.getElementById('reasonTextarea');
                                if (this.value === 'Approved' || this.value === 'Pending') {
                                    reasonTextarea.disabled = true;
                                    reasonTextarea.value = '';
                                } else if (this.value === 'Rejected') {
                                    reasonTextarea.disabled = false;
                                    if (!reasonTextarea.value) {
                                        reasonTextarea.value = '';
                                    }
                                } else {
                                    reasonTextarea.disabled = true;
                                    reasonTextarea.value = '';
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Document Modal -->
    <div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentModalLabel">Payment Slip Viewer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <iframe id="documentFrame" src="" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            function loadImage(src, imageId) {
                const frameElement = document.getElementById(imageId);
                if (frameElement) {
                    frameElement.src = src;
                    new bootstrap.Modal(document.getElementById('documentModal')).show();
                } else {
                    console.error('Iframe element not found:', imageId);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                if (typeof $ === 'undefined') {
                    console.error('jQuery is not loaded. Please include it in your layout.');
                }

                const modalElement = document.getElementById('documentModal');
                if (modalElement) {
                    modalElement.addEventListener('hidden.bs.modal', function() {
                        // Refresh the page when the modal is closed
                        window.location.reload();
                    });
                }
            });
        </script>
    @endsection
@endsection