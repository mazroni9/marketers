# خطة التحويل التدريجي من `mr20/` إلى `app/Mr20/` (DDD)

## ✅ الخطوات المكتملة

1. ✅ حذف مجلد `src/` (namespace غير متوافق)
2. ✅ نقل `LifetimeRulesEngine` → `app/Mr20/Application/Services/`
3. ✅ نقل `CommissionCalculator` → `app/Mr20/Application/Services/`

---

## 📋 الخطة التدريجية (Refactor آمن)

### المرحلة 1: إعداد Infrastructure Layer (آمن - لا يؤثر على المنطق)

**الهدف**: إنشاء Repositories و Controllers في Infrastructure بدون تغيير Models الحالية.

#### 1.1 إنشاء Eloquent Repositories
- [ ] `app/Mr20/Infrastructure/Persistence/Eloquent/MerchantEloquentRepository.php`
  - يعتمد على `App\Mr20\Models\Merchant` (الحالي)
  - يوفر methods مثل `findByApiKey()`, `create()`, `find()`
  
- [ ] `app/Mr20/Infrastructure/Persistence/Eloquent/PartnerEloquentRepository.php`
- [ ] `app/Mr20/Infrastructure/Persistence/Eloquent/ProgramEloquentRepository.php`
- [ ] `app/Mr20/Infrastructure/Persistence/Eloquent/LinkEloquentRepository.php`
- [ ] `app/Mr20/Infrastructure/Persistence/Eloquent/TransactionEloquentRepository.php`
- [ ] `app/Mr20/Infrastructure/Persistence/Eloquent/CommissionEloquentRepository.php`

#### 1.2 نقل Controllers إلى Infrastructure
- [ ] نقل `mr20/app/Http/Controllers/` → `app/Mr20/Infrastructure/Http/Controllers/`
  - تعديل namespace إلى `App\Mr20\Infrastructure\Http\Controllers\...`
  - تحديث imports لاستخدام Repositories بدلاً من Models مباشرة (تدريجياً)

**ملاحظة**: في هذه المرحلة، Controllers ما زالت تستخدم `App\Mr20\Models\...` مباشرة. هذا آمن ولا يكسر النظام.

---

### المرحلة 2: إنشاء Application Handlers (Use Cases)

**الهدف**: استخراج منطق الـ Controllers إلى Handlers في Application Layer.

#### 2.1 Handlers للمعاملات (Transactions)
- [ ] `app/Mr20/Application/Transactions/ReportTransactionHandler.php`
  - ينقل منطق `TransactionReportController::store()` إلى Handler
  - يستخدم Repositories و Services (`LifetimeRulesEngine`, `CommissionCalculator`)
  - Controller يصبح رفيع (validation + call handler)

#### 2.2 Handlers أخرى
- [ ] `app/Mr20/Application/Merchants/CreateMerchantHandler.php` (من `MerchantController`)
- [ ] `app/Mr20/Application/Merchants/CreateProgramHandler.php` (من `ProgramController`)
- [ ] `app/Mr20/Application/Merchants/AttachTiersHandler.php` (من `ProgramTierController`)
- [ ] `app/Mr20/Application/Partners/RegisterPartnerHandler.php` (من `PartnerRegisterController`)
- [ ] `app/Mr20/Application/Partners/EnrollPartnerInProgramHandler.php` (من `PartnerProgramController`)
- [ ] `app/Mr20/Application/Links/CreateLinkHandler.php` (من `LinkController`)

**ملاحظة**: في هذه المرحلة، Handlers ما زالت تستخدم `App\Mr20\Models\...` عبر Repositories. هذا آمن.

---

### المرحلة 3: تحويل Models إلى Domain Entities (تدريجي - اختياري لاحقاً)

**الهدف**: فصل Domain Logic عن Infrastructure (Eloquent).

#### 3.1 إنشاء Domain Entities (Pure PHP)
- [ ] `app/Mr20/Domain/Merchants/Merchant.php` (Pure class بدون Eloquent)
- [ ] `app/Mr20/Domain/Merchants/MerchantProgram.php`
- [ ] `app/Mr20/Domain/Partners/Partner.php`
- [ ] ... إلخ

#### 3.2 تحديث Repositories
- [ ] Repositories تقوم بـ mapping بين Eloquent Models و Domain Entities
- [ ] `MerchantEloquentRepository::find()` يرجع `App\Mr20\Domain\Merchants\Merchant`
- [ ] `MerchantEloquentRepository::save()` يأخذ Domain Entity ويحولها إلى Eloquent Model

#### 3.3 تحديث Services
- [ ] `LifetimeRulesEngine` يستخدم Domain Entities بدلاً من Eloquent Models
- [ ] `CommissionCalculator` يستخدم Domain Entities

**⚠️ تحذير**: هذه المرحلة تحتاج اختبارات شاملة. يمكن تأجيلها إذا كان النظام يعمل بشكل جيد.

---

## 🎯 التوصية: البدء بالمرحلة 1 و 2 فقط

**السبب**:
- المرحلة 1 و 2 تحسن البنية (DDD) بدون كسر النظام الحالي
- Models الحالية (`App\Mr20\Models\...`) تبقى كما هي وتعمل
- Controllers تصبح رفيعة، والمنطق في Handlers
- Services (`LifetimeRulesEngine`, `CommissionCalculator`) تعمل مع Models الحالية

**المرحلة 3 (Domain Entities)**:
- اختيارية ويمكن تأجيلها
- تحتاج وقت أكثر واختبارات
- يمكن تنفيذها لاحقاً إذا احتجنا فصل Domain Logic عن Infrastructure

---

## 📝 الخطوة التالية الموصى بها

1. إنشاء `MerchantEloquentRepository` كمثال
2. نقل `TransactionReportController` إلى `Infrastructure/Http/Controllers/`
3. إنشاء `ReportTransactionHandler` في `Application/Transactions/`
4. تحديث Controller لاستخدام Handler

**هل نبدأ بالمرحلة 1 و 2 الآن؟**
