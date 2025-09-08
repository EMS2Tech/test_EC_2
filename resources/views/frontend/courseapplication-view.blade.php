@extends('frontend.layouts.master')

@section('title', 'Course Application Details')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Course Application Details</h4>
                    </div>
                    <div>
                        <a href="{{ route('admin.course.applications') }}" class="btn btn-secondary">Back to Course Applications</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Course Application Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Personal and Course Details -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Course Application Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p><strong>User Full Name:</strong> {{ $courseApplication->full_name }}</p>
                                                <p><strong>Study Programme:</strong> {{ $courseApplication->study_programme_name }}</p>
                                                <p><strong>Course:</strong> {{ $courseApplication->course_name }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Batch No:</strong> {{ $courseApplication->batch_no }}</p>
                                                <p><strong>Apply Date:</strong> {{ $courseApplication->apply_date }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Course Application Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $courseApplication->status == 'Approved' ? 'success' : ($courseApplication->status == 'Pending' ? 'warning' : 'danger') }}">
                                                {{ $courseApplication->status ?? 'N/A' }}
                                            </span>
                                        </p>
                                        @if ($courseApplication->rejection_reason)
                                            <p><strong>Rejection Reason:</strong> {{ $courseApplication->rejection_reason }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Uploaded Documents -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Uploaded Documents</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            @php
                                                $documents = [
                                                    'ol_certificate' => 'O/L Certificate',
                                                    'al_certificate' => 'A/L Certificate',
                                                    'degree_certificate' => 'Degree Certificate',
                                                    'transcript_certificate' => 'Transcript Certificate',
                                                    'diploma_certificates' => 'Diploma Certificate',
                                                    'other_certificates' => 'Other Certificate',
                                                ];
                                            @endphp
                                            @foreach ($documents as $field => $label)
                                                @if ($courseApplication->$field)
                                                    <div class="col-md-4 mb-3">
                                                        <p><strong>{{ $label }}:</strong></p>
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#documentModal" onclick="loadImage('{{ Storage::url($courseApplication->$field) }}', 'documentFrame')">
                                                            <button type="button" class="btn btn-primary">View Document</button>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Payment Details</h6>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $payments = \App\Models\Payment::where('user_id', $courseApplication->user_id)->get();
                                        @endphp
                                        @if ($payments->isNotEmpty())
                                            <div class="table-responsive">
                                                <table class="table table-hover table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col">Date</th>
                                                            <th scope="col">Type</th>
                                                            <th scope="col">Remark</th>
                                                            <th scope="col">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($payments as $payment)
                                                            <tr>
                                                                <td>{{ $payment->created_at ? $payment->created_at->format('F j, Y h:i A') : 'N/A' }}</td>
                                                                <td>{{ $payment->payment_type ?? 'N/A' }}</td>
                                                                <td>{{ $payment->remark ?? 'N/A' }}</td>
                                                                <td>
                                                                    <span class="badge bg-{{ 
                                                                        $payment->status === 'Approved' ? 'success' : 
                                                                        ($payment->status === 'Pending' ? 'warning' : 
                                                                        ($payment->status === 'Rejected' ? 'danger' : 'secondary')) 
                                                                    }} me-2">
                                                                        {{ $payment->status ?? 'Pending' }}
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
                        </div>
                    </div>
                    <div class="card-footer">
                        <form method="POST" action="{{ route('admin.courseapplication.update.status', $courseApplication->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <select name="status" class="form-select" required id="statusSelect">
                                        <option value="" disabled {{ !$courseApplication->status ? 'selected' : '' }}>Select Status</option>
                                        <option value="Approved" {{ $courseApplication->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Rejected" {{ $courseApplication->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <textarea name="reason" class="form-control" placeholder="Reason for rejection (required if Rejected)" id="reasonTextarea">{{ $courseApplication->rejection_reason }}</textarea>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                                </div>
                            </div>
                        </form>
                        <script>
                            document.getElementById('statusSelect').addEventListener('change', function() {
                                var reasonTextarea = document.getElementById('reasonTextarea');
                                if (this.value === 'Approved') {
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
                    <h5 class="modal-title" id="documentModalLabel">Document Viewer</h5>
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