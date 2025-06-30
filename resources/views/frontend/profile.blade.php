@extends('frontend.layouts.master')

@section('title', 'Profile')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Profile</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow sm:rounded-lg">
                            <div class="card-body">
                                <div class="align-items-center">
                                    <div class="hando-main-sections">
                                        <div class="hando-profile-main">
                                            <img src="{{ Auth::user()->application && Auth::user()->application->photograph ? asset('storage/' . Auth::user()->application->photograph) : asset('frontend/assets/images/users/default.jpg') }}"
                                                class="rounded-circle img-fluid avatar-xxl img-thumbnail float-start"
                                                alt="image profile"
                                                id="profile-image">
                                            <span class="sil-profile_main-pic-change img-thumbnail" onclick="document.getElementById('photograph-input').click();">
                                                <i class="mdi mdi-camera text-white"></i>
                                            </span>
                                            <input type="file" id="photograph-input" name="photograph" accept="image/*" style="display: none;" onchange="updateProfilePicture(this)">
                                        </div>
                                        <div class="overflow-hidden ms-md-4 ms-0">
                                            <h4 class="m-0 text-dark fs-20 mt-2 mt-md-0">{{ Auth::user()->name }}</h4>
                                            <p class="my-1 text-muted fs-16">{{ Auth::user()->email }}</p>
                                            <span
                                                class="badge bg-primary-subtle text-primary px-2 py-1 fs-13 fw-normal">{{ ucfirst(Auth::user()->type ?? 'Student') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card shadow sm:rounded-lg">
                            <div class="card-body pt-0">
                                <ul class="nav nav-underline border-bottom pt-2" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active p-2" id="profile_about_tab" data-bs-toggle="tab"
                                            href="#profile_about" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-information"></i></span>
                                            <span class="d-none d-sm-block">About</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link p-2" id="portfolio_education_tab" data-bs-toggle="tab"
                                            href="#profile_education" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-school"></i></span>
                                            <span class="d-none d-sm-block">Education</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link p-2" id="portfolio_payment_tab" data-bs-toggle="tab"
                                            href="#profile_payment" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-school"></i></span>
                                            <span class="d-none d-sm-block">Payment</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link p-2" id="setting_tab" data-bs-toggle="tab"
                                            href="#profile_setting" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-information"></i></span>
                                            <span class="d-none d-sm-block">Setting</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content text-muted">
                                    <div class="tab-pane active show pt-4" id="profile_about" role="tabpanel">
                                        <div class="container-fluid">
                                            @php
                                                $application = App\Models\Application::where('user_id', Auth::id())->first();
                                                $student = App\Models\Student::where('user_id', Auth::id())->first(); // Fetch student record
                                            @endphp

                                            @if ($application)
                                                <div class="row g-4">
                                                    <!-- Personal Information -->
                                                    <div class="col-12 col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-header bg-primary text-white">
                                                                <h5 class="card-title mb-0">Personal Information</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">Full Name</label>
                                                                    <p class="text-muted mb-1">{{ $application->full_name }}</p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">Student ID</label>
                                                                    <p class="text-muted mb-1">{{ $student ? $student->student_id : 'Not Available' }}</p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">Name with Initials</label>
                                                                    <p class="text-muted mb-1">
                                                                        {{ $application->name_with_initials }}</p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">Email Address</label>
                                                                    <p class="text-muted mb-1"><i
                                                                            class="mdi mdi-email-outline me-2"></i>{{ $application->email_address }}
                                                                    </p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">Mobile Number</label>
                                                                    <p class="text-muted mb-1"><i
                                                                            class="mdi mdi-phone-outline me-2"></i>+{{ $application->contact_number }}
                                                                    </p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">WhatsApp Number</label>
                                                                    <p class="text-muted mb-1"><i
                                                                            class="mdi mdi-whatsapp me-2"></i>{{ $application->whatsapp_number ?? 'N/A' }}
                                                                    </p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">Birthday</label>
                                                                    <p class="text-muted mb-1">
                                                                        {{ $application->birthday->format('F j, Y') }}</p>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">Application Status</label>
                                                                    <span
                                                                        class="badge bg-{{ $application->application_completed ? 'success' : 'warning' }}">
                                                                        {{ $application->application_completed ? 'Completed' : 'Pending' }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Nationality Details -->
                                                    <div class="col-12 col-md-6">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-header bg-primary text-white">
                                                                <h5 class="card-title mb-0">Nationality Details</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="mb-3">
                                                                    <label class="fw-semibold">Nationality</label>
                                                                    <p class="text-muted mb-1">{{ $application->nationality }}
                                                                    </p>
                                                                </div>
                                                                @if ($application->nationality === 'Sri Lanka')
                                                                    <div class="mb-3">
                                                                        <label class="fw-semibold">NIC Number</label>
                                                                        <p class="text-muted mb-1">
                                                                            {{ $application->nic_number ?? 'N/A' }}</p>
                                                                    </div>
                                                                    @if ($application->nic_photo)
                                                                        <div class="mb-3">
                                                                            <label class="fw-semibold">NIC Photo</label>
                                                                            <a href="{{ asset('storage/' . $application->nic_photo) }}"
                                                                                target="_blank" class="d-block mt-1">
                                                                                <img src="{{ asset('storage/' . $application->nic_photo) }}"
                                                                                    alt="NIC Photo" class="img-thumbnail"
                                                                                    style="max-width: 150px;">
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                @else
                                                                    <div class="mb-3">
                                                                        <label class="fw-semibold">Other Nationality</label>
                                                                        <p class="text-muted mb-1">
                                                                            {{ $application->other_nationality ?? 'N/A' }}</p>
                                                                    </div>
                                                                    @if ($application->passport_photo)
                                                                        <div class="mb-3">
                                                                            <label class="fw-semibold">Passport Photo</label>
                                                                            <a href="{{ asset('storage/' . $application->passport_photo) }}"
                                                                                target="_blank" class="d-block mt-1">
                                                                                <img src="{{ asset('storage/' . $application->passport_photo) }}"
                                                                                    alt="Passport Photo" class="img-thumbnail"
                                                                                    style="max-width: 150px;">
                                                                            </a>
                                                                        </div>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Address -->
                                                    <div class="col-12">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-header bg-primary text-white">
                                                                <h5 class="card-title mb-0">Address</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <p class="text-muted mb-0">{{ $application->address }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-info text-center" role="alert">
                                                    <h5 class="alert-heading">No Application Found</h5>
                                                    <p>Please complete your application to view your profile details.</p>
                                                    <a href="{{ route('user.dashboard') }}" class="btn btn-primary">Complete
                                                        Application</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane pt-4" id="profile_education" role="tabpanel">
    <div class="container-fluid">
        @php
            $courseApplications = App\Models\CourseApplication::with(['studyProgram', 'course.batches'])->where('user_id', Auth::id())->get();
        @endphp

        @if ($courseApplications->isNotEmpty())
            <div class="row g-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">Course Applications</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">Study Programme</th>
                                            <th scope="col">Course</th>
                                            <th scope="col">Batch(s)</th>
                                            <th scope="col">Apply Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($courseApplications as $application)
                                            <tr>
                                                <td>{{ $application->studyProgram->program_name ?? 'N/A' }}</td>
                                                <td>{{ $application->course->course_name ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($application->course->batches->isNotEmpty())
                                                        {{ $application->course->batches->pluck('batch_no')->join(', ') }}
                                                    @else
                                                        No active batches
                                                    @endif
                                                </td>
                                                <td>{{ $application->created_at->format('F j, Y h:i A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center" role="alert">
                <h5 class="alert-heading">No Course Applications Found</h5>
                <p>Please apply for a course to view your application details.</p>
                <a href="{{ route('course.apply') }}" class="btn btn-primary">Apply for a Course</a>
            </div>
        @endif
    </div>
</div>

                                    <div class="tab-pane pt-4" id="profile_payment" role="tabpanel">
                                        <div class="container-fluid">
                                            @php
                                                $payments = \App\Models\Payment::where('user_id', auth()->id())->get();
                                            @endphp

                                            @if ($payments && $payments->isNotEmpty())
                                                <div class="row g-4">
                                                    <div class="col-12">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-header bg-primary text-white">
                                                                <h5 class="card-title mb-0">Payment History</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="table-responsive">
                                                                    <table class="table table-hover table-bordered">
                                                                        <thead class="table-light">
                                                                            <tr>
                                                                                <th scope="col">Slip</th>
                                                                                <th scope="col">Uploaded At</th>
                                                                                <th scope="col">Status</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($payments as $payment)
                                                                                <tr>
                                                                                    <td>
                                                                                        @if ($payment->payment_slip)
                                                                                            <a href="{{ Storage::url($payment->payment_slip) }}" target="_blank" class="btn btn-sm btn-info">
                                                                                                View Slip
                                                                                            </a>
                                                                                        @else
                                                                                            <span class="text-muted">Not uploaded</span>
                                                                                        @endif
                                                                                    </td>
                                                                                    <td>{{ $payment->created_at ?? 'N/A' }}</td>
                                                                                    <td>{{ $payment->status ?? 'Pending' }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="alert alert-info text-center" role="alert">
                                                    <h5 class="alert-heading">No Payment Found</h5>
                                                    <p>Please make a payment to view your payment history.</p>
                                                    <a href="{{ route('payment.verify') }}" class="btn btn-primary">Make Payment</a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane pt-4" id="profile_setting" role="tabpanel">
                                        <div class="row">
                                            <div class="col-lg-6 col-xl-6">
                                                <div class="card border bg-white shadow sm:rounded-lg">
                                                    <div class="card-header">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <h4 class="card-title mb-0">Personal Information</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <form id="send-verification" method="post"
                                                            action="{{ route('verification.send') }}">
                                                            @csrf
                                                        </form>

                                                        <form method="post" action="{{ route('profile.update') }}"
                                                            class="mt-4 space-y-4">
                                                            @csrf
                                                            @method('patch')

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="name" :value="__('Name')"
                                                                    class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <x-text-input id="name" name="name" type="text"
                                                                        class="form-control" :value="old('name', $user->name)" required autofocus
                                                                        autocomplete="name" />
                                                                    <x-input-error class="mt-2"
                                                                        :messages="$errors->get('name')" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="email" :value="__('Email')"
                                                                    class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i
                                                                                class="mdi mdi-email"></i></span>
                                                                        <x-text-input id="email" name="email" type="email"
                                                                            class="form-control" :value="old('email', $user->email)" required
                                                                            autocomplete="username" />
                                                                    </div>
                                                                    <x-input-error class="mt-2"
                                                                        :messages="$errors->get('email')" />

                                                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                                                        <div>
                                                                            <p class="text-muted fs-14 mt-2">
                                                                                {{ __('Your email address is unverified.') }}

                                                                                <button form="send-verification"
                                                                                    class="text-primary fs-14 text-decoration-underline bg-transparent border-0 p-0">
                                                                                    {{ __('Click here to re-send the verification email.') }}
                                                                                </button>
                                                                            </p>

                                                                            @if (session('status') === 'verification-link-sent')
                                                                                <p class="mt-2 fs-14 text-success">
                                                                                    {{ __('A new verification link has been sent to your email address.') }}
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-lg-12 col-xl-12 d-flex gap-2">
                                                                    <x-primary-button
                                                                        class="btn btn-primary">{{ __('Save') }}</x-primary-button>
                                                                    <button type="button" class="btn btn-danger"
                                                                        onclick="this.closest('form').reset()">Cancel</button>

                                                                    @if (session('status') === 'profile-updated')
                                                                        <p x-data="{ show: true }" x-show="show" x-transition
                                                                            x-init="setTimeout(() => show = false, 2000)"
                                                                            class="text-muted fs-14">{{ __('Saved.') }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-xl-6">
                                                <div class="card border bg-white shadow sm:rounded-lg">
                                                    <div class="card-header">
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <h4 class="card-title mb-0">Change Password</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <form method="post" action="{{ route('password.update') }}"
                                                            class="mt-4 space-y-4">
                                                            @csrf
                                                            @method('put')

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="update_password_current_password"
                                                                    :value="__('Current Password')" class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <x-text-input id="update_password_current_password"
                                                                        name="current_password" type="password"
                                                                        class="form-control"
                                                                        autocomplete="current-password" />
                                                                    <x-input-error
                                                                        :messages="$errors->updatePassword->get('current_password')"
                                                                        class="mt-2" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="update_password_password"
                                                                    :value="__('New Password')" class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <x-text-input id="update_password_password"
                                                                        name="password" type="password" class="form-control"
                                                                        autocomplete="new-password" />
                                                                    <x-input-error
                                                                        :messages="$errors->updatePassword->get('password')"
                                                                        class="mt-2" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="update_password_password_confirmation"
                                                                    :value="__('Confirm Password')" class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <x-text-input id="update_password_password_confirmation"
                                                                        name="password_confirmation" type="password"
                                                                        class="form-control" autocomplete="new-password" />
                                                                    <x-input-error
                                                                        :messages="$errors->updatePassword->get('password_confirmation')"
                                                                        class="mt-2" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-lg-12 col-xl-12 d-flex gap-2">
                                                                    <x-primary-button
                                                                        class="btn btn-primary">{{ __('Save') }}</x-primary-button>
                                                                    <button type="button" class="btn btn-danger"
                                                                        onclick="this.closest('form').reset()">Cancel</button>

                                                                    @if (session('status') === 'password-updated')
                                                                        <p x-data="{ show: true }" x-show="show" x-transition
                                                                            x-init="setTimeout(() => show = false, 2000)"
                                                                            class="text-muted fs-14">{{ __('Saved.') }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('scripts')
    <!-- Include SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function updateProfilePicture(input) {
            if (input.files && input.files[0]) {
                let formData = new FormData();
                formData.append('photograph', input.files[0]);
                formData.append('_token', '{{ csrf_token() }}');

                fetch('{{ route("profile.photograph") }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('profile-image').src = data.newPhotographUrl;
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Profile picture updated successfully!',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message || 'Failed to update profile picture.',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while updating the profile picture.',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            }
        }
    </script>
    @endsection
@endsection