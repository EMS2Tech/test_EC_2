@extends('frontend.layouts.master')

@section('title', 'Application')

@section('content')

    <div class="content-page">
        <div class="content">

            <!-- Start Content-->
            <div class="container-fluid">

                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Basic Details</h4>
                    </div>

                    <div class="text-end">
                        <ol class="breadcrumb m-0 py-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Pages</a></li>
                            <li class="breadcrumb-item active">Starter</li>
                        </ol>
                    </div>
                </div>
            </div> <!-- container-fluid -->

            <!-- General Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">Input Type</h5>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form class="needs-validation" novalidate>

                                        <!-- Title -->
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-2 pt-0">Title</legend>
                                            <div class="col-sm-10 d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleMr"
                                                        value="Mr" required>
                                                    <label class="form-check-label" for="titleMr">Mr</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleMrs"
                                                        value="Mrs" required>
                                                    <label class="form-check-label" for="titleMrs">Mrs</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleRev"
                                                        value="Rev" required>
                                                    <label class="form-check-label" for="titleRev">Rev</label>
                                                </div>
                                            </div>
                                        </fieldset>

                                        <!-- Full Name -->
                                        <div class="mb-3">
                                            <label for="fullName" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="fullName" required>
                                            <div class="invalid-feedback">Please enter your full name.</div>
                                            <small id="photoHelp" class="form-text text-muted">Use Block Letters</small>
                                        </div>

                                        <!-- Name with Initials -->
                                        <div class="mb-3">
                                            <label for="initials" class="form-label">Name With Initials</label>
                                            <input type="text" class="form-control" id="initials" required>
                                            <div class="invalid-feedback">Please enter name with initials.</div>
                                            <small id="photoHelp" class="form-text text-muted">Use Block Letters</small>
                                        </div>

                                        <!-- Birth Day -->
                                        <div class="mb-3">
                                            <label for="birthDay" class="form-label">Birth Day</label>
                                            <input type="date" class="form-control" id="birthDay" required>
                                            <div class="invalid-feedback">Please select your birthday.</div>
                                        </div>

                                        <!-- Nationality -->
                                        <div class="mb-3">
                                            <label for="example-select" class="form-label">Nationality</label>
                                            <select class="form-select" id="example-select" required
                                                onchange="handleNationalityChange()">
                                                <option value="">-- Select Nationality --</option>
                                                <option value="sri_lankan">Sri Lankan</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <div class="invalid-feedback">Please select your nationality.</div>
                                        </div>

                                        <!-- Sri Lankan Section -->
                                        <div id="sriLankanFields" style="display: none;">
                                            <div class="mb-3">
                                                <label for="nicNumber" class="form-label">National ID Card Number</label>
                                                <input type="text" id="nicNumber" class="form-control" required>
                                                <small class="form-text text-muted">Old ID Format - 000000000V<br>New ID
                                                    Format - 000000000000</small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nicFile" class="form-label">Scan Copy of National ID
                                                    Card</label>
                                                <input class="form-control" type="file" id="nicFile" required>
                                                <div class="invalid-feedback">Scan Copy of National ID Card</div>
                                            </div>
                                        </div>

                                        <!-- Other Nationality Section -->
                                        <div id="otherNationalityFields" style="display: none;">
                                            <div class="mb-3">
                                                <label for="otherNationality" class="form-label">Nationality</label>
                                                <input type="text" id="otherNationality" class="form-control"
                                                    placeholder="Nationality" required>
                                                <div class="invalid-feedback">Please select your nationality.</div>

                                            </div>
                                            <div class="mb-3">
                                                <label for="passportScan" class="form-label">Scan Copy Passport</label>
                                                <input class="form-control" type="file" id="passportScan" required>
                                                <div class="invalid-feedback">Scan Copy Passport</div>
                                                <small id="photoHelp" class="form-text text-muted">Maximum Size 4MB</small>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Photograph -->
                                        <div class="mb-3">
                                            <label for="photo" class="form-label">Photograph</label>
                                            <input class="form-control" type="file" id="photo" required>
                                            <small id="photoHelp" class="form-text text-muted">Maximum Size 4MB</small>
                                            <div class="invalid-feedback">Please upload your photo.</div>
                                        </div>
                                        <hr>

                                        <!-- Address -->
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" rows="2" required></textarea>
                                            <div class="invalid-feedback">Please enter your address.</div>
                                            <small id="photoHelp" class="form-text text-muted">Capital Block Letters</small>
                                        </div>

                                        <!-- Contact Phone -->
                                        <div class="mb-3">
                                            <label class="form-label">Contact Phone</label>
                                            <div class="col-lg-12 col-xl-12">
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text">
                                                        <i data-feather="phone" style="width: 16px; height: 16px;"></i>
                                                    </span>
                                                    <input class="form-control" type="tel" name="contactPhone"
                                                        placeholder="07XXXXXXXX" required pattern="^07[0-9]{8}$">
                                                    <div class="invalid-feedback">Enter a valid phone number starting with
                                                        07 and 10 digits total.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- WhatsApp Phone -->
                                        <div class="mb-3">
                                            <label class="form-label">WhatsApp Phone</label>
                                            <div class="col-lg-12 col-xl-12">
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text">
                                                        <i data-feather="message-circle"
                                                            style="width: 16px; height: 16px;"></i>
                                                    </span>
                                                    <input class="form-control" type="tel" name="whatsappPhone"
                                                        placeholder="07XXXXXXXX" required pattern="^07[0-9]{8}$">
                                                    <div class="invalid-feedback">Enter a valid WhatsApp number starting
                                                        with 07 and 10 digits total.</div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Email Address -->
                                        <div class="mb-3">
                                            <label class="form-label">Email Address</label>
                                            <div class="col-lg-12 col-xl-12">
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text">
                                                        <i data-feather="mail" style="width: 16px; height: 16px;"></i>
                                                    </span>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="example@email.com" required>
                                                    <div class="invalid-feedback">Enter a valid email address.</div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Submit Button -->
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- JavaScript Section -->
                        <script>
                            function handleNationalityChange() {
                                const selectedValue = document.getElementById("example-select").value;
                                const sriLankanFields = document.getElementById("sriLankanFields");
                                const otherNationalityFields = document.getElementById("otherNationalityFields");

                                sriLankanFields.style.display = "none";
                                otherNationalityFields.style.display = "none";

                                if (selectedValue === "sri_lankan") {
                                    sriLankanFields.style.display = "block";
                                } else if (selectedValue === "other") {
                                    otherNationalityFields.style.display = "block";
                                }
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
                        </script>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection