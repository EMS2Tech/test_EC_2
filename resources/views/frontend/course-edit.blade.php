@extends('frontend.layouts.master')

@section('title', 'Edit Course')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Edit Course</h4>
                    </div>
                </div>

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('status') }}
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

                <form action="{{ route('courses.update', $course->id) }}" method="POST" id="courseForm">
                    @csrf
                    @method('PATCH')
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="code" class="form-label">Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $course->code) }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="course_name" class="form-label">Course Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('course_name') is-invalid @enderror" id="course_name" name="course_name" value="{{ old('course_name', $course->course_name) }}" required>
                                @error('course_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="short_name" class="form-label">Short Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('short_name') is-invalid @enderror" id="short_name" name="short_name" value="{{ old('short_name', $course->short_name) }}" required>
                                @error('short_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="study_program_id" class="form-label">Study Program <span class="text-danger">*</span></label>
                                <select class="form-control @error('study_program_id') is-invalid @enderror" id="study_program_id" name="study_program_id" required>
                                    @foreach (\App\Models\StudyProgram::all() as $program)
                                        <option value="{{ $program->id }}" {{ old('study_program_id', $course->study_program_id) == $program->id ? 'selected' : '' }}>
                                            {{ $program->program_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('study_program_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="subject_count" class="form-label">Number of Subjects <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('subject_count') is-invalid @enderror" id="subject_count" name="subject_count" value="{{ old('subject_count', $course->subjects->count()) }}" min="1" required>
                                @error('subject_count')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @endif
                            </div>
                            <div class="mb-3" id="subjectFields">
                                <label class="form-label">Subjects</label>
                                <div id="subjectInputs">
                                    <!-- Dynamic fields will be inserted here -->
                                </div>
                                @error('subjects')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('admin.add-course') }}" class="btn btn-secondary ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const subjectCountInput = document.getElementById('subject_count');
                const subjectInputsDiv = document.getElementById('subjectInputs');
                let currentCount = parseInt(subjectCountInput.value) || 1;

                if (!subjectCountInput || !subjectInputsDiv) {
                    console.error('Required elements not found:', { subjectCountInput, subjectInputsDiv });
                    return;
                }

                // Get current subject IDs for pre-selection
                const currentSubjectIds = @json($course->subjects->pluck('id')->toArray());

                // Function to generate subject select fields
                function generateSubjectFields(count) {
                    subjectInputsDiv.innerHTML = '';
                    for (let i = 0; i < count; i++) {
                        const div = document.createElement('div');
                        div.className = 'mb-3';
                        div.innerHTML = `
                            <label for="subjects[${i}]" class="form-label">Subject #${i + 1}</label>
                            <select class="form-control" id="subjects[${i}]" name="subjects[${i}]" required>
                                <option value="">Select Subject</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">
                                        {{ $subject->subject_id }} - {{ $subject->subject_name }}
                                    </option>
                                @endforeach
                            </select>
                        `;
                        subjectInputsDiv.appendChild(div);

                        // Pre-select current subjects if available
                        const select = div.querySelector('select');
                        if (i < currentSubjectIds.length) {
                            select.value = currentSubjectIds[i];
                        }
                        // Repopulate with old data after validation
                        const oldValue = '{{ old("subjects.' + i + '") }}';
                        if (oldValue) {
                            select.value = oldValue;
                        }
                    }
                }

                // Initial generation
                generateSubjectFields(currentCount);

                // Update fields when subject_count changes
                subjectCountInput.addEventListener('input', function() {
                    const newCount = parseInt(this.value) || 1;
                    if (newCount !== currentCount) {
                        generateSubjectFields(newCount);
                        currentCount = newCount;
                    }
                });

                // Log to confirm script execution
                console.log('Subject fields script loaded successfully', { currentSubjectIds });
            });
        </script>
    @endpush
@endsection