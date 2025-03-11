function createSelect2Remote(selector, url, route, noResultsText, loadingText) {
    $(selector).select2({
        language: {
            searching: function () {
                return "กำลังค้นหาข้อมูล";
            },
            noResults: function () {
                return noResultsText;
            },
            errorLoading: function () {
                return loadingText
            },

        },
        ajax: {
            delay: 300,
            url: url,
            dataType: "json",
            type: "POST",
            data: function (params) {

                var queryParameters = {
                    search: params.term,
                    route: route
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id,
                        }
                    })
                };
            },
            complete(xhr, textStatus) {
                console.log(xhr.responseText)
            }
        }
    });
}

function createSelect2RemoteModal(data) {
    const { selector, modal, url, route, noResultsText, loadingText } = data
    $(selector).select2({
        dropdownParent: modal,
        language: {
            searching: function () {
                return "กำลังค้นหาข้อมูล";
            },
            noResults: function () {
                return noResultsText;
            },
            errorLoading: function () {
                return loadingText
            },

        },
        ajax: {
            delay: 300,
            url: url,
            dataType: "json",
            type: "POST",
            data: function (params) {

                var queryParameters = {
                    search: params.term,
                    route: route
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.text,
                            id: item.id,
                        }
                    })
                };
            },
            complete(xhr, textStatus) {
                console.log(xhr.responseText)
            }
        }
    });
}
function createSelect2Option(select) {
    const option = {
        searching: function () {
            return "กำลังค้นหาข้อมูล";
        },
        "noResults": function () {
            return "ไม่พบข้อมูล";
        },
    }


    $(select).select2({
        language: option
    })
}
function createSelect2Modal(selector, modal) {
    $(selector).select2({
        dropdownParent: modal,
        language: {
            searching: function () {
                return "กำลังค้นหาข้อมูล";
            },
            "noResults": function () {
                return "ไม่พบข้อมูล";
            },
        },

    });
}