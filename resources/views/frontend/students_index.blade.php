@extends('frontend.layouts.master')

@section('title', 'Students List')

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Students List</h4>
                    </div>
                    <div>
                        <a href="{{ route('admin.students.export') . '?' . http_build_query(request()->query()) }}" class="btn btn-success">Export to CSV</a>
                    </div>
                </div>

                <!-- Start Search and Filter -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0">All Students</h5>
                                    <div class="d-flex align-items-center">
                                        <form class="d-flex align-items-center me-3" method="GET" action="{{ route('admin.students.index') }}">
                                            <input type="text" class="form-control me-2" name="search" placeholder="Search by Student ID, Full Name, or Email" value="{{ request('search') }}" style="width: 250px;">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-magnify me-1"></i> Search
                                            </button>
                                            @foreach (request()->query() as $key => $value)
                                                @if ($key !== 'search')
                                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                                @endif
                                            @endforeach
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Search and Filter -->

                <!-- Start Students Table -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>Application No</th>
                                                <th>Full Name</th>
                                                <th>Contact Number</th>
                                                <th>Email</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($students as $student)
                                                <tr>
                                                    <td>{{ $student->student_id ?? 'N/A' }}</td>
                                                    <td class="d-flex align-items-center">
                                                        <img src="{{ $student->application && $student->application->photograph ? asset('storage/' . $student->application->photograph) : asset('frontend/assets/images/users/default.jpg') }}"
                                                             class="avatar avatar-sm rounded-circle me-3"
                                                             alt="student-image" />
                                                        <div>
                                                            <p class="mb-0 fw-medium fs-14">{{ $student->full_name ?? ($student->user->name ?? 'N/A') }}</p>
                                                        </div>
                                                    </td>
                                                    <td>{{ $student->application ? $student->application->contact_number : 'N/A' }}</td>
                                                    <td>{{ $student->application ? $student->application->email_address : ($student->user->email ?? 'N/A') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.student.details', $student->id) }}" class="btn btn-icon btn-sm bg-info-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View" onclick="return confirm('View details for {{ $student->full_name ?? 'Student' }}?');">
                                                            <i class="mdi mdi-eye-outline fs-12 text-info"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No students found for the selected filters.</td>
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
                                                    <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $students->url($currentPage - 1) . ($currentPage > 1 ? '&' . http_build_query(request()->except('page')) : '') }}" tabindex="-1" aria-disabled="{{ $currentPage == 1 ? 'true' : 'false' }}">Previous</a>
                                                    </li>
                                                    @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                                                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                            <a class="page-link" href="{{ $students->url($i) . ($i != $currentPage ? '&' . http_build_query(request()->except('page')) : '') }}">{{ $i }}</a>
                                                        </li>
                                                    @endfor
                                                    <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $students->url($currentPage + 1) . ($currentPage < $lastPage ? '&' . http_build_query(request()->except('page')) : '') }}" aria-disabled="{{ $currentPage == $lastPage ? 'true' : 'false' }}">Next</a>
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
                <!-- End Students Table -->
            </div>
            <!-- container-fluid -->
        </div>
        <!-- content -->
    </div>
@endsection