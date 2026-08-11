@extends('includes_admin.mainlayout')
@section('content')
    @php($defaultChatImage = asset('media/logo.png'))
    @php($authImage = auth()->user()->getRawOriginal('image') ? auth()->user()->image : $defaultChatImage)

    <div class="page-wrapper">
        <div class="content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">@lang('admin.dashboard')</a>
                            </li>
                            <li class="breadcrumb-item">
                                <i class="feather-chevron-right"></i>
                            </li>
                            <li class="breadcrumb-item active">@lang('admin.chat_list')</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-4 d-flex">
                    <div class="card chat-box-clinic">
                        <div class="chat-widgets">
                            <div class="chat-user-group-head d-flex align-items-center">
                                <div class="img-users call-user">
                                    <a href="#">
                                        <img src="{{ $authImage }}" alt="img" onerror="this.onerror=null;this.src='{{ $defaultChatImage }}';">
                                    </a>
                                    <span class="active-users"></span>
                                </div>

                                <div class="chat-users user-main">
                                    <div class="user-titles">
                                        <h5>{{ auth()->user()->name }}</h5>
                                        <div class="chat-user-time"></div>
                                    </div>

                                    <div class="drop-item chat-menu user-dot-list">
                                        <a href="#" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="feather-more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{ route('profile') }}">
                                                <i class="feather-user me-2 text-primary"></i>
                                                @lang('admin.edit_profile')
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="top-liv-search top-chat-search">
                                <div class="chat-search">
                                    <div class="form-group me-2 mb-0">
                                        <input type="text" class="form-control" placeholder="Search here">
                                        <a class="btn">
                                            <img src="/assets/img/icons/search-normal.svg" alt="">
                                        </a>
                                    </div>
                                    <div class="add-search">
                                        <a href="javascript:;"><i class="feather-plus"></i></a>
                                    </div>
                                </div>
                            </div>

                            @foreach($data['chat_list'] as $chat)
                                @php($receiverImage = ($chat->receiver && $chat->receiver->getRawOriginal('image')) ? $chat->receiver->image : $defaultChatImage)
                                <div class="chat-user-group d-flex align-items-center js-message-open"
                                     id="chat-{{ $chat->id }}"
                                     receiver_id="{{ $chat->receiver_id }}"
                                     reservation_id="{{ $chat->reservation_id }}"
                                     user_name="{{ $chat->receiver->name ?? '' }}"
                                     image="{{ $receiverImage }}">
                                    <div class="img-users call-user">
                                        <a href="#">
                                            <img src="{{ $receiverImage }}" alt="img" onerror="this.onerror=null;this.src='{{ $defaultChatImage }}';">
                                        </a>
                                        <span class="active-users bg-info"></span>
                                    </div>

                                    <div class="chat-users">
                                        <div class="user-titles d-flex">
                                            <h5>{{ $chat->receiver->name ?? '' }}</h5>
                                            <div class="chat-user-time">
                                                <p>{{ $chat->created_at ? $chat->created_at->format('h:i A') : '' }}</p>
                                            </div>
                                        </div>

                                        <div class="user-text d-flex">
                                            <p>{{ $chat->message }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <div class="col-xl-8" style="display: none" id="chat_list">
                    <div class="card chat-box">
                        <div class="chat-search-group">
                            <div class="chat-user-group mb-0 d-flex align-items-center">
                                <div class="img-users call-user">
                                    <a>
                                        <img id="image" src="{{ $defaultChatImage }}" alt="img" onerror="this.onerror=null;this.src='{{ $defaultChatImage }}';">
                                    </a>
                                    <span class="active-users bg-info"></span>
                                </div>
                                <div class="chat-users">
                                    <div class="user-titles">
                                        <h5 id="receiver"></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat -->
                    <div class="card chat-message-box">
                        <div class="card-body p-0">
                            <div class="chat-body">
                                <ul class="list-unstyled chat-message" id="chats"></ul>
                            </div>

                            <div class="chat-footer-box">
                                <div class="discussion-sent">
                                    <form class="need-validation chatsmessages">
                                        @csrf

                                        <div class="row gx-2">
                                            <div class="col-lg-12">
                                                <div class="footer-discussion">
                                                    <input type="hidden" id="receiver_id" name="receiver_id">
                                                    <input type="hidden" id="reservation_id" name="reservation_id">

                                                    <div class="inputgroups">
                                                        <input type="text"
                                                               name="message"
                                                               id="message"
                                                               required
                                                               placeholder="@lang('admin.write_message')">

                                                        <div class="micro-text position-icon">
                                                            <img src="/assets/img/icons/chat-foot-icon-04.svg" alt="">
                                                        </div>

                                                        <div class="send-chat position-icon comman-flex">
                                                            <button type="submit" class="btn btn-primary" id="submitButton">
                                                                <img src="/assets/img/icons/chat-foot-icon-03.svg" alt="">
                                                            </button>
                                                        </div>

                                                        <div class="symple-text position-icon">
                                                            <ul>
                                                                <li>
                                                                    <a href="javascript:;">
                                                                        <img src="/assets/img/icons/chat-foot-icon-01.svg" class="me-2" alt="">
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="javascript:;">
                                                                        <img src="/assets/img/icons/chat-foot-icon-02.svg" alt="">
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Chat -->
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/admin/js/jquery-3.2.1.min.js') }}"></script>

    <script>
        const defaultChatImage = @json($defaultChatImage);

        function chatImage(src) {
            if (!src || src.indexOf('/media/user.png') !== -1) {
                return defaultChatImage;
            }

            return src;
        }

        function formatChatDate(dateStr) {
            var date = new Date(dateStr);
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'short',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            }).format(date);
        }

        function scrollChatToBottom() {
            let chatBody = $('.chat-body');
            if (chatBody.length) {
                chatBody.scrollTop(chatBody[0].scrollHeight);
            }
        }

        function buildMessageItem(value, res) {
            let formattedDate = formatChatDate(value.created_at);
            let isSent = Number(value.sender_id) === Number(res.sender_id);
            let senderName = value.sender && value.sender.name ? value.sender.name : '';
            let senderImage = chatImage(value.sender && value.sender.image ? value.sender.image : '');
            let html = '';

            if (!isSent && value.message) {
                html = `
                    <li class="media d-flex received" data-id="${value.id}">
                        <div class="avatar flex-shrink-0">
                            <img src="${senderImage}" alt="User Image" class="avatar-img rounded-circle" onerror="this.onerror=null;this.src='${defaultChatImage}';">
                        </div>
                        <div class="media-body flex-grow-1">
                            <div class="msg-box">
                                <div class="message-sub-box">
                                    <h4>${senderName}</h4>
                                    <p>${value.message}</p>
                                    <span>${formattedDate}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                `;
            } else if (isSent && value.message) {
                html = `
                    <li class="media d-flex sent" data-id="${value.id}">
                        <div class="media-body flex-grow-1">
                            <div class="msg-box">
                                <div class="message-sub-box">
                                    <p>${value.message}</p>
                                    <span>${formattedDate}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                `;
            } else if (!isSent && value.file) {
                var isImage = value.file.match(/\.(jpeg|jpg|gif|png|webp|bmp)/i);
                html = `
                    <li class="media d-flex received" data-id="${value.id}">
                        <div class="avatar flex-shrink-0">
                            <img src="${senderImage}" alt="User Image" class="avatar-img rounded-circle" onerror="this.onerror=null;this.src='${defaultChatImage}';">
                        </div>
                        <div class="media-body flex-grow-1">
                            <div class="msg-box">
                                <div class="message-sub-box">
                                    <h4>${senderName}</h4>
                                    <ul class="msg-sub-list">
                                        <li>
                                            ${isImage ? '<img src="' + value.file + '" style="max-width:200px;border-radius:8px" alt="image">' : '<a href="' + value.file + '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="feather-paperclip"></i> عرض الملف</a>'}
                                        </li>
                                    </ul>
                                    <span>${formattedDate}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                `;
            } else if (isSent && value.file) {
                var isImage = value.file.match(/\.(jpeg|jpg|gif|png|webp|bmp)/i);
                html = `
                    <li class="media d-flex sent" data-id="${value.id}">
                        <div class="media-body flex-grow-1">
                            <div class="msg-box">
                                <div class="message-sub-box">
                                    <ul class="msg-sub-list">
                                        <li>
                                            ${isImage ? '<img src="' + value.file + '" style="max-width:200px;border-radius:8px" alt="image">' : '<a href="' + value.file + '" target="_blank" class="btn btn-sm btn-outline-light"><i class="feather-paperclip"></i> عرض الملف</a>'}
                                        </li>
                                    </ul>
                                    <span>${formattedDate}</span>
                                </div>
                            </div>
                        </div>
                    </li>
                `;
            }

            return html;
        }

        $(document).off('click', '.js-message-open').on('click', '.js-message-open', function (e) {
            e.preventDefault();

            var user_id = $(this).attr('receiver_id');
            var reservation_id = $(this).attr('reservation_id');
            var image = $(this).attr('image');
            var user_name = $(this).attr('user_name');

            $('#receiver').text(user_name);
            $('#image').attr('src', chatImage(image));
            $('#receiver_id').val(user_id);
            $('#reservation_id').val(reservation_id);

            $('#chat_list').show();
            $('#chats').html('<li class="text-center p-3">Loading...</li>');

            $.ajax({
                url: '{{ route('load-chat') }}',
                method: 'GET',
                data: {
                    receiver_id: user_id,
                    reservation_id: reservation_id
                },
                dataType: 'json',
                global: false,
                success: function (res) {
                    let html = '';

                    $('#chats').empty();

                    if (res.chatMessages && res.chatMessages.length > 0) {
                        $.each(res.chatMessages, function (key, value) {
                            html += buildMessageItem(value, res);
                        });

                        $('#chats').html(html);
                    } else {
                        $('#chats').html('<li class="text-center p-3">لا توجد رسائل</li>');
                    }

                    scrollChatToBottom();
                },
                error: function (xhr) {
                    $('#chats').html('<li class="text-center text-danger p-3">حدث خطأ أثناء تحميل الرسائل</li>');
                    console.log(xhr);
                }
            });

            $('.popup-chat-responsive').toggleClass('open-chat');
            return false;
        });

        $(document).off('submit', '.chatsmessages').on('submit', '.chatsmessages', function (event) {
            event.preventDefault();

            var form = this;
            var formData = new FormData(form);

            $.ajax({
                url: "{{ route('sendMessage') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: formData,
                dataType: 'json',
                global: false,
                success: function (data) {
                    let message = data.message || data;
                    let formattedDate = formatChatDate(message.created_at);

                    let newMessage = `
                        <li class="media d-flex sent" data-id="${message.id ?? ''}">
                            <div class="media-body flex-grow-1">
                                <div class="msg-box">
                                    <div class="message-sub-box">
                                        <p>${message.message ?? ''}</p>
                                        <span>${formattedDate}</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    `;

                    form.reset();
                    $('#chats').append(newMessage);
                    scrollChatToBottom();
                },
                error: function (data) {
                    sweetAlertErrorResponse(data);
                }
            });
        });
    </script>

@endsection
