function errDialog(title, subtitle, msg = '') {
    Swal.fire({
        icon: "error",
        title: title,
        text: subtitle,
        footer: msg
    });
}

function success(title, reload = true) {
    Swal.fire({
        position: "top",
        icon: "success",
        title: title,
        showConfirmButton: false,
        timer: 1500
    });
    if (reload) {
        setInterval(() => {
            location.reload()
        }, 1500)
    }
}
function confirmDialog(title, text,footer='') {
    return Swal.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "ตกลง",
        cancelButtonText: "ยกเลิก",
        footer:footer
    })
    // .then((result) => {
    //     if (result.isConfirmed) {
    //         Swal.fire({
    //             title: "Deleted!",
    //             text: "Your file has been deleted.",
    //             icon: "success"
    //         });
    //     }
    // });
}