@auth
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
    <script>
        (function () {
            if (window.__takafolRealtimeNotificationsReady) {
                return;
            }

            window.__takafolRealtimeNotificationsReady = true;

            var pusherKey = @json(config('broadcasting.connections.pusher.key'));
            var pusherCluster = @json(config('broadcasting.connections.pusher.options.cluster'));

            if (!pusherKey || typeof Pusher === 'undefined') {
                return;
            }

            var audioContext = null;
            var lastNotificationKey = null;
            var lastNotificationAt = 0;

            function getAudioContext() {
                var AudioContextClass = window.AudioContext || window.webkitAudioContext;

                if (!AudioContextClass) {
                    return null;
                }

                if (!audioContext) {
                    audioContext = new AudioContextClass();
                }

                if (audioContext.state === 'suspended') {
                    audioContext.resume().catch(function () {});
                }

                return audioContext;
            }

            function unlockAudio() {
                getAudioContext();
                document.removeEventListener('click', unlockAudio);
                document.removeEventListener('keydown', unlockAudio);
                document.removeEventListener('touchstart', unlockAudio);
            }

            document.addEventListener('click', unlockAudio, { once: true });
            document.addEventListener('keydown', unlockAudio, { once: true });
            document.addEventListener('touchstart', unlockAudio, { once: true });

            window.playAdminNotificationSound = function () {
                var context = getAudioContext();

                if (!context) {
                    return;
                }

                var now = context.currentTime;
                var notes = [
                    { frequency: 740, start: 0, duration: 0.12 },
                    { frequency: 990, start: 0.13, duration: 0.12 },
                    { frequency: 1320, start: 0.27, duration: 0.18 }
                ];

                notes.forEach(function (note) {
                    var oscillator = context.createOscillator();
                    var gain = context.createGain();
                    var startAt = now + note.start;
                    var stopAt = startAt + note.duration;

                    oscillator.type = 'triangle';
                    oscillator.frequency.setValueAtTime(note.frequency, startAt);

                    gain.gain.setValueAtTime(0.0001, startAt);
                    gain.gain.exponentialRampToValueAtTime(0.22, startAt + 0.015);
                    gain.gain.exponentialRampToValueAtTime(0.0001, stopAt);

                    oscillator.connect(gain);
                    gain.connect(context.destination);
                    oscillator.start(startAt);
                    oscillator.stop(stopAt + 0.02);
                });
            };

            function getMessage(data, fallback) {
                if (!data) {
                    return fallback;
                }

                if (typeof data === 'string') {
                    return data;
                }

                if (data.message && typeof data.message === 'string') {
                    return data.message;
                }

                if (data.data) {
                    if (typeof data.data === 'string') {
                        return data.data;
                    }

                    if (data.data.message_ar || data.data.message_en) {
                        return data.data.message_ar || data.data.message_en;
                    }

                    if (data.data.message) {
                        return data.data.message;
                    }

                    if (data.data.title_ar || data.data.title_en) {
                        return data.data.title_ar || data.data.title_en;
                    }

                    if (data.data.title) {
                        return data.data.title;
                    }
                }

                if (data.message && data.message.message) {
                    return data.message.message;
                }

                return fallback;
            }

            function getNotificationKey(data, message) {
                if (data && data.data && data.data.group_key) {
                    return 'group-' + data.data.group_key;
                }

                if (data && data.data && data.data.id) {
                    return 'notification-' + data.data.id;
                }

                if (data && data.message && data.message.id) {
                    return 'message-' + data.message.id;
                }

                return message;
            }

            function isChatBroadcastMessage(data) {
                return data
                    && data.message
                    && data.message.sender_type !== undefined
                    && data.message.reservation_id !== undefined;
            }

            function showRealtimeNotification(message, data) {
                if (isChatBroadcastMessage(data)) {
                    return;
                }

                var now = Date.now();
                var key = getNotificationKey(data, message);

                if (key === lastNotificationKey && now - lastNotificationAt < 1500) {
                    return;
                }

                lastNotificationKey = key;
                lastNotificationAt = now;

                window.playAdminNotificationSound();

                if (window.Noty) {
                    new Noty({
                        type: 'info',
                        layout: 'topRight',
                        text: message,
                        timeout: 5000,
                        killer: false
                    }).show();
                } else if (window.toastr) {
                    toastr.info(message);
                }
            }

            var pusher = new Pusher(pusherKey, {
                cluster: pusherCluster || 'eu',
                forceTLS: true
            });

            var channels = [
                {
                    name: 'takafoul-notify',
                    events: ['notify', 'App\\Events\\Notify'],
                    fallback: @json(app()->getLocale() === 'ar' ? 'إشعار جديد' : 'New notification')
                },
                {
                    name: 'admin-notifications',
                    events: ['new-message', 'reservation.chat.message', 'App\\Events\\ReservationChatMessageEvent'],
                    fallback: @json(app()->getLocale() === 'ar' ? 'رسالة شات جديدة' : 'New chat message')
                },
                {
                    name: 'status-liked',
                    events: ['App\\Events\\StatusLiked'],
                    fallback: @json(app()->getLocale() === 'ar' ? 'إشعار جديد' : 'New notification')
                }
            ];

            channels.forEach(function (channelConfig) {
                var channel = pusher.subscribe(channelConfig.name);

                channelConfig.events.forEach(function (eventName) {
                    channel.bind(eventName, function (data) {
                        showRealtimeNotification(getMessage(data, channelConfig.fallback), data);
                    });
                });
            });
        })();
    </script>
@endauth
