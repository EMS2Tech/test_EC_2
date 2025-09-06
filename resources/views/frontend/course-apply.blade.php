@extends('frontend.layouts.master')

@section('title', 'Application')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Course Registration</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form class="needs-validation" novalidate method="POST" action="{{ route('course-application.store') }}" enctype="multipart/form-data" id="registrationForm">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Study Programme <span class="text-danger">*</span></label>
                                            <select class="form-select" id="studyProgramme" name="study_programme" required onchange="filterCourses()">
                                                <option value="">-- Select Study Programme --</option>
                                                @foreach ($studyPrograms as $program)
                                                    <option value="{{ $program->id }}">{{ $program->program_name }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('study_programme')" class="mt-2" />
                                            <div class="invalid-feedback">Please select a study programme.</div>
                                        </div>
                                        <div class="mb-3" id="courseWrapper" style="display: none;">
                                            <label class="form-label">Course <span class="text-danger">*</span></label>
                                            <select class="form-select" id="course" name="course" required>
                                                <option value="">-- Select Course --</option>
                                                @foreach ($courses as $course)
                                                    <option value="{{ $course->id }}" data-study-program-id="{{ $course->program_id }}">
                                                        {{ $course->course_name }} (Batch(s): @php
                                                            $activeBatches = $course->batches;
                                                            echo $activeBatches->isNotEmpty() ? $activeBatches->pluck('batch_no')->implode(', ') : 'No active batches';
                                                        @endphp)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <x-input-error :messages="$errors->get('course')" class="mt-2" />
                                            <div class="invalid-feedback">Please select a course.</div>
                                        </div>
                                        <hr>
                                        <div id="uploads" style="display: none;">
                                            <div class="mb-3" id="olUpload" style="display: none;">
                                                <label class="form-label">G.C.E Ordinary Level Certificate <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="ol_certificate" required>
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('ol_certificate')" class="mt-2" />
                                                <div class="invalid-feedback">Please upload O/L certificate.</div>
                                            </div>
                                            <div class="mb-3" id="alUpload" style="display: none;">
                                                <label class="form-label">G.C.E Advanced Level Certificate <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="al_certificate" required>
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('al_certificate')" class="mt-2" />
                                                <div class="invalid-feedback">Please upload A/L certificate.</div>
                                            </div>
                                            <div class="mb-3" id="diplomaUpload" style="display: none;">
                                                <label class="form-label">Diploma Certificate(s) <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="diplomaCertificates" name="diploma_certificates[]" multiple required>
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('diploma_certificates.*')" class="mt-2" />
                                                <ul id="diplomaFileList" class="mt-2"></ul>
                                                <div class="invalid-feedback">Please upload diploma certificate(s).</div>
                                            </div>
                                            <div class="mb-3" id="degreeUpload" style="display: none;">
                                                <label class="form-label">University Degree Certificate <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="degree_certificate" required>
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('degree_certificate')" class="mt-2" />
                                                <div class="invalid-feedback">Please upload degree certificate.</div>
                                            </div>
                                            <div class="mb-3" id="transcriptUpload" style="display: none;">
                                                <label class="form-label">University Transcript <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" name="transcript_certificate" required>
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('transcript_certificate')" class="mt-2" />
                                                <div class="invalid-feedback">Please upload transcript.</div>
                                            </div>
                                            <div class="mb-3" id="otherCertificatesUpload">
                                                <label for="otherCertificates" class="form-label">Other Certificates (if any)</label>
                                                <input type="file" class="form-control" id="otherCertificates" name="other_certificates[]" multiple>
                                                <x-input-error :messages="$errors->get('other_certificates.*')" class="mt-2" />
                                                <div class="invalid-feedback">You can upload additional certificates here.</div>
                                                <ul id="otherCertificatesFileList" class="mt-2"></ul>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Apply Now</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function filterCourses() {
            const studyProgramme = document.getElementById("studyProgramme");
            const courseSelect = document.getElementById("course");
            const courseWrapper = document.getElementById("courseWrapper");
            const selectedId = studyProgramme.value;

            // Hide all course options initially
            const options = courseSelect.getElementsByTagName("option");
            for (let option of options) {
                option.style.display = "none";
            }

            // Show the default option
            courseSelect.options[0].style.display = "block";

            if (selectedId) {
                courseWrapper.style.display = "block";
                // Filter and show courses for the selected study program
                for (let option of options) {
                    if (option.dataset.studyProgramId === selectedId) {
                        option.style.display = "block";
                    }
                }
            } else {
                courseWrapper.style.display = "none";
            }

            // Reset uploads
            const uploadsDiv = document.getElementById("uploads");
            uploadsDiv.style.display = "none";
            document.getElementById("olUpload").style.display = "none";
            document.getElementById("alUpload").style.display = "none";
            document.getElementById("diplomaUpload").style.display = "none";
            document.getElementById("degreeUpload").style.display = "none";
            document.getElementById("transcriptUpload").style.display = "none";
        }

        courseSelect.addEventListener("change", function () {
            const selectedCourseId = this.value;
            const uploadsDiv = document.getElementById("uploads");
            const olUpload = document.getElementById("olUpload");
            const alUpload = document.getElementById("alUpload");
            const diplomaUpload = document.getElementById("diplomaUpload");
            const degreeUpload = document.getElementById("degreeUpload");
            const transcriptUpload = document.getElementById("transcriptUpload");

            uploadsDiv.style.display = selectedCourseId ? "block" : "none";
            olUpload.style.display = "none";
            alUpload.style.display = "none";
            diplomaUpload.style.display = "none";
            degreeUpload.style.display = "none";
            transcriptUpload.style.display = "none";

            if (selectedCourseId) {
                const courseType = selectedCourseId.split('-')[0]; // Assuming ID format like "4-1" where 4 is the type
                const courseRequirements = {
                    '1': ['ol'], // Bachelor's
                    '2': ['al', 'diploma'], // Higher Diploma
                    '3': ['ol'], // Diploma
                    '4': ['degree', 'transcript'] // Postgraduate
                }[courseType] || [];

                if (courseRequirements.includes('ol')) olUpload.style.display = "block";
                if (courseRequirements.includes('al')) alUpload.style.display = "block";
                if (courseRequirements.includes('diploma')) diplomaUpload.style.display = "block";
                if (courseRequirements.includes('degree')) degreeUpload.style.display = "block";
                if (courseRequirements.includes('transcript')) transcriptUpload.style.display = "block";
            }
        });

        const selectedDiplomaFiles = [];
        if (diplomaCertificates && diplomaFileList) {
            diplomaCertificates.addEventListener("change", function () {
                const newFiles = Array.from(this.files);
                newFiles.forEach(file => {
                    if (!selectedDiplomaFiles.some(f => f.name === file.name && f.size === file.size)) {
                        selectedDiplomaFiles.push(file);
                    }
                });
                this.value = "";
                renderDiplomaFileList();
            });
        }

        function renderDiplomaFileList() {
            diplomaFileList.innerHTML = "";
            selectedDiplomaFiles.forEach((file, index) => {
                const li = document.createElement("li");
                li.textContent = file.name + " ";
                const removeBtn = document.createElement("button");
                removeBtn.textContent = "Remove";
                removeBtn.className = "btn btn-sm btn-danger ms-2";
                removeBtn.onclick = function () {
                    selectedDiplomaFiles.splice(index, 1);
                    renderDiplomaFileList();
                };
                li.appendChild(removeBtn);
                diplomaFileList.appendChild(li);
            });
        }

        const selectedOtherFiles = [];
        if (otherCertificates && otherCertificatesFileList) {
            otherCertificates.addEventListener("change", function () {
                const newFiles = Array.from(this.files);
                newFiles.forEach(file => {
                    if (!selectedOtherFiles.some(f => f.name === file.name && f.size === file.size)) {
                        selectedOtherFiles.push(file);
                    }
                });
                this.value = "";
                renderOtherFileList();
                updateOtherValidationState();
            });
        }

        function renderOtherFileList() {
            otherCertificatesFileList.innerHTML = "";
            selectedOtherFiles.forEach((file, index) => {
                const li = document.createElement("li");
                li.textContent = file.name + " ";
                const removeBtn = document.createElement("button");
                removeBtn.textContent = "Remove";
                removeBtn.className = "btn btn-sm btn-danger ms-2";
                removeBtn.onclick = function () {
                    selectedOtherFiles.splice(index, 1);
                    renderOtherFileList();
                    updateOtherValidationState();
                };
                li.appendChild(removeBtn);
                otherCertificatesFileList.appendChild(li);
            });
        }

        function updateOtherValidationState() {
            if (selectedOtherFiles.length > 0) {
                otherCertificates.classList.remove("is-invalid");
                otherCertificates.classList.add("is-valid");
            } else {
                otherCertificates.classList.remove("is-valid");
                otherCertificates.classList.add("is-invalid");
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('.needs-validation');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
@endsection