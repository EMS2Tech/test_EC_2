@extends('frontend.layouts.auth')

@section('title', 'Lock Screen')

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
                                            <div class="auth-title-section mb-4 text-lg-start text-center">
                                                <h3 class="text-dark fw-semibold mb-3">Lock Screen</h3>
                                                <p class="text-muted fs-14 mb-0">Hello, {{ Auth::user()->name }}!</p>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center mb-3">
                                                <div class="lh-1">
                                                    <span class="">
                                                        <img src="{{ Auth::user()->profile_photo_path ? asset('storage/' . Auth::user()->profile_photo_path) : asset('frontend/assets/images/users/default.jpg') }}"
                                                            alt="{{ Auth::user()->name }}" class="avatar avatar-sm rounded-circle me-1">
                                                    </span>
                                                </div>
                                                <div>
                                                    <p class="mb-0 text-dark fw-medium">{{ Auth::user()->email }}</p>
                                                </div>
                                            </div>

                                            <div class="pt-0">
                                                <form method="POST" action="{{ route('password.confirm') }}" class="mt-4 mb-3">
                                                    @csrf

                                                    <div class="form-group mb-3">
                                                        <x-input-label for="password" :value="__('Password')" />
                                                        <x-text-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
                                                    </div>

                                                    <div class="form-check mb-3">
                                                        <input type="checkbox" class="form-check-input" id="checkbox-signin" checked>
                                                        <label class="form-check-label" for="checkbox-signin">Remember me</label>
                                                    </div>

                                                    <div class="form-group mb-0 row">
                                                        <div class="col-12">
                                                            <div class="d-grid">
                                                                <x-primary-button class="btn btn-primary">
                                                                    {{ __('Unlock') }}
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