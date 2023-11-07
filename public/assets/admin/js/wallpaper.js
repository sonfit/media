$(function() {
    "use strict";
// $(document).ready(function () {
    const THUMBNAIL_WALLPAPER = `${thumbnailWallpaperUrl}`;
    const ORIGINAL_WALLPAPER = `${originalWallpaperUrl}`;
    const btnListView = $('#btnListView');
    const btnGridView = $('#btnGridView');
    const gridView = $('#gridView');
    const listView = $('#listView');
    const loadGridWallpapers = $('.loadGridWallpapers');
    const loadListWallpapers = $('.loadListWallpapers');
    const pagination = $('#pagination');

// Biến toàn cục
    let viewParam = getURLParam('view');
    let lengthParam = getLengthFromURL();
    let currentPage = getPageFromURL();
    let searchParam = getSearchFromURL();

// Sự kiện click nút chuyển đổi giữa chế độ xem danh sách và chế độ xem lưới
    btnListView.click(() => updateViewMode('list'));
    btnGridView.click(() => updateViewMode('grid'));

// Cập nhật chế độ xem ban đầu
    updateViewMode(viewParam);

// Hàm lấy tham số từ URL
    function getURLParam(paramName) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(paramName);
    }

// Hàm lấy trang từ URL
    function getPageFromURL() {
        return parseInt(getURLParam('page')) || 1;
    }

// Hàm lấy độ dài từ URL
    function getLengthFromURL() {
        return parseInt(getURLParam('length')) || 30;
    }

    function getSearchFromURL() {
        return getURLParam('search');
    }



// Hàm cập nhật chế độ xem
    function updateViewMode(viewMode) {
        viewParam = viewMode ?? 'grid';
        performAjaxRequest(currentPage)
            .then(dataWallpapers => {
                if(dataWallpapers.data.length != 0){
                    if (viewMode === 'grid') {
                        hideListView();
                        showGridView();
                        renderGrid(dataWallpapers.data);
                    } else {
                        hideGridView();
                        showListView();
                        renderList(dataWallpapers.data);
                    }
                    if (!pagination.data('twbs-pagination')) {
                        initializePagination(dataWallpapers.last_page);
                    }
                    setupMagnificPopup('.image-popup-no-margins');
                }else {
                    loadListWallpapers.html('No data available in table');
                }

            })
            .catch(error => {
                console.error('Lỗi trong quá trình tải dữ liệu: ' + error);
            });

        updateURL(currentPage, lengthParam);

    }

// Hàm hiển thị dữ liệu dưới dạng lưới
    function renderGrid(data) {
        const html = data.map(item => {
            const tags = item.tags.map(tag => {
                return `<span class="badge badge-pill badge-secondary m-1 font-16 text-truncate ">${tag.tag_name}
                            <i class="fa fa-times close-icon deleteTag" data-wallpaper="${item.id}" data-tag="${tag.id}"></i>
                        </span>`;
            }).join('');
            const checkIcon = item.wallpaper_status === 1 ? '<i class="fa fa-check check-icon"></i>' : ''; // Kiểm tra wallpaper_status

            return `<div class="col-sm-4 col-md-4 col-lg-3 col-xl-2">
                              <div class="mb-5">
                                <div class="border-right">
                                ${checkIcon}
                                      <a class="image-popup-no-margins" href="${ORIGINAL_WALLPAPER}/${item.wallpaper_image}">
                                        <img class="rounded mx-auto d-block w-100" src="${THUMBNAIL_WALLPAPER}/${item.wallpaper_image}">
                                      </a>
                                     <a href="javascript:void(0)" data-id="${item.id}" class="btn deleteWallpaper">
                                        <i class="fa fa-trash delete-icon"></i>
                                     </a>
                                </div>
                                ${tags}
                              </div>
                            </div>`;
        }).join('');
        loadGridWallpapers.html(html);
    }

// Hàm hiển thị dữ liệu dưới dạng danh sách
    function renderList(data) {
        const html = data.map(item => {
            const tags = item.tags.map(tag => {
                return `<span class="badge badge-pill badge-secondary m-1 font-16 text-truncate">${tag.tag_name}
                            <i class="fa fa-times close-icon deleteTag" data-wallpaper="${item.id}" data-tag="${tag.id}"></i>
                        </span>`;
            }).join('');
            return `<tr>
                                <td data-label="Wallpaper">
                                   <a class="image-popup-no-margins" href="${ORIGINAL_WALLPAPER}/${item.wallpaper_image}">
                                    <img src="${THUMBNAIL_WALLPAPER}/${item.wallpaper_image}" width="50">
                                  </a>
                                </td>
                                <td data-label="Name">${item.wallpaper_name}</td>
                                <td data-label="Tags">${tags}</td>
                                <td data-label="Extension">${item.wallpaper_extension}</td>
                                <td data-label="Type">${item.wallpaper_type}</td>
                                <td data-label="Action">
                                    <a href="javascript:void(0)" data-id="${item.id}" class="btn deleteWallpaper">
                                        <i class="fa fa-trash text-danger"></i></a>
                                </td>
                            </tr>`;
        }).join('');
        loadListWallpapers.html(html);
    }

// Hàm khởi tạo phân trang
    function initializePagination(totalPages) {
        pagination.twbsPagination({
            totalPages: totalPages,
            visiblePages: 7,
            startPage: currentPage,
            onPageClick: (event, page) => {
                currentPage = page;
                updateViewMode(viewParam);
                updateURL(page, lengthParam);

            }
        });
    }

// Hàm cập nhật URL
    function updateURL(currentPage, lengthParam, searchParam = null,) {
        let newUrl = window.location.pathname + "?";
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.forEach((value, key) => {
            if (key !== 'view' && key !== 'page' && key !== 'length' && key !== 'search') {
                newUrl += key + '=' + value + '&';
            }
        });
        newUrl += 'view=' + viewParam;
        if (currentPage) {
            newUrl += '&page=' + currentPage;
        }
        if (lengthParam) {
            newUrl += '&length=' + lengthParam;
        }

        if (searchParam) {
            newUrl += '&search=' + searchParam;
        }
        window.history.pushState({}, '', newUrl);
    }

// Hàm thực hiện yêu cầu AJAX
    function performAjaxRequest(currentPage) {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${wallpapersIndexUrl}?page=${currentPage}&length=${lengthParam}&search=${searchParam}`,
                type: 'get',
                success: data => {
                    const response = JSON.parse(data);
                    resolve(response);
                },
                error: error => {
                    reject(error);
                }
            });
        });
    }

// Hàm ẩn chế độ xem danh sách
    function hideListView() {
        listView.fadeOut(500); // Ẩn chế độ xem danh sách
    }

// Hàm hiển thị chế độ xem danh sách
    function showListView() {
        listView.fadeIn(500); // Hiển thị chế độ xem danh sách
    }

// Hàm ẩn chế độ xem lưới
    function hideGridView() {
        gridView.fadeOut(500); // Ẩn chế độ xem lưới
    }

// Hàm hiển thị chế độ xem lưới
    function showGridView() {
        gridView.fadeIn(500); // Hiển thị chế độ xem lưới
    }

    document.querySelector('#searchInput').addEventListener('input', function () {
        const inputText = this.value;
        currentPage = 1;
        if (inputText.length > 3) {
            searchParam = inputText;
        } else {
            searchParam = 'null';
        }
        updateViewMode(viewParam);
        updateURL(currentPage, lengthParam, searchParam);

    });

    $(document).on('click', '.deleteWallpaper', function (data) {
        var _id = $(this).data("id");
        var $element = viewParam === 'list' ? $(this).closest('tr') : $(this).closest('.col-sm-4.col-md-4.col-lg-3.col-xl-2');

        console.log(viewParam)
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#02a499",
            cancelButtonColor: "#ec4561",
            confirmButtonText: "Yes, delete it!"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: `${wallpapersDeleteUrl}`,
                    data: {
                        'id': _id
                    },
                    success: function (data) {
                        if (data.error) {
                            Notiflix.Notify.Failure(data.error);

                        }
                        if (data.success) {
                            Notiflix.Notify.Success(data.success);
                            $element.fadeOut(400, function () {
                                $(this).remove();
                            });
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });

    $(document).on('click', '.deleteTag', function (data) {
        var wallpaper_id = $(this).data("wallpaper");
        var tag_id = $(this).data("tag");
        var $element = $(this).closest('span');
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#02a499",
            cancelButtonColor: "#ec4561",
            confirmButtonText: "Yes, delete it!"
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: `${wallpapersDeleteTagUrl}`,
                    data: {
                        'wallpaper_id': wallpaper_id,
                        'tag_id': tag_id,
                    },
                    success: function (data) {
                        if (data.error) {
                            Notiflix.Notify.Failure(data.error);

                        }
                        if (data.success) {
                            Notiflix.Notify.Success(data.success);
                            $element.fadeOut(400, function () {
                                $(this).remove();
                            });
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });
})
