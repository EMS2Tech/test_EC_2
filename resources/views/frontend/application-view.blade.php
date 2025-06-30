@extends('frontend.layouts.master')

@section('title', 'View Application')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Application Details</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-semibold mb-3">Applicant Information</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Full Name</th>
                                <td>{{ $application->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Nationality</th>
                                <td>{{ $application->nationality }} {{ $application->other_nationality ? '(' . $application->other_nationality . ')' : '' }}</td>
                            </tr>
                            <tr>
                                <th>NIC Number</th>
                                <td>{{ $application->nic_number ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Contact Number</th>
                                <td>{{ $application->contact_number }}</td>
                            </tr>
                            <tr>
                                <th>Email Address</th>
                                <td>{{ $application->email_address }}</td>
                            </tr>
                            <tr>
                                <th>Application Completed</th>
                                <td>{{ $application->application_completed ? 'Yes' : 'No' }}</td>
                            </tr>
                        </table>

                        @if ($courseApplications->isNotEmpty())
                            <h5 class="fw-semibold mt-4 mb-3">Course Applications</h5>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Study Programme</th>
                                        <th>Course</th>
                                        <th>Batch No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courseApplications as $courseApplication)
                                        <tr>
                                            <td>{{ $courseApplication->studyProgram->program_name ?? 'N/A' }}</td>
                                            <td>{{ $courseApplication->course->course_name ?? 'N/A' }}</td>
                                            <td>
                                                @if ($courseApplication->course->batches->isNotEmpty())
                                                    {{ $courseApplication->course->batches->pluck('batch_no')->join(', ') }}
                                                @else
                                                    No active batches
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No course applications found.</p>
                        @endif

                        @if ($payments->isNotEmpty())
                            <h5 class="fw-semibold mt-4 mb-3">Payment History</h5>
                            <table class="table table-bordered">
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
                                                    <a href="{{ Storage::url($payment->payment_slip) }}" target="_blank" class="btn btn-sm btn-info">View Slip</a>
                                                @else
                                                    Not uploaded
                                                @endif
                                            </td>
                                            <td>{{ $payment->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted">No payment records found.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection