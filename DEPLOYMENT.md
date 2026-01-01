# دليل النشر - منصة DASMe للمسوقين

## الخطوات المطلوبة للنشر على GitHub و Vercel

### 1. إعداد مستودع GitHub

```bash
# تهيئة Git (إذا لم يكن موجوداً)
git init

# إضافة جميع الملفات
git add .

# عمل commit أولي
git commit -m "إطلاق منصة DASMe للمسوقين - النسخة الكاملة"

# إضافة المستودع البعيد (استبدل YOUR_USERNAME باسم المستخدم)
git remote add origin https://github.com/YOUR_USERNAME/marketers.git

# رفع الكود
git branch -M main
git push -u origin main
```

**ملاحظة**: استبدل `YOUR_USERNAME` باسم المستخدم الحقيقي على GitHub.

### 2. النشر على Vercel

#### الطريقة الأولى: من خلال واجهة Vercel

1. اذهب إلى [vercel.com](https://vercel.com) وسجل الدخول
2. اضغط على **"Add New Project"** أو **"Import Project"**
3. اختر **"Import Git Repository"**
4. اختر المستودع `marketers` من قائمة المستودعات
5. Vercel سيكتشف الإعدادات تلقائياً:
   - **Framework Preset**: Vite
   - **Build Command**: `pnpm build`
   - **Output Directory**: `dist/public`
   - **Install Command**: `pnpm install`
6. اضغط **"Deploy"**

#### الطريقة الثانية: من خلال Vercel CLI

```bash
# تثبيت Vercel CLI
npm i -g vercel

# تسجيل الدخول
vercel login

# النشر
vercel

# للنشر في الإنتاج
vercel --prod
```

### 3. إعدادات البيئة (Environment Variables)

إذا كنت تحتاج متغيرات بيئة، أضفها في Vercel:
- اذهب إلى Project Settings > Environment Variables
- أضف المتغيرات المطلوبة (مثل API keys)

### 4. التحقق من النشر

بعد النشر، ستحصل على رابط مثل:
- `https://marketers.vercel.app`
- أو رابط مخصص إذا قمت بإعداد نطاق مخصص

### 5. تحديثات مستقبلية

عندما تريد تحديث الموقع:

```bash
# عمل التغييرات
git add .
git commit -m "وصف التحديث"
git push origin main
```

Vercel سيقوم تلقائياً بإعادة البناء والنشر عند كل push.

## إعدادات Vercel الموصى بها

في إعدادات المشروع على Vercel:

- **Node.js Version**: 18.x أو أحدث
- **Build Command**: `pnpm build`
- **Output Directory**: `dist/public`
- **Install Command**: `pnpm install`
- **Root Directory**: `.` (الجذر)

## استكشاف الأخطاء

### مشكلة في البناء
- تأكد من أن `pnpm install` يعمل بدون أخطاء محلياً
- تحقق من أن جميع المتطلبات موجودة في `package.json`

### مشكلة في المسارات (Routing)
- تأكد من وجود `vercel.json` مع إعدادات `rewrites`
- تأكد من أن جميع المسارات تعيد توجيه إلى `index.html`

### مشكلة في الصور
- تأكد من أن الصور موجودة في `client/public/images/`
- تحقق من المسارات في الكود

## الدعم

إذا واجهت أي مشاكل:
1. تحقق من سجلات البناء في Vercel
2. راجع ملف `vercel.json`
3. تأكد من أن جميع الملفات المطلوبة موجودة

