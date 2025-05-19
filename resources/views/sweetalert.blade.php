<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Success message
        @if (session('status'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('status') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Error message
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonText: 'OK'
            });
        @endif

        // Validation errors
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>