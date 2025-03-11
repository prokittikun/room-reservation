function getNumberFormat(number) {
    const n = new Intl.NumberFormat('th').format(
        number,
    )
    return n.includes('.') ? n : n + '.00'
}


function retainRadio(value, optionList) {
    $.each(optionList, (i, opt) => {
        if ($(opt).val() == value || $(opt).attr('id') == value) {
            $(opt).prop('checked', true)
        }
    })
}




function getCountDate(date) {
    return date.toString().length == 2 ? date : `0${date}`
}



function validatePassword(pass) {
    let upper = 0
    let lower = 0
    let num = 0
    let thaiLang = 0
    let alert = ''
    let validate = true

    if (pass.length < 8) {
        validate = false
        alert = 'รหัสผ่านต้องมีอักขระอย่างน้อย 8 ตัว'
    } else {
        for (let i = 0; i < pass.length; i++) {
            const text = pass[i]
            const char = /[a-zA-Z]/.test(text)
            const n = /\d/.test(text)
            const thai_letter = /[ก-ฮะ-์]/.test(text)
            // if (char) {
            //     if (text.toUpperCase() == text) {
            //         console.log('A')
            //         upper++
            //     }
            //     if (text.toUpperCase() != text) {
            //         console.log('a')
            //         lower++
            //     }
            // }
            // if (n) {
            //     num++
            // }
            if (thai_letter) {
                thaiLang++
            }
        }
        if (thaiLang > 0) {
            validate = false
            alert = 'รหัสผ่านต้องใช้เป็นภาษาอังกฤษเท่านั้น'
        }
    }
    return { validate, alert }
}

function obscureText(input) {
    const element = $(input)
    const isType = element.attr('type') == 'text' ? 'password' : 'text'
    element.attr('type', isType)
}
function hideErrValidate() {
    $('.err-validate').css('display', 'none')
}

function retainOption(value, optionList) {
    $.each(optionList.children(), (i, opt) => {
        if ($(opt).val() == value.trim()) {
            $(opt).prop('selected', true)
        }
    })
}





function isUsernameThaiLetter(username) {
    let thaiLang = 0
    for (let i = 0; i < username.length; i++) {
        const thai_letter = /[ก-ฮะ-์]/.test(username[i])

        if (thai_letter) {
            thaiLang++
        }
    }
    return thaiLang > 0 ? false : true
}



function getFullMonthThai(m) {
    const month = [
        'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน',
        'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม',
        'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
    ]
    return month[parseInt(m) - 1]
}

function getFullYear(y) {
    return parseInt(y) + 543
}



function getFullDateThai(date) {
    const [dt, time] = date.split(' ')
    const [y, m, d] = dt.split('-')
    const [h, minute] = time.split(':')
    return `${parseInt(d)} ${getFullMonthThai(m)} ${getFullYear(y)} เวลา ${h}:${minute}`
}

function numberFormatThai(number) {
    return new Intl.NumberFormat("TH", { style: "currency", currency: "THB" }).format(number)
}

function getFullDateFormat(data) {
    return data.replace('T', ' ').replace('Z', '')
}

function getParam(search) {
    return new URLSearchParams(search)
}

function getCountDayOfMonth(y, m) {
    return new Date(y, m, 0).getDate()
}