@extends('frontend.layouts.master')

@section('title', 'Add Batch')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Add New Batch</h4>
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

                <form action="{{ route('batches.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Course <span class="text-danger">*</span></label>
                                <select class="form-select @error('course_id') is-invalid @enderror" id="course_id" name="course_id" required>
                                    <option value="">Select Course</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->code }} - {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="batch_no" class="form-label">Batch No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('batch_no') is-invalid @enderror" id="batch_no" name="batch_no" value="{{ old('batch_no') }}" required>
                                @error('batch_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Add Batch</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>

                <!-- Batches Table -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0">Batches</h5>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>Course Code</th>
                                                <th>Course Name</th>
                                                <th>Batch No</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Date Added</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($batches as $batch)
                                                <tr>
                                                    <td>{{ $batch->course->code ?? 'N/A' }}</td>
                                                    <td>{{ $batch->course->course_name ?? 'N/A' }}</td>
                                                    <td>{{ $batch->batch_no }}</td>
                                                    <td>{{ $batch->start_date->format('Y-m-d') ?? 'N/A' }}</td>
                                                    <td>{{ $batch->end_date->format('Y-m-d') ?? 'N/A' }}</td>
                                                    <td>{{ $batch->created_at->format('Y-m-d') ?? 'N/A' }}</td>
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
                                                <span class="fw-medium">{{ $batches->firstItem() }} - {{ $batches->lastItem() }} of {{ $batches->total() }}</span>
                                            </div>
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination">
                                                    <li class="page-item {{ $batches->currentPage() == 1 ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $batches->url($batches->currentPage() - 1) }}" tabindex="-1" aria-disabled="{{ $batches->currentPage() == 1 ? 'true' : 'false' }}">Previous</a>
                                                    </li>
                                                    @for ($i = max(1, $batches->currentPage() - 1); $i <= min($batches->lastPage(), $batches->currentPage() + 1); $i++)
                                                        <li class="page-item {{ $i == $batches->currentPage() ? 'active' : '' }}">
                                                            <a class="page-link" href="{{ $batches->url($i) }}">{{ $i }}</a>
                                                        </li>
                                                    @endfor
                                                    <li class="page-item {{ $batches->currentPage() == $batches->lastPage() ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $batches->url($batches->currentPage() + 1) }}" aria-disabled="{{ $batches->currentPage() == $batches->lastPage() ? 'true' : 'false' }}">Next</a>
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
                <!-- End Batches Table -->
            </div>
        </div>
    </div>
@endsection