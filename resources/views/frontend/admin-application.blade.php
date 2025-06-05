@extends('frontend.layouts.master')

@section('title', 'Admin - Application Details')

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

            <!-- Start Search and Table -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card overflow-hidden">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">All Applicants</h5>
                                <form class="d-flex align-items-center" method="GET" action="{{ route('admin.applications') }}">
                                    <input type="text" class="form-control me-2" name="search" placeholder="Search by NIC, User ID, or Email" value="{{ request('search') }}" style="width: 250px;">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="mdi mdi-magnify me-1"></i> Search
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-traffic mb-0">
                                    <thead>
                                        <tr>
                                            <th>User ID</th>
                                            <th>Full Name</th>
                                            <th>Nationality</th>
                                            <th>Other Nationality</th>
                                            <th>NIC Number</th>
                                            <th>Contact Number</th>
                                            <th>Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($applications as $application)
                                            <tr>
                                                <td>{{ $application->user_id }}</td>
                                                <td>{{ $application->full_name ?? 'N/A' }}</td>
                                                <td>{{ $application->nationality ?? 'N/A' }}</td>
                                                <td>{{ $application->other_nationality ?? 'N/A' }}</td>
                                                <td>{{ $application->nic_number ?? 'N/A' }}</td>
                                                <td>{{ $application->contact_number ?? 'N/A' }}</td>
                                                <td>{{ $application->email_address ?? 'N/A' }}</td>
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
            <!-- End Search and Table -->
        </div>
        <!-- container-fluid -->
    </div>
    <!-- content -->
</div>
@endsection