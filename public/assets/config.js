function notif_success(message) {
    Swal.fire({
        title: "Sukses!",
        html: message,
        icon: "success",
        showConfirmButton: false,
        timer: 1500,
    });
}

function notif_error(message) {
    Swal.fire({
        icon: "error",
        title: "Kesalahan",
        html: message,
        showConfirmButton: false,
        timer: 1500,
    });
}

$(".select2").select2({
    placeholder: "Pilih...",
    allowClear: true,
});
