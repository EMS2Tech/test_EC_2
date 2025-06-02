@extends('frontend.layouts.master')

@section('title', 'Application')

@section('content')
    <div class="content-page">
        <div class="content">
            <!-- Start Content-->
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Personal Details</h4>
                    </div>
                </div>
            </div> <!-- container-fluid -->

            <!-- General Form -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Application Form</h5>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <form class="needs-validation" novalidate method="POST" action="{{ route('application.store') }}" enctype="multipart/form-data">
                                        @csrf

                                        <!-- Title -->
                                        <fieldset class="row mb-3">
                                            <legend class="col-form-label col-sm-2 pt-0">Title</legend>
                                            <div class="col-sm-10 d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleMr" value="Mr" {{ old('title', $application->title ?? '') === 'Mr' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="titleMr">Mr</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleMrs" value="Mrs" {{ old('title', $application->title ?? '') === 'Mrs' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="titleMrs">Mrs</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleMiss" value="Miss" {{ old('title', $application->title ?? '') === 'Miss' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="titleMiss">Miss</label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="title" id="titleRev" value="Rev" {{ old('title', $application->title ?? '') === 'Rev' ? 'checked' : '' }} required>
                                                    <label class="form-check-label" for="titleRev">Rev</label>
                                                </div>
                                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                                            </div>
                                        </fieldset>

                                        <!-- Full Name -->
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Full Name</label>
                                            <x-text-input id="full_name" name="full_name" type="text" class="form-control" :value="old('full_name', $application->full_name ?? '')" required autocomplete="name" />
                                            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                                            <small class="form-text text-muted">Use Block Letters</small>
                                        </div>

                                        <!-- Name with Initials -->
                                        <div class="mb-3">
                                            <label for="name_with_initials" class="form-label">Name With Initials</label>
                                            <x-text-input id="name_with_initials" name="name_with_initials" type="text" class="form-control" :value="old('name_with_initials', $application->name_with_initials ?? '')" required />
                                            <x-input-error :messages="$errors->get('name_with_initials')" class="mt-2" />
                                            <small class="form-text text-muted">Use Block Letters</small>
                                        </div>

                                        <!-- Birth Day -->
                                        <div class="mb-3">
                                            <label for="birthday" class="form-label">Birth Day</label>
                                            <x-text-input id="birthday" name="birthday" type="date" class="form-control" :value="old('birthday', $application->birthday ?? '')" required />
                                            <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
                                        </div>

                                        <!-- Nationality -->
                                        <div class="mb-3">
                                            <label for="nationality" class="form-label">Nationality</label>
                                            <select class="form-select" id="nationality" name="nationality" required onchange="handleNationalityChange()">
                                                <option value="" disabled {{ !old('nationality', $application->nationality ?? '') ? 'selected' : '' }}>-- Select Nationality --</option>
                                                <option value="Sri Lanka" {{ old('nationality', $application->nationality ?? '') === 'Sri Lanka' ? 'selected' : '' }}>Sri Lankan</option>
                                                <option value="Other" {{ old('nationality', $application->nationality ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            <x-input-error :messages="$errors->get('nationality')" class="mt-2" />
                                        </div>

                                        <!-- Sri Lankan Section -->
                                        <div id="sriLankanFields" style="display: {{ old('nationality', $application->nationality ?? 'Sri Lanka') === 'Sri Lanka' ? 'block' : 'none' }};">
                                            <div class="mb-3">
                                                <label for="nic_number" class="form-label">National ID Card Number</label>
                                                <x-text-input id="nic_number" name="nic_number" type="text" class="form-control" :value="old('nic_number', $application->nic_number ?? '')" />
                                                <x-input-error :messages="$errors->get('nic_number')" class="mt-2" />
                                                <small class="form-text text-muted">Old ID Format - 123456789V<br>New ID Format - 123456789012</small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nic_photo" class="form-label">Scan Copy of National ID Card</label>
                                                <input class="form-control" type="file" id="nic_photo" name="nic_photo" accept="image/*" />
                                                <x-input-error :messages="$errors->get('nic_photo')" class="mt-2" />
                                            </div>
                                        </div>

                                        <!-- Other Nationality Section -->
                                        <div id="otherNationalityFields" style="display: {{ old('nationality', $application->nationality ?? 'Sri Lanka') === 'Other' ? 'block' : 'none' }};">
                                            <div class="mb-3">
                                                <label for="other_nationality" class="form-label">Nationality</label>
                                                <x-text-input id="other_nationality" name="other_nationality" type="text" class="form-control" :value="old('other_nationality', $application->other_nationality ?? '')" placeholder="Enter Nationality" />
                                                <x-input-error :messages="$errors->get('other_nationality')" class="mt-2" />
                                            </div>
                                            <div class="mb-3">
                                                <label for="passport_photo" class="form-label">Scan Copy Passport</label>
                                                <input class="form-control" type="file" id="passport_photo" name="passport_photo" accept="image/*" />
                                                <x-input-error :messages="$errors->get('passport_photo')" class="mt-2" />
                                                <small class="form-text text-muted">Maximum Size 4MB</small>
                                            </div>
                                        </div>

                                        <hr>

                                        <!-- Photograph -->
                                        <div class="mb-3">
                                            <label for="photograph" class="form-label">Photograph</label>
                                            <input class="form-control" type="file" id="photograph" name="photograph" accept="image/*" required />
                                            <x-input-error :messages="$errors->get('photograph')" class="mt-2" />
                                            <small class="form-text text-muted">Maximum Size 4MB</small>
                                        </div>
                                        <hr>

                                        <!-- Address -->
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address', $application->address ?? '') }}</textarea>
                                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                            <small class="form-text text-muted">Capital Block Letters</small>
                                        </div>

                                        <!-- Contact Phone -->
                                        <div class="mb-3">
                                            <label class="form-label">Contact Phone</label>
                                            <div class="col-lg-12 col-xl-12">
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text">
                                                        <i data-feather="phone" style="width: 16px; height: 16px;"></i>
                                                    </span>
                                                    <x-text-input class="form-control" type="tel" name="contact_number" placeholder="07XXXXXXXX" :value="old('contact_number', $application->contact_number ?? '')" required pattern="^07[0-9]{8}$" />
                                                    <x-input-error :messages="$errors->get('contact_number')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- WhatsApp Phone -->
                                        <div class="mb-3">
                                            <label class="form-label">WhatsApp Phone</label>
                                            <div class="col-lg-12 col-xl-12">
                                                <div class="input-group has-validation">
                                                    <span class="input-group-text">
                                                        <i data-feather="message-circle" style="width: 16px; height: 16px;"></i>
                                                    </span>
                                                    <x-text-input class="form-control" type="tel" name="whatsapp_number" placeholder="07XXXXXXXX" :value="old('whatsapp_number', $application->whatsapp_number ?? '')" pattern="^07[0-9]{8}$" />
                                                    <x-input-error :messages="$errors->get('whatsapp_number')" class="mt-2" />
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
                                                    <x-text-input type="email" class="form-control" name="email_address" placeholder="example@email.com" :value="old('email_address', $application->email_address ?? Auth::user()->email)" required />
                                                    <x-input-error :messages="$errors->get('email_address')" class="mt-2" />
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <x-primary-button class="btn btn-primary">Submit</x-primary-button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- JavaScript Section -->
                        @section('scripts')
                            <script>
                                function handleNationalityChange() {
                                    const nationality = document.getElementById('nationality').value;
                                    const sriLankanFields = document.getElementById('sriLankanFields');
                                    const otherNationalityFields = document.getElementById('otherNationalityFields');

                                    sriLankanFields.style.display = nationality === 'Sri Lanka' ? 'block' : 'none';
                                    otherNationalityFields.style.display = nationality === 'Other' ? 'block' : 'none';
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

                                    // Initialize nationality fields visibility
                                    handleNationalityChange();
                                });
                            </script>
                        @endsection
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection