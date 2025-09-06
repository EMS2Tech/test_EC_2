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
                                            <select class="form-select" id="studyProgramme" name="study_programme" required>
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
                                            <div id="dynamicDocumentFields"></div>
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
        document.addEventListener('DOMContentLoaded', function () {
            // Preload study program data into JavaScript
            const studyProgramData = @json($studyPrograms->mapWithKeys(function ($program) {
                return [$program->id => $program->required_documents ?? []];
            })->all());

            console.log("Preloaded studyProgramData:", studyProgramData);

            function updateDocumentFields(programId) {
                const courseSelect = document.getElementById("course");
                const courseWrapper = document.getElementById("courseWrapper");
                const uploadsDiv = document.getElementById("uploads");
                const dynamicDocumentFields = document.getElementById("dynamicDocumentFields");

                if (!dynamicDocumentFields) {
                    console.error("dynamicDocumentFields element not found!");
                    return;
                }

                // Clear and hide uploads
                uploadsDiv.style.display = "none";
                dynamicDocumentFields.innerHTML = "";

                if (programId) {
                    // Show courses for the selected study program
                    const options = courseSelect.getElementsByTagName("option");
                    for (let option of options) {
                        option.style.display = option.dataset.studyProgramId === programId ? "block" : "none";
                    }
                    courseWrapper.style.display = "block";

                    // Get required documents
                    const requiredDocs = studyProgramData[programId] || [];
                    console.log("Required documents for programId", programId, ":", requiredDocs);

                    if (requiredDocs.length > 0) {
                        requiredDocs.forEach(doc => {
                            const docDiv = document.createElement("div");
                            docDiv.className = "mb-3 document-upload";
                            docDiv.innerHTML = `
                                <label class="form-label">${doc.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())} <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" name="${doc}" required>
                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                <div class="invalid-feedback">Please upload ${doc.replace('_', ' ')}.</div>
                            `;
                            dynamicDocumentFields.appendChild(docDiv);
                            console.log("Added upload field for:", doc);
                        });
                        uploadsDiv.style.display = "block";
                    } else {
                        console.log("No required documents for programId:", programId);
                    }
                } else {
                    courseWrapper.style.display = "none";
                }
            }

            // Attach event listeners
            const studyProgramme = document.getElementById("studyProgramme");
            if (studyProgramme) {
                studyProgramme.addEventListener("change", function () {
                    const selectedId = this.value;
                    console.log("Study programme changed to:", selectedId);
                    updateDocumentFields(selectedId);
                });
            } else {
                console.error("studyProgramme element not found!");
            }

            const courseSelect = document.getElementById("course");
            if (courseSelect) {
                courseSelect.addEventListener("change", function () {
                    const programId = this.options[this.selectedIndex].dataset.studyProgramId;
                    console.log("Course changed, programId:", programId);
                    updateDocumentFields(programId);
                });
            } else {
                console.error("courseSelect element not found!");
            }

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