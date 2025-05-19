@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-fluid">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        @if (Auth::user()->isAdmin())
            <p>This is the Admin Dashboard.</p>
        @else
            <p>Unauthorized access. Redirecting...</p>
            <script>
                window.location.href = "{{ route('frontend.application') }}";
            </script>
        @endif
    </div>
@endsection