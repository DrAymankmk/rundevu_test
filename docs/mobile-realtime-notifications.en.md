# User App Real-Time Notifications (Pusher)

## Pusher settings

| | |
|---|---|
| Key | `14d6cce7cb3f4cd9125c` |
| Cluster | `eu` |
| Channel type | Public (no auth required) |
| Event name | `notify` (same for all notifications) |

## Channels

| Channel | Purpose |
|---|---|
| `user-notify.{user_id}` | Notifications for one specific user (by their id) |
| `users-notify` | General notifications for all users |

**After login, the app must subscribe to both channels and stay subscribed while the app is open**, not only on the notifications screen.
On logout, unsubscribe from both channels.

## Notifications

| # | Notification | Channel | Event | `type` | `title_en` |
|---|---|---|---|---|---|
| 1 | Main admin sends a notification to users | `users-notify` | `notify` | `0` | (whatever the admin writes) |
| 2 | New offer added | `users-notify` | `notify` | `0` | `New offer` |
| 3 | Offer expires within 48 hours | `users-notify` | `notify` | `0` | `Offer expiry alert` |
| 4 | Booking cancelled by reception | `user-notify.{user_id}` | `notify` | `1` | `Booking cancelled` |
| 5 | Booking completed (request a rating) | `user-notify.{user_id}` | `notify` | `1` | `Rate your booking` |
| 6 | Clinic manager replied to a complaint | `user-notify.{user_id}` | `notify` | `1` | `Reply from clinic manager` |
| 7 | Main admin replied to a complaint | `user-notify.{user_id}` | `notify` | `1` | `Reply from main admin` |

- `type = 0`: general notification for all users.
- `type = 1`: notification for this user only.
- For case 5 (`Rate your booking`), tapping the notification should open the booking rating screen.

## Payload

```json
{
  "data": {
    "id": 125,
    "type": 1,
    "app_type": 1,
    "user_id": 46,
    "clinic_id": 3,
    "title_ar": "تم إلغاء الحجز",
    "title_en": "Booking cancelled",
    "message_ar": "تم إلغاء الحجز رقم 55",
    "message_en": "Booking #55 has been cancelled.",
    "image": "https://.../media/clinics/user.png",
    "created_at": "2026-10-01 11:22:48",
    "play_sound": true,
    "sound": "default"
  },
  "socket": null
}
```

| Field | Description |
|---|---|
| `id` | Notification id in the database |
| `type` | `0` general / `1` user-specific |
| `user_id` | Receiving user (`null` for general notifications) |
| `clinic_id` | Related clinic (may be `null`) |
| `title_ar` / `title_en` | Title |
| `message_ar` / `message_en` | Notification text |
| `image` | Notification image |
| `created_at` | Notification time |
| `play_sound` | Always `true`: play the notification sound |

## Sound

1. **App in foreground (Pusher):** when the `notify` event arrives, show a local notification with sound (e.g. `flutter_local_notifications`) and update the notifications badge.
2. **App in background or closed (Firebase FCM):** the same notification is also sent through FCM with `sound: default`.
   - On Android, create a Notification Channel with id **`default`** and high importance (`Importance.high`), otherwise no sound will play.
   - The user's `firebase_token` must be registered on the server.

## Flutter example (`pusher_channels_flutter`)

```dart
final pusher = PusherChannelsFlutter.getInstance();

await pusher.init(
  apiKey: '14d6cce7cb3f4cd9125c',
  cluster: 'eu',
  onEvent: (PusherEvent event) {
    if (event.eventName != 'notify') return;
    final data = jsonDecode(event.data)['data'];
    // Show a local notification with sound + update the badge
    showLocalNotification(
      title: data['title_en'],
      body: data['message_en'],
      payload: event.data,
    );
  },
);

await pusher.subscribe(channelName: 'user-notify.$userId');
await pusher.subscribe(channelName: 'users-notify');
await pusher.connect();
```
