@extends('frontend.layouts.master')

@section('title', 'Application')

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Course Registration</h4>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
            <!-- General Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form class="needs-validation" novalidate method="POST" action="{{ route('course.store') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Study Programme</label>
                                            <select class="form-select" id="studyProgramme" name="study_programme" required>
                                                <option value="">-- Select Study Programme --</option>
                                                <option value="bachelors">Bachelor’s Degree</option>
                                                <option value="higher_diploma">Higher Diploma</option>
                                                <option value="diploma">Diploma</option>
                                                <option value="postgraduate_diploma">Postgraduate Diploma</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('study_programme')" class="mt-2" />
                                            <div class="invalid-feedback">Please select a study programme.</div>
                                        </div>
                                        <!-- Course Dropdown (hidden by default) -->
                                        <div class="mb-3" id="courseWrapper" style="display: none;">
                                            <label class="form-label">Course</label>
                                            <select class="form-select" id="course" name="course" required>
                                                <option value="">-- Select Course --</option>
                                                <!-- Options will be populated dynamically -->
                                            </select>
                                            <x-input-error :messages="$errors->get('course')" class="mt-2" />
                                            <div class="invalid-feedback">Please select a course.</div>
                                        </div>
                                        <hr>
                                        <!-- Upload Sections -->
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
                                        <!-- Submit Button -->
                                        <button class="btn btn-primary" type="submit">Apply Now</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <script>
                            const courseOptions = {
                                bachelors: [
                                    "Bachelor of Business Administration (HRM)",
                                    "Bachelor of Psychology",
                                    "Bachelor of Education TESOL (Hon)",
                                    "Bachelor of Education"
                                ],
                                higher_diploma: [
                                    "Higher Diploma in Education",
                                    "Higher Diploma in HRM",
                                    "Higher Diploma in Psychology",
                                    "Higher Diploma in TESOL"
                                ],
                                diploma: [
                                    "Diploma in ICT",
                                    "Diploma in Education",
                                    "Diploma In Teaching Korean as a Foreign Language",
                                    "Diploma In Teaching Hindi as a Foreign Language",
                                    "Diploma In Teaching Chinese as a Foreign Language",
                                    "Diploma In Teaching Japanese as a Foreign Language",
                                    "Diploma in Criminology",
                                    "Diploma In Human Resourse Management",
                                    "Diploma in Social Work and Human Rights",
                                    "Diploma in Teaching Tamil as a Second Language",
                                    "Diploma In Business Management",
                                    "Diploma In Law And Criminal Justice",
                                    "Diploma In Psychology And Counselling",
                                    "Diploma In Tourism And Heritage Management",
                                    "Diploma in Disaster and Environmental Management",
                                    "Diploma in Teaching English as a Second Language",
                                    "Diploma In Buddhist Counselling And Management",
                                    "Diploma in Marketing Management"
                                ],
                                postgraduate_diploma: [
                                    "Postgraduate Diploma in Criminal Justice",
                                    "Postgraduate Diploma in Education"
                                ]
                            };

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

                            // When programme changes
                            studyProgramme.addEventListener("change", function () {
                                const selected = this.value;

                                // Reset course dropdown
                                courseSelect.innerHTML = '<option value="">-- Select Course --</option>';
                                if (selected && courseOptions[selected]) {
                                    courseWrapper.style.display = "block";
                                    courseOptions[selected].forEach(course => {
                                        const option = document.createElement("option");
                                        option.value = course;
                                        option.textContent = course;
                                        courseSelect.appendChild(option);
                                    });
                                } else {
                                    courseWrapper.style.display = "none";
                                }

                                // Reset upload sections
                                uploadsDiv.style.display = "block";
                                olUpload.style.display = "none";
                                alUpload.style.display = "none";
                                diplomaUpload.style.display = "none";
                                degreeUpload.style.display = "none";
                                transcriptUpload.style.display = "none";

                                // Show relevant uploads
                                if (selected === "diploma") {
                                    olUpload.style.display = "block";
                                } else if (selected === "higher_diploma") {
                                    alUpload.style.display = "block";
                                    diplomaUpload.style.display = "block";
                                } else if (selected === "bachelors") {
                                    olUpload.style.display = "block";
                                    alUpload.style.display = "block";
                                    diplomaUpload.style.display = "block";
                                } else if (selected === "postgraduate_diploma") {
                                    degreeUpload.style.display = "block";
                                    transcriptUpload.style.display = "block";
                                } else {
                                    uploadsDiv.style.display = "none";
                                }
                            });

                            // Handle displaying multiple diploma file names
                            const selectedDiplomaFiles = [];

                            if (diplomaCertificates && diplomaFileList) {
                                diplomaCertificates.addEventListener("change", function () {
                                    const newFiles = Array.from(this.files);

                                    // Add new files without duplicates
                                    newFiles.forEach(file => {
                                        if (!selectedDiplomaFiles.some(f => f.name === file.name && f.size === file.size)) {
                                            selectedDiplomaFiles.push(file);
                                        }
                                    });

                                    // Reset file input to allow selecting the same file again if needed
                                    this.value = "";

                                    // Update the visible list
                                    renderDiplomaFileList();
                                });
                            }

                            function renderDiplomaFileList() {
                                diplomaFileList.innerHTML = "";
                                selectedDiplomaFiles.forEach((file, index) => {
                                    const li = document.createElement("li");
                                    li.textContent = file.name + " ";

                                    // Add remove button
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

                            // Bootstrap Validation
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

                            const selectedOtherFiles = [];
                            const otherCertificates = document.getElementById("otherCertificates");
                            const otherCertificatesFileList = document.getElementById("otherCertificatesFileList");

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
                                    updateOtherValidationState(); // validation class
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

                            // Update validation classes for other certificates
                            function updateOtherValidationState() {
                                if (selectedOtherFiles.length > 0) {
                                    otherCertificates.classList.remove("is-invalid");
                                    otherCertificates.classList.add("is-valid");
                                } else {
                                    otherCertificates.classList.remove("is-valid");
                                    otherCertificates.classList.add("is-invalid");
                                }
                            }
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection