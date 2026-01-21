# MR 20% – Commission Engine & Um Al-Musawiqin

## 1. تعريف عام

- النظام: MR 20% – محرك عمولات مستقل يعمل بنمط API-First.
- الهدف: خدمة منصة DASM وأي أنظمة خارجية (صيدليات، نون، سلة، متاجر أخرى) بحساب العمولات للوسطاء (Partners) وربطهم بالتجار (Merchants) والعملاء (Customers) والمنتجات (Products).
- واجهات الاستخدام:
  - REST JSON عبر HTTPS.
  - لكل تاجر api_key في الهيدر: `X-API-KEY: <merchant_api_key>`.
  - لكل وسيط Access Token: `Authorization: Bearer <PARTNER_JWT_TOKEN>`.
- استجابات الـ API دائمًا تتبع الشكل:
  - `success` (true/false)
  - `data` أو `error`.

## 2. الكيانات الرئيسية (Entities)

### 2.1 Merchants
- يمثل التاجر (مثلاً DASM Auctions أو صيدلية).
- حقول أساسية:
  - `id`
  - `name`
  - `sector` (cars, pharmacy, general, …)
  - `default_commission_model` (percentage/flat)
  - `default_commission_value`
  - `default_payout_delay_days`
  - `api_key`

### 2.2 Products / Categories
- Products:
  - `id`
  - `external_product_code`
  - `name`
  - `category`
  - `base_price`
- Categories (اختياري للتوسع):
  - `id`
  - `name`
  - `external_category_code`

### 2.3 MerchantPrograms
- تعريف برنامج عمولات لكل تاجر.
- الحقول:
  - `id`
  - `merchant_id`
  - `name`
  - `commission_type` (percentage/flat)
  - `commission_value`
  - `lifetime_mode` ("lifetime" | "by_count" | "by_period")
  - `lifetime_count_limit` (nullable)
  - `lifetime_period_days` (nullable)
  - `attribution_model` ("first_click" | "last_click")
  - `scope` ("product" | "category")
  - `terms_summary`
  - حالة البرنامج (Active/Inactive).

### 2.4 ProgramCommissionTiers
- شرائح عمولات متغيرة حسب عدد العمليات:
- الحقول:
  - `id`
  - `program_id`
  - `from_count`
  - `to_count` (nullable للـ "أعلى من")
  - `commission_type`
  - `commission_value`

### 2.5 Partners
- الوسطاء/المسوقون.
- الحقول:
  - `id`
  - `full_name`
  - `phone`
  - `email`
  - `city`
  - `expertise_tags` (cars, pharmacy, …)
  - بيانات الدخول والـ JWT.

### 2.6 Customers
- عملاء التاجر (بائع سيارات، مشتري، مريض…).
- الحقول:
  - `id`
  - `external_customer_id`
  - `name` (اختياري)
  - حقول تعريفية إضافية حسب الحاجة.

### 2.7 PartnerCustomerProductLinks
- الرابط الأساسي لفكرة Mr 20% (Partner–Customer–Product/Category–Program).
- الحقول:
  - `id`
  - `partner_id`
  - `customer_id`
  - `product_id` أو `category_id` (بحسب `scope` في البرنامج)
  - `program_id`
  - `first_eligible_at`
  - `total_eligible_transactions`
  - حالة الرابط (Active/Inactive)
  - طوابع زمنية.

### 2.8 Transactions & Commissions & Wallet
- Transactions (عمليات البيع الواصلة من التاجر):
  - `id`
  - `merchant_id`
  - `external_transaction_id`
  - `customer_id`
  - `product_id` أو `category_id`
  - `program_id` (إن وجد)
  - `amount`
  - `occurred_at`
- Commissions:
  - `id`
  - `partner_id`
  - `transaction_id`
  - `program_id`
  - `commission_amount`
  - `status` (pending, available, paid_out, cancelled)
  - `will_be_available_at`
- WalletEntries:
  - `id`
  - `partner_id`
  - `type` (commission_pending, commission_available, payout)
  - `amount`
  - `related_commission_id`
  - `created_at`.

## 3. واجهات الـ API (Endpoints)

### 3.1 Merchants Admin

#### POST /api/admin/merchants
- إنشاء تاجر جديد (لوحة إدارة داخلية).
- Request:
```json
{
  "name": "DASM Auctions",
  "sector": "cars",
  "default_commission_model": "percentage",
  "default_commission_value": 20,
  "default_payout_delay_days": 7
}
```

- Response:
```json
{
  "success": true,
  "data": {
    "id": 1,
    "api_key": "MERCHANT_API_KEY_GENERATED"
  }
}
```

### 3.2 Products

#### POST /api/v1/products
- تعريف منتج/أصل من طرف التاجر.
- Header: `X-API-KEY`.
- Request/Response كما في النص الأصلي (يُستكمل لاحقاً في التنفيذ).

### 3.3 Programs

#### POST /api/v1/programs
- إنشاء برنامج عمولات:
- Request:
```json
{
  "name": "Car Auctions 2026 - Standard",
  "commission_type": "percentage",
  "commission_value": 20,
  "lifetime_mode": "by_count",
  "lifetime_count_limit": 100,
  "lifetime_period_days": null,
  "attribution_model": "first_click",
  "scope": "product",
  "terms_summary": "20% من عمولة المنصة لأول 100 عملية بيع لكل سيارة يجلبها الوسيط."
}
```

#### POST /api/v1/programs/{program_id}/tiers
- تعريف الـ Tiers.
- Request/Response كما في النص الأصلي (يُستكمل لاحقاً في التنفيذ).

### 3.4 Partners

#### POST /api/public/partners/register
- تسجيل وسيط جديد.
- Request/Response كما في النص الأصلي (يُستكمل لاحقاً في التنفيذ).

### 3.5 Partner Programs

- `GET /api/partner/programs/available`
- `POST /api/partner/programs/enroll`
- كما في النص الأصلي (يُستكمل لاحقاً في التنفيذ).

### 3.6 Links (Partner–Customer–Product)

#### POST /api/v1/links
- يستقبل: `partner_id`, `program_id`, `external_customer_id`, `external_product_code`.
- يطبّق سياسة `attribution_model` (`first_click` / `last_click`).
- ينشئ أو يحدث Link ويعيد `link_id` و `linked_at`.

### 3.7 Transactions & Commissions Logic

#### POST /api/v1/transactions/report
- Request يتضمن:
  - `external_transaction_id`
  - `external_customer_id`
  - `external_product_code`
  - `amount`
  - `occurred_at`
- الاستجابة في حالة استحقاق:
  - كما في المثال (يتضمن `commission_created`, `partner_id`, `commission_amount`, `status`, `will_be_available_at`).
- في حالة عدم الاستحقاق:
```json
{
  "success": true,
  "data": {
    "transaction_id": 30099,
    "commission_created": false,
    "reason": "count_limit_reached"
  }
}
```

## 4. منطق الـ Lifetime Rules و Tiers (Rules Engine)

### 4.1 Lifetime Modes
- `lifetime_mode = "lifetime"`  
  أي عملية مستقبلية لنفس (العميل + المنتج/الفئة) تستحق عمولة ما دام البرنامج والرابط Active.

- `lifetime_mode = "by_count"`  
  الوسيط يستحق لأول `lifetime_count_limit` عملية فقط لكل رابط، بعدها لا عمولة.

- `lifetime_mode = "by_period"`  
  الوسيط يستحق لأي عملية خلال `lifetime_period_days` من `first_eligible_at`.

### 4.2 خطوات `/transactions/report` (Pseudo-flow)

1. استرجاع `merchant` من `api_key`.
2. استنتاج أو استعادة `program` (قد يرسل `program_id` أو يحدد حسب المنتج).
3. جلب/إنشاء `customer` من `external_customer_id`.
4. جلب/إنشاء `product` من `external_product_code` (أو `category` حسب `scope`).
5. إيجاد Link مناسب في `PartnerCustomerProductLinks` طبقًا لـ:
   - `customer_id`
   - `product_id` أو `category_id`
   - `program_id`
   - مع مراعاة سياسة `attribution_model`.
6. إذا لم يوجد Link:
   - لا عمولة (أو منطق Default لاحقاً).
7. إذا وُجد Link:
   - حساب أهلية العملية حسب `lifetime_mode`:
     - **lifetime**: طالما البرنامج والرابط Active → مؤهل.
     - **by_count**: مؤهل إذا `total_eligible_transactions < lifetime_count_limit`.
     - **by_period**:
       - إذا `first_eligible_at` فارغ → عيّنه = `occurred_at`.
       - احسب الفرق (أيام) بين `occurred_at` و `first_eligible_at`.
       - مؤهل إذا الفارق ≤ `lifetime_period_days`.
8. تحديد شريحة العمولة (Tier):
   - `next_tx_number = total_eligible_transactions + 1`.
   - ابحث في `ProgramCommissionTiers` عن:
     - `from_count <= next_tx_number`
     - و (`to_count` = null أو `next_tx_number <= to_count`).
   - إذا وجد Tier → استخدم قيمه.
   - إذا لم يوجد → استخدم `commission_type` / `commission_value` من البرنامج.
9. إذا العملية مؤهّلة:
   - إنشاء `Transaction` + `Commission` + `WalletEntry`.
   - تحديث `total_eligible_transactions`.
   - إعادة Response مع `commission_created = true`.
10. إذا غير مؤهّلة:
    - لا تنشئ `Commission`.
    - إعادة Response مع `commission_created = false` و `reason` مناسب.

## 5. واجهات المسوق (Um Al-Musawiqin UI Hints)

### 5.1 ما يظهر في واجهة البرنامج
- أمثلة نصوص:
  - "عمولة 20% لأول 10 عمليات، 15% حتى 50، 10% بعدها".
  - "العدد الأقصى للعمليات المؤهلة لكل عميل/منتج: 100".
  - "الاستحقاق لمدة 365 يوم من أول عملية".
  - "استحقاق مدى الحياة ما دام البرنامج فعالاً".

### 5.2 ما يظهر في شاشة العميل/المنتج لدى الوسيط
- في حالة `by_count`:
  - عداد بصيغة: `32 / 100 عملية`.
  - نص يوضح الشريحة الحالية: "من 11 إلى 50 عملية → عمولة 15%".
- في حالة `by_period`:
  - نص: "هذا الرابط ساري حتى: `YYYY-MM-DD` (بقي `XX` يوماً)".

## 6. ربط خاص مع DASM

- عند إدخال سيارة جديدة في DASM:
  - استدعاء `/api/v1/products` مرة واحدة لكل كود سيارة أو via sync.
- عند ربط السيارة بالوسيط:
  - استدعاء `/api/v1/links` مع:
    - `partner_id`
    - `program_id`
    - `external_customer_id` (مالك السيارة)
    - `external_product_code` (كود السيارة في DASM).
- عند بيع السيارة في المزاد:
  - استدعاء `/api/v1/transactions/report` بقيمة البيع أو بقيمة عمولة DASM.
- MR 20% يحسب 20% للوسيط ويضيفها لمحفظته وفق برنامج DASM.

