function createCalendarByMonthAndYear(year, month) {
    const sum_month = new Date(year, month, 0).getDate();
    const startMonth = new Date(year, month - 1, 1).getDay();
    const colspan = startMonth;
    const sumWeek = Math.ceil(sum_month / 7);
    let calendar = ``;
    calendar += `<table id="${year}-${getCountDate(month)}" class="table">`
    calendar += `<thead>`
    calendar += `<tr class="bg-teal">`
    calendar += `<th class="p-4" style="width: 14%" scope="col">Sun</th>`
    calendar += `<th style="width: 14%" scope="col">Mon</th>`
    calendar += `<th style="width: 14%" scope="col">Tue</th>`
    calendar += `<th style="width: 14%" scope="col">Wed</th>`
    calendar += `<th style="width: 14%" scope="col">Thu</th>`
    calendar += `<th style="width: 14%" scope="col">Fri</th>`
    calendar += `<th style="width: 12%" scope="col">Sat</th>`
    calendar += `</tr>`
    calendar += `</thead>`
    calendar += `<tbody class="table-bordered">`
    let d = 0;
    for (let i = 0; i < sumWeek; i++) {
        let r = "";
        if (i == 0) {
            r += `<tr>`;
            for (let p = 0; p < colspan; p++) {
                r += `<td class="cr-date"></td>`;
            }

            for (let c = 0; c < colspan + 7; c++) {
                if (c + 1 < 7 - colspan + 1) {
                    let t = i + (c + 1);
                    const dt = `${year}-${getCountDate(month)}-${getCountDate(t)}`
                    r += createTd(dt)
                    d = t;
                }
            }
            r += `</tr>`;
        }
        if (i >= 1 && i < sumWeek - 1) {
            r += `<tr>`;
            let t = 0;
            for (let c = 0; c < 7; c++) {
                t = d + (c + 1);

                const dt = `${year}-${getCountDate(month)}-${getCountDate(t)}`
                r += createTd(dt)
            }
            d = t;
            r += `</tr>`;
        }
        if (i == sumWeek - 1) {
            r += `<tr>`;
            let t = 0;
            for (let c = 0; c < 7; c++) {
                if (d + (c + 1) <= sum_month) {
                    t = d + (c + 1);
                    const dt = `${year}-${getCountDate(month)}-${getCountDate(t)}`
                    r += createTd(dt)
                }
            }
            d = t;
            r += `</tr>`;
            if (d < sum_month) {
                for (let c = 0; c < 7; c++) {
                    if (d + (c + 1) <= sum_month) {
                        t = d + (c + 1);
                        const dt = `${year}-${getCountDate(month)}-${getCountDate(t)}`
                        r += createTd(dt)
                    }
                }
            }
        }
        calendar += r;
    }
    calendar += `</tbody>`
    calendar += `</table>`
    return calendar
}


function createTd(dt) {
    const checkbox = calendarSchedule().attr('data-check')
    const display = checkbox == 'false' ? 'd-none' : 'd-inline'
    const for_id = `R${dt}`
    const _dt = parseInt(dt.split('-')[2])
    let td = ``
    td += `<td class="cr-date" data-date="${dt}">`
    td += `<button class="reservatCal-btn">`
    td += `<div class="custom-control custom-checkbox ${display}">`
    td += `<input class="custom-control-input" type="checkbox" onchange="reservatCalDateChange(event,'${dt}')" name="reservatCal-date-check" value="${dt}" id="${for_id}">`
    td += `<label for="${for_id}" class="custom-control-label"></label>`
    td += `</div>`
    td += `<strong class="h5 m-0 font-weight-bold ">${_dt}</strong>`
    td += `</button>`
    td += `</td>`;
    return td
}

