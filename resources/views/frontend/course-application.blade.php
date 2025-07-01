@extends('frontend.layouts.master')

@section('title', 'Course Applications')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Course Applications</h4>
                    </div>
                </div>

                <!-- Filter Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.course.applications') }}" class="row g-3">
                            <div class="col-md-4">
                                <label for="study_program_name" class="form-label">Study Program Name</label>
                                <input type="text" name="study_program_name" id="study_program_name" class="form-control" value="{{ request('study_program_name') }}" placeholder="Filter by Study Program">
                            </div>
                            <div class="col-md-4">
                                <label for="course_name" class="form-label">Course Name</label>
                                <input type="text" name="course_name" id="course_name" class="form-control" value="{{ request('course_name') }}" placeholder="Filter by Course">
                            </div>
                            <div class="col-md-4">
                                <label for="batch_no" class="form-label">Batch No</label>
                                <input type="text" name="batch_no" id="batch_no" class="form-control" value="{{ request('batch_no') }}" placeholder="Filter by Batch">
                            </div>
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('admin.course.applications') }}" class="btn btn-secondary">Clear Filters</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Course Applications Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>User Full Name</th>
                                        <th>Study Programme Name</th>
                                        <th>Course Name</th>
                                        <th>Batch No</th>
                                        <th>Apply Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($courseApplications as $courseApplication)
                                        <tr>
                                            <td>{{ $courseApplication->full_name }}</td>
                                            <td>{{ $courseApplication->study_programme_name }}</td>
                                            <td>{{ $courseApplication->course_name }}</td>
                                            <td>{{ $courseApplication->batch_no ?? 'N/A' }}</td>
                                            <td>{{ $courseApplication->apply_date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer py-0 border-top">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div class="text-block text-muted">
                                            <span class="fw-medium">{{ $courseApplications->firstItem() }} - {{ $courseApplications->lastItem() }} of {{ $courseApplications->total() }}</span>
                                        </div>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $courseApplications->url($currentPage - 1) }}" tabindex="-1" aria-disabled="{{ $currentPage == 1 ? 'true' : 'false' }}">Previous</a>
                                                </li>
                                                @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                        <a class="page-link" href="{{ $courseApplications->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endfor
                                                <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $courseApplications->url($currentPage + 1) }}" aria-disabled="{{ $currentPage == $lastPage ? 'true' : 'false' }}">Next</a>
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
        </div>
    </div>
@endsection