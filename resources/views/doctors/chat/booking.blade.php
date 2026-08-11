@extends('includes_admin.mainlayout')
@section('content')

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
                                <a href="{{ route('appointments') }}">@lang('admin.Appointments')</a>
                            </li>
                            <li class="breadcrumb-item active">
                                @lang('admin.chat_with_patient')
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Chat List Sidebar -->
                <div class="col-xl-4 d-flex">
                    <div class="card chat-box-clinic">
                        <div class="chat-widgets">
                            <!-- Current User Profile -->
                            <div class="chat-user-group-head d-flex align-items-center">
                                <div class="img-users call-user">
                                    <a href="#"><img src="{{ auth()->user()->image ?? '/assets/img/user.jpg' }}" alt="img" onerror="this.src='/assets/img/user-02.jpg'"></a>
                                    <span class="active-users"></span>
                                </div>
                                <div class="chat-users user-main">
                                    <div class="user-titles">
                                        <h5>{{ auth()->user()->name }}</h5>
                                        <span class="badge bg-primary">{{  \App\Models\Clinic::app_type_account(auth()->user()->app_type) }}</span>
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

                            <!-- Booking Info -->
                            @if(isset($booking))
                                <div class="booking-info-chat p-3 border-bottom">
                                    <h6 class="mb-2">@lang('admin.booking_info'):</h6>
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="mb-1"><strong>@lang('admin.patient'):</strong>
                                                {{ $booking->user->name ?? 'N/A' }}
                                            </p>
                                            <p class="mb-1"><strong>@lang('admin.appointment_date'):</strong>
                                                {{ $booking->date .' - ' . $booking->appointment ?? 'N/A' }}
                                            </p>
                                            <p class="mb-1"><strong>@lang('admin.status'):</strong>
                                                <span class="badge bg-secondary">
                                            {{ $booking->reservation_status['name_'.app()->getLocale()] ?? 'N/A' }}
                                        </span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Search -->
                            <div class="top-liv-search top-chat-search mt-3">
                                <div class="chat-search">
                                    <div class="form-group me-2 mb-0">
                                        <input type="text" class="form-control"
                                               placeholder="@lang('admin.search_chats')"
                                               id="search_chats">
                                        <a class="btn"><img src="/assets/img/icons/search-normal.svg" alt=""></a>
                                    </div>
                                </div>
                            </div>

                            <!-- Chat List -->
                            <div class="chat-list-container" style="max-height: 500px; overflow-y: auto;">
                                @if(isset($booking) && isset($booking->user))
                                    <!-- Current Booking Chat -->
                                    <div class="chat-user-group d-flex align-items-center active-chat js-message-open"
                                         data-receiver-id="{{ $booking->user->id }}"
                                         data-user-name="{{ $booking->user->name }}"
                                         data-image="{{ $booking->user->image }}"
                                         data-reservation-id="{{ $booking->id }}">
                                        <div class="img-users call-user">
                                            <a href="#">
                                                <img src="{{ $booking->user->image ?? '/assets/img/user-02.jpg' }}" alt="img" onerror="this.src='/assets/img/user-02.jpg'">
                                            </a>
                                            <span class="active-users bg-success"></span>
                                        </div>
                                        <div class="chat-users">
                                            <div class="user-titles d-flex">
                                                <h5>{{ $booking->user->name }}</h5>
                                                @if($unreadCount > 0)
                                                    <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                                                @endif
                                                <div class="chat-user-time ms-auto">
                                                    <p>{{ now()->format('h:i A') }}</p>
                                                </div>
                                            </div>
                                            <div class="user-text d-flex">
                                                <p class="text-muted">
                                                    @if(isset($messages) && $messages->last())
                                                        {{ Str::limit($messages->last()->message, 25) }}
                                                    @else
                                                        @lang('admin.start_conversation')
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Other Chats -->
                                @foreach($data['chat_list'] ?? [] as $chat)
                                    @if(!isset($booking) || $chat->receiver_id != $booking->user->id)
                                        <div class="chat-user-group d-flex align-items-center js-message-open"
                                             data-receiver-id="{{ $chat->receiver_id }}"
                                             data-user-name="{{ $chat->receiver->name }}"
                                             data-image="{{ $chat->receiver->image }}"
                                             data-reservation-id="{{ $chat->reservation_id ?? $booking->id ?? '' }}">
                                            <div class="img-users call-user">
                                                <a href="#"><img src="{{ $chat->receiver->image }}" alt="img"></a>
                                                <span class="active-users bg-info"></span>
                                            </div>
                                            <div class="chat-users">
                                                <div class="user-titles d-flex">
                                                    <h5>{{ $chat->receiver->name }}</h5>
                                                    <div class="chat-user-time ms-auto">
                                                        <p>{{ $chat->created_at->format('h:i A') }}</p>
                                                    </div>
                                                </div>
                                                <div class="user-text d-flex">
                                                    <p>{{ Str::limit($chat->message, 30) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Messages Area -->
                <div class="col-xl-8 {{ isset($booking) ? 'd-block' : 'd-none' }}" id="chat_messages_area">
                    <div class="card chat-box">
                        <div class="chat-search-group">
                            <!-- Chat Header -->
                            <div class="chat-user-group mb-0 d-flex align-items-center">
                                <div class="img-users call-user">
                                    @if(isset($booking) && isset($booking->user))
                                        <a>
                                            <img id="receiver_image"
                                                    src="{{ $booking->user->image ?? '/assets/img/user-02.jpg' }}" onerror="this.src='/assets/img/user-02.jpg'"
                                                 alt="img">
                                        </a>
                                    @else
                                        <a><img id="receiver_image" src="" alt="img"></a>
                                    @endif
                                    <span class="active-users bg-success"></span>
                                </div>
                                <div class="chat-users">
                                    <div class="user-titles">
                                        @if(isset($booking) && isset($booking->user))
                                            <h5 id="receiver_name">{{ $booking->user->name }}</h5>
                                            <p class="text-muted mb-0">@lang('admin.patient')</p>
                                        @else
                                            <h5 id="receiver_name"></h5>
                                        @endif
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <button class="btn btn-sm btn-light" id="back_to_list">
                                        <i class="feather-arrow-left"></i> @lang('admin.back')
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Chat Messages -->
                    <div class="card chat-message-box">
                        <div class="card-body p-0">
                            <div class="chat-body" style="max-height: 500px; overflow-y: auto;" id="chats_container">
                                <ul class="list-unstyled chat-message mb-0" id="chats">
                                    @if(isset($messages) && $messages->count() > 0)
                                        @foreach($messages as $message)
                                            @php
                                                $isMe = $message->sender_id == auth()->id();
                                                $senderName = $isMe ? 'You' : ($message->sender->name ?? 'Unknown');
                                                $senderImage = $isMe ? (auth()->user()->image ?? '/assets/img/user-02.jpg') : (($message->sender->image ?? $booking->user->image) ?? '/assets/img/user-02.jpg');
                                            @endphp
                                            <li class="media d-flex {{ $isMe ? 'sent' : 'received' }}">
                                                @if(!$isMe)
                                                    <div class="avatar flex-shrink-0">
                                                        <img src="{{ $senderImage }}"
                                                             alt="User Image"
                                                             class="avatar-img rounded-circle">
                                                    </div>
                                                @endif
                                                <div class="media-body flex-grow-1">
                                                    <div class="msg-box">
                                                        <div class="message-sub-box">
                                                            @if(!$isMe)
                                                                <h6 class="mb-1">{{ $senderName }}</h6>
                                                            @endif
                                                            @if($message->message)
                                                                <p>{{ $message->message }}</p>
                                                            @endif
                                                            @if($message->file)
                                                                <div class="mt-2">
                                                                    <a href="{{ $message->file }}" target="_blank"
                                                                       class="btn btn-sm btn-outline-primary">
                                                                        <i class="feather-paperclip"></i>
                                                                        @lang('admin.view_file')
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            <small class="text-muted d-block mt-1">
                                                                {{ $message->created_at->format('h:i A | d/m/Y') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @elseif(isset($booking))
                                        <div class="text-center py-5">
                                            <i class="feather-message-square display-4 text-muted"></i>
                                            <h5 class="mt-3">@lang('admin.no_messages_yet')</h5>
                                            <p class="text-muted">@lang('admin.start_conversation_with_patient')</p>
                                        </div>
                                    @endif
                                </ul>
                            </div>

                            <!-- Message Input -->
                            @if(isset($booking))
                                <div class="chat-footer-box">
                                    <div class="discussion-sent">
                                        <form id="message_form" onsubmit="return false;">
                                            @csrf
                                            <input type="hidden" id="receiver_id"
                                                   name="receiver_id"
                                                   value="{{ $booking->user->id ?? '' }}">
                                            <input type="hidden" id="reservation_id"
                                                   name="reservation_id"
                                                   value="{{ $booking->id ?? '' }}">
                                            <div class="row gx-2">
                                                <div class="col-lg-12">
                                                    <div class="footer-discussion">
                                                        <div class="inputgroups">
                                                            <input type="text"
                                                                   name="message"
                                                                   id="message_input"
                                                                   placeholder="@lang('admin.write_message_to_patient')"
                                                                   autocomplete="off">

                                                            <!-- File Upload -->
                                                            <div class="position-icon">
                                                                <input type="file"
                                                                       id="file_upload"
                                                                       name="file"
                                                                       style="display: none;"
                                                                       accept="image/*">
                                                                <a href="javascript:;"
                                                                   onclick="document.getElementById('file_upload').click()">
                                                                    <img src="/assets/img/icons/chat-foot-icon-02.svg"
                                                                         alt="Upload File">
                                                                </a>
                                                            </div>

                                                            <!-- Send Button -->
                                                            <div class="send-chat position-icon comman-flex">
                                                                <button type="submit"
                                                                        class="btn btn-primary"
                                                                        id="send_button">
                                                                    <img src="/assets/img/icons/chat-foot-icon-03.svg"
                                                                         alt="Send">
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted" id="file_name"></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            let isLoading = false;
            let chatRefreshInterval = null;
            let lastMessageId = 0;

            function showChatArea() {
                $('#chat_messages_area').removeClass('d-none').addClass('d-block');
            }

            function scrollToBottom() {
                setTimeout(function() {
                    let container = $('#chats_container');
                    if (container.length) {
                        container.scrollTop(container[0].scrollHeight);
                    }
                }, 100);
            }

            function appendMessage(message, type) {

                if (!message || $('#message-' + message.id).length) return;

                lastMessageId = message.id;

                let date = new Date(message.created_at);
                let formattedDate = date.toLocaleString();

                let messageText = message.message ? message.message.replace(/\n/g, '<br>') : '';
                let senderName = message.sender?.name ?? 'Unknown';
                let senderImage = message.sender?.image ?? '/assets/img/default-user.png';

                let html = '';

                if (type === 'received') {
                    html = `
                <li class="media d-flex received" id="message-${message.id}">
                    <div class="avatar flex-shrink-0">
                        <img src="${senderImage}" class="avatar-img rounded-circle">
                    </div>
                    <div class="media-body flex-grow-1">
                        <div class="msg-box">
                            <div class="message-sub-box">
                                <h6>${senderName}</h6>
                                ${messageText ? `<p>${messageText}</p>` : ''}
                                ${message.file ? `
                                    <a href="${message.file}" target="_blank"
                                       class="btn btn-sm btn-outline-primary mt-2">
                                       عرض الملف
                                    </a>` : ''}
                                <small class="text-muted d-block mt-1">${formattedDate}</small>
                            </div>
                        </div>
                    </div>
                </li>`;
                } else {
                    html = `
                <li class="media d-flex sent" id="message-${message.id}">
                    <div class="media-body flex-grow-1">
                        <div class="msg-box">
                            <div class="message-sub-box">
                                ${messageText ? `<p>${messageText}</p>` : ''}
                                ${message.file ? `
                                    <a href="${message.file}" target="_blank"
                                       class="btn btn-sm btn-outline-light mt-2">
                                       عرض الملف
                                    </a>` : ''}
                                <small class="text-light d-block mt-1">${formattedDate}</small>
                            </div>
                        </div>
                    </div>
                </li>`;
                }

                $('#chats').append(html);
                scrollToBottom();
            }

            function loadChat(receiverId, reservationId = null, firstLoad = false) {

                if (isLoading) return;
                isLoading = true;

                let data = {
                    receiver_id: receiverId,
                    last_id: lastMessageId,
                    _token: '{{ csrf_token() }}'
                };

                if (reservationId) {
                    data.reservation_id = reservationId;
                }

                if (firstLoad && $('#chats li').length === 0) {
                    $('#chats').html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            `);
                }

                $.ajax({
                    url: "{{ route('load-chat') }}",
                    method: "GET",
                    data: data,
                    success: function(response) {

                        if (response.success && response.chatMessages.length > 0) {

                            if (firstLoad) {
                                $('#chats').empty();
                            }

                            $.each(response.chatMessages, function(index, message) {
                                let type = (message.sender_id == {{ auth()->id() }}) ? 'sent' : 'received';
                                appendMessage(message, type);
                            });
                        }

                    },
                    complete: function() {
                        isLoading = false;
                    }
                });
            }

            function startAutoRefresh() {

                if (chatRefreshInterval) {
                    clearInterval(chatRefreshInterval);
                }

                chatRefreshInterval = setInterval(function() {

                    let receiverId = $('#receiver_id').val();
                    let reservationId = $('#reservation_id').val();

                    if (receiverId && reservationId) {
                        loadChat(receiverId, reservationId, false);
                    }

                }, 5000); // كل 5 ثواني
            }

            // فتح شات من القائمة
            $(document).on('click', '.js-message-open', function() {

                let receiverId = $(this).data('receiver-id');
                let reservationId = $(this).data('reservation-id');
                let image = $(this).data('image');
                let name = $(this).data('user-name');

                $('#receiver_id').val(receiverId);
                $('#reservation_id').val(reservationId);
                $('#receiver_name').text(name);
                $('#receiver_image').attr('src', image);

                lastMessageId = 0;
                $('#chats').empty();

                showChatArea();
                loadChat(receiverId, reservationId, true);
                startAutoRefresh();
            });

            // إرسال رسالة
            $('#message_form').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('sendMessage') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {

                        if (response.success && response.message) {
                            appendMessage(response.message, 'sent');
                            $('#message_input').val('');
                            $('#file_upload').val('');
                            $('#file_name').text('');
                        }
                    }
                });
            });

            // عرض اسم الملف
            $('#file_upload').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $('#file_name').text(fileName ? 'الملف: ' + fileName : '');
            });

            // تشغيل الريفريش
            startAutoRefresh();

        });
    </script>

    <style>
        .active-chat {
            background-color: #f0f7ff;
            border-left: 3px solid #007bff;
        }

        .chat-user-group {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #eee;
            transition: all 0.3s;
        }

        .chat-user-group:hover {
            background-color: #f8f9fa;
        }

        .chat-list-container {
            scrollbar-width: thin;
            max-height: 500px;
            overflow-y: auto;
        }

        .booking-info-chat {
            background-color: #f8f9fa;
            border-radius: 8px;
            margin: 10px;
        }

        .sent .msg-box {
            background-color: #007bff;
            color: white;
            border-radius: 18px 18px 0 18px;
            max-width: 70%;
            margin-left: auto;
            margin-right: 10px;
        }

        .received .msg-box {
            background-color: #f1f3f4;
            color: #333;
            border-radius: 18px 18px 18px 0;
            max-width: 70%;
            margin-right: auto;
            margin-left: 10px;
        }

        .msg-box {
            padding: 10px 15px;
            margin: 5px 0;
            display: inline-block;
        }

        .message-sub-box h6 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        #chats_container {
            max-height: 500px;
            overflow-y: auto;
            padding: 15px;
        }

        #message_input {
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 10px 15px;
            width: 100%;
        }

        #send_button {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #file_name {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }
    </style>
@endsection
