@extends('frontend.layouts.auth')

@section('title', 'Reset Password')

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
                                                <h3 class="text-dark fw-semibold mb-3">Reset Your Password!</h3>
                                                <p class="text-muted fs-14 mb-0">Enter your email address and new password to reset your account access.</p>
                                            </div>

                                            <div class="pt-0">
                                                <!-- Session Status -->
                                                <x-auth-session-status class="mb-4" :status="session('status')" />

                                                <form method="POST" action="{{ route('password.store') }}" class="my-4">
                                                    @csrf

                                                    <!-- Password Reset Token -->
                                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                                    <!-- Email Address -->
                                                    <div class="form-group mb-3">
                                                        <x-input-label for="email" :value="__('Email Address')" />
                                                        <x-text-input id="email" class="form-control" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="Enter your email" />
                                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                    </div>

                                                    <!-- Password -->
                                                    <div class="form-group mb-3">
                                                        <x-input-label for="password" :value="__('Password')" />
                                                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Enter your new password" />
                                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                    </div>

                                                    <!-- Confirm Password -->
                                                    <div class="form-group mb-3">
                                                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                                        <x-text-input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your new password" />
                                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                                    </div>

                                                    <!-- Submit Button -->
                                                    <div class="form-group mb-0 row">
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <x-primary-button class="btn btn-primary">
                                                                    {{ __('Reset Password') }}
                                                                </x-primary-button>
                                                            </div>
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