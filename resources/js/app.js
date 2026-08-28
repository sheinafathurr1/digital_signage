import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

const confirmColors = {
    danger: '#E74C3C',
    warning: '#E67E22',
    primary: '#E8844A',
};

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) {
        return;
    }

    if (form.dataset.confirmed === 'true') {
        return;
    }

    event.preventDefault();

    Swal.fire({
        title: form.dataset.confirmTitle || 'Apakah Anda yakin?',
        text: form.dataset.confirm,
        icon: form.dataset.confirmIcon || 'warning',
        showCancelButton: true,
        confirmButtonText: form.dataset.confirmButton || 'Ya, lanjutkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: confirmColors[form.dataset.confirmColor] || confirmColors.danger,
        cancelButtonColor: confirmColors.primary,
        reverseButtons: true,
        buttonsStyling: true,
        focusCancel: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.dataset.confirmed = 'true';
            form.submit();
        }
    });
});

Alpine.start();
