@extends('frontend.layouts.auth')

@section('title', 'Register')

@section('content')

    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row align-items-center g-0 px-3 py-3 vh-100">

                <div class="col-xl-5">
                    <div class="row">
                        <div class="col-md-8 mx-auto">

                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-0 p-0 p-lg-3">

                                        <div class="mb-0 border-0 p-md-4 p-lg-0">
                                            <div class="mb-4 p-0">
                                                <div class="auth-brand">
                                                    <a class='logo logo-dark'>
                                                        <span class="logo-lg">
                                                            <img src="{{ asset('frontend/assets/images/ec_logo.webp') }}"
                                                                alt="" height="50">
                                                        </span>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="auth-title-section mb-4 text-lg-start text-center">
                                                <h3 class="text-dark fw-semibold mb-3">Sign Up Now!</h3>
                                                <p class="text-muted fs-14 mb-0">Unlock exclusive student perks, special offers, and be the first to know about exciting updates!</p>
                                            </div>

                                            <div class="pt-0">
                                                <form method="POST" action="{{ route('register') }}">
                                                    @csrf

                                                    <div class="form-group mb-3">
                                                        <x-input-label for="username" :value="__('Username')" />
                                                        <x-text-input id="username" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your Username" />
                                                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <x-input-label for="emailaddress" :value="__('Email address')" />
                                                        <x-text-input id="emailaddress" class="form-control" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email" />
                                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <x-input-label for="password" :value="__('Password')" />
                                                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password" />
                                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                                        <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Enter your password" />
                                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                                    </div>

                                                    <div class="form-group d-flex mb-3">
                                                        <div class="col-12">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="checkbox-signin" name="terms" {{ old('terms') ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="checkbox-signin">I agree to the <a href="#" class="text-primary fw-medium">Terms and Conditions</a></label>
                                                                <x-input-error :messages="$errors->get('terms')" class="mt-2" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-0 row">
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <x-primary-button class="btn btn-primary">
                                                                    {{ __('Register') }}
                                                                </x-primary-button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form><br>

                                                <div class="text-center text-muted">
                                                    <p class="mb-0">Already have an account? <a class="text-primary ms-2 fw-medium" href="{{ route('login') }}">Login here</a></p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-xl-7 d-none d-xl-inline-block">
                    <div class="account-page-bg rounded-4">
                        <div class="auth-user-review text-center">
                            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection