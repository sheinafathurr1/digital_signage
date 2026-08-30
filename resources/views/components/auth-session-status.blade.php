@props(['status'])

@if ($status)
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: @js($status),
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                iconColor: '#27AE60',
            });
        });
    </script>
@endif
