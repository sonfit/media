$(function() {
    "use strict";
    feather.replace(), $(".preloader").fadeOut(), $(".nav-toggler").on("click", function() {
        $("#main-wrapper").toggleClass("show-sidebar"), $(".nav-toggler i").toggleClass("ti-menu")
    }), $(function() {
        $(".service-panel-toggle").on("click", function() {
            $(".customizer").toggleClass("show-service-panel")
        }), $(".page-wrapper").on("click", function() {
            $(".customizer").removeClass("show-service-panel")
        })
    }), $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    }), $(function() {
        $('[data-toggle="popover"]').popover()
    }), $(".message-center, .customizer-body, .scrollable, .scroll-sidebar").perfectScrollbar({
        wheelPropagation: !0
    }), $("body, .page-wrapper").trigger("resize"), $(".page-wrapper").delay(20).show(), $(".list-task li label").click(function() {
        $(this).toggleClass("task-done")
    }), $(".show-left-part").on("click", function() {
        $(".left-part").toggleClass("show-panel"), $(".show-left-part").toggleClass("ti-menu")
    }), $(".custom-file-input").on("change", function() {
        var e = $(this).val();
        $(this).next(".custom-file-label").html(e)
    })


    $('#navbar_search').on('input', function () {
        var search = $(this).val().toLowerCase();
        var search_result_pane = $('#navbar_search_result_area .navbar_search_result');
        $(search_result_pane).html('');
        if (search.length == 0) {
            return;
        }

        var match = $('#sidebarnav .sidebar-link').filter(function (idx, element) {
            return $(element).text().trim().toLowerCase().indexOf(search) >= 0 ? element : null;
        }).sort();

        if (match.length == 0) {
            $(search_result_pane).append('<li class="text-muted">No search result found.</li>');
            return;
        }

        match.each(function (index, element) {
            var item_url = $(element).attr('href') || $(element).data('default-url');
            var item_text = $(element).text().replace(/(\d+)/g, '').trim();
            $(search_result_pane).append(`<li><a href="${item_url}">${item_text}</a></li>`);
        });
    });


    $('select').select2({
        width: '100%',
    });
});


let viewportHeight = window.innerHeight;

function setMinAndValueDate(elementId, daysToAdd) {
    var date = new Date();

    var day = ("0" + date.getDate()).slice(-2);
    var month = ("0" + (date.getMonth() + 1)).slice(-2);

    var today = date.getFullYear()+"-"+(month)+"-"+(day) ;

    document.getElementById(elementId).min = today;

    date.setDate(date.getDate() + daysToAdd);
    day = ("0" + date.getDate()).slice(-2);
    month = ("0" + (date.getMonth() + 1)).slice(-2);

    var futureDate = date.getFullYear()+"-"+(month)+"-"+(day) ;

    document.getElementById(elementId).value = futureDate;
}

function generateRandomString(inputId, length,prefix = '') {
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var randomString = '';

    for (var i = 0; i < length; i++) {
        var randomIndex = Math.floor(Math.random() * characters.length);
        randomString += characters.charAt(randomIndex);
    }

    // Gán chuỗi ngẫu nhiên vào input có ID được chỉ định
    var inputElement = document.getElementById(inputId);
    if (inputElement) {
        inputElement.value = prefix + randomString;
    } else {
        console.log('Phần tử với ID ' + inputId + ' không tồn tại.');
    }
}

function applySelect2(elementId, placeholderText, ajaxUrl, dataSelected = null, setValue = 'id') {
    var selectElement = $(elementId);
    selectElement.select2({
        width: '100%',
        placeholder: placeholderText,
        ajax: {
            url: ajaxUrl,
            dataType: 'json',
            type: "GET",
            data: function(params) {
                return {
                    q: params.term, // search term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item.name,
                            id: item[setValue]
                        }
                    })
                };
            },
        },
        initSelection : function (element, callback) {
            var data = [];
            $(element.val()).each(function () {
                data.push({id: this, text: this});
            });
            callback(data);
        }
    });

    selectElement.val(null).trigger('change');

    if (dataSelected !== null) {
        var convertedData = dataSelected.map(item => ({ id: item.id, text: item.tag_name }));
        convertedData.forEach(function(item) {
            selectElement.select2("trigger", "select", {
                data: item
            });
        });
    }
}

function copyValueName(elementId, value) {
    document.querySelectorAll(elementId).forEach(function(button) {
        button.addEventListener('click', function() {
            var fullUrl = this.getAttribute(value);
            var tempInput = document.createElement('input');
            document.body.appendChild(tempInput);
            tempInput.value = fullUrl;
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            Notiflix.Notify.Success('URL copied: ' + fullUrl);
        });
    });
}

function changeListTypeUser(listTypeId,classId='badge') {
    var button = document.querySelector('[data-id="' + listTypeId + '"]');
    var user_id = button.getAttribute('data-user');
    var statusType = button.classList.toggle(classId+'-success') ? 'add' : 'remove';
    console.log(statusType)
    button.classList.toggle(classId+'-dark');
    changeListTypeUserAjax(listTypeId, statusType,user_id);
}

function changeListTypeUserAjax(listTypeId, statusType,user_id) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type: "POST",
        url: "{{route('admin.ipblock.changeListType')}}",
        data: {
            'typeId': listTypeId,
            'statusType': statusType,
            'user_id': user_id
        },
        success: function (response) {
            Notiflix.Notify.Success(response.success);
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

function handleAjaxRequest(formData, url, successCallback) {
    $.ajax({
        data: formData,
        url: url,
        type: "POST",
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (data) {
            if (data.errors) {
                data.errors.forEach(function (error) {
                    Notiflix.Notify.Failure(error);
                });
            }
            if (data.error) {
                Notiflix.Notify.Failure(error);
            }

            if (data.success) {
                Notiflix.Notify.Success(data.success);
                if (typeof successCallback === 'function') {
                    successCallback();
                }
            }
        },
    });
}

// var firstVisit = true;
function setupDataTableScrollTop(table,offsetFromTop=0,firstVisit = true) {
    table.on('draw.dt', function () {
        // Sử dụng giá trị cách đỉnh tùy thuộc vào firstVisit
        var offset = firstVisit ? 0 : offsetFromTop;

        // Cuộn lên đầu trang với khoảng cách từ đỉnh (offset)
        $('html, body').animate({
            scrollTop: offset
        }, 'fast');

        // Đánh dấu là lần đầu truy cập đã qua
        firstVisit = false;
    });
}

function setupDataTable1(selector, url, columns, order, extraData, fnDrawCallback,createdRow) {
    $.fn.dataTable.ext.errMode = 'throw';
    var config = {
        displayLength: paginate,
        processing: false,
        serverSide: true,
        fixedHeader: {
            header: true,
            headerOffset: 80
        },
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                return $.extend({}, d, extraData, { page: d.start/d.length + 1 });
            }
        },
        columns: columns,
        order: order,
    };

    if (fnDrawCallback) {
        config.fnDrawCallback = fnDrawCallback;
    }
    if (createdRow) {
        config.createdRow = createdRow;
    }

    var tableName = $(selector).DataTable(config);

    return tableName.on('init.dt', function() {
        if (page !== 0) {
            tableName.page(page).draw(false);
        }
    }).on('draw', function() {
        var currentPage = tableName.page.info().page + 1;
        var url = new URL(window.location.href);
        url.searchParams.set('page', currentPage);
        window.history.pushState({}, '', url);
    });
}


function setupDataTable(selector, url, columns, order, extraData, fnDrawCallback, createdRow) {
    $.fn.dataTable.ext.errMode = 'throw';

    var currentPage = 1;
    var urlSearchParams = new URLSearchParams(window.location.search);
    if (urlSearchParams.has('page')) {
        currentPage = parseInt(urlSearchParams.get('page'));
    }
    var config = {
        displayLength: paginate,
        processing: false,
        serverSide: true,
        destroy: true,
        fixedHeader: {
            header: true,
            headerOffset: 80
        },
        ajax: {
            url: url,
            type: "POST",
            data: function (d) {
                return $.extend({}, d, extraData, { page: currentPage });
            }
        },
        columns: columns,
        order: order,
    };

    if (fnDrawCallback) {
        config.fnDrawCallback = fnDrawCallback;
    }
    if (createdRow) {
        config.createdRow = createdRow;
    }


    var tableName = $(selector).DataTable(config);

    return tableName
        .on('init.dt', function() {
            if (page !== 0) {
                tableName.page(page).draw(false);
            }
        })
        .on('draw', function() {
            var currentPage = tableName.page.info().page + 1;
            var url = new URL(window.location.href);
            url.searchParams.set('page', currentPage);
            window.history.pushState({}, '', url);
        });

    // return tableName;
}

function setupMagnificPopup(selector) {
    $(selector).magnificPopup({
        type: 'image',
        closeOnContentClick: true,
        closeBtnInside: false,
        fixedContentPos: true,
        mainClass: 'mfp-no-margins mfp-with-zoom',
        // class to remove default margin from left and right side
        image: {
            verticalFit: true
        },
        zoom: {
            enabled: true,
            duration: 300 // don't foget to change the duration also in CSS
        }
    });
}




