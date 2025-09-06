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
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="code" name="code" value="{{ old('code', $program->code) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="program_name" class="form-label">Program Name</label>
                                <input type="text" class="form-control" id="program_name" name="program_name" value="{{ old('program_name', $program->program_name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="required_documents" class="form-label">Required Documents <span class="text-danger">*</span></label>
                                <select class="form-select" id="required_documents" name="required_documents[]" multiple required>
                                    <option value="ol_certificate" {{ in_array('ol_certificate', old('required_documents', $program->required_documents ?? [])) ? 'selected' : '' }}>O-level Certificate</option>
                                    <option value="al_certificate" {{ in_array('al_certificate', old('required_documents', $program->required_documents ?? [])) ? 'selected' : '' }}>A-level Certificate</option>
                                    <option value="diploma_certificates" {{ in_array('diploma_certificates', old('required_documents', $program->required_documents ?? [])) ? 'selected' : '' }}>Diploma Certificate(s)</option>
                                    <option value="degree_certificate" {{ in_array('degree_certificate', old('required_documents', $program->required_documents ?? [])) ? 'selected' : '' }}>Degree Certificate</option>
                                    <option value="transcript_certificate" {{ in_array('transcript_certificate', old('required_documents', $program->required_documents ?? [])) ? 'selected' : '' }}>Transcript</option>
                                    
                                </select>
                                <small class="form-text text-muted">Select all documents required for this program (e.g., Degree might need Degree Certificate and Transcript).</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection