

retainOption($('#status').attr('data-status'), $('#status'))




$('#findReportBtn').click(function () {
    const params = getParam(location.search).get('r')
    const status = getStatus().val()
    let { is_enddate, is_startdate, is_date, r } = findDataByDate()
    let p = ''
    let is_valid = true
    if (status != '' && status) p += `&status=${status}`
    if (r != '') p += r

    if (is_date && (!is_enddate || !is_startdate)) {
        is_valid = false
    }
    if (is_valid && p != '') {
        const route = `./?r=${params}${p}`
        location.assign(route)
    }

})
$('[name="sortby"]').change(function () {
    $('#sortDataFilterOption').val('')
})
$('button[name="report-to-file"]').click(function () {
    const filetype = $(this).attr('data-file')
    const { r } = findDataByDate()
    const p = getParam(r)
    const start = p.get('start_dt')
    const end = p.get('end_dt')

    if (!start && !end) {
        errDialog('แจ้งเตือน', '', 'โปรดป้อนวันที่ในการค้นหา')
    }
    if (r != '') {
        const sort_by = $('[name="sortby"]').filter(':checked').val() ?? 'all'
        const url = '../controller/pdfController.php'

        $.ajax({
            url: url,
            type: 'post',
            data: {
                'start_dt': start,
                'end_dt': end,
                'sort_by': sort_by,
            },
            complete: function (xhr, textStatus) {
                try {
                    const data = JSON.parse(xhr.responseText)
                    const is_items = data.is_items
                    if (xhr.status == 200) {
                        success('สร้างไฟล์รายงานเรียบร้อย', false)
                        setTimeout(() => {
                            window.open('../assets/' + data.file_target, '_blank')
                        }, 2000)


                    } else {
                        let msg = data.err;
                        if (is_items == false) {
                            msg = 'สามารถสร้างรายงานมากสุด 10,000 รายการเท่านั้น '
                            msg += 'โปรดลดจำนวนวันที่สำหรับการออกรายงาน เพื่อไม่เกินจำนวนที่กำหนด '
                            msg += 'หรือให้ออกเป็นแบบเฉลี่ยเป็นรอบ เพื่อให้ได้รายงานครบตามวันที่ต้องการ'
                        }
                        errDialog('แจ้งเตือน', msg, '')
                    }
                } catch (err) {
                    errDialog('เกิดข้อผิดพลาด', '', err)
                }

            }
        })
    }
})

$('#filterByData').click(function () {
    const sort = $('[name="sortby"]').filter(':checked').val() || 'all'
    const sort_by = sort == 'name' ? 'rm' : sort
    let sortDataFilter = JSON.parse(atob($('#sortDataFilter').val()))
    sortDataFilter = sortDataFilter.filter((v, i) => {
        if (v.id.includes(sort_by)) {
            return v
        }
    })

    if (sort == 'all') {
        errDialog('การจัดเรียง', 'หากต้องการกรองข้อมูลตามที่เลือกโปรดเลือกการจัดเรียงแบบอื่น')
    }
    if (sort && sort != 'all') {
        let sortDataFilterOptionEl = ``
        sortDataFilter.forEach((d) => {
            sortDataFilterOptionEl += `<option value="${d.id}">${d.text}</option>`
        })
        $('#sortDataFilterOption').html(sortDataFilterOptionEl)
        $('#reportSortByData').modal('show')
    }
})