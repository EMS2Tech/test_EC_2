@extends('frontend.layouts.master')

@section('title', 'Add Complaint')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3">
                    <h4 class="fs-18 fw-semibold m-0">Add New Complaint</h4>
                </div>

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

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('admin.complaints.store') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="nic_number" class="form-label">NIC/Passport Number</label>
                                <input type="text" class="form-control" id="nic_number" name="nic_number" value="{{ old('nic_number') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="complaint_type" class="form-label">Complaint Type</label>
                                <select class="form-select" id="complaint_type" name="complaint_type" required>
                                    <option value="">Select Complaint Type</option>
                                    <option value="Financial Problem" {{ old('complaint_type') == 'Financial Problem' ? 'selected' : '' }}>Financial Problem</option>
                                    <option value="Exam Problem" {{ old('complaint_type') == 'Exam Problem' ? 'selected' : '' }}>Exam Problem</option>
                                    <option value="Disciplinary Problem" {{ old('complaint_type') == 'Disciplinary Problem' ? 'selected' : '' }}>Disciplinary Problem</option>
                                    <option value="Result Problem" {{ old('complaint_type') == 'Result Problem' ? 'selected' : '' }}>Result Problem</option>
                                    <option value="Other" {{ old('complaint_type') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Complaint</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection