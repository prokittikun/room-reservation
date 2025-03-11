
function getStartDate() {
    return $('#startDate')
}
function getEndDate() {
    return $('#endDate')
}

function getStatus() {
    return $('#status')
}

function getSortBy() {
    return $('#sortby')
}

function getTimeStampNumber(dateString) {
    if (!dateString) return NaN;
    const date = new Date(dateString);
    return date.getTime();
}

function findDataByDate() {
    let r = ``
    const start_dt = getStartDate().val()
    const end_dt = getEndDate().val()
    const is_startdate = start_dt != ''
    const is_enddate = end_dt != ''
    let is_date = is_startdate || is_enddate

    if (is_date) {
        errValidate(!is_startdate, $('#validateStartDate'), 'กรุณาป้อนวันเริ่มต้น')
        errValidate(!is_enddate, $('#validateEndDate'), 'กรุณาป้อนวันสิ้นสุด')
        const start_stamp = getTimeStampNumber(start_dt)
        const end_stamp = getTimeStampNumber(end_dt)
        if (!isNaN(end_stamp) && !isNaN(start_stamp)) {
            if (end_stamp < start_stamp) {
                errDialog('แจ้งเตือน', '', 'กรุณาป้อนวันที่ให้ถูกต้อง')
            } else {
                r += `&start_dt=${start_dt}&end_dt=${end_dt}`
            }
        }
    }

    return { is_enddate, is_startdate, is_date, r }
}

