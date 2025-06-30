@extends('frontend.layouts.master')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Dashboard</h4>
                    </div>
                </div>
                <!-- Start Main Widgets -->
                <div class="row">
                    <div class="col-md-6 col-lg-4 col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="widget-first">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="p-2 border border-primary border-opacity-10 bg-primary-subtle rounded-2 me-2">
                                            <div class="bg-primary rounded-circle widget-size text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="#ffffff" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-dark fs-15">Diploma</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-22 text-dark me-3">3,456</h3>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0 text-dark fs-10">Approved</p>
                                        <p class="mb-0 text-dark fs-10">Pending</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-15 text-dark me-3">256</h3>
                                        <h5 class="mb-0 fs-15 text-dark me-3">10</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="widget-first">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="p-2 border border-primary border-opacity-10 bg-primary-subtle rounded-2 me-2">
                                            <div class="bg-primary rounded-circle widget-size text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="#ffffff" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-dark fs-15">Higher Diploma</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-22 text-dark me-3">3,456</h3>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0 text-dark fs-10">Approved</p>
                                        <p class="mb-0 text-dark fs-10">Pending</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-15 text-dark me-3">256</h3>
                                        <h5 class="mb-0 fs-15 text-dark me-3">10</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="widget-first">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="p-2 border border-primary border-opacity-10 bg-primary-subtle rounded-2 me-2">
                                            <div class="bg-primary rounded-circle widget-size text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="#ffffff" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-dark fs-15">Degree</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-22 text-dark me-3">3,456</h3>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0 text-dark fs-10">Approved</p>
                                        <p class="mb-0 text-dark fs-10">Pending</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-15 text-dark me-3">256</h3>
                                        <h5 class="mb-0 fs-15 text-dark me-3">10</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="widget-first">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="p-2 border border-primary border-opacity-10 bg-primary-subtle rounded-2 me-2">
                                            <div class="bg-primary rounded-circle widget-size text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="#ffffff" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-dark fs-15">Postgraduate Diploma</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-22 text-dark me-3">3,456</h3>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0 text-dark fs-10">Approved</p>
                                        <p class="mb-0 text-dark fs-10">Pending</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-15 text-dark me-3">256</h3>
                                        <h5 class="mb-0 fs-15 text-dark me-3">10</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl">
                        <div class="card">
                            <div class="card-body">
                                <div class="widget-first">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="p-2 border border-primary border-opacity-10 bg-primary-subtle rounded-2 me-2">
                                            <div class="bg-primary rounded-circle widget-size text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                                    <path fill="#ffffff" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="mb-0 text-dark fs-15">Masters Degree</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-22 text-dark me-3">3,456</h3>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0 text-dark fs-10">Approved</p>
                                        <p class="mb-0 text-dark fs-10">Pending</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h3 class="mb-0 fs-15 text-dark me-3">256</h3>
                                        <h5 class="mb-0 fs-15 text-dark me-3">10</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Main Widgets -->

                <!-- Start Product Orders -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0">Applications</h5>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>Application No</th>
                                                <th>Applicant Name</th>
                                                <th>Study Programme</th>
                                                <th>Course</th>
                                                <th>Batch(s)</th>
                                                <th>Application Status</th>
                                                <th>Payment Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($applications as $application)
                                                <tr>
                                                    <td>
                                                        <a href="javascript:void(0);" class="text-dark">{{ sprintf('%.5d', $application->application_id ?? $application->user_id) }}</a>
                                                    </td>
                                                    <td class="d-flex align-items-center">
                                                        <img src="{{ ($app = \App\Models\Application::where('user_id', $application->user_id)->first()) && $app->photograph ? asset('storage/' . $app->photograph) : asset('frontend/assets/images/users/default.jpg') }}"
                                                             class="avatar avatar-sm rounded-circle me-3"
                                                             alt="user-image" />
                                                        <div>
                                                            <p class="mb-0 fw-medium fs-14">{{ $application->full_name }}</p>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 text-muted">{{ $application->study_programme_name ?? 'N/A' }}</p>
                                                    </td>
                                                    <td>
                                                        <p class="mb-0 text-muted">{{ $application->course_name ?? 'N/A' }}</p>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $courseApplication = \App\Models\CourseApplication::where('user_id', $application->user_id)->first();
                                                            $batches = $courseApplication ? $courseApplication->course->batches->pluck('batch_no')->join(', ') : 'N/A';
                                                        @endphp
                                                        <p class="mb-0 text-muted">{{ $batches }}</p>
                                                    </td>
                                                    <td>
                                                        @if (is_null($application->application_completed))
                                                            <span class="badge bg-secondary-subtle text-secondary fw-semibold">Not Complete</span>
                                                        @else
                                                            <span class="badge {{ $application->application_completed ? 'bg-primary-subtle text-primary' : 'bg-warning-subtle text-warning' }} fw-semibold">
                                                                {{ $application->application_completed ? 'Complete' : 'Pending' }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ $application->payment_status == 'Completed' ? 'bg-success-subtle text-success' : ($application->payment_status == 'Pending Verification' ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }} fw-semibold">
                                                            {{ $application->payment_status }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.application.view', $application->application_id ?? $application->user_id) }}" aria-label="anchor" class="btn btn-icon btn-sm bg-info-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View">
                                                            <i class="mdi mdi-eye-outline fs-12 text-info"></i>
                                                        </a>
                                                        @if ($application->payment_status == 'Pending Verification')
                                                            <form action="{{ route('admin.payment.status', $application->user_id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="status" value="completed">
                                                                <button type="submit" aria-label="Approve" class="btn btn-icon btn-sm bg-success-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="Approve">
                                                                    <i class="mdi mdi-check-circle-outline fs-12 text-success"></i>
                                                                </button>
                                                            </form>
                                                            <form action="{{ route('admin.payment.status', $application->user_id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                <input type="hidden" name="status" value="rejected">
                                                                <button type="submit" aria-label="Not Approve" class="btn btn-icon btn-sm bg-danger-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="Not Approve">
                                                                    <i class="mdi mdi-close-circle-outline fs-12 text-danger"></i>
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer py-0 border-top">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div class="text-block text-muted">
                                                <span class="fw-medium">{{ $applications->firstItem() }} - {{ $applications->lastItem() }} of {{ $applications->total() }}</span>
                                            </div>
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination">
                                                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $applications->url($currentPage - 1) }}" tabindex="-1" aria-disabled="{{ $currentPage == 1 ? 'true' : 'false' }}">Previous</a>
                                                    </li>
                                                    @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                            <a class="page-link" href="{{ $applications->url($i) }}">{{ $i }}</a>
                                                        </li>
                                                    @endfor
                                                    <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $applications->url($currentPage + 1) }}" aria-disabled="{{ $currentPage == $lastPage ? 'true' : 'false' }}">Next</a>
                                                    </li>
                                                </ul>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Recent Order -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- content -->
    </div>
@endsection