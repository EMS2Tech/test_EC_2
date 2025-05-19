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
                                                    <a class='logo logo-light' href='index.html'>
                                                        <span class="logo-lg">
                                                            <img src="{{ asset('frontend/assets/images/logo-light-3.png') }}"
                                                                alt="" height="24">
                                                        </span>
                                                    </a>
                                                    <a class='logo logo-dark' href='index.html'>
                                                        <span class="logo-lg">
                                                            <img src="{{ asset('frontend/assets/images/logo-dark-3.png') }}"
                                                                alt="" height="24">
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

                                    <div class="carousel-item active">
                                        <p class="prelead mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                viewBox="0 0 24 24">
                                                <path fill="#ffffff"
                                                    d="M4.583 17.321C3.553 16.227 3 15 3 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621c.537-.278 1.24-.375 1.929-.311c1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5a3.87 3.87 0 0 1-2.748-1.179m10 0C13.553 16.227 13 15 13 13.011c0-3.5 2.457-6.637 6.03-8.188l.893 1.378c-3.335 1.804-3.987 4.145-4.247 5.621c.537-.278 1.24-.375 1.929-.311c1.804.167 3.226 1.648 3.226 3.489a3.5 3.5 0 0 1-3.5 3.5a3.87 3.87 0 0 1-2.748-1.179" />
                                            </svg>
                                            With Untitled, your support process can be as enjoyable as your product. With
                                            it's this easy, customers keep coming back.
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                viewBox="0 0 24 24">
                                                <path fill="#ffffff"
                                                    d="M19.417 6.679C20.447 7.773 21 9 21 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.248-5.621c-.537.278-1.24.375-1.93.311c-1.804-.167-3.226-1.648-3.226-3quest: true
                                                    d="M19.417 6.679C20.447 7.773 21 9 21 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.248-5.621c-.537.278-1.24.375-1.93.311c-1.804-.167-3.226-1.648-3.226-3.489a3.5 3.5 0 0 1 3.5-3.5c1.073 0 2.1.49 2.748 1.179m-10 0C10.447 7.773 11 9 11 10.989c0 3.5-2.456 6.637-6.03 8.188l-.893-1.378c3.335-1.804 3.987-4.145 4.247-5.621c-.537.278-1.24.375-1.929.311C4.591 12.323 3.17 10.842 3.17 9a3.5 3.5 0 0 1 3.5-3.5c1.073 0 2.1.49 2.748 1.179" />
                                            </svg>
                                        </p>
                                        <h4 class="mb-1">Camilla Johnson</h4>
                                        <p class="mb-0">Software Developer</p>
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