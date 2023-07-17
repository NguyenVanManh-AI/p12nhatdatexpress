import Echo from "laravel-echo"
window.io = require('socket.io-client');

// const socket = io();

if (typeof io !== 'undefined') {
    window.Echo = new Echo({
        broadcaster: 'socket.io',
        host: SOCKET_HOST_URL,
    });
}

jQuery(function () {
    $('body').on('click', '.active-chat-box .send-message-box .input-icon.attach .icon', function (e) {
        e.preventDefault();
        $(this).parent().find('input[type="file').trigger('click')
    });

    let __typingTimeout,
        __channelToken = USER_CHANNEL_TOKEN,
        __isActionAtAdmin = $('#chat-boxes').hasClass('support__chat-boxes'),
        __prefix = __isActionAtAdmin ? '/admin' : '/thanh-vien',
        __scrollShowJumpButton = 200, // scroll up 200px show jump to last button
        __pageTitle = $(document).attr('title'),
        __activeConversationChannels = [],
        __loadingMoreMessage = false;

    const scrollBottom = $boxChat => {
        $boxChat.animate({ scrollTop: $boxChat.find('.list-message').height() }, 1000);
    }

    const newConversation = (receiverId, isSupport) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${__prefix}/conversations/new-conversation`,
                method: 'POST',
                data: {
                    user_chat_id: receiverId,
                    is_support: isSupport
                },
                success: res => resolve(res),
                error: err => reject(err)
            })
        })
    }

    const openConversation = token => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${__prefix}/conversations/open-conversation`,
                method: 'POST',
                data: {
                    token: token,
                },
                success: res => resolve(res),
                error: err => reject(err)
            })
        })
    }

    const readConversation = token => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${__prefix}/conversations/${token}/read-conversation`,
                method: 'POST',
                success: res => resolve(res),
                error: err => reject(err)
            })
        })
    }

    const moreMessages = (token, page) => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${__prefix}/conversations/${token}/get-messages`,
                method: 'GET',
                data: {
                    page: page,
                },
                success: res => resolve(res),
                error: err => reject(err)
            })
        })
    }

    const chatOpened = (res, token) => {
        let $chatBoxes = $('#chat-boxes')
        $chatBoxes.attr('data-token', token)

        appendBoxChat(res)
        echoEvent()
    }

    const closeChat = token => {
        return new Promise((resolve, reject) => {
            $.ajax({
                url: `${__prefix}/conversations/close-conversation`,
                method: 'GET',
                success: res => resolve(res),
                error: err => reject(err)
            })
        })
    }

    const echoEvent = () => {
        let timeOut = 0,
            $chatBoxes = $('#chat-boxes'),
            $showMessageBox = $chatBoxes.find('.show-messages-box'),
            token = $showMessageBox.attr('data-token'),
            lastReadId = parseInt($('.show-messages-box input[name="user_last_read_id"]').val())

        if (!token) return;

        if ($.inArray(token, __activeConversationChannels) === -1) {
            __activeConversationChannels.push(token)

            // echo conversation listener chatpage
            window.Echo.private(`conversation.${token}`)
                .listen('ConversationMessagePushed', (args) => {
                    pushMessage(args)
                    // showUnreadMessage()
                })
                .listenForWhisper('typing', (e) => {
                    let $boxChat = $chatBoxes.find(`.show-messages-box[data-token="${e.conversation.token}"]`)

                    if (!$boxChat.length || __channelToken == e.user.token) return;

                    let $boxTyping = $chatBoxes.find(`.active-chat-box__typing`);

                    if (e.isTyping == true) {
                        $boxTyping.removeClass('d-none')
                        // scrollBottom($boxChat)

                        clearTimeout(__typingTimeout)

                        __typingTimeout = setTimeout(function () {
                            $boxTyping.addClass('d-none')
                        }, 10000)
                    } else {
                        $boxTyping.addClass('d-none')
                    }
                })
                .listenForWhisper('seen', (e) => {
                    let $boxChat = $chatBoxes.find(`.show-messages-box[data-token="${e.conversation.token}"]`)
                    if (!$boxChat.length || __channelToken == e.user.token) return;

                    if (e.isSeen == true) {
                        let $selfLastRead = $boxChat.find(`.box-message.self[data-id="${e.conversation.userLastReadId}"]`)

                        if ($selfLastRead && $selfLastRead.length) {
                            $boxChat.find('.message-seen').remove()
                            $selfLastRead.append(`
                                <div class="message-seen">
                                    Đã xem <i class="fas fa-check"></i>
                                </div>
                            `)
                        }
                    }
                });

            timeOut = 500
        }

        // conversation seen event
        setTimeout(() => {
            window.Echo.private(`conversation.${token}`)
                .whisper('seen', {
                    user: {
                        token: __channelToken
                    },
                    conversation: {
                        token: token,
                        userLastReadId: lastReadId
                    },
                    isSeen: true
                });
        }, timeOut)
    }

    const appendBoxChat = html => {
        let $chatBoxes = $('#chat-boxes')
        if (!html) {
            $chatBoxes.empty().removeClass('opened minimum')
            return
        }

        $chatBoxes.html(html).removeClass('minimum').addClass('opened')

        let $boxChat = $chatBoxes.find('.show-messages-box');
        if ($boxChat && $boxChat.length) {
            scrollBottom($boxChat)
            $boxChat.find('.send-message-box .send-message').trigger('focus')
        }

        showUnreadMessage()
    }

    // html
    const boxMessageHtml = (message, nextMessage = null, extraClass, lastReadId = null) => {
        let messageHTML = `<div class="box-message ${extraClass || ''}" data-id="${message.id || ''}">`

        if (message.sender.avatar
            && message.sender_channel_token !== __channelToken
            && (!nextMessage || nextMessage.sender_channel_token !== message.sender_channel_token)
        ) {
            messageHTML += `<div class="avatar">
                <img src="${message.sender.avatar}" alt="">
            </div>`
        }

        messageHTML += `<div class="content" data-time="${message.time || ''}">`
        messageHTML += `<div class="message">${message.message || ''}</div>
          ${lastReadId && lastReadId == message.id ? '<div class="message-seen">Đã xem <i class="fas fa-check"></i></div>' : ''}`

        messageHTML += `</div></div>`

        return messageHTML;
    }
    // end html

    // echo message
    const showUnreadMessage = () => {
        $.ajax({
            url: `${__prefix}/chat-boxes/get-unread-messages`,
            method: 'GET',
            success: (res) => {
                if (res && res.data && res.data.unread) {
                    document.title = `(${res.data.unread}) ${__pageTitle}`;
                    $('.chat-box__show-unread-message').removeClass('d-none')
                    $('.chat-box__show-unread-message').text(`${res.data.unread}`)
                } else {
                    document.title = __pageTitle
                    $('.chat-box__show-unread-message').addClass('d-none')
                    $('.chat-box__show-unread-message').text('0')
                }
            }
        })
    }

    // makeMessageLinkClick = () => {
    //     $('.show-messages-box .box-message .message:not(.no-link)').each(function(){
    //       let regex = /(((https?:\/\/(w\.)?)|www\.)[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*))/ig
    //       $(this).html($(this).html().replace(/(\&amp\;)+/g, '&').replace(regex, "<a href='$1' rel='nofollow noopener noreferrer' target='_blank' >$1</a>"));
    //       $(this).addClass('no-link')
    //     });
    // }

    const pushMessage = args => {
        let message = args.message
        if (!message) return;

        let $chatBoxes = $('#chat-boxes'),
            chatOpened = $chatBoxes.hasClass('opened'),
            extraClass = __channelToken == message.sender_channel_token ? 'self' : '',
            token = message.conversation_token,
            $boxMessage = $chatBoxes.find(`.show-messages-box[data-token="${token}"]`),
            $pendingMessage = $chatBoxes.find(`.list-message .box-message[data-unique-id="${message.unique_id}"]`);

        // remove pending message
        if ($pendingMessage && $pendingMessage.length)
            $pendingMessage.remove();

        $boxMessage.find(`.list-message`).append(`
          <div class="box-message ${extraClass}" data-id="${message.id}">
            <div class="avatar" data-title="${message.sender.name}">
              <img src="${message.sender.avatar}" alt="">
            </div>
            <div class="content" data-time="${message.time}">
              <div class="message">${message.message}</div>
            </div>
          </div>
        `);

        $chatBoxes.find('.active-chat-box__typing:not(d-none)').addClass('d-none')

        if (chatOpened) {
            readConversation(token)
                .then(res => {
                    if (res && res.data && res.data.last_read_id) {
                        const lastReadId = res.data.last_read_id;
                        $('.show-messages-box input[name="user_last_read_id"]').val(lastReadId)

                        window.Echo.private(`conversation.${token}`)
                            .whisper('seen', {
                                user: {
                                    token: __channelToken
                                },
                                conversation: {
                                    token: token,
                                    userLastReadId: lastReadId
                                },
                                isSeen: true
                            });
                    }

                })
        }

        // maybe just scroll when self send message not receiver
        if ($pendingMessage && $pendingMessage.length)
            scrollBottom($boxMessage)
    }

    window.Echo.private(`user.${__channelToken}`)
        .listen('UserConversationPushed', () => {
            // pushMessage(args)
            showUnreadMessage()
        })

    $('body').on('keyup', '.send-message-box .send-message', (e) => {
        if (e.keyCode !== 13) {
            let $parents = $(e.target).parents('.show-messages-box'),
                token = $parents.attr('data-token');

            window.Echo.private(`conversation.${token}`)
                .whisper('typing', {
                    user: {
                        token: __channelToken
                    },
                    conversation: {
                        token: token,
                    },
                    isTyping: e.target.value ? true : false
                });
        }
    })

    // end echo message

    // start chat
    $('.care-staff__chat-action .care-staff__btn-chat').on('click', function (evt) {
        evt.preventDefault();
        let _this = $(this),
            receiverId = _this.attr('data-receiver-id'),
            isSupport = _this.attr('data-is-support');

        newConversation(receiverId, isSupport)
            .then(res => {
                if (res && res.success && res.data) {
                    const conversation = res.data.conversation;

                    if (conversation) {
                        const token = conversation.token;

                        openConversation(token)
                            .then(res => {
                                chatOpened(res, token)
                            })
                    } else if (res.data.html) {
                        const html = res.data.html;
                        // append empty chat for user
                        let $chatBoxes = $('#chat-boxes')
                        $chatBoxes.attr('data-token', '')
                        $chatBoxes.html(html).removeClass('minimum').addClass('opened')
                    }
                }
            })
    })

    const sendMessage = ($parents, message) => {
        if (!message) return;
        let conversationToken = $parents.attr('data-token'),
            receiverId = $parents.attr('data-receiver-id'),
            isSupport = $parents.attr('data-is-support'),
            $chatTypeBox = $parents.find('.active-chat__chat-type'),
            chatType = null,
            uniqueId = Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15);

        if ($chatTypeBox && $chatTypeBox.length) {
            chatType = $chatTypeBox.find('[name="chat_type"]:checked').val()

            if (!chatType) {
                $chatTypeBox.find('.active-chat__chat-type-button').addClass('is-flicking')
                setTimeout(function () {
                    $parents.find('.active-chat__chat-type-button').removeClass('is-flicking')
                }, 800)
                return
            }
        }

        let $messageInput = $parents.find('.send-message-box .send-message')
        if ($messageInput && $messageInput.length)
            $messageInput.val('')

        $parents.find('.list-message').append(`
            <div class="box-message self" data-id="" data-unique-id="${uniqueId}">
                <div class="content" data-time="">
                <div class="message">${message}</div>
                </div>
            </div>`)

        $.ajax({
            url: `${__prefix}/conversations/send-conversation-message`,
            method: 'POST',
            data: {
                message: message,
                conversation_token: conversationToken,
                user_chat_id: receiverId,
                is_support: isSupport,
                chat_type: chatType,
                unique_id: uniqueId
            },
            success: (res) => {
                if (res && res.success && res.data && res.data.message) {
                    const message = res.data.message

                    if ($chatTypeBox && $chatTypeBox.length) {
                        $chatTypeBox.remove()
                        $parents.find('.active-chat__rating-box .active-chat__rating .rating-stars-action').removeClass('disable-rating')

                        const resToken = message.conversation_token
                        if (resToken)
                            $parents.attr('data-token', resToken)
                    }
                }

                showMessage(res)
            },
            error: err => {
                showError(err)
            }
        })
    }

    // start user chat
    // send message
    $('body').on('keyup', '.support-popup-chat-box .send-message-box .send-message', (e) => {
        if (e.keyCode == 13 && e.target.value) {
            let $parents = $(e.target).parents('.show-messages-box'),
                message = e.target.value;

            if (!message) return;

            sendMessage($parents, message)
        }
    })

    $('body').on('click', '.support-popup-chat-box .send-message-box .input-icon.send-icon', (e) => {
        let $parents = $(e.target).parents('.show-messages-box'),
            $inputMessage = $parents.find('.send-message-box .send-message'),
            message = $inputMessage.val();

        if (!message) return;
        sendMessage($parents, message)
    })

    // rating
    $('body').on('change', '.active-chat-box .active-chat__rating-box .active-chat__rating input[name="rating"]', function (event) {
        event.preventDefault();
        let rating = parseInt($(this).val());

        if (!rating) {
            toastr.error('vui lòng chọn đánh giá.')
            return
        }

        let _this = $(this),
            token = _this.parents('.show-messages-box').attr('data-token'),
            $ratingParents = $(this).parents('.active-chat__rating-box');

        if (!token) return

        $ratingParents.find('.c-overlay-loading.inner').addClass('loading')

        $.ajax({
            url: `${__prefix}/conversations/${token}/rating`,
            type: 'POST',
            data: {
                rating: rating
            },
            success: res => {
                $ratingParents.find('.c-overlay-loading.inner').removeClass('loading')
                showMessage(res)
            },
            error: err => {
                $ratingParents.find('.c-overlay-loading.inner').removeClass('loading')
                showError(err)
            }
        });
    })

    $('body').on('change', '#chat-boxes .active-chat__chat-type [name="chat_type"]', function (e) {
        let $parents = $(this).parents('#chat-boxes'),
            defaultContent = $(this).attr('data-default'),
            $inputMessage = $parents.find('.send-message-box .send-message');

        if (defaultContent)
            $inputMessage.val(defaultContent)
    })

    // end user chat

    // admin support chat
    // start chat
    $('body').on('click', '.user__open-conversation, .support__open-conversation', function (evt) {
        evt.preventDefault();
        let _this = $(this),
            token = _this.attr('data-token');

        if (!token) return

        openConversation(token)
            .then(res => {
                chatOpened(res, token)
            })
    })
    $('body').on('click', '#chat-boxes.minimum', function (evt) {
        evt.preventDefault();
        let _this = $(this)

        if (_this.find('.active-chat-box') && _this.find('.active-chat-box').length) {
            _this.removeClass('minimum').addClass('opened')
        } else {
            let token = _this.attr('data-token');
            if (!token) return;

            openConversation(token)
                .then(res => {
                    chatOpened(res, token)
                })
        }
    })

    // end admin support chat

    // common chat
    $('body').on('click', '.js-close-chat', function (evt) {
        evt.preventDefault();
  
        $(this).parents('#chat-boxes').removeClass('opened')
        closeChat()
    })

    $('body').on('click', '.js-minimum-chat', function (evt) {
        evt.preventDefault();

        $(this).parents('#chat-boxes').addClass('minimum')
    })

    document.addEventListener('scroll', function (event) {
        if ($(event.target).hasClass('show-messages-box')) {
            let pos = $(event.target).scrollTop();

            // show jump button
            if (pos + $(event.target).innerHeight() + __scrollShowJumpButton >= $(event.target)[0].scrollHeight) {
                $(event.target).find('.jump-to-last').addClass('d-none')
            } else {
                $(event.target).find('.jump-to-last').removeClass('d-none')
            }

            if (pos == 0) {
                let _this = $(event.target),
                    token = _this.attr('data-token'),
                    page = parseInt(_this.attr('data-current-page')) + 1;

                if (!token || __loadingMoreMessage) return

                __loadingMoreMessage = true
                _this.find('.active-chat-box__load-more-messages').addClass('loading')

                moreMessages(token, page)
                    .then(res => {
                        if (res.success && res.data && res.data.messages) {
                            let firstMsg = _this.find('.list-message .box-message:first'),
                                curOffset = firstMsg.offset().top - _this.scrollTop();

                            for (let i = 0; i < res.data.messages.length; i++) {
                                const message = res.data.messages[i],
                                      nextMessage = res.data.messages[i - 1] // check nextMessage if it already has will wrong avatar

                                let extraClass = __channelToken == message.sender_channel_token ? 'self' : '';

                                if (!_this.find(`.list-message .box-message[data-id="${message.id}"]`).length) {
                                    _this.find(`.list-message`).prepend(boxMessageHtml(message, nextMessage, extraClass))
                                }
                            }

                            // makeMessageLinkClick()
                            _this.attr('data-current-page', page)
                            _this.scrollTop(firstMsg.offset().top-curOffset);
                        } else {
                            _this.attr('data-current-page', '')
                        }
                    })
                    .finally(() => {
                        __loadingMoreMessage = false
                        _this.find('.active-chat-box__load-more-messages').removeClass('loading')
                    })
            }
        }
    }, true);

    // jump to last message
    $('body').on('click', '.show-messages-box .jump-to-last', function (event) {
        let $boxChat = $(event.target).parents('.show-messages-box');

        scrollBottom($boxChat)
    })
    // end common chat
})
