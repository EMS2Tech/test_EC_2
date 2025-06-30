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
                                            <label class="form-label">Study Programme</label>
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
                                            <label class="form-label">Course</label>
                                            <select class="form-select" id="course" name="course" required>
                                                <option value="">-- Select Course --</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('course')" class="mt-2" />
                                            <div class="invalid-feedback">Please select a course.</div>
                                        </div>
                                        <hr>
                                        <div id="uploads" style="display: none;">
                                            <div class="mb-3" id="olUpload" style="display: none;">
                                                <label class="form-label">G.C.E Ordinary Level Certificate</label>
                                                <input type="file" class="form-control" name="ol_certificate">
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('ol_certificate')" class="mt-2" />
                                                <div class="invalid-feedback">Please upload.</div>
                                            </div>
                                            <div class="mb-3" id="alUpload" style="display: none;">
                                                <label class="form-label">G.C.E Advanced Level Certificate</label>
                                                <input type="file" class="form-control" name="al_certificate">
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('al_certificate')" class="mt-2" />
                                                <div class="invalid-feedback">Please upload.</div>
                                            </div>
                                            <div class="mb-3" id="diplomaUpload" style="display: none;">
                                                <label class="form-label">Diploma Certificate(s)</label>
                                                <input type="file" class="form-control" id="diplomaCertificates" name="diploma_certificates[]" multiple>
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('diploma_certificates.*')" class="mt-2" />
                                                <ul id="diplomaFileList" class="mt-2"></ul>
                                            </div>
                                            <div class="mb-3" id="degreeUpload" style="display: none;">
                                                <label class="form-label">University Degree Certificate</label>
                                                <input type="file" class="form-control" name="degree_certificate">
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('degree_certificate')" class="mt-2" />
                                                <div class="invalid-feedback">Please upload.</div>
                                            </div>
                                            <div class="mb-3" id="transcriptUpload" style="display: none;">
                                                <label class="form-label">University Transcript</label>
                                                <input type="file" class="form-control" name="transcript_certificate">
                                                <small class="form-text text-muted">Max Size 4MB - Double Side</small>
                                                <x-input-error :messages="$errors->get('transcript_certificate')" class="mt-2" />
                                                <div class="invalid-feedback">Please upload.</div>
                                            </div>
                                            <div id="otherCertificatesUpload" class="mb-3">
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
        const studyProgramme = document.getElementById("studyProgramme");
        const courseSelect = document.getElementById("course");
        const courseWrapper = document.getElementById("courseWrapper");

        const uploadsDiv = document.getElementById("uploads");
        const olUpload = document.getElementById("olUpload");
        const alUpload = document.getElementById("alUpload");
        const diplomaUpload = document.getElementById("diplomaUpload");
        const degreeUpload = document.getElementById("degreeUpload");
        const transcriptUpload = document.getElementById("transcriptUpload");
        const diplomaCertificates = document.getElementById("diplomaCertificates");
        const diplomaFileList = document.getElementById("diplomaFileList");
        const otherCertificates = document.getElementById("otherCertificates");
        const otherCertificatesFileList = document.getElementById("otherCertificatesFileList");

        studyProgramme.addEventListener("change", function () {
            const selectedId = this.value;
            courseWrapper.style.display = "none";
            courseSelect.innerHTML = '<option value="">-- Select Course --</option>';

            if (selectedId) {
                $.ajax({
                    url: '/get-courses/' + selectedId,
                    method: 'GET',
                    success: function (response) {
                        courseWrapper.style.display = "block";
                        $.each(response, function (id, text) {
                            const option = document.createElement("option");
                            option.value = id;
                            option.textContent = text;
                            courseSelect.appendChild(option);
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching courses:', error);
                    }
                });
            }

            uploadsDiv.style.display = "block";
            olUpload.style.display = "none";
            alUpload.style.display = "none";
            diplomaUpload.style.display = "none";
            degreeUpload.style.display = "none";
            transcriptUpload.style.display = "none";

            if (selectedId) {
                const program = {
                    '1': function() { olUpload.style.display = "block"; }, // Bachelor's
                    '2': function() { alUpload.style.display = "block"; diplomaUpload.style.display = "block"; }, // Higher Diploma
                    '3': function() { olUpload.style.display = "block"; }, // Diploma
                    '4': function() { degreeUpload.style.display = "block"; transcriptUpload.style.display = "block"; } // Postgraduate
                }[selectedId];

                if (program) program();
            } else {
                uploadsDiv.style.display = "none";
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