@extends('frontend.layouts.auth')

@section('title', 'Login')

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
                                                <h3 class="text-dark fw-semibold mb-3">Welcome back! Please Sign in to continue.</h3>
                                                <p class="text-muted fs-14 mb-0">Sign in to access your personalized dashboard, stay updated, and make the most of your campus experience.</p>
                                            </div>

                                            <div class="pt-0">
                                                <form method="POST" action="{{ route('login') }}" class="my-4">
                                                    @csrf

                                                    <div class="form-group mb-3">
                                                        <x-input-label for="emailaddress" :value="__('Email address')" />
                                                        <x-text-input id="emailaddress" class="form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
                                                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <x-input-label for="password" :value="__('Password')" />
                                                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                                    </div>

                                                    <div class="form-group d-flex mb-3">
                                                        <div class="col-sm-6">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" id="checkbox-signin" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 text-end">
                                                            @if (Route::has('password.request'))
                                                                <a class="text-muted fs-14" href="{{ route('password.request') }}">Forgot password?</a>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="form-group mb-0 row">
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <x-primary-button class="btn btn-primary fw-semibold">
                                                                    {{ __('Log In') }}
                                                                </x-primary-button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="text-center text-muted">
                                                    <p class="mb-0">Don't have an account? <a class="text-primary ms-2 fw-medium" href="{{ route('register') }}">Sign up</a></p>
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

    @include('sweetalert')
@endsection