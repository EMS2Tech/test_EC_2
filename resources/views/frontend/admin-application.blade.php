@extends('frontend.layouts.master')

@section('title', 'Application Details')

@section('content')
<div class="content-page">
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-18 fw-semibold m-0">Application Details</h4>
                </div>
            </div>

            <!-- Start Search and Filter -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">All Applicants</h5>
                                <div class="d-flex align-items-center">
                                    <form class="d-flex align-items-center me-3" method="GET" action="{{ route('admin.applications') }}">
                                        <input type="text" class="form-control me-2" name="search" placeholder="Search by NIC, Passport, Application No, or Email" value="{{ request('search') }}" style="width: 250px;">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="mdi mdi-magnify me-1"></i> Search
                                        </button>
                                    </form>
                                    <form class="d-flex align-items-center" method="GET" action="{{ route('admin.applications') }}">
                                        <select name="status" class="form-select me-2" style="width: 150px;" onchange="this.form.submit()">
                                            <option value="" {{ !request('status') ? 'selected' : '' }}>All Statuses</option>
                                            <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Search and Filter -->

            <!-- Start Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-traffic mb-0">
                                    <thead>
                                        <tr>
                                            <th>Number</th>
                                            <th>Full Name</th>
                                            <th>Contact Number</th>
                                            <th>Email</th>
                                            <th>NIC/Passport</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($applications as $application)
                                            <tr>
                                                <td>
                                                    <a href="javascript:void(0);" class="text-dark">{{ sprintf('%.5d', $application->id ?? 0) }}</a>
                                                </td>
                                                <td>{{ $application->full_name ?? 'N/A' }}</td>
                                                <td>{{ $application->contact_number ?? 'N/A' }}</td>
                                                <td>{{ $application->email_address ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($application->nationality === 'Sri Lanka')
                                                        {{ $application->nic_number ?? 'N/A' }}
                                                    @else
                                                        {{ $application->passport_number ?? 'N/A' }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $application->status == 'Approved' ? 'bg-success-subtle text-success' : ($application->status == 'Pending' ? 'bg-warning-subtle text-warning' : ($application->status == 'Rejected' ? 'bg-danger-subtle text-danger' : 'bg-secondary-subtle text-secondary')) }} fw-semibold">
                                                        {{ $application->status ?? 'Not Complete' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.application.details', $application->id ?? 0) }}" aria-label="anchor" class="btn btn-icon btn-sm bg-info-subtle me-1" data-bs-toggle="tooltip" data-bs-original-title="View" {{ !$application->id ? 'disabled' : '' }}>
                                                        <i class="mdi mdi-eye-outline fs-12 text-info"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card-footer py-0 border-top">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <div class="text-block text-muted">
                                            <span class="fw-medium">{{ $applications->firstItem() }} - {{ $applications->lastItem() }} of {{ $applications->total() }}</span>
                                        </div>
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $applications->url($currentPage - 1) }}" tabindex="-1" aria-disabled="{{ $currentPage == 1 ? 'true' : 'false' }}">Previous</a>
                                                </li>
                                                @for ($i = max(1, $currentPage - 1); $i <= min($lastPage, $currentPage + 1); $i++)
                                                    <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                                        <a class="page-link" href="{{ $applications->url($i) }}">{{ $i }}</a>
                                                    </li>
                                                @endfor
                                                <li class="page-item {{ $currentPage == $lastPage ? 'disabled' : '' }}">
                                                    <a class="page-link" href="{{ $applications->url($currentPage + 1) }}" aria-disabled="{{ $currentPage == $lastPage ? 'true' : 'false' }}">Next</a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Table -->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- content -->
</div>
@endsection