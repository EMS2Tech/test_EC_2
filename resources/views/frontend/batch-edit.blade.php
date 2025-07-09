@extends('frontend.layouts.master')

@section('title', 'Edit Batch')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Edit Batch</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('batches.update', $batch->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="batch_no" class="form-label">Batch No</label>
                                <input type="text" class="form-control" id="batch_no" name="batch_no" value="{{ old('batch_no', $batch->batch_no) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $batch->start_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $batch->end_date->format('Y-m-d')) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="course_id" class="form-label">Course</label>
                                <select class="form-control" id="course_id" name="course_id" required>
                                    @foreach (\App\Models\Course::all() as $course)
                                        <option value="{{ $course->id }}" {{ $batch->course_id == $course->id ? 'selected' : '' }}>
                                            {{ $course->course_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection