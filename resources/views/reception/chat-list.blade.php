@extends('includes_admin.mainlayout')

@section('styles')
    <style>
        .chat-page {
            padding-bottom: 24px;
        }

        .chat-layout {
            display: grid;
            grid-template-columns: 360px minmax(0, 1fr);
            gap: 22px;
        }

        .chat-sidebar,
        .chat-panel {
            border: 0;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
            background: #fff;
        }

        .chat-sidebar-head {
            padding: 24px;
            background: linear-gradient(135deg, #517bf2 0%, #517bf2 45%, #517bf2 100%);
            color: #fff;
        }

        .chat-sidebar-head h4 {
            margin: 0 0 6px;
            color: #fff;
            font-size: 24px;
            font-weight: 700;
        }

        .chat-sidebar-head p {
            margin: 0;
            color: rgba(255, 255, 255, 0.82);
            font-size: 13px;
        }

        .chat-current-user {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 18px;
            padding: 14px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.1);
        }

        .chat-current-user img,
        .chat-item-avatar img,
        .chat-panel-user img {
            width: 52px;
            height: 52px;
            object-fit: cover;
            border-radius: 50%;
        }

        .chat-current-user h6,
        .chat-panel-user h5 {
            margin: 0;
            color: #fff;
            font-weight: 700;
        }

        .chat-current-user span,
        .chat-panel-user p {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.72);
        }

        .chat-sidebar-body {
            padding: 18px;
            background: linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        }

        .chat-search-box {
            position: relative;
            margin-bottom: 16px;
        }

        .chat-search-box .form-control {
            height: 48px;
            padding-inline-start: 44px;
            border-radius: 14px;
            border: 1px solid #dbe4ef;
            background: #fff;
        }

        .chat-search-box i {
            position: absolute;
            top: 50%;
            inset-inline-start: 16px;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .chat-list {
            max-height: 620px;
            overflow-y: auto;
            padding-inline-end: 4px;
        }

        .chat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 14px;
            margin-bottom: 10px;
            border: 1px solid #e6edf5;
            border-radius: 18px;
            background: #fff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .chat-item:hover,
        .chat-item.is-active {
            border-color: #517bf2;
            background: #f0fdfa;
            box-shadow: 0 10px 24px rgba(15, 118, 110, 0.08);
        }

        .chat-item-body {
            min-width: 0;
            flex: 1;
        }

        .chat-item-top,
        .chat-item-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .chat-item-top h6,
        .chat-item-bottom p {
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chat-item-top h6 {
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
        }

        .chat-item-bottom p,
        .chat-reservation-label {
            color: #64748b;
            font-size: 12px;
        }

        .chat-time {
            flex-shrink: 0;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
        }

        .chat-badge {
            flex-shrink: 0;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ef4444;
            color: #fff;
            font-size: 11px;
            font-weight: 700;
        }

        .chat-panel-empty {
            min-height: 760px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px;
            background: radial-gradient(circle at top, #ecfeff 0%, #ffffff 58%);
        }

        .chat-panel-empty i {
            font-size: 42px;
            color: #517bf2;
        }

        .chat-panel-empty h5 {
            margin: 14px 0 8px;
            color: #0f172a;
            font-weight: 700;
        }

        .chat-panel-empty p {
            margin: 0;
            color: #64748b;
        }

        .chat-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 24px;
            border-bottom: 1px solid #edf2f7;
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }

        .chat-panel-user {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .chat-panel-user h5 {
            color: #0f172a;
        }

        .chat-panel-user p {
            margin: 2px 0 0;
            color: #64748b;
        }

        .chat-thread {
            height: 540px;
            overflow-y: auto;
            padding: 24px;
            background:
                radial-gradient(circle at top right, rgba(153, 246, 228, 0.18), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, #ffffff 100%);
        }

        .chat-thread-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .chat-message-item {
            display: flex;
            margin-bottom: 16px;
        }

        .chat-message-item.is-sent {
            justify-content: flex-end;
        }

        .chat-message-row {
            display: flex;
            align-items: flex-end;
            gap: 10px;
            max-width: min(78%, 720px);
        }

        .chat-message-item.is-sent .chat-message-row {
            flex-direction: row-reverse;
        }

        .chat-message-row img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .chat-bubble {
            padding: 12px 15px;
            border-radius: 18px 18px 18px 6px;
            background: #fff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.04);
        }

        .chat-message-item.is-sent .chat-bubble {
            color: #fff;
            background: linear-gradient(135deg, #517bf2 0%, #517bf2 100%);
            border-color: transparent;
            border-radius: 18px 18px 6px 18px;
            box-shadow: 0 10px 20px rgba(20, 184, 166, 0.22);
        }

        .chat-bubble h6,
        .chat-bubble p,
        .chat-bubble small {
            margin: 0;
        }

        .chat-bubble h6 {
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
        }

        .chat-message-item.is-sent .chat-bubble h6 {
            color: rgba(255, 255, 255, 0.92);
        }

        .chat-bubble p {
            font-size: 14px;
            line-height: 1.8;
            word-break: break-word;
        }

        .chat-bubble small {
            display: block;
            margin-top: 7px;
            color: #64748b;
            font-size: 11px;
        }

        .chat-message-item.is-sent .chat-bubble small {
            color: rgba(255, 255, 255, 0.78);
        }

        .chat-file-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            padding: 8px 10px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.82);
            color: #0f172a;
            font-size: 12px;
            font-weight: 700;
        }

        .chat-message-item.is-sent .chat-file-link {
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }

        .chat-panel-footer {
            padding: 18px 24px 22px;
            border-top: 1px solid #edf2f7;
            background: #fff;
        }

        .chat-composer {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            padding: 12px;
            border: 1px solid #dbe4ef;
            border-radius: 18px;
            background: #f8fafc;
        }

        .chat-composer textarea {
            flex: 1;
            min-height: 52px;
            max-height: 160px;
            border: 0;
            background: transparent;
            resize: none;
            outline: none;
            padding: 8px 4px;
            color: #0f172a;
        }

        .chat-composer-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .chat-file-trigger,
        .chat-send-btn {
            width: 46px;
            height: 46px;
            border: 0;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .chat-file-trigger {
            background: #e2e8f0;
            color: #334155;
        }

        .chat-send-btn {
            background: linear-gradient(135deg, #517bf2 0%, #517bf2 100%);
            color: #fff;
            box-shadow: 0 10px 18px rgba(20, 184, 166, 0.24);
        }

        .chat-file-name {
            display: block;
            margin-top: 8px;
            color: #64748b;
            font-size: 12px;
        }

        .chat-loading,
        .chat-no-messages {
            text-align: center;
            color: #64748b;
            padding: 22px 0;
        }

        @media (max-width: 1199.98px) {
            .chat-layout {
                grid-template-columns: 1fr;
            }

            .chat-panel-empty,
            .chat-thread {
                min-height: unset;
                height: 480px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-wrapper chat-page">
        <div class="content">
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

            <div class="chat-layout">
                <div class="card chat-sidebar">
                    <div class="chat-sidebar-head">
                        <h4>@lang('admin.chat_list')</h4>
                        <p>@lang('admin.chat_list_hint')</p>

                        <div class="chat-current-user">
                            <img src="{{ auth()->user()->image }}" alt="img">
                            <div>
                                <h6>{{ auth()->user()->name }}</h6>
                                <span>@lang('admin.reception.reception')</span>
                            </div>
                        </div>
                    </div>

                    <div class="chat-sidebar-body">
                        <div class="chat-search-box">
                            <i class="feather-search"></i>
                            <input type="text" class="form-control" id="chatSearchInput" placeholder="@lang('admin.search_chats')">
                        </div>

                        <div class="chat-list" id="chatConversationList">
                            @forelse($chatList as $chat)
                                <button type="button"
                                        class="chat-item js-chat-open"
                                        data-receiver-id="{{ $chat['user']->id }}"
                                        data-reservation-id="{{ $chat['reservation_id'] }}"
                                        data-user-name="{{ $chat['user']->name }}"
                                        data-image="{{ $chat['user']->image ?? '/assets/img/user-02.jpg' }}"
                                        data-preview="{{ $chat['last_message']->message ?: trans('admin.file_attachment') }}"
                                        data-time="{{ $chat['last_message']->created_at->format('Y-m-d H:i:s') }}">
                                    <div class="chat-item-avatar">
                                        <img src="{{ $chat['user']->image ?? '/assets/img/user-02.jpg' }}" alt="{{ $chat['user']->name }}" onerror="this.src='/assets/img/user-02.jpg'">
                                    </div>

                                    <div class="chat-item-body">
                                        <div class="chat-item-top">
                                            <h6>{{ $chat['user']->name }}</h6>
                                            <span class="chat-time">{{ $chat['last_message']->created_at->format('h:i A') }}</span>
                                        </div>

                                        <div class="chat-item-bottom">
                                            <p>{{ \Illuminate\Support\Str::limit($chat['last_message']->message ?: trans('admin.file_attachment'), 34) }}</p>
                                            @if($chat['unread_count'] > 0)
                                                <span class="chat-badge">{{ $chat['unread_count'] }}</span>
                                            @endif
                                        </div>

                                        <div class="chat-reservation-label">
                                            @lang('admin.reservation') #{{ $chat['reservation_id'] ?? '-' }}
                                        </div>
                                    </div>
                                </button>
                            @empty
                                <div class="chat-no-messages">@lang('admin.no_conversations')</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="card chat-panel" id="chatPanel">
                    <div class="chat-panel-empty" id="chatEmptyState">
                        <div>
                            <i class="feather-message-square"></i>
                            <h5>@lang('admin.select_chat_title')</h5>
                            <p>@lang('admin.select_chat_hint')</p>
                        </div>
                    </div>

                    <div id="chatActiveState" style="display:none;">
                        <div class="chat-panel-header">
                            <div class="chat-panel-user">
                                <img id="chatReceiverImage" src="" alt="img">
                                <div>
                                    <h5 id="chatReceiverName"></h5>
                                    <p id="chatReservationMeta"></p>
                                </div>
                            </div>
                        </div>

                        <div class="chat-thread" id="chatsContainer">
                            <ul class="chat-thread-list" id="chats"></ul>
                        </div>

                        <div class="chat-panel-footer">
                            <form id="messageForm">
                                @csrf
                                <input type="hidden" name="receiver_id" id="receiver_id">
                                <input type="hidden" name="reservation_id" id="reservation_id">
                                <input type="file" name="file" id="file_upload" accept="image/*" hidden>

                                <div class="chat-composer">
                                    <textarea name="message" id="message_input" placeholder="@lang('admin.write_message')" rows="1"></textarea>

                                    <div class="chat-composer-actions">
{{--                                        <button type="button" class="chat-file-trigger" id="chatFileTrigger">--}}
{{--                                            <i class="feather-paperclip"></i>--}}
{{--                                        </button>--}}
                                        <button type="submit" class="chat-send-btn" id="send_button">
                                            <i class="feather-send"></i>
                                        </button>
                                    </div>
                                </div>

                                <span class="chat-file-name" id="file_name"></span>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('/admin/js/jquery-3.2.1.min.js') }}"></script>
    <script>
        $(function () {
            var activeReceiverId = null;
            var activeReservationId = null;
            var lastMessageId = 0;
            var refreshTimer = null;
            var isLoading = false;

            function formatChatDate(dateStr) {
                var date = new Date(dateStr);
                return new Intl.DateTimeFormat(document.documentElement.lang === 'ar' ? 'ar-EG' : 'en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                }).format(date);
            }

            function scrollChatToBottom() {
                var container = $('#chatsContainer');
                if (container.length) {
                    container.scrollTop(container[0].scrollHeight);
                }
            }

            function escapeHtml(text) {
                return $('<div>').text(text || '').html();
            }

            function renderMessageItem(message, isSent) {
                if (!message || $('#message-' + message.id).length) {
                    return '';
                }

                lastMessageId = Math.max(lastMessageId, Number(message.id || 0));

                var senderName = message.sender && message.sender.name ? message.sender.name : '';
                var senderImage = message.sender && message.sender.image ? message.sender.image : '/assets/img/user-02.jpg';
                var messageHtml = message.message ? '<p>' + escapeHtml(message.message).replace(/\n/g, '<br>') + '</p>' : '';
                var fileHtml = message.file
                    ? (message.file.match(/\.(jpeg|jpg|gif|png|webp|bmp)/i)
                        ? '<img src="' + message.file + '" style="max-width:200px;border-radius:8px;display:block;margin-top:8px" alt="image">'
                        : '<a href="' + message.file + '" target="_blank" class="chat-file-link"><i class="feather-paperclip"></i><span>{{ trans('admin.view_file') }}</span></a>')
                    : '';

                return `
                    <li class="chat-message-item ${isSent ? 'is-sent' : ''}" id="message-${message.id}">
                        <div class="chat-message-row">
                            ${isSent ? '' : '<img src="' + senderImage + '" alt="' + escapeHtml(senderName) + '">'}
                            <div class="chat-bubble">
                                ${isSent ? '' : '<h6>' + escapeHtml(senderName) + '</h6>'}
                                ${messageHtml}
                                ${fileHtml}
                                <small>${formatChatDate(message.created_at)}</small>
                            </div>
                        </div>
                    </li>
                `;
            }

            function setActiveState(userName, image, reservationId) {
                $('#chatEmptyState').hide();
                $('#chatActiveState').show();
                $('#chatReceiverName').text(userName || '');
                $('#chatReceiverImage').attr('src', image || '');
                $('#chatReservationMeta').text('{{ trans('admin.reservation') }} #' + (reservationId || '-'));
            }

            function loadChat(firstLoad) {
                if (!activeReceiverId || !activeReservationId || isLoading) {
                    return;
                }

                isLoading = true;

                if (firstLoad) {
                    $('#chats').html('<li class="chat-loading">{{ trans('admin.loading') }}</li>');
                }

                $.ajax({
                    url: '{{ route('load-chat') }}',
                    method: 'GET',
                    dataType: 'json',
                    data: {
                        receiver_id: activeReceiverId,
                        reservation_id: activeReservationId,
                        last_id: firstLoad ? 0 : lastMessageId
                    },
                    success: function (res) {
                        if (!res.success) {
                            return;
                        }

                        if (firstLoad) {
                            $('#chats').empty();
                            lastMessageId = 0;
                        }

                        if (res.chatMessages && res.chatMessages.length) {
                            $.each(res.chatMessages, function (_, message) {
                                var html = renderMessageItem(message, Number(message.sender_id) === Number({{ auth()->id() }}));
                                if (html) {
                                    $('#chats').append(html);
                                }
                            });
                            scrollChatToBottom();
                        } else if (firstLoad) {
                            $('#chats').html('<li class="chat-no-messages">{{ trans('admin.no_messages_yet') }}</li>');
                        }
                    },
                    error: function () {
                        if (firstLoad) {
                            $('#chats').html('<li class="chat-no-messages text-danger">{{ trans('admin.error_loading_data') }}</li>');
                        }
                    },
                    complete: function () {
                        isLoading = false;
                    }
                });
            }

            function startRefresh() {
                if (refreshTimer) {
                    clearInterval(refreshTimer);
                }

                refreshTimer = setInterval(function () {
                    loadChat(false);
                }, 5000);
            }

            $(document).on('click', '.js-chat-open', function () {
                var $item = $(this);

                $('.js-chat-open').removeClass('is-active');
                $item.addClass('is-active');

                activeReceiverId = $item.data('receiver-id');
                activeReservationId = $item.data('reservation-id');
                lastMessageId = 0;

                $('#receiver_id').val(activeReceiverId);
                $('#reservation_id').val(activeReservationId);
                $('#message_input').val('');
                $('#file_upload').val('');
                $('#file_name').text('');

                setActiveState($item.data('user-name'), $item.data('image'), activeReservationId);
                loadChat(true);
                startRefresh();
            });

            var isSendingMessage = false;

            $('#messageForm').on('submit', function (event) {
                event.preventDefault();

                if (!activeReceiverId || !activeReservationId || isSendingMessage) {
                    return;
                }

                var formData = new FormData(this);
                isSendingMessage = true;
                $('#send_button').prop('disabled', true);

                $.ajax({
                    url: '{{ route('sendMessage') }}',
                    method: 'POST',
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    data: formData,
                    success: function (res) {
                        if (!res.success || !res.message) {
                            return;
                        }

                        $('#chats .chat-no-messages').remove();

                        var html = renderMessageItem(res.message, true);
                        if (html) {
                            $('#chats').append(html);
                            scrollChatToBottom();
                        }

                        $('#message_input').val('');
                        $('#file_upload').val('');
                        $('#file_name').text('');
                    },
                    complete: function () {
                        isSendingMessage = false;
                        $('#send_button').prop('disabled', false);
                    }
                });
            });

            $('#chatFileTrigger').on('click', function () {
                $('#file_upload').trigger('click');
            });

            $('#file_upload').on('change', function () {
                var fileName = ($(this).val() || '').split('\\').pop();
                $('#file_name').text(fileName || '');
            });

            $('#chatSearchInput').on('input', function () {
                var value = ($(this).val() || '').toLowerCase().trim();

                $('.js-chat-open').each(function () {
                    var text = $(this).text().toLowerCase();
                    $(this).toggle(text.indexOf(value) !== -1);
                });
            });
        });
    </script>
@endsection
