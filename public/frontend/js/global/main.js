const getProvinceFromName = async (province, $provinceEl) => {
    $.ajax({
        url: '/params/ajax-get-province-name',
        type: 'GET',
        dataType: 'JSON',
        data: {
            province_name: province.trim()
        },
        success: res => {
            if (!$provinceEl) return
            const oldVal = $provinceEl.val()
            $provinceEl.val(res?.province?.id);

            if (oldVal != res?.province?.id)
                $provinceEl.trigger('change');
        },
    });
}

const getDistrictFromName = async (district, $districtEl) => {
    $.ajax({
        url: '/params/ajax-get-district-name',
        type: 'GET',
        dataType: 'JSON',
        data: {
            district_name: district.trim()
        },
        success: res => {
            if (!$districtEl) return
            $districtEl.attr('data-selected', res?.district?.id);

            const oldVal = $districtEl.val()
            $districtEl.val(res?.district?.id);

            if (oldVal != res?.district?.id)
                $districtEl.trigger('change');
        }
    });
}

function sleep(ms) {
    return new Promise(res => setTimeout(res, ms))
}

(() => {
    $('.js-content-title').each(function() {
        const content = $(this).text()

        if (content)
            $(this).attr('data-title', content)
    })

    // js has count
    const jsHasCountUpdate = _$element => {
        const _$parents = _$element.parents('.js-has-count__area'),
        _text = _$parents.find('.js-has-count__input').val()

        if (_text)
            _$parents.find('.js-has-count__length').text(_text.length)
    }
    $('.js-has-count__input').each(function() {
        jsHasCountUpdate($(this))
    })
    $('body').on('change keyup', '.js-has-count__area .js-has-count__input', function () {
        jsHasCountUpdate($(this))
    })
    // end js has count

    $(document).on('mouseover', '[data-title]', function (event) {
        showTitle($(this))
    });

    $(document).on('mouseout', '[data-title]', function (event) {
        hideTitle($(this))
    });

    $('body').on('touchstart', '[data-title]', function (event) {
        showTitle($(this))
    });

    $('body').on('touchend', '[data-title]', function (event) {
        hideTitle($(this))
    });

    const showTitle = (self) => {
        if (!self.attr('data-title')) return
        if (!$('.title-container').length) {
            $('body').append('<div class="title-container"></div>')
        }

        $('.title-container').text(self.attr('data-title'))
        let top = self.offset().top - $(document).scrollTop() - $('.title-container').height() - 5
        let left = self.offset().left - $('.title-container').width() / 2;

        if (left < 0) {
            left = 10;
        }

        $('.title-container').css({
            'top': top + 'px',
            'left': left + 'px',
            'display': 'block'
        })
    }

    const hideTitle = () => {
        $('.title-container').css('display', 'none')
    }

    $('body').on('click', '.parent-need-close .click-close-icon', function () {
        let _this = $(this)

        _this.parents('.parent-need-close').fadeOut()
    })

    // close click outside element
    $('body').on('click', event => {
        if (!$(event.target).parents('.js-search-dropdown').length) {
            $('.js-search-dropdown .js-search-result-body').addClass('hide')
        }
    })

    // classified list
    $('body').on('click', '.classified-item-box .content-box .item-foot-box.js-show-detail', function () {
        let _this = $(this),
            $item = _this.parents('.classified-item-box')

        _this.toggleClass('active')
        $item.find('.detail-box').slideToggle('fast')
    })

    // header
    $('body').on('click', ".js-open-add-classified__login", function (event) {
        event.preventDefault();
        $('#add-classified__login-popup-modal').modal('show')
        // let post_popup = $('#new-post'),
        //     layout = $('#layout');
        // post_popup.show();
        // layout.show();
    });


    const saveIntendedUrl = (intendedUrl) => {
        if (!intendedUrl) return;
        $('.c-overlay-loading:not(.inner)').addClass('loading')

        return new Promise((resolve, reject) => {
            $.ajax({
                url: '/params/put-intended-url',
                method: 'GET',
                data: {
                    url: intendedUrl,
                },
                success: res => {
                    $('.c-overlay-loading:not(.inner)').removeClass('loading')
                    resolve(res)
                },
                error: err => {
                    $('.c-overlay-loading:not(.inner)').removeClass('loading')
                    reject(err)
                }
            });
        })
    }

    $('body').on('click', '.js-open-login', async function (event) {
        event.preventDefault();
        let login_popup = $("#login"),
            layout = $("#layout"),
            intendedUrl = $(this).data('url');

        if (intendedUrl)
            await saveIntendedUrl(intendedUrl);

        $('*[data-wrap=".wrapper-login"]').addClass("active");
        $('*[data-wrap=".wrapper-login"]').siblings(".log-switch").removeClass("active");
        $("#login .wrap").removeClass("active");
        $("#login .wrapper-login").addClass("active");
        login_popup.show();
        login_popup.css('top', $(window).scrollTop() + 30);
        layout.show();
    });

    $('body').on('click', '.js-open-guest-add-classified', function (event) {
        event.preventDefault();
        $('.c-overlay-loading:not(.inner)').removeClass('loading')
        // if ($('#add-classified__login-popup-modal') && $('#add-classified__login-popup-modal').length)
        //     $('#add-classified__login-popup-modal').modal('hide')

        $('.c-overlay-loading:not(.inner)').addClass('loading')
        initMap('classified-page__map', '.classified-page [name="map_latitude"]', '.classified-page [name="map_longtitude"]');

        $('#guest-add-classified__modal').modal('show')
        $('.c-overlay-loading:not(.inner)').removeClass('loading')
    });

    $('body').on('click', '.file-with-button .button-with-file', function (e) {
        e.preventDefault();
        $(this).parents('.file-with-button').find('input[type="file"]').trigger('click')
    })

    $('body').on('click', '.js-radio-toggle-active input[type="radio"]', function (evt) {
        let _this = $(this),
            $parents = _this.parents('.radio-with-button-group');

        $parents.find('.js-radio-toggle-active').removeClass('active')
        _this.parents('.js-radio-toggle-active').addClass('active')
    })

    $('body').on('click', '.js-go-to-top', function () {
        let body = $("html, body");
        body.stop().animate({scrollTop:0}, 500, 'swing');
    })

    // show text count
    showDescriptionTextCount = element => {
        let count = element.val().length ?? 0;

        let $parent = element.parents('.text-need-show-count'),
            $wordCount = $parent.find('.word-count');

        if ($wordCount && $wordCount.length)
            $wordCount.text(count);
    }

    $('.text-need-show-count .input-need-show-count').on('input', function () {
        showDescriptionTextCount($(this))
    })

    $('.text-need-show-count .input-need-show-count').each(function(el) {
        if ($(this).val() && $(this).val().length)
            showDescriptionTextCount($(this))
    })
    // end show text count

    // view map // old should change modal
    const viewMap = (id, type) => {
        if (!id) return
        $('.c-overlay-loading:not(.inner)').addClass('loading')

        let url = type == 'project'
            ? `/du-an/view-map/${id}`
            : `/tin-rao/view-map/${id}`

        $.ajax({
            url: url,
            type: 'GET',
            success: res => {
                if (!res) return;

                if (type == 'project') {
                    if (!res || !res.data || !res.data.html) return

                    $('.project-view-map-modal-box').modal('show')
                    $('.project-view-map-modal-box #append-view-map').html(res.data.html)
                } else {
                    if (!res || !res.data || !res.data.html) return
                    $('.classified-view-map__box').modal('show')
                    $('.classified-view-map__box .classified-view-map__append-box').html(res.data.html)
                }

                const viewMapInitLocation = {
                    lat: parseFloat(res.data.location.map_latitude) || 0,
                    lng: parseFloat(res.data.location.map_longtitude) || 0,
                }
                initViewMapSimpleMap('view-map__map', viewMapInitLocation);
            },
            error: () => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
            }
        }).done(() => {
            $('.c-overlay-loading:not(.inner)').removeClass('loading')
        })
    }

    $('body').on('click', '.classified-item-box .js-view-map', function (event) {
        event.preventDefault();
        viewMap($(this).data('id'));
    });
    $('body').on('click', '.project-item-box .js-view-map', function (event) {
        event.preventDefault();
        viewMap($(this).data('id'), 'project');
    });
    // end view map

    // toggle active
    $('body').on('click', '.js-need-toggle-active .js-toggle-active', function (e) {
        e.preventDefault();
        let _this = $(this),
            $parents = _this.parents('.js-need-toggle-active');

        if (_this.hasClass('js-switch-active') && _this.hasClass('active'))
            return
  
        if (!$parents.find('.js-toggle-active').hasClass('is-on')) {
            if ($parents.hasClass('should-toggle-active'))
                $parents.addClass('is-active')
            if ($parents.hasClass('js-toggle-area'))
                $parents.addClass('active')
            $parents.find('.js-toggle-area').addClass('active')
            $parents.find('.js-toggle-active').addClass('is-on')
            $parents.find('.js-toggle-area-slide').slideDown()
        } else {
            if ($parents.hasClass('should-toggle-active'))
                $parents.removeClass('is-active')
            if ($parents.hasClass('js-toggle-area'))
                $parents.removeClass('active')
            $parents.find('.js-toggle-area').removeClass('active')
            $parents.find('.js-toggle-active').removeClass('is-on')
            $parents.find('.js-toggle-area-slide').slideUp()
        }

        // switch active
        if (_this.hasClass('js-switch-active')) {
            $parents.find('.js-switch-active').removeClass('active')
            _this.addClass('active')
        }
    });

    $('body').on('click', '.js-need-toggle-in .js-toggle-in', function (e) {
        e.preventDefault();
        let _this = $(this),
            $parents = _this.parents('.js-need-toggle-in');

        $parents.toggleClass('in')
    });

    // input date placeholder
    $('.js-date-placeholder').on('keypress', function(e) {
        if (this.type != 'date') return false
    })
    $('.js-date-placeholder').on('focus', function() {
        if (this.type != 'date') this.type = 'date'
    })
    $('.js-date-placeholder').on('blur', function() {
        if (!$(this).val() && this.type != 'text') this.type = 'text'
    })
    // end input date placeholder

    // c-input__control
    $('.c-input__control input').on('change', function () {
        let isDisabled = $(this).prop('disabled')
        isDisabled
          ? $(this).parents('.c-input__control').addClass('c-input__disabled')
          : $(this).parents('.c-input__control').removeClass('c-input__disabled')
    })
    // end c-input__control

    // fancybox
    if ($('[data-fancybox="gallery"]') && $('[data-fancybox="gallery"]').length) {
        $('[data-fancybox="gallery"]').fancybox({
            cyclic: true,
        })
    }

    // stars rating
    $('body').on('click', '.rating-stars-action:not(.disable-rating) i', function (event) {
        let _this = $(this),
            $ratingBox = _this.parents('.rating-stars-action'),
            $input = $ratingBox.find('input');

        if (!$input || !$input.length) return

        let ratingValue = parseInt(_this.index()) + 1

        $input.val(ratingValue).trigger('change')
        _this.siblings().removeClass('selected')
        _this.addClass('selected')
    })
    $('body').on('mouseover', '.rating-stars-action:not(.disable-rating) i', function(event) {
        event.preventDefault();
        let _this = $(this),
            $ratingBox = _this.parents('.rating-stars-action'),
            stars = $(this).nextAll();

        $ratingBox.find('i').removeClass('far').addClass('fas')
        stars.removeClass('fas').addClass('far')
    });
    $('body').on('mouseout', '.rating-stars-action:not(.disable-rating) i', function () {
        let _this = $(this),
            $ratingBox = _this.parents('.rating-stars-action');

        let $selectedStar = $ratingBox.find('i.selected')

        if ($selectedStar && $selectedStar.length) {
            $selectedStar.nextAll().removeClass('fas').addClass('far');
            $selectedStar.prevAll().removeClass('far').addClass('fas');
            $selectedStar.removeClass('far').addClass('fas');
        } else {
            $ratingBox.find('i').removeClass('fas').addClass('far')
        }
    })

    // show detail page rating
    let _loadingDetailRating = false;

    $('body').on('change', '.js-detail-rating-form input[name="rating"]', function (event) {
        event.preventDefault();
        let rating = parseInt($('.js-detail-rating-form input[name="rating"]').val());
        if (!rating) {
            toastr.error('vui lòng chọn đánh giá.')
            return
        }

        if (_loadingDetailRating) return

        let _this = $(this),
            $parents = _this.parents('.detail-review-result-box')
            url = $parents.data('url');

        if (!url) return

        _loadingDetailRating = true;
        $('.c-overlay-loading:not(.inner)').addClass('loading')

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                star: rating
            },
            success: res => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                _loadingDetailRating = false

                if (res && res.data) {
                    if (res.data.html)
                        $('.detail-review-result-box').html(res.data.html)

                    showMessage(res)
                }
            },
            error: err => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                _loadingDetailRating = false

                showError(err)
            }
        });
    })

    // should change for global detail page
    // show detail page agency receive advice / nhận tư van
    let _loadingReceiveAdvice = false
    $('body').on('click', '.user-detail-box .js-send-advisory', function (event) {
        event.preventDefault();

        $(this).parents('.user-detail-box').find('.js-send-advisory-form').trigger('submit')
    })

    $('body').on('submit', '.user-detail-box .js-send-advisory-form', function (event) {
        event.preventDefault();
        if (_loadingReceiveAdvice) return

        let _this = $(this),
            $parents = _this.parents('.user-detail-box'),
            url = _this.attr('action');

        if (!url) return

        _loadingReceiveAdvice = true;
        $parents.find('.c-overlay-loading.send-advisory-loading').addClass('loading')

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: res => {
                $parents.find('.c-overlay-loading.send-advisory-loading').removeClass('loading')
                _loadingReceiveAdvice = false

                showMessage(res)
            },
            error: err => {
                $parents.find('.c-overlay-loading.send-advisory-loading').removeClass('loading')
                _loadingReceiveAdvice = false
                showError(err)
            }
        });
    })

    // comment
    const detailUrl = $('.js-comment-section').data('url'),
          detailId = $('.js-comment-section').data('id');

    const showError = error => {
        if (error && error.responseJSON && error.responseJSON.errors) {
            const errors = Object.values(error.responseJSON.errors)
            if (errors[0] && errors[0][0])
            toastr.error(errors[0][0])
        } else if (error && error.responseJSON && error.responseJSON.message) {
            toastr.error(error.responseJSON.message)
        }
    }

    const showMessage = res => {
        if (res && res.message)
            res.success ? toastr.success(res.message) : toastr.error(res.message)
    }

    const openHtml5lightBox = data => {
        $('body').find('.js-html5lightbox-append').remove()

        if (data && data.html && data.video_url) {
            $('body').append(`<a href="${data.video_url}" class="js-html5lightbox-append d-none"
                    data-titlestyle="right"
                ></a>`)
            let $html5lightbox = $('.js-html5lightbox-append')
            $html5lightbox.attr('data-description', data.html)
            $html5lightbox.html5lightbox()
            $html5lightbox.trigger('click')
        }
    }

    const comment = selectValue => {
        const content = selectValue.val(),
                $parentItem = selectValue.parents('.comment-item'),
                parentId = selectValue.data('comment_id');

        if (!content.trim() || !detailUrl || !detailId) return

        $('.c-overlay-loading:not(.inner)').addClass('loading')

        $.ajax({
            url: `/${detailUrl}/${detailId}/comments`,
            method: 'POST',
            data: {
                content: content,
                parent_id: parentId
            },
            success: res => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                const data = res.data
                showMessage(res)

                if (data && res.success) {
                    selectValue.val('')
                    pushMessage(data, $parentItem)
                }
            },
            error: error => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                showError(error)
            }
        });
    }

    const pushMessage = (data, $parentItem) => {
        let rating = '',
            comment = data.comment,
            reactionHtml = '',
            replyFormHtml = '';

        if (!data || !comment || !detailUrl) return;

        const isChild = comment.parent_id ? true : false;

        if (!isChild) {
            rating = '<div class="color-stars-box yellow star-1x"><div class="box-rating">';

            for (let i = 1; i <= 5; i++) {
                rating += i > data.user_rating ? '<i class="far fa-star"></i>' : '<i class="fas fa-star"></i>'
            }
            rating += '</div></div>'
        }

        const userAvatarHtml = `<div class="avatar">
                    <img src="${comment.user.avatar}" alt="">
                </div>`,
            commentHtml = `<div class="group-report">
                    <div class="name">${comment.user.full_name}</div>
                    <div class="button-report cursor-pointer">
                        <span><i class="fas fa-ellipsis-h"></i></span>
                        <div class="report-main">
                            <ul>
                                <li class="hover-bg-gray">
                                    <button data-id="${comment.id}" class="border-0 bg-white hover-bg-gray w-100 js-show-edit-comment">Chỉnh sửa</button>
                                </li>
                                <li class="hover-bg-gray">
                                    <form class="js-delete-comment" data-id="${comment.id}" method="post">
                                        <button class="border-0 bg-white hover-bg-gray w-100" type="submit">Xoá</button>
                                    </form>
                                </li>
                                <li class="hover-bg-gray">
                                <a class="report_comment" data-comment_id="${comment.id}"
                                    href="javascript:void(0)">Báo cáo</a>
                                <li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="date">Bình luận vào ${comment.created_at}</div>
                    ${ rating ? `<div class="rate">${rating}</div>` : '' }
                <div class="content text-break">
                    ${comment.content}
                </div>
                <form action="/${detailUrl}/comments/${comment.id}" method="post" class="js-update-comment" id="form-edit-content${comment.id}">
                    <textarea name="comment_content" placeholder="Nhập bình luận..." class="edit-comment">${comment.content}</textarea>
                    <button type="submit" class="btn-edit-content">Submit</button>
                </form>`

        if (!isChild) {
            reactionHtml = `<div class="flex-between">
                        <div>
                        <div class="reaction justify-content-start d-none">
                            <div data-id=${comment.id} class="show-hide-reply user-select-none ml-0">
                                <i class="far fa-comment-dots"></i>
                                Phản hồi (<span class="count_reply">0</span>)
                            </div>
                        </div>
                        </div>
                        <div class="reaction">
                            <div class="like js-like-comment" data-comment_id="${comment.id}">
                                <i class="far fa-thumbs-up"></i>
                                Hữu ích
                                (<span class="js-num-like">0</span>)
                            </div>
                            <div class="reply user-select-none">
                                <i class="far fa-comment-dots"></i>
                                Trả lời
                            </div>
                        </div>
                    </div>
                    <div class="list-reply" style="display:none">
                    </div>`

            replyFormHtml = `<div class="reply-form mt-2">
                        <div class="avatar">
                            <img src="${comment.user.avatar}" alt="">
                        </div>
                        <div class="comment">
                            <div class="name">${comment.user.full_name}</div>
                            <div>
                                <textarea class="rep-home" data-comment_id="${comment.id}" placeholder="Nhập bình luận..."></textarea>
                                <input type="submit"  class="button_reply_comment">
                            </div>
                            <span class="reply-note">Nhấn enter để gửi, nhấn shift + enter để xuống dòng</span>
                        </div>
                    </div>`

            $('.comment-section').prepend(`<div class="comment-item">
                        ${userAvatarHtml}
                        <div class="comment">
                            ${commentHtml}
                            ${reactionHtml}
                        </div>
                        ${replyFormHtml}
                    </div>`)
        } else {
            $parentItem.find('.list-reply').append(`<div class="reply-section">
                    ${userAvatarHtml}
                    <div class="comment">
                        ${commentHtml}
                    </div>
                </div>`)
            $parentItem.find('.reaction').removeClass('d-none')
            $parentItem.find('.reaction .show-hide-reply').removeClass('d-none')
            $parentItem.find('.reaction .count_reply').text(comment.parent_children_count)
            $parentItem.find('.list-reply').show()
        }
    }

    $('body').on('keypress', '.rep-home, .home-text', function(e) {
        if (e.which == 13 && !e.shiftKey) {
            e.preventDefault();
            comment($(this))
        }
    });

    // old should change everything
    $("body").on('click', '.send-home-text', function() {
        comment($(".home-text"));
    });

    // display form reply
    $('body').on('click', '.reaction .reply', function(event) {
        event.preventDefault();
        $(this).toggleClass('active');
        $(this).parents('.comment-item').find('.reply-form').toggleClass('active');
    });
    // old should change to ajax get replies
    // show list reply
    $('body').on('click', '.show-hide-reply', function(event) {
        event.preventDefault();
        $(this).parents('.comment-item').find('.list-reply').toggle()
    });

    // like comment
    $('body').on('click', '.reaction .js-like-comment ', function(event) {
        event.preventDefault();
        let _this = $(this),
          commentId = _this.data('comment_id');

        if (!commentId || !detailUrl) return;

        $('.c-overlay-loading:not(.inner)').addClass('loading')

        $.ajax({
            url: `/${detailUrl}/comments/${commentId}/like`,
            method: 'POST',
            success: res => {
                const data = res.data
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                showMessage(res)

                if (data && res.success) {
                    data.liked ? _this.addClass('liked') : _this.removeClass('liked')
                    _this.children('span.js-num-like').html(data.likes_count);
                }
            },
            error: error => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                showError(error)
            }
        })
    });

    // show edit form
    $("body").on("click", ".js-show-edit-comment", function (event) {
        event.preventDefault();
        $("#form-edit-content"+$(this).attr('data-id')).prev().hide();
        $("#form-edit-content"+$(this).attr('data-id')).show();
    });

    // update comment
    $('body').on('submit', '.js-update-comment', function (event) {
        event.preventDefault();
        let form = $(this),
            updateUrl = form.attr('action'),
            content = form.find('[name="comment_content"]').val()

        if (!content.trim() || !detailUrl) return

        $('.c-overlay-loading:not(.inner)').addClass('loading')

        $.ajax({
            url: updateUrl,
            type: 'POST',
            data: {
                content: content,
            },
            success: res => {
                const data = res.data
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                showMessage(res)

                if (data && res.success) {
                    form.prev().text(data.comment.content);
                    form.prev().show();
                    form.hide();
                }
            },
            error: error => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                showError(error)
            }
        });
    });

    $('body').on('submit', '.js-delete-comment', function (event) {
        event.preventDefault();
        var section_comment = $(this).parents('.reply-section');
        if(!section_comment.length){
            section_comment = $(this).parents('.comment-item');
        }

        let _this = $(this),
        commentId = _this.data('id');

        if (!commentId || !detailUrl) return;

        $('.c-overlay-loading:not(.inner)').addClass('loading')

        $.ajax({
            url: `/${detailUrl}/comments/${commentId}`,
            method: 'DELETE',
            success: res => {
                const data = res.data
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                showMessage(res)

                if (data && res.success) {
                    let $parentItem = _this.parents('.comment-item'),
                        resComment = data.comment;

                    if (resComment.parent_children_count) {
                    $parentItem.find('.reaction .count_reply').text(resComment.parent_children_count)
                    } else if (resComment.parent_children_count == 0) {
                    $parentItem.find('.reaction .show-hide-reply').addClass('d-none')
                    }

                    section_comment.remove();
                }
            },
            error: error => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
                showError(error)
            }
        });
    });

    const timeCountDown = setInterval(function () {
        let count = parseInt($('.js-time-countdown').text());
        if (count <= 0) {
            clearInterval(timeCountDown);
            return
        }
        $('.js-time-countdown').text(count - 1)
    }, 1000)

    if ($('.js-time-countdown') && $('#js-time-countdown').length) {
        timeCountDown()
    }

    const searchDropdown = $parents => {
        if (!$parents || !$parents.length) return

        let $resultBody = $parents.find('.js-search-result-body'),
            searchUrl = $parents.data('url'),
            search = $parents.find('.js-input-search').val();

        if (!searchUrl || !search) {
            $resultBody.addClass('hide')
            return
        }

        let params = {
            search: search
        }

        let $categoryForm = $parents.parents('.category-search-form')
        if ($categoryForm && $categoryForm.length) {
            let category = $categoryForm.find('input[name="category"]:checked').val()
            if (!category) return
            params.category = category
        }

        $resultBody.removeClass('hide')
        $resultBody.html(
            '<div class="text-center p-2"><i class="fa-spin fas fa-sync-alt"></i></div>'
        )

        $.ajax({
            url: searchUrl,
            method: 'GET',
            data: params,
            success: res => {
                const data = res.data

                if (data && data.html && data.html.trim() !== '' && res.success) {
                    $resultBody.html(data.html)
                    $resultBody.removeClass('hide')
                } else {
                    $resultBody.addClass('hide')
                }
            },
            error: () => {
                $resultBody.addClass('hide')
                // $resultBody.html(
                //     '<p class="text-center fs-12 p-2 mb-0">Something was wrong. Please try again!</p>'
                // )
            }
        })
    }

    // search dropdown
    $('body').on('click', '.js-search-dropdown .icon-search', function() {
        let $parents = $(this).parents('.js-search-dropdown');
        searchDropdown($parents)
    })
    $('body').on('keyup', '.js-search-dropdown .js-input-search', function() {
        let $parents = $(this).parents('.js-search-dropdown');
        searchDropdown($parents)
    })

    $('body').on('click', '.js-search-dropdown .js-close-search-dropdown', function() {
        $(this).parents('.js-search-dropdown').find('.js-search-result-body').addClass('hide')
    })

    // form need accept alert
    $('body').on('click', '.submit-accept-alert', function (event){
        event.preventDefault();
        let _this = $(this),
            action = _this.data('action') || 'thao tác'

        Swal.fire({
            title: `Xác nhận ${action}`,
            text: `Nhấn đồng ý thì sẽ tiến hành ${action}!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Quay lại',
            confirmButtonText: 'Đồng ý'
        }).then((result) => {
            if (result.isConfirmed) {
                let $form = _this.parents('form');

                if ($form && $form.length)
                    _this.parents('form').trigger('submit')
            } else {
                return false;
            }
        });
    });

    const appendOptions = (element, [...datas], value, text, label, checkDisabled) => {
        if (checkDisabled && !datas.length) {
            element.html(`<option value="selected">${label}</option>`);
            element.prop('disabled', true);
            return;
        }
        element.prop('disabled', false);
        element.empty()
        datas.unshift({
            [text]: label,
        })
        $.each(datas, (i, data) => {
            element.append($('<option>', {
                value: data[value] || '',
                text : data[text] || '',
            }));
        });
    }

    // address auto load load
    async function getDistrict(_this) {
        let province_id = _this.val(),
            $districtProvinceSelect = _this.parents('.group-load-address').find('.district-province'),
            districtPlaceholder = $districtProvinceSelect.data('placeholder') || '-- Chọn Quận/Huyện --'

        $districtProvinceSelect.empty();
        $districtProvinceSelect.append(`<option selected value="">${districtPlaceholder}</option>`);

        if (!province_id) {
            if ($districtProvinceSelect.val())
                $districtProvinceSelect.trigger('change')

            return
        }

        await $.ajax({
            url: '/params/ajax-get-district',
            method: 'GET',
            data: {
                province_id
            },
            success: data => {
                if (data && data['districts']) {
                    appendOptions($districtProvinceSelect, data['districts'], 'id', 'district_name', districtPlaceholder)

                    const oldVal = $districtProvinceSelect.val(),
                          selectedDistrict = $districtProvinceSelect.attr('data-selected');

                    $districtProvinceSelect.val(selectedDistrict)

                    if (oldVal != selectedDistrict)
                        $districtProvinceSelect.trigger('change')
                }
            },
            error: () => {}
        });
    }

    async function getWard(_this) {
        let district_id = _this.val(),
        $wardDistrictSelect = _this.parents('.group-load-address').find('.ward-district'),
        wardPlaceholder = $wardDistrictSelect.data('placeholder') || '-- Chọn Xã/Phường --'

        $wardDistrictSelect.empty();
        $wardDistrictSelect.append(`<option selected value="">${wardPlaceholder}</option>`);

        if (!district_id) {
            if ($wardDistrictSelect.val())
                $wardDistrictSelect.trigger('change')

            return
        };

        await $.ajax({
            url: '/params/ajax-get-ward',
            method: 'GET',
            data: {
                district_id
            },
            success: data => {
                if (data && data['wards']) {
                    appendOptions($wardDistrictSelect, data['wards'], 'id', 'ward_name', wardPlaceholder)
                    const oldVal = $wardDistrictSelect.val(),
                        selectedWard = $wardDistrictSelect.attr('data-selected');

                    $wardDistrictSelect.val(selectedWard)

                    if (oldVal != selectedWard)
                        $wardDistrictSelect.trigger('change')
                }
            },
            error: () => {}
        });
    }

    $('body').on('change', '.district-load-ward', function () {
        getWard($(this))
    });

    $('body').on('change', '.province-load-district', function () {
        // should add parent class group-load-address
        getDistrict($(this))
    });

    // group load paradigm
    $('body').on('change', '.group-load-paradigm .category-load-paradigm', function () {
        getParadigm($(this))
    })

    async function getParadigm(_this) {
        let categoryId = _this.val(),
            $paradigmSelect = _this.parents('.group-load-paradigm').find('.paradigm-category'),
            paradigmPlaceholder = $paradigmSelect.data('placeholder') || 'Chọn mô hình'

        $paradigmSelect.empty();
        $paradigmSelect.append(`<option selected value="">${paradigmPlaceholder}</option>`);

        if (!categoryId) {
            if ($paradigmSelect.val())
                $paradigmSelect.trigger('change')

            return
        }

        await $.ajax({
            url: '/params/ajax-get-child-group',
            method: 'GET',
            dataType: 'JSON',
            data: {
                parent_id: categoryId
            },
            success: data => {
                appendOptions($paradigmSelect, data['child_group'], 'id', 'group_name', paradigmPlaceholder)

                const oldVal = $paradigmSelect.val(),
                    selectedParadigm = $paradigmSelect.attr('data-selected');

                $paradigmSelect.val(selectedParadigm)

                if (oldVal != selectedParadigm)
                    $paradigmSelect.trigger('change')
            },
            error: () => {}
        });
    }

    // end group load paradigm

    // trigger change for select2 old input
    const checkAddressSelect = () => {
        let $provinceDistrict = $('.province-load-district')

        if ($provinceDistrict && $provinceDistrict.length) {
            $provinceDistrict.trigger('change')
            return
        }

        let $districtWard = $('.district-load-ward')

        if ($districtWard && $districtWard.length)
            $districtWard.trigger('change')
    }

    checkAddressSelect()
    //  end address load

    $('.js-clear-form').on('click', function() {
        $(this).parents('form')[0].reset()
    })

    // custom upload file input base64
    const updateFileBoxInput = ($parents, URL = '') => {
        const isBase64 = $parents.hasClass('base-64-input')

        if (URL) {
            $parents.find('.file-input__preview').removeClass('d-none')
        } else {
            $parents.find('.file-input__preview').addClass('d-none')
            $parents.find('.file-input__preview .file-input__preview-input').val(URL)
            $parents.find('.file-input__upload input[type="file"]').val(null)
        }

        $parents.find('.file-input__preview .file-input__preview-image').attr('href', URL)
        $parents.find('.file-input__preview .file-input__preview-image img').attr('src', URL)

        if (isBase64) {
            $parents.find('.file-input__preview .file-input__preview-input').val(URL)
        }
    }

    $('.file-input-group .file-input__upload input[type="file"]').on('change', function (event) {
        let $parents = $(this).parents('.file-input-group');

        if (this.files && this.files[0]) {
            if ($parents.hasClass('base-64-input')) {
                // base64 input preview
                let file = this.files[0],
                    reader = new FileReader();

                reader.readAsDataURL(file);
                reader.onload = function () {
                    updateFileBoxInput($parents, reader.result)
                }
            } else {
                // normal file input with preview
                updateFileBoxInput($parents, URL.createObjectURL(this.files[0]))
            }
        }
    })

    $('.file-input-group .file-input__remove-image').on('click', function (e) {
        e.preventDefault();
        let $parents = $(this).parents('.file-input-group');

        updateFileBoxInput($parents)
    })

    // old should check
    // $('body').on('click', '.map-utilities__list .map-utilities__item', function () {
    //     // $('.tienich .item').not($(this)).removeClass('show');
    //     $(this).toggleClass('active');
    //     $(this).siblings().removeClass('active')

    //     var name = $(this).data('value');
    //     var latitude = $(this).parent('.map-utilities__list').siblings('input[name="latitude"]').val();
    //     var longtitude = $(this).parent('.map-utilities__list').siblings('input[name="longtitude"]').val();
    //     var mapApi = $(this).parent('.map-utilities__list').siblings('input[name="map-api"]').val();
    //     if (!latitude || !longtitude || !mapApi) return

    //     let address = $(this).parent('.map-utilities__list').siblings('input[name="full_address"]').val(),
    //         link = $(this).hasClass('active')
    //             ? `https://www.google.com/maps/embed/v1/search?key=${mapApi}&zoom=15&center=${latitude},${longtitude}&q=${name}+gần+${address}`
    //             : `https://www.google.com/maps/embed/v1/place?key=${mapApi}&zoom=15&q=${latitude},${longtitude}`

    //     $(this).parent('.map-utilities__list').siblings('.mapparent').attr('src', link);
    // });
    // end old should check

    $('body').on('click', '.show-mail__part .show-mail__domain', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var input = $('<input>');
        $('body').append(input);
        let email = $(this).data('pre-part') + '@' + $(this).data('last-part')
        input.val(email).select();
        document.execCommand("copy");
        input.remove();
        toastr.success('Đã copy');
    });

    $('.user-notify__box .js-read-notify').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        let _this = $(this),
            notifyId = _this.data('id'),
            $parents = _this.parents('.user-notify__box');

        if (!notifyId) return;

        $('.c-overlay-loading:not(.inner)').addClass('loading')

        $.ajax({
            url: `/thanh-vien/notifies/${notifyId}/read-notify`,
            method: 'POST',
            success: res => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')

                showMessage(res)

                if (res && res.success) {
                    _this.parents('.user-notify-box__item').remove()

                    let countNotify = parseInt($parents.find('.header-action__badge').text()),
                        $item = $parents.find('.user-notify-box__item');

                    $parents.find('.user-notify-box__count').text(countNotify ? countNotify - 1 : 0)

                    if (!$item || !$item.length) {
                        $parents.find('.dropdown-menu').html(`<span class="dropdown-item text-center">
                            Không có thông báo mới!
                        </span>`)
                    }
                }
            },
            error: () => {
                location.reload();
            }
        })
    })

    $('body').on('click', '.js-focus__like-action .js-focus__toggle-reaction, .js-focus__detail-like-action .js-focus__toggle-reaction', function(e) {
        e.preventDefault();
        let _this = $(this),
            id = _this.data('id'),
            type = _this.data('type') ? 1 : 0;

        if (!id) return;

        _this.prop('disabled', true);
        _this.find('.c-overlay-loading').addClass('loading')

        $.ajax({
            url: `/focus/toggle-reaction`,
            method: 'POST',
            data: {
                id: id,
                type: type
            },
            success: res => {
                const data = res.data
                _this.prop('disabled', false);
                _this.find('.c-overlay-loading').removeClass('loading')
                showMessage(res)

                if (data && res.success) {
                    let isDetail = _this.parent().hasClass('js-focus__detail-like-action')
                    
                    if (!isDetail) {
                        data.toggled
                            ? _this.find('.js-reaction-icon').addClass('active')
                            : _this.find('.js-reaction-icon').removeClass('active')

                        _this.parents('.js-focus__like-action').find('.js-num-likes').text(data.likesCount || 0)
                        _this.parents('.js-focus__like-action').find('.js-num-dislikes').text(data.dislikeCount || 0)
                    } else {
                        data.toggled
                            ? _this.find('.like-icon').removeAttr('class').attr('class', 'like-icon fa fa-check')
                            : _this.find('.like-icon').removeAttr('class').attr('class', 'like-icon fa fa-thumbs-up')
                    }
                }
            },
            error: err => {
                _this.prop('disabled', false);
                _this.find('.c-overlay-loading').removeClass('loading')
                showError(err)
            }
        })
    });

    $('body').on('click', '.js-focus__open-html5lightbox', function () {
        let _this = $(this),
            id = _this.attr('data-id')
        if (!id) return
        $('.c-overlay-loading:not(.inner)').addClass('loading')

        $.ajax({
            url: `/focus/get-description`,
            method: 'GET',
            data: {
                id: id,
            },
            success: res => {
                const data = res.data
                $('.c-overlay-loading:not(.inner)').removeClass('loading')

                if (res.success)
                    openHtml5lightBox(res.data)
            },
            error: () => {
                $('.c-overlay-loading:not(.inner)').removeClass('loading')
            }
        })
    })

    // phone
    $('body').on('click', '.phone .click-show-phone', function (event) {
        event.preventDefault();
        event.stopPropagation();
        let _this = $(this)
            number = _this.data("phone"),
            display = _this.siblings("a").find(".display-phone");

        _this.hide();
        $(display).addClass('click-copy');
        display.html(number);
    });
    $('body').on('click', '.hide-phone', function (event) {
        event.preventDefault();
        event.stopPropagation();
        let _this = $(this)
            number = _this.data("phone"),
            display = _this.siblings("a").find(".display-phone");

        _this.hide();
        $(display).addClass('click-copy');
        display.html(number);
    });
    $('body').on('click', '.display-phone:not(.click-copy)', function (e) {
        e.preventDefault();
    })
    $('body').on('click', '.click-copy', function (e) {
        e.stopPropagation();

        // mobile device make call, so don't need copy
        if ($(window).width() < 767) return

        e.preventDefault();
        var input = $('<input>');
        $('body').append(input);
        var temp = $(this).html();
        input.val(temp).select();
        document.execCommand("copy");
        input.remove();
        toastr.success('Đã copy');
    });
    // end phone

    // old paginate should change
    $('#paginateNumber').on('change', function (e) {
        const searchParams = new URLSearchParams(window.location.search);
        let itemsPerPage = $(this).val(),
            name = $(this).attr('name');

        searchParams.set(name, itemsPerPage);
        window.location.href = window.location.pathname + '?' + searchParams.toString()
    })
}) ()
