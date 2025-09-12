@extends('frontend.layouts.master')

@section('title', 'Add Manager')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Add New Manager</h4>
                    </div>
                    <div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Add Manager Details</h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.manager.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="type" class="form-label">User Type</label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select User Type</option>
                                        <option value="finance_manager" {{ old('type') === 'finance_manager' ? 'selected' : '' }}>Finance Manager</option>
                                        <option value="course_manager" {{ old('type') === 'course_manager' ? 'selected' : '' }}>Course Manager</option>
                                        <option value="front_manager" {{ old('type') === 'front_manager' ? 'selected' : '' }}>Front Manager</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Add Manager</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Manager List Table -->
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Manager List</h5>
                    </div>
                    <div class="card-body">
                        @if ($managers->isEmpty())
                            <div class="alert alert-info text-center">
                                No managers found.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($managers as $manager)
                                            <tr>
                                                <td>{{ $manager->name }}</td>
                                                <td>{{ $manager->email }}</td>
                                                <td>{{ ucfirst($manager->type) }}</td>
                                                <td>
                                                    <form action="{{ route('admin.manager.delete', $manager->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete {{ $manager->name }}?');">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection