retainOption($('#province').attr('data-province'), $('#province'))
$('#contactHandleSave').click(function () {
  const id = $(this).attr('data-id')
  const data = {
    'company_name': $('#companyName').val(),
    'tel': $('#tel').val(),
    'email': $('#email').val(),
    'province': $('#province').val().trim(),
    'district': $('#district').val().trim(),
    'sub_district': $('#subDistrict').val().trim(),
    'house_no': $('#houseNo').val(),
    'village_no': $('#villageNo').val(),
    // 'village_name': "",//$('#villageName').val()
    // 'alley': "",//$('#alley').val(),
    // 'junction': "",//$('#junction').val(),
    // 'road': "",//$('#road').val(),
    'route': '/contact/save'
  }
  if (id != '') {
    Object.assign(data, { id })
  }


  $.ajax({
    url: contactRoute(),
    type: 'post',
    data: data,
    complete: function (xhr, textStatus) {

      if (xhr.status == 200) {
        success('บันทึกสำเร็จ')
      } else {
        errDialog('แจ้งเตือน', xhr.responseText, '')
      }

    }
  })
})