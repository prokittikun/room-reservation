retainOption($('#status').attr('data-status'), $('#status'))
retainOption($('#payStatus').attr('data-status'), $('#payStatus'))



$('#findDataByQueryForm').click(function () {
    const startDt = $('#startDate').val()
    const endDt = $('#endDate').val()
    const payStatus = $('#payStatus').val()
    const status = $('#status').val()
    const queryIdAndName = $('#queryIdAndName').val()

    const params = new URLSearchParams(location.search)
    const r = params.get('r')
    let path = ``
    const data = {
        'start_dt': startDt, 'end_dt': endDt, 'pay_status': payStatus, 'status': status,
        'id': queryIdAndName
    }

    const _keys = Object.keys(data)
    const _val = Object.values(data)
    for (let i = 0; i < _keys.length; i++) {
        if (_val[i] && _val[i] != '') {
            path += `&${_keys[i]}=${_val[i]}`
        }
    }
    if (path != '') {
        path = `./?r=${r}${path}`
        location.assign(path)
    }

})