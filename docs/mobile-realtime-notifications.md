# الإشعارات الفورية لتطبيق المستخدم (Pusher)

## إعدادات Pusher

| | |
|---|---|
| Key | `14d6cce7cb3f4cd9125c` |
| Cluster | `eu` |
| نوع القنوات | Public (لا تحتاج auth) |
| اسم الـ Event | `notify` (لكل الإشعارات) |

## القنوات

| القناة | الاستخدام |
|---|---|
| `user-notify.{user_id}` | إشعارات خاصة بالمستخدم (بالـ id بتاعه) |
| `users-notify` | إشعارات عامة لكل المستخدمين |

**التطبيق لازم يشترك في القناتين مع بعض بعد الـ login، ويفضل مشترك طول ما التطبيق مفتوح** (مش في شاشة الإشعارات بس).
عند الـ logout يعمل unsubscribe من القناتين.

## الإشعارات

| # | الإشعار | Channel | Event | `type` | `title_en` |
|---|---|---|---|---|---|
| 1 | إشعار من الأدمن الرئيسي للمستخدمين | `users-notify` | `notify` | `0` | (حسب ما يكتبه الأدمن) |
| 2 | إضافة عرض جديد | `users-notify` | `notify` | `0` | `New offer` |
| 3 | قبل انتهاء العرض بـ 48 ساعة | `users-notify` | `notify` | `0` | `Offer expiry alert` |
| 4 | إلغاء الحجز من الاستقبال | `user-notify.{user_id}` | `notify` | `1` | `Booking cancelled` |
| 5 | إنهاء الحجز (طلب تقييم) | `user-notify.{user_id}` | `notify` | `1` | `Rate your booking` |
| 6 | رد مدير العيادة على شكوى | `user-notify.{user_id}` | `notify` | `1` | `Reply from clinic manager` |
| 7 | رد الأدمن الرئيسي على شكوى | `user-notify.{user_id}` | `notify` | `1` | `Reply from main admin` |

- `type = 0`: إشعار عام لكل المستخدمين.
- `type = 1`: إشعار خاص بالمستخدم.
- في الحالة 5 (`Rate your booking`) يُفضّل فتح شاشة تقييم الحجز عند الضغط على الإشعار.

## شكل البيانات (Payload)

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

| الحقل | الوصف |
|---|---|
| `id` | رقم الإشعار في قاعدة البيانات |
| `type` | `0` عام / `1` خاص بالمستخدم |
| `user_id` | المستخدم المستلم (`null` في الإشعارات العامة) |
| `clinic_id` | العيادة المرتبطة بالإشعار (ممكن `null`) |
| `title_ar` / `title_en` | العنوان |
| `message_ar` / `message_en` | نص الإشعار |
| `image` | صورة الإشعار |
| `created_at` | وقت الإشعار |
| `play_sound` | دايماً `true`: شغّل صوت الإشعار |

## الصوت

1. **التطبيق مفتوح (Pusher):** عند وصول event `notify` اعرض local notification بصوت (مثلاً `flutter_local_notifications`) وحدّث عداد الإشعارات.
2. **التطبيق مقفول أو في الخلفية (Firebase FCM):** نفس الإشعار بيتبعت كمان عن طريق FCM بـ `sound: default`.
   - على Android لازم تعمل Notification Channel بالـ id ده: **`default`** وبـ importance عالي (`Importance.high`)، وإلا الصوت مش هيشتغل.
   - لازم الـ `firebase_token` بتاع المستخدم يكون متسجل على السيرفر.

## مثال Flutter (`pusher_channels_flutter`)

```dart
final pusher = PusherChannelsFlutter.getInstance();

await pusher.init(
  apiKey: '14d6cce7cb3f4cd9125c',
  cluster: 'eu',
  onEvent: (PusherEvent event) {
    if (event.eventName != 'notify') return;
    final data = jsonDecode(event.data)['data'];
    // اعرض local notification بصوت + حدّث العداد
    showLocalNotification(
      title: data['title_ar'],
      body: data['message_ar'],
      payload: event.data,
    );
  },
);

await pusher.subscribe(channelName: 'user-notify.$userId');
await pusher.subscribe(channelName: 'users-notify');
await pusher.connect();
```
