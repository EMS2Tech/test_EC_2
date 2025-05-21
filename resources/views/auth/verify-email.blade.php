@extends('frontend.layouts.auth')

@section('title', 'Confirm Mail')

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
                                            

                                            <div class="auth-title-section mb-4 text-center">
                                                <h3 class="text-dark fw-semibold mb-3">Email Confirmation</h3>
                                                <p class="text-muted fs-14 mb-0">
                                                    {{ __('Please check your email for confirmation
                                                    mail. <br>Click link in email to verification your account') }}
                                                </p>
                                            </div>

                                            @if (session('status') == 'verification-link-sent')
                                                <div class="mb-4 font-medium text-sm text-green-600 text-center">
                                                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                                </div>
                                            @endif

                                            <div class="text-center">
                                                <form method="POST" action="{{ route('verification.send') }}">
                                                    @csrf
                                                    <x-primary-button class="btn btn-primary mt-3 me-1">
                                                        {{ __('Resend Confirmation') }}
                                                    </x-primary-button>
                                                </form>
                                            </div>

                                            <div class="text-center pt-4">
                                                <div class="maintenance-img">
                                                    <img src="{{ asset('frontend/assets/images/svg/confirmation-email.svg') }}"
                                                        height="200" alt="svg-logo">
                                                </div>
                                            </div>

                                            <div class="text-center text-muted mt-4">
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf
                                                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                        {{ __('Log Out') }}
                                                    </button>
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