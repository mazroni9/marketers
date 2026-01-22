# منصة DASMe – محرك العمولات MR 20% ولوحة تحكم المسوقين

منصة متكاملة لإدارة جيش التسويق واللوجستيات بنظام الشراكة والعمولة، تتكون من:
- **لوحة تحكم المسوقين (Frontend)**: واجهة React احترافية للمسوقين
- **محرك العمولات MR 20% (Backend)**: نظام DDD متكامل لحساب وإدارة العمولات

---

## 📋 المحتويات

- [المميزات الرئيسية](#المميزات-الرئيسية)
- [هيكل المشروع](#هيكل-المشروع)
- [التقنيات المستخدمة](#التقنيات-المستخدمة)
- [التثبيت والتشغيل](#التثبيت-والتشغيل)
- [محرك MR 20%](#محرك-mr-20)
- [API Endpoints](#api-endpoints)
- [النشر](#النشر)
- [الوثائق](#الوثائق)

---

## 🎯 المميزات الرئيسية

### لوحة تحكم المسوقين (Frontend)

#### نظام الرتب والمستويات
- **الرتبة البرونزية (مستكشف)**: جلب العملاء والمركبات، عمولة أساسية 10%
- **الرتبة الفضية (موثق)**: مهام التصوير والمعاينة، عمولة إضافية للمهام
- **الرتبة الذهبية (سفير)**: تمثيل في نقل الملكية، عمولة 20% + مكافآت

#### نظام المحفظة الرقمية
- رصيد قابل للسحب
- رصيد معلق (للضمان)
- رصيد قيد التحقق
- سجل عمليات تفصيلي

#### نظام التحفيز (Gamification)
- لوحة الشرف (Leaderboard)
- شارات الإنجاز
- شريط التقدم للترقية
- نظام النقاط والمكافآت

#### المهام الميدانية
- تصوير احترافي للمركبات
- فحص مبدئي ومعاينة
- تمثيل في نقل الملكية
- تتبع GPS للمهام

#### نظام الإحالات
- روابط إحالة عميقة (Deep Links)
- أكواد خصم خاصة
- تتبع تلقائي للعملاء
- عمولة 20% من الاشتراك وأول عملية بيع

#### أداة القنص (Hunter Tool)
- إضافة عملاء جدد
- إضافة مركبات للعرض
- ربط تلقائي بالشريك
- متابعة حالة المركبات

### محرك العمولات MR 20% (Backend)

#### نظام العمولات المتقدم
- **Lifetime Modes**: 
  - `lifetime`: عمولة مدى الحياة
  - `by_count`: عمولة محدودة بعدد العمليات
  - `by_period`: عمولة محدودة بفترة زمنية
- **Commission Tiers**: شرائح عمولات متغيرة حسب عدد العمليات
- **Attribution Models**: `first_click` أو `last_click`
- **Scope**: عمولة على مستوى المنتج أو الفئة

#### إدارة البرامج والشركاء
- إنشاء برامج عمولات مخصصة
- تسجيل شركاء جدد
- ربط الشركاء بالعملاء والمنتجات
- تتبع العمليات والعمولات

#### نظام المحفظة الرقمية
- حساب الرصيد المعلق والمتاح
- تتبع العمولات حسب الحالة
- إدارة عمليات السحب

---

## 📁 هيكل المشروع

```
marketers/
├── client/                          # Frontend (React)
│   ├── src/
│   │   ├── components/              # المكونات المشتركة
│   │   ├── pages/                   # صفحات التطبيق
│   │   ├── contexts/                # Context API
│   │   └── hooks/                   # Custom Hooks
│   └── public/                      # الملفات الثابتة
│
├── app/Mr20/                        # Backend Module (Laravel DDD)
│   ├── Domain/                      # Domain Layer
│   │   ├── Merchants/               # كيانات التجار
│   │   ├── Partners/                # كيانات الشركاء
│   │   ├── Catalog/                 # كيانات المنتجات
│   │   └── Customers/               # كيانات العملاء
│   │
│   ├── Application/                 # Application Layer
│   │   ├── Merchants/               # Handlers للتجار
│   │   ├── Partners/                # Handlers للشركاء
│   │   ├── Links/                   # Handlers للروابط
│   │   ├── Transactions/            # Handlers للمعاملات
│   │   ├── Wallet/                  # Handlers للمحفظة
│   │   └── Services/                # Services (LifetimeRulesEngine, CommissionCalculator)
│   │
│   └── Infrastructure/              # Infrastructure Layer
│       ├── Http/Controllers/        # API Controllers
│       └── Persistence/Eloquent/    # Repositories
│
├── mr20/                            # Legacy Module (للتوافق)
│   ├── app/
│   │   ├── Models/                  # Eloquent Models
│   │   ├── Services/                # Services
│   │   └── Listeners/               # Event Listeners
│   ├── database/migrations/         # Database Migrations
│   └── routes/                      # API Routes
│
├── docs/                             # الوثائق
│   ├── MR20-SPEC.md                 # مواصفات محرك MR 20%
│   └── DASM-EVENTS-EXPLORATION.md   # استكشاف أحداث DASM
│
└── Public/                           # الوثائق العربية الأصلية
```

---

## 🛠 التقنيات المستخدمة

### Frontend
- **React 19** + **TypeScript**
- **Tailwind CSS 4** للتصميم
- **Wouter** للتنقل
- **Radix UI** للمكونات
- **Google Maps API** للخرائط
- **Vite** كأداة بناء
- **pnpm** لإدارة الحزم

### Backend
- **Laravel** (PHP)
- **Domain-Driven Design (DDD)**
- **Eloquent ORM**
- **RESTful API**
- **Event-Driven Architecture**

---

## 🚀 التثبيت والتشغيل

### المتطلبات
- **Node.js 18+**
- **pnpm 10+**
- **PHP 8.1+** (للباكند)
- **Composer** (للباكند)
- **Laravel** (للباكند)

### تثبيت Frontend

```bash
# تثبيت المتطلبات
pnpm install

# تشغيل المشروع في وضع التطوير
pnpm dev

# بناء المشروع للإنتاج
pnpm build

# معاينة البناء
pnpm preview
```

### تثبيت Backend (MR20 Module)

```bash
# الانتقال إلى مجلد Laravel project
cd /path/to/dasm-platform

# تثبيت المتطلبات
composer install

# نسخ ملفات MR20 إلى المشروع
# (يتم دمج app/Mr20/ مع app/ في Laravel)

# تشغيل Migrations
php artisan migrate

# تسجيل Service Provider
# في config/app.php:
# App\Mr20\Providers\Mr20ServiceProvider::class
```

---

## 🔧 محرك MR 20%

### البنية المعمارية (DDD)

المشروع منظم وفق **Domain-Driven Design**:

- **Domain Layer**: الكيانات الأساسية (Merchants, Partners, Products, etc.)
- **Application Layer**: Use Cases (Handlers) و Services
- **Infrastructure Layer**: Controllers, Repositories, External Services

### المكونات الرئيسية

#### 1. Handlers (Application Layer)
- `CreateMerchantHandler` - إنشاء تاجر جديد
- `CreateProgramHandler` - إنشاء برنامج عمولات
- `RegisterPartnerHandler` - تسجيل شريك جديد
- `CreateLinkHandler` - ربط شريك بعميل ومنتج
- `ReportTransactionHandler` - تقرير عملية بيع
- `GetWalletSummaryHandler` - ملخص المحفظة
- `GetPartnerCommissionsHandler` - قائمة العمولات

#### 2. Services (Application Layer)
- `LifetimeRulesEngine` - محرك قواعد Lifetime (lifetime/by_count/by_period)
- `CommissionCalculator` - حساب العمولات (percentage/flat)
- `WalletService` - إدارة المحفظة

#### 3. Repositories (Infrastructure Layer)
- `MerchantEloquentRepository`
- `PartnerEloquentRepository`
- `ProgramEloquentRepository`
- `LinkEloquentRepository`
- `TransactionEloquentRepository`
- `CommissionEloquentRepository`
- وغيرها...

---

## 📡 API Endpoints

### Admin APIs
- `POST /api/admin/merchants` - إنشاء تاجر جديد

### Merchant APIs (تتطلب X-API-KEY)
- `POST /api/v1/products` - تسجيل منتج
- `POST /api/v1/programs` - إنشاء برنامج عمولات
- `POST /api/v1/programs/{id}/tiers` - إضافة شرائح عمولات
- `POST /api/v1/links` - ربط شريك بعميل ومنتج
- `POST /api/v1/transactions/report` - تقرير عملية بيع

### Partner APIs (تتطلب Authorization Bearer Token)
- `GET /api/partner/programs/available` - البرامج المتاحة
- `POST /api/partner/programs/enroll` - التسجيل في برنامج
- `GET /api/partner/wallet/summary` - ملخص المحفظة
- `GET /api/partner/commissions` - قائمة العمولات

### Public APIs
- `POST /api/public/partners/register` - تسجيل شريك جديد

### شكل الاستجابة

جميع APIs ترجع نفس الشكل:

```json
{
  "success": true,
  "data": { ... }
}
```

أو في حالة الخطأ:

```json
{
  "success": false,
  "error": {
    "message": "Error message"
  }
}
```

---

## 🔗 الربط مع DASM-Platform

المشروع يحتوي على **Listeners** جاهزة للربط مع أحداث DASM-Platform:

### Listeners المتوفرة

1. **SyncProductWithMr20**
   - يستمع لحدث: `CarCreated`
   - ينفذ: تسجيل السيارة كمنتج في MR20

2. **SyncLinkWithMr20**
   - يستمع لحدث: `CarPartnerAssigned`
   - ينفذ: ربط الشريك بالسيارة في MR20

3. **ReportTransactionToMr20**
   - يستمع لحدث: `CarSold`
   - ينفذ: تقرير عملية البيع لحساب العمولة

### التسجيل في EventServiceProvider

```php
// app/Providers/EventServiceProvider.php

protected $listen = [
    \App\Events\CarCreated::class => [
        \App\Listeners\Mr20\SyncProductWithMr20::class,
    ],
    \App\Events\CarPartnerAssigned::class => [
        \App\Listeners\Mr20\SyncLinkWithMr20::class,
    ],
    \App\Events\CarSold::class => [
        \App\Listeners\Mr20\ReportTransactionToMr20::class,
    ],
];
```

**ملاحظة**: راجع `docs/DASM-EVENTS-EXPLORATION.md` للتفاصيل الكاملة.

---

## 📦 النشر

### النشر على Vercel (Frontend)

1. **إعداد المشروع على GitHub**
   ```bash
   git init
   git add .
   git commit -m "Initial commit"
   git remote add origin https://github.com/YOUR_USERNAME/marketers.git
   git push -u origin main
   ```

2. **النشر على Vercel**
   - اذهب إلى [Vercel](https://vercel.com)
   - اضغط "Import Project"
   - اختر المستودع من GitHub
   - Vercel سيكتشف الإعدادات تلقائياً من `vercel.json`
   - اضغط "Deploy"

3. **إعدادات Vercel المطلوبة**
   - Build Command: `pnpm build`
   - Output Directory: `dist/public`
   - Install Command: `pnpm install`

### النشر على Server (Backend)

الموديول MR20 يتم دمجه مع مشروع Laravel الرئيسي (DASM-Platform).

---

## 📚 الوثائق

### الوثائق المتوفرة

1. **docs/MR20-SPEC.md**
   - المواصفات الكاملة لمحرك MR 20%
   - تفاصيل الكيانات والـ APIs
   - قواعد Lifetime و Tiers

2. **docs/DASM-EVENTS-EXPLORATION.md**
   - استكشاف أحداث DASM-Platform
   - كيفية ربط Listeners مع Events

3. **app/Mr20/MIGRATION_PLAN.md**
   - خطة التحويل من Laravel تقليدي إلى DDD
   - خطوات التنفيذ

4. **Public/** (7 ملفات)
   - الوثائق العربية الأصلية للمشروع

---

## 🎯 الصفحات الرئيسية (Frontend)

- `/` - الصفحة الرئيسية (Dashboard)
- `/wallet` - المحفظة الرقمية
- `/add-client` - أداة القنص (إضافة عميل/مركبة)
- `/quotes-archive` - أرشيف العروض
- `/referrals` - الإحالات والعملاء
- `/tasks` - المهام الميدانية
- `/achievements` - الإنجازات والرتب

---

## 🔐 الأمان

### API Authentication

- **Merchants**: استخدام `X-API-KEY` header
- **Partners**: استخدام `Authorization: Bearer <JWT_TOKEN>`

### البيانات الحساسة

- جميع كلمات المرور مشفرة باستخدام Hash
- API Keys يتم توليدها تلقائياً عند إنشاء التاجر
- JWT Tokens للشركاء (يتم ربطه بنظام Auth الرئيسي)

---

## 🧪 الاختبارات

```bash
# Frontend Tests (إن وجدت)
pnpm test

# Backend Tests
php artisan test
```

---

## 📝 الترخيص

MIT License

---

## 🤝 المساهمة

للمساهمة في المشروع:
1. Fork المشروع
2. أنشئ branch جديد (`git checkout -b feature/AmazingFeature`)
3. Commit التغييرات (`git commit -m 'Add some AmazingFeature'`)
4. Push إلى Branch (`git push origin feature/AmazingFeature`)
5. افتح Pull Request

---

## 📞 الدعم

للمساعدة والدعم، يرجى التواصل مع فريق التطوير.

---

## 🔄 التحديثات المستقبلية

- [ ] تحسين نظام الاختبارات
- [ ] إضافة Real-time notifications
- [ ] تحسين أداء API
- [ ] إضافة Analytics dashboard
- [ ] دعم Multi-currency
- [ ] تحسين نظام المحفظة

---

**تم التطوير بواسطة فريق DASMe** 🚀
