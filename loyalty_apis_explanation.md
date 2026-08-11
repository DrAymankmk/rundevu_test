# شرح APIs نظام نقاط الولاء

الكلاس:

```php
class LoyaltyController extends APIController
```

مسؤول عن APIs نظام نقاط الولاء للمستخدم داخل التطبيق.

كل الـ APIs موجودة تحت:

```http
/api/user/loyalty
```

وكلها تحتاج توكن المستخدم في الهيدر:

```http
Authorization: Bearer USER_JWT_TOKEN
```

واللغة اختيارية:

```http
lang: ar
```

أو:

```http
lang: en
```

## 1. Wallet

```http
GET /api/user/loyalty/wallet
```

هذه الـ API ترجع محفظة نقاط المستخدم.

تقوم بالآتي:

- تتأكد أن المستخدم مسجل دخول عن طريق `jwt_token`.
- تجيب كل معاملات النقاط الخاصة بالمستخدم من جدول `loyalty_point_transactions`.
- تحسب الرصيد الحالي عن طريق `LoyaltyPointsService::balance`.
- تجيب قواعد النقاط المفعلة من جدول `loyalty_point_rules`.

مثال للرد:

```json
{
  "status": 200,
  "message": "Loyalty points",
  "data": {
    "balance": 50,
    "rules": [],
    "transactions": {}
  }
}
```

معنى البيانات:

- `balance`: إجمالي النقاط الصالحة للمستخدم.
- `rules`: أسباب كسب النقاط مثل `welcome`, `rating`, `share`.
- `transactions`: سجل عمليات النقاط مثل كسب أو صرف نقاط.

## 2. Rewards

```http
GET /api/user/loyalty/rewards
```

هذه الـ API تعرض متجر المكافآت أو الكوبونات المتاحة.

تقوم بالآتي:

- تتأكد أن المستخدم مسجل دخول.
- تجيب الكوبونات المفعلة فقط.
- تجيب الكوبونات التي لم تنته صلاحيتها.
- إذا تم إرسال `clinic_id`، ترجع كوبونات عيادة معينة فقط.

مثال:

```http
GET /api/user/loyalty/rewards?clinic_id=5
```

مثال للرد:

```json
{
  "status": 200,
  "message": "Rewards store",
  "data": {
    "balance": 120,
    "coupons": {}
  }
}
```

## 3. Redeem

```http
POST /api/user/loyalty/redeem
```

هذه الـ API تستخدم عندما يريد المستخدم استبدال نقاطه بكوبون.

Body:

```json
{
  "coupon_id": 3
}
```

تقوم بالآتي:

- تتأكد أن المستخدم مسجل دخول.
- تبحث عن الكوبون بشرط أن يكون `status = 1`.
- تستدعي:

```php
$this->pointsService->redeem($user, $coupon);
```

داخل `LoyaltyPointsService::redeem` يحدث الآتي:

- يتأكد أن الكوبون متاح وغير منتهي.
- يتأكد من حد الاستخدام `usage_limit`.
- يحسب تكلفة الكوبون بالنقاط حسب `PointsExchange`.
- يتأكد أن رصيد المستخدم يكفي.
- ينشئ record في جدول `loyalty_coupon_redemptions`.
- ينشئ transaction بنقاط سالبة في جدول `loyalty_point_transactions`.

مثال: إذا كان الكوبون يحتاج 100 نقطة، يتم إنشاء transaction مثل:

```php
'type' => 'spend',
'points' => -100
```

مثال للرد:

```json
{
  "status": 200,
  "message": "Coupon redeemed",
  "data": {
    "redemption": {},
    "balance": 20
  }
}
```

## 4. Redemptions

```http
GET /api/user/loyalty/redemptions
```

هذه الـ API تعرض الكوبونات التي قام المستخدم باستبدالها.

تقرأ البيانات من جدول:

```text
loyalty_coupon_redemptions
```

وتحمل معها بيانات:

- الكوبون `coupon`
- العيادة `clinic`

الرد يكون paginated.

## 5. Share

```http
POST /api/user/loyalty/share
```

هذه الـ API تستخدم لتسجيل أن المستخدم قام بعمل share لشيء داخل التطبيق، وبعدها يحصل على نقاط مشاركة.

Body مثال:

```json
{
  "clinic_id": 5,
  "shareable_type": "App\\Models\\Clinic",
  "shareable_id": 5
}
```

تقوم بالآتي:

- تتأكد أن المستخدم مسجل دخول.
- تنشئ log في جدول `loyalty_share_logs`.
- تمنح المستخدم نقاط rule اسمها `share`.

في migration قاعدة `share` قيمتها 5 نقاط وحدها اليومي:

```php
'max_per_day' => 2
```

يعني المستخدم يستطيع أخذ نقاط مشاركة مرتين فقط في اليوم.

## الخلاصة

الـ controller يحتوي على 5 APIs:

```text
GET  /wallet       عرض الرصيد وسجل النقاط
GET  /rewards      عرض الكوبونات المتاحة
POST /redeem       استبدال النقاط بكوبون
GET  /redemptions  عرض كوبونات المستخدم
POST /share        تسجيل مشاركة وإضافة نقاط
```

## ملاحظة مهمة

`redeem` و `share` حاليًا لا يوجد عليهم validation request واضح.

الأفضل إضافة validation لهم، لأن إرسال `coupon_id` أو `clinic_id` بقيم غير صحيحة قد يؤدي إلى أخطاء أو سلوك غير مضبوط.
