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

                <!-- Start Students -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0">Students</h5>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>Application No</th>
                                                <th>Full Name</th>
                                                <th>Contact Number</th>
                                                <th>NIC/Passport</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($students as $student)
                                                <tr>
                                                    <td>{{ $student->student_id ?? 'N/A' }}</td>
                                                    <td class="d-flex align-items-center">
                                                        <img src="{{ $student->photograph ? asset('storage/' . $student->photograph) : asset('frontend/assets/images/users/default.jpg') }}"
                                                             class="avatar avatar-sm rounded-circle me-3"
                                                             alt="student-image" />
                                                        <div>
                                                            <p class="mb-0 fw-medium fs-14">{{ $student->full_name ?? 'N/A' }}</p>
                                                        </div>
                                                    </td>
                                                    <td>{{ $student->contact_number ?? 'N/A' }}</td>
                                                    <td>{{ $student->nic_number ?? 'N/A' }}</td>
                                                    <td>{{ $student->email ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.student.details', $student->id) }}" class="btn btn-icon btn-sm bg-info-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View" onclick="return confirm('View details for {{ $student->full_name ?? 'Student' }}?');">
                                                            <i class="mdi mdi-eye-outline fs-12 text-info"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">No students found for the selected filters.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer py-0 border-top">
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div class="text-block text-muted">
                                                <span class="fw-medium">{{ $students->firstItem() }} - {{ $students->lastItem() }} of {{ $students->total() }}</span>
                                            </div>
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination">
                                                    <li class="page-item {{ $students->currentPage() == 1 ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $students->previousPageUrl() . ($students->currentPage() > 1 ? '&' . http_build_query(request()->except('page')) : '') }}" tabindex="-1" aria-disabled="{{ $students->currentPage() == 1 ? 'true' : 'false' }}">Previous</a>
                                                    </li>
                                                    @for ($i = max(1, $students->currentPage() - 1); $i <= min($students->lastPage(), $students->currentPage() + 1); $i++)
                                                        <li class="page-item {{ $i == $students->currentPage() ? 'active' : '' }}">
                                                            <a class="page-link" href="{{ $students->url($i) . ($i != $students->currentPage() ? '&' . http_build_query(request()->except('page')) : '') }}">{{ $i }}</a>
                                                        </li>
                                                    @endfor
                                                    <li class="page-item {{ $students->currentPage() == $students->lastPage() ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $students->nextPageUrl() . ($students->currentPage() < $students->lastPage() ? '&' . http_build_query(request()->except('page')) : '') }}" aria-disabled="{{ $students->currentPage() == $students->lastPage() ? 'true' : 'false' }}">Next</a>
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
                <!-- End Students -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- content -->
    </div>
@endsection