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
                                            <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('frontend/assets/images/users/default.jpg') }}" class="rounded-circle img-fluid avatar-xxl img-thumbnail float-start" alt="image profile">
                                            <span class="sil-profile_main-pic-change img-thumbnail">
                                                <i class="mdi mdi-camera text-white"></i>
                                            </span>
                                        </div>
                                        <div class="overflow-hidden ms-md-4 ms-0">
                                            <h4 class="m-0 text-dark fs-20 mt-2 mt-md-0">{{ Auth::user()->name }}</h4>
                                            <p class="my-1 text-muted fs-16">{{ Auth::user()->email }}</p>
                                            <span class="badge bg-primary-subtle text-primary px-2 py-1 fs-13 fw-normal">{{ ucfirst(Auth::user()->type ?? 'Student') }}</span>
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
                                        <a class="nav-link active p-2" id="profile_about_tab" data-bs-toggle="tab" href="#profile_about" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-information"></i></span>
                                            <span class="d-none d-sm-block">About</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link p-2" id="portfolio_education_tab" data-bs-toggle="tab" href="#profile_education" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-school"></i></span>
                                            <span class="d-none d-sm-block">Education</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link p-2" id="setting_tab" data-bs-toggle="tab" href="#profile_setting" role="tab">
                                            <span class="d-block d-sm-none"><i class="mdi mdi-information"></i></span>
                                            <span class="d-none d-sm-block">Setting</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content text-muted">
                                    <div class="tab-pane active show pt-4" id="profile_about" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 col-sm-6 mb-4">
                                                <div>
                                                    <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">About me</h5>
                                                    <p>Geetings, fellow software enthusiasts! I'm thrilled to see your intereset in exploring my profile. I'm Christian Mayo, a 24-year-old software engineer from the United Kingdom. My educational path led me to earn a Bachelor's Degeer in Computer Science, specializing in Software Engineering. With this qualification, I'm equipped to dive into the world of coding and develooment,ready to tackle exciting projects and contribute to cutting-edge technological advancement...</p>
                                                </div>
                                                <div class="skills-details mt-3">
                                                    <h6 class="text-uppercase fs-13">Skills</h6>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge bg-light px-3 text-dark py-2 fw-semibold">User Interface</span>
                                                        <span class="badge bg-light px-3 text-dark py-2 fw-semibold">User Experience</span>
                                                        <span class="badge bg-light px-3 text-dark py-2 fw-semibold">Interaction Design</span>
                                                        <span class="badge bg-light px-3 text-dark py-2 fw-semibold">3D Design</span>
                                                        <span class="badge bg-light px-3 text-dark py-2 fw-semibold">Information Architecture</span>
                                                        <span class="badge bg-light px-3 text-dark py-2 fw-semibold">User Research</span>
                                                        <span class="badge bg-light px-3 text-dark py-2 fw-semibold">Wireframing</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 mb-4">
                                                <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">Contact Details</h5>
                                                <div class="row">
                                                    <div class="col-md-4 col-sm-4 col-lg-4 mb-3">
                                                        <div class="profile-email mb-md-2">
                                                            <h6 class="text-uppercase fs-13">Email Addess</h6>
                                                            <a href="#" class="text-primary fs-14 text-decoration-underline">{{ Auth::user()->email }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-lg-4 mb-3">
                                                        <div class="profile-email">
                                                            <h6 class="text-uppercase fs-13">Social Media</h6>
                                                            <ul class="social-list list-inline mt-0 mb-0">
                                                                <li class="list-inline-item">
                                                                    <a href="javascript: void(0);" class="social-item border-primary text-primary justify-content-center align-content-center"><i class="mdi mdi-facebook fs-14"></i></a>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="javascript: void(0);" class="social-item border-danger text-danger justify-content-center align-content-center"><i class="mdi mdi-google fs-14"></i></a>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="javascript: void(0);" class="social-item border-info text-info justify-content-center align-content-center"><i class="mdi mdi-twitter fs-14"></i></a>
                                                                </li>
                                                                <li class="list-inline-item">
                                                                    <a href="javascript: void(0);" class="social-item border-secondary text-secondary justify-content-center align-content-center"><i class="mdi mdi-github fs-14"></i></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 col-sm-4 col-lg-4 mb-3">
                                                        <div class="profile-email">
                                                            <h6 class="text-uppercase fs-13">Location</h6>
                                                            <a href="#" class="fs-14">Melbourne, Australia</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="skills-details">
                                                    <h6 class="text-uppercase fs-13">Fluent In</h6>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge bg-light px-3 py-2 text-dark fw-semibold">English</span>
                                                        <span class="badge bg-light px-3 py-2 text-dark fw-semibold">Mandarin</span>
                                                        <span class="badge bg-light px-3 py-2 text-dark fw-semibold">Spanish</span>
                                                        <span class="badge bg-light px-3 py-2 text-dark fw-semibold">French</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane pt-4" id="profile_education" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-lg-6">
                                                <h5 class="fs-16 text-dark fw-semibold mb-3 text-capitalize">My Education</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-12 col-md-4">
                                                    <ol class="profile-section list-unstyled mb-0 px-4">
                                                        <li class="profile-item">
                                                            <div class="avatar-sm profile-icon p-1">
                                                                <div class="avatar-title rounded-2 bg-light" style="height: 40px; width: 40px;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512">
                                                                        <mask id="circleFlagsHausa0">
                                                                            <circle cx="256" cy="256" r="256" fill="#fff" />
                                                                        </mask>
                                                                        <g mask="url(#circleFlagsHausa0)">
                                                                            <path fill="#eee" d="M0 0h512v512H0z" />
                                                                            <path fill="#6da544" d="m218 154l38-84l38 84l-140 140l-84-38l84-38l140 140l-38 84l-38-84l140-140l84 38l-84 38z" />
                                                                        </g>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            <div class="exper-item-list">
                                                                <h5 class="fs-18 text-dark">Middles Earth Technic University</h5>
                                                                <p class="mb-2 fw-semibold text-dark">Master Degree In Computer Science and Mathematics</p>
                                                                <div class="list-inline list-inline-dots mb-2 fs-14">
                                                                    <div class="list-inline-item">January 2018</div>
                                                                    <div class="list-inline-item list-inline-item-second">Istanbul, Turkey</div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ol>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <ol class="profile-section list-unstyled mb-0 px-4">
                                                        <li class="profile-item">
                                                            <div class="avatar-sm profile-icon p-1">
                                                                <div class="avatar-title rounded-2 bg-light" style="height: 40px; width: 40px;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512">
                                                                        <mask id="circleFlagsCheckered0">
                                                                            <circle cx="256" cy="256" r="256" fill="#fff" />
                                                                        </mask>
                                                                        <g mask="url(#circleFlagsCheckered0)">
                                                                            <path fill="#eee" d="M0 0h512v512H0z" />
                                                                            <path fill="#333" d="M384 0h128v128H0v128h512v128H0v128h128V0h128v512h128z" />
                                                                        </g>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            <div class="exper-item-list">
                                                                <h5 class="fs-16 text-dark">Bogazicied Technic University</h5>
                                                                <p class="mb-2 fw-semibold text-dark">Bachelor Degree In Computer Science and Mathematics</p>
                                                                <div class="list-inline list-inline-dots mb-2 fs-14">
                                                                    <div class="list-inline-item">June 2016</div>
                                                                    <div class="list-inline-item list-inline-item-second">Istanbul, Turkey</div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ol>
                                                </div>
                                                <div class="col-12 col-md-4">
                                                    <ol class="profile-section list-unstyled mb-0 px-4">
                                                        <li class="profile-item">
                                                            <div class="avatar-sm profile-icon p-1">
                                                                <div class="avatar-title rounded-2 bg-light" style="height: 40px; width: 40px;">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 512 512">
                                                                        <mask id="circleFlagsUnitedNations0">
                                                                            <circle cx="256" cy="256" r="256" fill="#fff" />
                                                                        </mask>
                                                                        <g mask="url(#circleFlagsUnitedNations0)">
                                                                            <path fill="#338af3" d="M0 0h512v512H0z" />
                                                                            <circle cx="256" cy="256" r="145" fill="#eee" />
                                                                            <circle cx="256" cy="256" r="111" fill="#338af3" />
                                                                            <path fill="#338af3" d="M76 76h360L256 256z" />
                                                                            <circle cx="256" cy="256" r="89" fill="#eee" />
                                                                            <circle cx="256" cy="256" r="69" fill="#338af3" />
                                                                            <path fill="#eee" d="M246 178h20v156h-20z" />
                                                                            <path fill="#eee" d="M334 246v20H178v-20z" />
                                                                            <path fill="#eee" d="m304 193.7l14.2 14.2l-110.3 110.3l-14.2-14.1z" />
                                                                            <path fill="#eee" d="m318.2 304l-14.1 14.2l-110.4-110.3l14.2-14.2z" />
                                                                            <circle cx="256" cy="256" r="44" fill="#eee" />
                                                                            <circle cx="256" cy="256" r="22" fill="#338af3" />
                                                                            <ellipse cx="256" cy="412" fill="#eee" rx="44" ry="40" />
                                                                            <path fill="#338af3" d="m256 407l-78 63h156z" />
                                                                        </g>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            <div class="exper-item-list">
                                                                <h5 class="fs-16 text-dark">Ascham School</h5>
                                                                <p class="mb-2 fw-semibold text-dark">School Regular</p>
                                                                <div class="list-inline list-inline-dots mb-2 fs-14">
                                                                    <div class="list-inline-item">February 2015</div>
                                                                    <div class="list-inline-item list-inline-item-second">Istanbul, Turkey</div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ol>
                                                </div>
                                            </div>
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
                                                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                                                            @csrf
                                                        </form>

                                                        <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-4">
                                                            @csrf
                                                            @method('patch')

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="name" :value="__('Name')" class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <x-text-input id="name" name="name" type="text" class="form-control" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                                                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="email" :value="__('Email')" class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                                                                        <x-text-input id="email" name="email" type="email" class="form-control" :value="old('email', $user->email)" required autocomplete="username" />
                                                                    </div>
                                                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                                                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                                                        <div>
                                                                            <p class="text-muted fs-14 mt-2">
                                                                                {{ __('Your email address is unverified.') }}

                                                                                <button form="send-verification" class="text-primary fs-14 text-decoration-underline bg-transparent border-0 p-0">
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
                                                                    <x-primary-button class="btn btn-primary">{{ __('Save') }}</x-primary-button>
                                                                    <button type="button" class="btn btn-danger" onclick="this.closest('form').reset()">Cancel</button>

                                                                    @if (session('status') === 'profile-updated')
                                                                        <p
                                                                            x-data="{ show: true }"
                                                                            x-show="show"
                                                                            x-transition
                                                                            x-init="setTimeout(() => show = false, 2000)"
                                                                            class="text-muted fs-14"
                                                                        >{{ __('Saved.') }}</p>
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
                                                        <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-4">
                                                            @csrf
                                                            @method('put')

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="update_password_current_password" :value="__('Current Password')" class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
                                                                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="update_password_password" :value="__('New Password')" class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <x-text-input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
                                                                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group mb-3 row">
                                                                <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="form-label" />
                                                                <div class="col-lg-12 col-xl-12">
                                                                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
                                                                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-lg-12 col-xl-12 d-flex gap-2">
                                                                    <x-primary-button class="btn btn-primary">{{ __('Save') }}</x-primary-button>
                                                                    <button type="button" class="btn btn-danger" onclick="this.closest('form').reset()">Cancel</button>

                                                                    @if (session('status') === 'password-updated')
                                                                        <p
                                                                            x-data="{ show: true }"
                                                                            x-show="show"
                                                                            x-transition
                                                                            x-init="setTimeout(() => show = false, 2000)"
                                                                            class="text-muted fs-14"
                                                                        >{{ __('Saved.') }}</p>
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
@endsection