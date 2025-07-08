@extends('frontend.layouts.master')

@section('title', 'Edit Study Program')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Edit Study Program</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('study-programs.update', $program->id) }}" method="POST">
                            @csrf
                            @method('PATCH') <!-- Changed from PUT to PATCH -->
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $program->code) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="program_name" class="form-label">Program Name</label>
                                <input type="text" class="form-control" id="program_name" name="program_name" value="{{ old('program_name', $program->program_name) }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection