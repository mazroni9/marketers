# تقرير استكشافي: أحداث DASM-Platform المرتبطة بمحرك MR 20%

## ملاحظة مهمة

هذا التقرير مبني على:
- **Listeners الموجودة** في `mr20/app/Listeners/` و `app/Mr20/Infrastructure/Integration/Dasm/Listeners/`
- **التعليقات** في الكود التي توضح الأحداث المتوقعة
- **متطلبات MR20-SPEC** للربط مع DASM-Platform

**⚠️ هذا المشروع (marketers) لا يحتوي على DASM-Platform الفعلي.**  
**يجب البحث في مشروع DASM-Platform الفعلي للعثور على الأحداث الحقيقية.**

---

## جدول الأحداث المتوقعة

| # | اسم الـ Event | Namespace المتوقع | أين يتم إطلاقه | البيانات الأساسية |
|---|--------------|-------------------|----------------|-------------------|
| 1 | **CarCreated** | `App\Events\CarCreated`<br>أو<br>`App\Events\Auction\CarCreated`<br>أو<br>`App\Events\Listing\CarCreated` | عند إنشاء سيارة جديدة في DASM:<br>- `CarController::store()`<br>- `CarService::create()`<br>- `ListingController::create()`<br>- بعد `Car::create()` أو `Car::save()` | `$event->car` يحتوي على:<br>- `id` (car ID)<br>- `code` (unique car code)<br>- `title` أو `name`<br>- `category_name` (optional)<br>- `base_price` (optional)<br>- `owner_id` أو `owner_external_id` (optional) |
| 2 | **CarPartnerAssigned**<br>أو<br>**CarAssignedToPartner** | `App\Events\CarPartnerAssigned`<br>أو<br>`App\Events\Auction\CarAssignedToPartner`<br>أو<br>`App\Events\Dealer\CarAssigned` | عند ربط سيارة بشريك/تاجر:<br>- `CarController::assignToPartner()`<br>- `CarService::assignPartner()`<br>- `DealerController::assignCar()`<br>- عند تحديث `car->partner_id` | `$event->car` يحتوي على:<br>- `id`<br>- `code`<br>- `owner_external_id` أو `owner_id`<br><br>`$event->partner` يحتوي على:<br>- `id`<br>- `mr20_partner_id` (optional - ID في MR20)<br><br>`$event->program_id` (optional - MR20 program ID) |
| 3 | **CarSold**<br>أو<br>**AuctionCompleted**<br>أو<br>**BidFinalized** | `App\Events\CarSold`<br>أو<br>`App\Events\Auction\AuctionCompleted`<br>أو<br>`App\Events\Auction\BidFinalized`<br>أو<br>`App\Events\Sale\CarSold` | عند بيع سيارة في المزاد:<br>- `AuctionController::finalize()`<br>- `AuctionService::complete()`<br>- `SaleController::create()`<br>- `BidController::accept()`<br>- بعد تحديث حالة المزاد إلى "completed" أو "sold" | `$event->car` يحتوي على:<br>- `id`<br>- `code`<br>- `owner_external_id` أو `owner_id`<br><br>`$event->sale` يحتوي على:<br>- `id` (sale ID)<br>- `amount` (سعر البيع)<br>- `commission_amount` (عمولة المنصة)<br>- `occurred_at` (تاريخ البيع)<br>- `reference` (optional - رقم مرجعي)<br><br>`$event->program_id` (optional - MR20 program ID)<br><br>`$event->buyer` (optional - المشتري)<br>`$event->seller` (optional - البائع) |

---

## تفاصيل إضافية من Listeners الموجودة

### 1. SyncProductWithMr20 (إنشاء منتج)
**الملف**: `mr20/app/Listeners/Mr20/SyncProductWithMr20.php`  
**Event المتوقع**: `App\Events\CarCreated` (أو ما يعادله)

**البيانات المطلوبة من Event**:
```php
$event->car->code              // required
$event->car->title              // أو name
$event->car->category_name      // optional
$event->car->base_price         // optional
```

---

### 2. SyncLinkWithMr20 (ربط شريك)
**الملف**: `mr20/app/Listeners/Mr20/SyncLinkWithMr20.php`  
**Event المتوقع**: `App\Events\CarPartnerAssigned` (أو ما يعادله)

**البيانات المطلوبة من Event**:
```php
$event->car->code                    // required
$event->car->owner_external_id       // أو owner_id
$event->partner->id                  // required
$event->partner->mr20_partner_id     // optional (ID في MR20)
$event->program_id                   // required (MR20 program ID)
```

---

### 3. ReportTransactionToMr20 (تقرير بيع)
**الملف**: `mr20/app/Listeners/Mr20/ReportTransactionToMr20.php`  
**Event المتوقع**: `App\Events\CarSold` (أو ما يعادله)

**البيانات المطلوبة من Event**:
```php
$event->car->code                    // required
$event->car->owner_external_id       // أو owner_id
$event->sale->id                     // sale ID
$event->sale->amount                 // سعر البيع
$event->sale->commission_amount      // عمولة المنصة
$event->sale->occurred_at            // DateTimeInterface
$event->sale->reference               // optional
$event->program_id                   // optional (MR20 program ID)
```

---

## أماكن البحث في DASM-Platform الفعلي

عند البحث في مشروع DASM-Platform الفعلي، ابحث في:

### 1. Events Directory
```
app/Events/
app/Events/Auction/
app/Events/Car/
app/Events/Listing/
app/Events/Sale/
app/Events/Dealer/
```

### 2. EventServiceProvider
```
app/Providers/EventServiceProvider.php
```
ابحث عن:
- `CarCreated`
- `CarAssigned`
- `CarSold`
- `AuctionCompleted`
- `BidFinalized`

### 3. Controllers & Services
ابحث عن:
- `CarController`
- `AuctionController`
- `SaleController`
- `DealerController`
- `CarService`
- `AuctionService`

ابحث عن استخدامات:
- `event(new CarCreated(...))`
- `CarCreated::dispatch(...)`
- `Event::dispatch(new CarCreated(...))`

### 4. Models
ابحث عن:
- `Car` model
- `Auction` model
- `Sale` model
- `Bid` model

ابحث عن:
- `protected $dispatchesEvents = [...]`
- `static::created()`, `static::updated()`, `static::saved()`

---

## الخطوات التالية

1. **افتح مشروع DASM-Platform الفعلي**
2. **ابحث في `app/Events/`** عن Events المتعلقة بالسيارات
3. **ابحث في `app/Providers/EventServiceProvider.php`** عن تسجيل Events
4. **ابحث في Controllers/Services** عن استخدامات `event()` أو `Event::dispatch()`
5. **حدّث هذا الجدول** بالأحداث الفعلية الموجودة
6. **ربط Listeners الموجودة** بالأحداث الفعلية في `EventServiceProvider`

---

## ملاحظات للتنفيذ

- **Listeners الموجودة** جاهزة للربط، لكنها تحتاج إلى:
  - تسجيل في `EventServiceProvider`
  - التأكد من أن Events الفعلية تطابق التوقعات (أسماء الخصائص)
  
- **إذا كانت Events الفعلية مختلفة**:
  - يمكن إنشاء Adapters في `app/Mr20/Infrastructure/Integration/Dasm/Events/`
  - أو تعديل Listeners لتتوافق مع Events الفعلية
