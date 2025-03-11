function copyText(text, tooltipAlert) {

    const textCopy = $(text).val()
    const tooltip = $(tooltipAlert)
    navigator.clipboard.writeText(textCopy);
    tooltip.text("คัดลอกสำเร็จ")
    tooltip.css('display', 'block')
    outCopyText(tooltip)
}

function outCopyText(tooltip) {
    setInterval(() => {
        $(tooltip).css('display', 'none')
    }, 1500)
}

function pasteData(input, callback = null) {
    navigator.clipboard
        .readText()
        .then((clipText) => {
            input.val(clipText)
            if (callback) callback()
        })
        ;
}
