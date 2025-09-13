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
                        <a href="{{ route('admin.students.export', request()->all()) }}" class="btn btn-success">Export to CSV</a>
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
                                            <input type="text" class="form-control me-2" name="search" placeholder="Search by Student ID or NIC" value="{{ request('search') }}" style="width: 250px;">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-magnify me-1"></i> Search
                                            </button>
                                        </form>
                                        <form class="d-flex align-items-center" method="GET" action="{{ route('admin.students.index') }}">
                                            <select name="no_of_courses" class="form-select me-2" style="width: 150px;" onchange="this.form.submit()">
                                                <option value="" {{ !request('no_of_courses') ? 'selected' : '' }}>All Courses Count</option>
                                                @for ($i = 0; $i <= 5; $i++)
                                                    <option value="{{ $i }}" {{ request('no_of_courses') == $i ? 'selected' : '' }}>
                                                        {{ $i }} Course{{ $i != 1 ? 's' : '' }}
                                                    </option>
                                                @endfor
                                                <option value="6+" {{ request('no_of_courses') === '6+' ? 'selected' : '' }}>6+ Courses</option>
                                            </select>
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('admin.students.index') }}" class="row g-3" id="filterForm">
                                    <div class="col-md-3">
                                        <label for="study_program" class="form-label">Study Program</label>
                                        <select name="study_program" id="study_program" class="form-select" onchange="fetchCourses()">
                                            <option value="" {{ !request('study_program') ? 'selected' : '' }}>All Study Programs</option>
                                            @foreach ($studyPrograms as $program)
                                                <option value="{{ $program->id }}" {{ request('study_program') == $program->id ? 'selected' : '' }}>
                                                    {{ $program->program_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="course" class="form-label">Course</label>
                                        <select name="course" id="course" class="form-select" onchange="fetchBatches()">
                                            <option value="" {{ !request('course') ? 'selected' : '' }}>All Courses</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}" data-study-program-id="{{ $course->program_id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                                                    {{ $course->course_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="batch" class="form-label">Batch</label>
                                        <select name="batch" id="batch" class="form-select">
                                            <option value="" {{ !request('batch') ? 'selected' : '' }}>All Batches</option>
                                            @foreach ($batches as $batch)
                                                <option value="{{ $batch->id }}" data-course-id="{{ $batch->course_id }}" {{ request('batch') == $batch->id ? 'selected' : '' }}>
                                                    {{ $batch->batch_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!--<div class="col-md-2">
                                        <label for="date_range" class="form-label">Date Range</label>
                                        <select name="date_range" id="date_range" class="form-select" onchange="toggleDateFields()">
                                            <option value="" {{ !request('date_range') ? 'selected' : '' }}>Select Date Range</option>
                                            <option value="last_24h" {{ request('date_range') === 'last_24h' ? 'selected' : '' }}>Last 24 Hours</option>
                                            <option value="last_7d" {{ request('date_range') === 'last_7d' ? 'selected' : '' }}>Last 7 Days</option>
                                            <option value="last_month" {{ request('date_range') === 'last_month' ? 'selected' : '' }}>Last Month</option>
                                            <option value="custom" {{ request('date_range') === 'custom' ? 'selected' : '' }}>Custom Date Range</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 custom-date-fields" style="display: {{ request('date_range') === 'custom' ? 'block' : 'none' }};">
                                        <label for="start_date" class="form-label">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}" max="{{ now()->format('Y-m-d') }}">
                                    </div>
                                    <div class="col-md-2 custom-date-fields" style="display: {{ request('date_range') === 'custom' ? 'block' : 'none' }};">
                                        <label for="end_date" class="form-label">End Date</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}" max="{{ now()->format('Y-m-d') }}">
                                    </div>-->
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Clear Filters</a>
                                    </div>
                                </form>
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
                                                <th>NIC or Passport</th>
                                                <th>Contact Number</th>
                                                <th>Courses</th>
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
                                                    <td>{{ $student->application ? ($student->application->nic_number ?? $student->application->passport_number ?? 'N/A') : 'N/A' }}</td>
                                                    <td>{{ $student->application ? $student->application->contact_number : 'N/A' }}</td>
                                                    <td>{{ $student->application->courseApplications->count() ?? 0 }}</td>
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

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            function toggleDateFields() {
                const dateRange = document.getElementById('date_range').value;
                const customDateFields = document.querySelectorAll('.custom-date-fields');
                customDateFields.forEach(field => {
                    field.style.display = dateRange === 'custom' ? 'block' : 'none';
                });
            }

            function fetchCourses() {
                const studyProgramId = document.getElementById('study_program').value;
                const courseSelect = document.getElementById('course');
                const batchSelect = document.getElementById('batch');

                if (!studyProgramId) {
                    courseSelect.innerHTML = '<option value="">All Courses</option>';
                    batchSelect.innerHTML = '<option value="">All Batches</option>';
                    return;
                }

                $.ajax({
                    url: '{{ route("admin.getCourses") }}',
                    method: 'GET',
                    data: { study_program_id: studyProgramId },
                    success: function(response) {
                        courseSelect.innerHTML = '<option value="">All Courses</option>';
                        response.courses.forEach(course => {
                            const selected = '{{ request("course") }}' == course.id ? 'selected' : '';
                            courseSelect.innerHTML += `<option value="${course.id}" ${selected}>${course.course_name}</option>`;
                        });
                        fetchBatches(); // Refresh batches after updating courses
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching courses:', error);
                    }
                });
            }

            function fetchBatches() {
                const courseId = document.getElementById('course').value;
                const batchSelect = document.getElementById('batch');

                if (!courseId) {
                    batchSelect.innerHTML = '<option value="">All Batches</option>';
                    return;
                }

                $.ajax({
                    url: '{{ route("admin.getBatches") }}',
                    method: 'GET',
                    data: { course_id: courseId },
                    success: function(response) {
                        batchSelect.innerHTML = '<option value="">All Batches</option>';
                        response.batches.forEach(batch => {
                            const selected = '{{ request("batch") }}' == batch.id ? 'selected' : '';
                            batchSelect.innerHTML += `<option value="${batch.id}" ${selected}>${batch.batch_no}</option>`;
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching batches:', error);
                    }
                });
            }

            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function () {
                toggleDateFields();
                // Trigger initial fetch if study program is pre-selected
                const initialStudyProgram = document.getElementById('study_program').value;
                if (initialStudyProgram) {
                    fetchCourses();
                }
                // Trigger initial batch fetch if course is pre-selected
                const initialCourse = document.getElementById('course').value;
                if (initialCourse) {
                    fetchBatches();
                }
            });
        </script>
    @endsection
@endsection