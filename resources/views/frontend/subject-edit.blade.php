@extends('frontend.layouts.master')

@section('title', 'Edit Subject')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Edit Subject</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="subject_id" class="form-label">Subject ID</label>
                                <input type="text" class="form-control" id="subject_id" name="subject_id" value="{{ old('subject_id', $subject->subject_id) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="subject_name" class="form-label">Subject Name</label>
                                <input type="text" class="form-control" id="subject_name" name="subject_name" value="{{ old('subject_name', $subject->subject_name) }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection