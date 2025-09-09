@extends('frontend.layouts.master')

@section('title', 'Initiate Payment Request')

@section('content')
    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
                    <div class="flex-grow-1">
                        <h4 class="fs-18 fw-semibold m-0">Initiate Payment Request</h4>
                    </div>
                    <div>
                        
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Create Payment Request</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.payment.request.send') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="course_id" class="form-label">Select Course</label>
                                    <select name="course_id" id="course_id" class="form-select" required>
                                        <option value="">Select Course</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="batch_id" class="form-label">Select Batch</label>
                                    <select name="batch_id" id="batch_id" class="form-select" required>
                                        <option value="">Select Batch</option>
                                        <!-- Populated via AJAX based on course selection -->
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea name="message" id="message" class="form-control" rows="3" required placeholder="Enter payment request message"></textarea>
                                </div>
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">Send Payment Request</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#course_id').change(function() {
                    const courseId = $(this).val();
                    if (courseId) {
                        $.ajax({
                            url: '{{ route('admin.get.batches', ':course_id') }}'.replace(':course_id', courseId),
                            method: 'GET',
                            success: function(response) {
                                const $batchSelect = $('#batch_id');
                                $batchSelect.empty();
                                $batchSelect.append('<option value="">Select Batch</option>');
                                $.each(response, function(index, batch) {
                                    $batchSelect.append('<option value="' + batch.id + '">' + batch.batch_no + '</option>');
                                });
                            },
                            error: function() {
                                alert('Failed to load batches.');
                            }
                        });
                    } else {
                        $('#batch_id').empty().append('<option value="">Select Batch</option>');
                    }
                });
            });
        </script>
    @endsection
@endsection