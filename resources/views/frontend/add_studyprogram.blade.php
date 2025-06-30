@extends('frontend.layouts.master')

@section('title', 'Add Study Program')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Add New Study Program</h4>
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

                <form action="{{ route('study-programs.store') }}" method="POST">
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
                                <label for="program_name" class="form-label">Program Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('program_name') is-invalid @enderror" id="program_name" name="program_name" value="{{ old('program_name') }}" required>
                                @error('program_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Add Program</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>

                <!-- Study Programs Table -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card overflow-hidden">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <h5 class="card-title mb-0">Study Programs</h5>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-traffic mb-0">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Program Name</th>
                                                <th>Date Added</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($studyPrograms as $program)
                                                <tr>
                                                    <td>{{ $program->code }}</td>
                                                    <td>{{ $program->program_name }}</td>
                                                    <td>{{ $program->created_at->format('Y-m-d') ?? 'N/A' }}</td>
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
                                                <span class="fw-medium">{{ $studyPrograms->firstItem() }} - {{ $studyPrograms->lastItem() }} of {{ $studyPrograms->total() }}</span>
                                            </div>
                                            <nav aria-label="Page navigation">
                                                <ul class="pagination">
                                                    <li class="page-item {{ $studyPrograms->currentPage() == 1 ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $studyPrograms->url($studyPrograms->currentPage() - 1) }}" tabindex="-1" aria-disabled="{{ $studyPrograms->currentPage() == 1 ? 'true' : 'false' }}">Previous</a>
                                                    </li>
                                                    @for ($i = max(1, $studyPrograms->currentPage() - 1); $i <= min($studyPrograms->lastPage(), $studyPrograms->currentPage() + 1); $i++)
                                                        <li class="page-item {{ $i == $studyPrograms->currentPage() ? 'active' : '' }}">
                                                            <a class="page-link" href="{{ $studyPrograms->url($i) }}">{{ $i }}</a>
                                                        </li>
                                                    @endfor
                                                    <li class="page-item {{ $studyPrograms->currentPage() == $studyPrograms->lastPage() ? 'disabled' : '' }}">
                                                        <a class="page-link" href="{{ $studyPrograms->url($studyPrograms->currentPage() + 1) }}" aria-disabled="{{ $studyPrograms->currentPage() == $studyPrograms->lastPage() ? 'true' : 'false' }}">Next</a>
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
                <!-- End Study Programs Table -->
            </div>
        </div>
    </div>
@endsection