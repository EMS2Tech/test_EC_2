@extends('frontend.layouts.master')

@section('title', 'Add Subject')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Add New Subject</h4>
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

                <form action="{{ route('subjects.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="subject_id" class="form-label">Subject ID <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" value="{{ old('subject_id') }}" required>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="subject_name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('subject_name') is-invalid @enderror" id="subject_name" name="subject_name" value="{{ old('subject_name') }}" required>
                                @error('subject_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Add Subject</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>

                <!-- Subjects Table -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0">Subjects</h5>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>Subject ID</th>
                                                <th>Subject Name</th>
                                                <th>Date Added</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($subjects as $subject)
                                                <tr>
                                                    <td>{{ $subject->subject_id }}</td>
                                                    <td>{{ $subject->subject_name }}</td>
                                                    <td>{{ $subject->created_at->format('Y-m-d') ?? 'N/A' }}</td>
                                                    <td>
                                                        <a href="{{ route('subjects.edit', $subject->id) }}" class="btn btn-icon btn-sm bg-primary-subtle me-1" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="mdi mdi-pencil fs-12 text-primary"></i>
                                                        </a>
                                                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-icon btn-sm bg-danger-subtle me-1" data-bs-toggle="tooltip" title="Delete">
                                                                <i class="mdi mdi-trash-can fs-12 text-danger"></i>
                                                            </button>
                                                        </form>
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
                                                <span class="fw-medium">{{ $subjects->firstItem() }} - {{ $subjects->lastItem() }} of {{ $subjects->total() }}</span>
                                            </div>
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination">
                                                    <li class="page-item {{ $subjects->currentPage() == 1 ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $subjects->url($subjects->currentPage() - 1) }}" tabindex="-1" aria-disabled="{{ $subjects->currentPage() == 1 ? 'true' : 'false' }}">Previous</a>
                                                    </li>
                                                    @for ($i = max(1, $subjects->currentPage() - 1); $i <= min($subjects->lastPage(), $subjects->currentPage() + 1); $i++)
                                                        <li class="page-item {{ $i == $subjects->currentPage() ? 'active' : '' }}">
                                                            <a class="page-link" href="{{ $subjects->url($i) }}">{{ $i }}</a>
                                                        </li>
                                                    @endfor
                                                    <li class="page-item {{ $subjects->currentPage() == $subjects->lastPage() ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $subjects->url($subjects->currentPage() + 1) }}" aria-disabled="{{ $subjects->currentPage() == $subjects->lastPage() ? 'true' : 'false' }}">Next</a>
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
                <!-- End Subjects Table -->
            </div>
        </div>
    </div>
@endsection