@extends('layouts.app')

@section('title', 'Manager Dashboard')

@section('content')
    <div class="container-fluid">
        <h1>Welcome, {{ Auth::user()->name }}!</h1>
        @if (Auth::user()->isManager())
            <p>This is the Manager Dashboard.</p>
        @else
            <p>Unauthorized access. Redirecting...</p>
            <script>
                window.location.href = "{{ route('user.dashboard') }}";
            </script>
        @endif
        <div class="debug-info" style="display: none;">
            User Type: {{ Auth::user()->type ?? 'null' }}
        </div>
    </div>
@endsection