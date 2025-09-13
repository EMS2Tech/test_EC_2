@extends('frontend.layouts.master')

@section('title', 'Complaint List')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3">
                    <h4 class="fs-18 fw-semibold m-0">Complaint List</h4>
                </div>

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

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        <th>NIC/Passport</th>
                                        <th>Type</th>
                                        <th>Message</th>
                                        <th>Reported By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($complaints as $complaint)
                                        <tr>
                                            <td>{{ $complaint->application->full_name ?? 'N/A' }}</td>
                                            <td>{{ $complaint->nic_number }}</td>
                                            <td>{{ $complaint->complaint_type }}</td>
                                            <td>{{ $complaint->message }}</td>
                                            <td>{{ $complaint->reportedBy->name ?? 'Unknown' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No complaints found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection