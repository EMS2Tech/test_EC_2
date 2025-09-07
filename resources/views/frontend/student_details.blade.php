@extends('frontend.layouts.master')

@section('title', 'Student Details')

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Student Details</h4>
                    </div>
                    <div>
                        <a href="{{ route('admin.students.index') }}" class="btn btn-primary">Back to Student List</a>
                    </div>
                </div>

                <!-- Start Student Information -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Student Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 text-center">
                                        <img src="{{ $student->application && $student->application->photograph ? asset('storage/' . $student->application->photograph) : asset('frontend/assets/images/users/default.jpg') }}"
                                             class="avatar avatar-lg rounded-circle mb-3"
                                             alt="student-image" />
                                        <h5 class="fw-medium fs-16">{{ $student->full_name ?? ($student->user->name ?? 'N/A') }}</h5>
                                        <p class="text-muted mb-0">
    Application No:
    <span class="text-primary">
        {{ $student->student_id ?? 'N/A' }}
    </span>
</p>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Title</label>
                                                <p class="mb-0 text-muted">{{ $student->application ? $student->application->title : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Name with Initials</label>
                                                <p class="mb-0 text-muted">{{ $student->application ? $student->application->name_with_initials : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Birthday</label>
                                                <p class="mb-0 text-muted">{{ $student->application ? $student->application->birthday->format('Y-m-d') : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Nationality</label>
                                                <p class="mb-0 text-muted">{{ $student->application ? ($student->application->nationality === 'Other' ? $student->application->other_nationality : $student->application->nationality) : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">NIC Number</label>
                                                <p class="mb-0 text-muted">{{ $student->application && $student->application->nic_number ? $student->application->nic_number : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Passport Number</label>
                                                <p class="mb-0 text-muted">{{ $student->application && $student->application->passport_number ? $student->application->passport_number : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label fw-semibold">Address</label>
                                                <p class="mb-0 text-muted">{{ $student->application ? $student->application->address : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Contact Number</label>
                                                <p class="mb-0 text-muted">{{ $student->application ? $student->application->contact_number : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">WhatsApp Number</label>
                                                <p class="mb-0 text-muted">{{ $student->application ? $student->application->whatsapp_number : 'N/A' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Email</label>
                                                <p class="mb-0 text-muted">{{ $student->application ? $student->application->email_address : ($student->user->email ?? 'N/A') }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold">Application Status</label>
                                                <span class="badge {{ $student->application ? ($student->application->status === 'Approved' ? 'bg-success-subtle text-success' : ($student->application->status === 'Rejected' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning')) : 'bg-secondary-subtle text-secondary' }}">
                                                    {{ $student->application ? $student->application->status : 'N/A' }}
                                                </span>
                                            </div>
                                            @if ($student->application && $student->application->rejection_reason)
                                                <div class="col-md-12 mb-3">
                                                    <label class="form-label fw-semibold">Rejection Reason</label>
                                                    <p class="mb-0 text-muted">{{ $student->application->rejection_reason }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Student Information -->

                <!-- Start Uploaded Documents -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Uploaded Documents</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if ($student->application && $student->application->photograph)
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">Photograph</label>
                                            <div>
                                                <a href="{{ asset('storage/' . $student->application->photograph) }}"
                                                   class="btn btn-sm btn-primary"
                                                   target="_blank">{{ basename($student->application->photograph) }}</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($student->application && $student->application->nic_photo)
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">NIC Photo</label>
                                            <div>
                                                <a href="{{ asset('storage/' . $student->application->nic_photo) }}"
                                                   class="btn btn-sm btn-primary"
                                                   target="_blank">{{ basename($student->application->nic_photo) }}</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($student->application && $student->application->passport_photo)
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-semibold">Passport Photo</label>
                                            <div>
                                                <a href="{{ asset('storage/' . $student->application->passport_photo) }}"
                                                   class="btn btn-sm btn-primary"
                                                   target="_blank">{{ basename($student->application->passport_photo) }}</a>
                                            </div>
                                        </div>
                                    @endif
                                    @if (!$student->application || (!$student->application->photograph && !$student->application->nic_photo && !$student->application->passport_photo))
                                        <div class="col-12 text-center">
                                            <p class="text-muted">No documents uploaded.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Uploaded Documents -->

                <!-- Start Course Applications -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Course Applications</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>CAID</th>
                                                <th>Study Program</th>
                                                <th>Course</th>
                                                <th>Batch</th>
                                                <th>Status</th>
                                                <th>Applied Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($student->application ? $student->application->courseApplications : [] as $courseApplication)
                                                <tr>
                                                    <td>{{ $courseApplication->id ?? 'N/A' }}</td>
                                                    <td>{{ $courseApplication->studyProgram ? $courseApplication->studyProgram->program_name : 'N/A' }}</td>
                                                    <td>{{ $courseApplication->course ? $courseApplication->course->course_name : 'N/A' }}</td>
                                                    <td>
                                                        {{ $courseApplication->course && $courseApplication->course->batches->isNotEmpty() ? $courseApplication->course->batches->pluck('batch_no')->implode(', ') : 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $courseApplication->status === 'Approved' ? 'bg-success-subtle text-success' : ($courseApplication->status === 'Rejected' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">
                                                            {{ $courseApplication->status ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $courseApplication->created_at ? $courseApplication->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.courseapplication.view', $courseApplication->id) }}"
                                                           class="btn btn-icon btn-sm bg-info-subtle me-1"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-original-title="View"
                                                           onclick="return confirm('View course application details?');">
                                                            <i class="mdi mdi-eye-outline fs-12 text-info"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No course applications found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Course Applications -->

                <!-- Start Payment Details -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Payment Details</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>PID</th>
                                                <th>Type</th>
                                                <th>Remark</th>
                                                <th>Status</th>
                                                <th>Payment Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($student->application ? $student->application->payments : [] as $payment)
                                                <tr>
                                                    <td>{{ $payment->id ?? 'N/A' }}</td>
                                                    <td>{{ $payment->payment_type ?? 'N/A' }}</td>
                                                    <td>{{ $payment->remark ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge {{ $payment->status === 'Approved' ? 'bg-success-subtle text-success' : ($payment->status === 'Rejected' ? 'bg-danger-subtle text-danger' : 'bg-warning-subtle text-warning') }}">
                                                            {{ $payment->status ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $payment->created_at ? $payment->created_at->format('Y-m-d') : 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.payment.details', $payment->id) }}"
                                                           class="btn btn-icon btn-sm bg-info-subtle me-1"
                                                           data-bs-toggle="tooltip"
                                                           data-bs-original-title="View"
                                                           onclick="return confirm('View payment details?');">
                                                            <i class="mdi mdi-eye-outline fs-12 text-info"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No payments found.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Payment Details -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- content -->
    </div>
@endsection