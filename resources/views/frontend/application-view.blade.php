@extends('frontend.layouts.master')

@section('title', 'Application Details')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Application Details</h4>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Applicant Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Personal Details -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Personal Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p><strong>Title:</strong> {{ $application->title }}</p>
                                                <p><strong>Full Name:</strong> {{ $application->full_name }}</p>
                                                <p><strong>Name with Initials:</strong> {{ $application->name_with_initials }}</p>
                                                <p><strong>Birthday:</strong> {{ $application->birthday->format('F j, Y') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>Contact Number:</strong> {{ $application->contact_number }}</p>
                                                <p><strong>WhatsApp Number:</strong> {{ $application->whatsapp_number ?? 'N/A' }}</p>
                                                <p><strong>Email Address:</strong> {{ $application->email_address }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Nationality Details -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Nationality Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <p><strong>Nationality:</strong> {{ $application->nationality }}</p>
                                                @if ($application->nationality === 'Sri Lanka')
                                                    <p><strong>NIC Number:</strong> {{ $application->nic_number }}</p>
                                                    @if ($application->nic_photo)
                                                        <p><strong>NIC Photo:</strong> 
                                                            <a href="#" onclick="loadFile('{{ asset('storage/' . $application->nic_photo) }}', 'nicPhotoContainer')">View</a>
                                                        </p>
                                                    @endif
                                                @else
                                                    <p><strong>Other Nationality:</strong> {{ $application->other_nationality ?? 'N/A' }}</p>
                                                    <p><strong>Passport Number:</strong> {{ $application->passport_number ?? 'N/A' }}</p>
                                                    @if ($application->passport_photo)
                                                        <p><strong>Passport Photo:</strong> 
                                                            <a href="#" onclick="loadFile('{{ asset('storage/' . $application->passport_photo) }}', 'passportPhotoContainer')">View</a>
                                                        </p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Address</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted">{{ $application->address }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Photograph -->
                            @if ($application->photograph)
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light">
                                            <h6 class="card-title mb-0">Photograph</h6>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Photograph:</strong> 
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#photographModal" onclick="loadImage('{{ asset('storage/' . $application->photograph) }}', 'photographImage')">View</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Status -->
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h6 class="card-title mb-0">Application Status</h6>
                                    </div>
                                    <div class="card-body">
                                        <p><strong>Status:</strong> 
                                            <span class="badge bg-{{ $application->status == 'Approved' ? 'success' : ($application->status == 'Pending' ? 'warning' : 'danger') }}">
                                                {{ $application->status ?? 'Not Complete' }}
                                            </span>
                                        </p>
                                        @if ($application->rejection_reason)
                                            <p><strong>Rejection Reason:</strong> {{ $application->rejection_reason }}</p>
                                        @endif
                                        @if ($application->updated_by && in_array($application->status, ['Approved', 'Rejected']))
                                            <p><strong>Updated By:</strong> <span class="text-primary">{{ $application->updatedBy->name ?? 'N/A' }}</span></p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <form method="POST" action="{{ route('admin.application.update-status', $application->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <select name="status" class="form-select" required id="statusSelect">
                                        <option value="" disabled {{ !$application->status ? 'selected' : '' }}>Select Status</option>
                                        <option value="Approved" {{ $application->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Rejected" {{ $application->status === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <textarea name="reason" class="form-control" placeholder="Reason for rejection (required if Rejected)" id="reasonTextarea">{{ $application->rejection_reason }}</textarea>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                                </div>
                            </div>
                        </form>
                        <script>
                            document.getElementById('statusSelect').addEventListener('change', function() {
                                var reasonTextarea = document.getElementById('reasonTextarea');
                                if (this.value === 'Approved') {
                                    reasonTextarea.disabled = true;
                                    reasonTextarea.value = '';
                                } else if (this.value === 'Rejected') {
                                    reasonTextarea.disabled = false;
                                    if (!reasonTextarea.value) {
                                        reasonTextarea.value = '';
                                    }
                                } else {
                                    reasonTextarea.disabled = true;
                                    reasonTextarea.value = '';
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for NIC Document -->
<div class="modal fade" id="nicPhotoModal" tabindex="-1" aria-labelledby="nicPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nicPhotoModalLabel">NIC Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="nicPhotoContainer">
                {{-- Content (image or PDF) will be injected via JS --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Passport Document -->
<div class="modal fade" id="passportPhotoModal" tabindex="-1" aria-labelledby="passportPhotoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passportPhotoModalLabel">Passport Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" id="passportPhotoContainer">
                {{-- Content (image or PDF) will be injected via JS --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <!-- Modal for Photograph -->
    <div class="modal fade" id="photographModal" tabindex="-1" aria-labelledby="photographModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="photographModalLabel">Photograph</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="photographImage" src="" alt="Photograph" class="img-fluid" style="max-height: 80vh;">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    function loadFile(src, containerId) {
        const container = document.getElementById(containerId);
        if (!container) {
            console.error('Container element not found:', containerId);
            return;
        }

        // Clear previous content
        container.innerHTML = '';

        // Detect file type
        const fileExtension = src.split('.').pop().toLowerCase();

        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
            // Render image
            const img = document.createElement('img');
            img.src = src;
            img.alt = 'Preview';
            img.className = 'img-fluid';
            img.style.maxHeight = '80vh';
            container.appendChild(img);
        } else if (fileExtension === 'pdf') {
            // Render PDF
            const iframe = document.createElement('iframe');
            iframe.src = src;
            iframe.width = '100%';
            iframe.height = '600px';
            iframe.style.border = 'none';
            container.appendChild(iframe);
        } else {
            // Fallback
            container.innerHTML = `<p class="text-danger">Unsupported file type: ${fileExtension}</p>`;
        }

        // Show modal
        const modalId =
            containerId === 'nicPhotoContainer' ? '#nicPhotoModal' :
            containerId === 'passportPhotoContainer' ? '#passportPhotoModal' :
            '#photographModal';

        $(modalId).modal('show');
    }

    // Keep Photograph modal working with <img>
    function loadImage(src, imageId) {
        const imgElement = document.getElementById(imageId);
        if (imgElement) {
            imgElement.src = src;
            $(imageId === 'photographImage' ? '#photographModal' : '').modal('show');
        }
    }
</script>

@endsection