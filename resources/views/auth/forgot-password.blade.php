@extends('frontend.layouts.auth')

@section('title', 'Recover Password')

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
                                                <h3 class="text-dark fw-semibold mb-3">Reset your password!</h3>
                                            </div>

                                            <div class="pt-0">
                                                <div class="mb-4 text-sm text-gray-600">
                                                    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                                                </div>

                                                <!-- Session Status -->
                                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                                <form method="POST" action="{{ route('password.email') }}" class="my-4">
                                                    @csrf

                                                    <div class="form-group mb-3">
                                                        <x-input-label for="emailaddress" :value="__('Email address')" />
                                                        <x-text-input id="emailaddress" class="form-control" type="email" name="email" :value="old('email')" required autofocus placeholder="Enter your email" />
                                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                    </div>

                                                    <div class="form-group mb-0 row">
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <x-primary-button class="btn btn-primary">
                                                                    {{ __('Recover Password') }}
                                                                </x-primary-button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="text-center text-muted">
                                                    <p class="mb-0">Change your mind? <a class='text-primary ms-2 fw-medium' href="{{ route('login') }}">Back to Login</a></p>
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