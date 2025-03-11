$('#slipPayment').change(function () {
    const file = $(this)[0].files
    console.log('ddd')
    let slipPaymentEl = ``
    for (let i = 0; i < file.length; i++) {
        const src = URL.createObjectURL(file[i])
        slipPaymentEl += `<div class="col-md-4" style="height: 18rem;overflow-y:scroll;">`
        slipPaymentEl += `<img src="${src}" style="width: 100%;object-fit:contain;"></img>`
        slipPaymentEl += `</div>`
    }

    $('#slipPaymentPreview').html(slipPaymentEl)
})