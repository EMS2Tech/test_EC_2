@extends('frontend.layouts.master')

@section('title', 'Add Course')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Add New Course</h4>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('courses.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="program_id" class="form-label">Study Program <span class="text-danger">*</span></label>
                                <select class="form-select @error('program_id') is-invalid @enderror" id="program_id" name="program_id" required>
                                    <option value="">Select Study Program</option>
                                    @foreach ($studyPrograms as $program)
                                        <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                            {{ $program->program_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('program_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('course_name') is-invalid @enderror" id="course_name" name="course_name" value="{{ old('course_name') }}" required>
                                @error('course_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="short_name" class="form-label">Short Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('short_name') is-invalid @enderror" id="short_name" name="short_name" value="{{ old('short_name') }}" required>
                                @error('short_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Add Course</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>

                <!-- Courses Table -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0">Courses</h5>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Program Name</th>
                                                <th>Course Name</th>
                                                <th>Short Name</th>
                                                <th>Date Added</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($courses as $course)
                                                <tr>
                                                    <td>{{ $course->code }}</td>
                                                    <td>{{ $course->studyProgram->program_name ?? 'N/A' }}</td>
                                                    <td>{{ $course->course_name }}</td>
                                                    <td>{{ $course->short_name }}</td>
                                                    <td>{{ $course->created_at->format('Y-m-d') ?? 'N/A' }}</td>
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
                                                <span class="fw-medium">{{ $courses->firstItem() }} - {{ $courses->lastItem() }} of {{ $courses->total() }}</span>
                                            </div>
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination">
                                                    <li class="page-item {{ $courses->currentPage() == 1 ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $courses->url($courses->currentPage() - 1) }}" tabindex="-1" aria-disabled="{{ $courses->currentPage() == 1 ? 'true' : 'false' }}">Previous</a>
                                                    </li>
                                                    @for ($i = max(1, $courses->currentPage() - 1); $i <= min($courses->lastPage(), $courses->currentPage() + 1); $i++)
                                                        <li class="page-item {{ $i == $courses->currentPage() ? 'active' : '' }}">
                                                            <a class="page-link" href="{{ $courses->url($i) }}">{{ $i }}</a>
                                                        </li>
                                                    @endfor
                                                    <li class="page-item {{ $courses->currentPage() == $courses->lastPage() ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $courses->url($courses->currentPage() + 1) }}" aria-disabled="{{ $courses->currentPage() == $courses->lastPage() ? 'true' : 'false' }}">Next</a>
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
                <!-- End Courses Table -->
            </div>
        </div>
    </div>
@endsection