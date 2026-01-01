import DashboardLayout from "@/components/DashboardLayout";
import StatCard from "@/components/StatCard";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { 
  Wallet, 
  Users, 
  Car, 
  TrendingUp, 
  ArrowUpRight, 
  Clock, 
  CheckCircle2,
  AlertCircle
} from "lucide-react";
import { cn } from "@/lib/utils";

export default function Home() {
  return (
    <DashboardLayout>
      {/* Hero Section */}
      <div className="relative rounded-2xl overflow-hidden mb-8 bg-primary text-primary-foreground shadow-lg">
        <div className="absolute inset-0 bg-[url('/images/dashboard-hero.png')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
        <div className="absolute inset-0 bg-gradient-to-r from-primary/90 to-primary/40"></div>
        <div className="relative p-8 md:p-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
          <div>
            <h1 className="text-3xl font-bold mb-2">مرحباً، سعود 👋</h1>
            <p className="text-primary-foreground/80 max-w-xl">
              أنت الآن في الرتبة <span className="font-bold text-white">الفضية</span>. أكمل 3 مهام إضافية للوصول للرتبة الذهبية وزيادة عمولتك بنسبة 5%.
            </p>
          </div>
          <div className="flex gap-3">
            <Button variant="secondary" className="font-bold shadow-md hover:shadow-lg transition-all">
              <Car className="ml-2 h-4 w-4" />
              إضافة سيارة
            </Button>
            <Button className="bg-white/10 hover:bg-white/20 text-white border-white/20 backdrop-blur-sm">
              نسخ رابط الإحالة
            </Button>
          </div>
        </div>
      </div>

      {/* Stats Grid */}
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-4 mb-8">
        <StatCard
          title="إجمالي الرصيد"
          value="12,450 ر.س"
          icon={Wallet}
          trend={{ value: 12, isPositive: true }}
          description="مقارنة بالشهر الماضي"
        />
        <StatCard
          title="العملاء النشطين"
          value="45"
          icon={Users}
          trend={{ value: 8, isPositive: true }}
          description="عميل جديد هذا الشهر"
        />
        <StatCard
          title="السيارات المباعة"
          value="12"
          icon={Car}
          description="سيارة تم بيعها عن طريقك"
        />
        <StatCard
          title="معدل التحويل"
          value="3.2%"
          icon={TrendingUp}
          trend={{ value: 0.4, isPositive: false }}
          description="انخفاض طفيف"
        />
      </div>

      {/* Main Content Grid */}
      <div className="grid gap-6 md:grid-cols-7">
        
        {/* Recent Activity - 4 Columns */}
        <Card className="md:col-span-4 dashboard-card">
          <CardHeader>
            <CardTitle>آخر العمليات</CardTitle>
            <CardDescription>سجل أحدث العمولات والعمليات المالية</CardDescription>
          </CardHeader>
          <CardContent>
            <div className="space-y-6">
              {[
                { type: "commission", title: "عمولة بيع - تويوتا كامري 2023", amount: "+1,200 ر.س", date: "منذ ساعتين", status: "completed" },
                { type: "withdrawal", title: "سحب رصيد للمحفظة البنكية", amount: "-5,000 ر.س", date: "أمس", status: "processing" },
                { type: "commission", title: "عمولة فحص - هيونداي سوناتا", amount: "+150 ر.س", date: "منذ يومين", status: "completed" },
                { type: "referral", title: "مكافأة إحالة عميل جديد", amount: "+50 ر.س", date: "منذ 3 أيام", status: "completed" },
              ].map((item, i) => (
                <div key={i} className="flex items-center justify-between">
                  <div className="flex items-center gap-4">
                    <div className={cn(
                      "h-10 w-10 rounded-full flex items-center justify-center",
                      item.type === "withdrawal" ? "bg-destructive/10 text-destructive" : "bg-secondary/10 text-secondary"
                    )}>
                      {item.type === "withdrawal" ? <ArrowUpRight className="h-5 w-5" /> : <Wallet className="h-5 w-5" />}
                    </div>
                    <div>
                      <p className="text-sm font-medium leading-none">{item.title}</p>
                      <p className="text-xs text-muted-foreground mt-1">{item.date}</p>
                    </div>
                  </div>
                  <div className="text-right">
                    <p className={cn(
                      "text-sm font-bold",
                      item.type === "withdrawal" ? "text-foreground" : "text-secondary"
                    )}>{item.amount}</p>
                    <p className={cn(
                      "text-[10px] mt-1 px-2 py-0.5 rounded-full inline-block",
                      item.status === "completed" ? "bg-secondary/10 text-secondary" : "bg-yellow-500/10 text-yellow-600"
                    )}>
                      {item.status === "completed" ? "مكتمل" : "قيد المعالجة"}
                    </p>
                  </div>
                </div>
              ))}
            </div>
          </CardContent>
        </Card>

        {/* Tasks & Badges - 3 Columns */}
        <div className="md:col-span-3 space-y-6">
          {/* Tasks Card */}
          <Card className="dashboard-card">
            <CardHeader>
              <CardTitle>المهام المتاحة</CardTitle>
              <CardDescription>مهام ميدانية قريبة منك</CardDescription>
            </CardHeader>
            <CardContent>
              <div className="space-y-4">
                {[
                  { title: "تصوير سيارة - حي الملقا", reward: "150 ر.س", distance: "2.5 كم", urgent: true },
                  { title: "فحص مبدئي - حي النرجس", reward: "200 ر.س", distance: "5.0 كم", urgent: false },
                ].map((task, i) => (
                  <div key={i} className="p-3 border border-border rounded-lg hover:bg-muted/50 transition-colors cursor-pointer group">
                    <div className="flex justify-between items-start mb-2">
                      <h4 className="font-medium text-sm group-hover:text-primary transition-colors">{task.title}</h4>
                      {task.urgent && (
                        <span className="bg-destructive/10 text-destructive text-[10px] px-2 py-0.5 rounded-full font-medium">
                          عاجل
                        </span>
                      )}
                    </div>
                    <div className="flex justify-between items-center text-xs text-muted-foreground">
                      <span className="flex items-center gap-1"><Clock className="h-3 w-3" /> {task.distance}</span>
                      <span className="font-bold text-secondary">{task.reward}</span>
                    </div>
                  </div>
                ))}
                <Button variant="outline" className="w-full text-xs">عرض كل المهام</Button>
              </div>
            </CardContent>
          </Card>

          {/* Badges Card */}
          <Card className="dashboard-card bg-gradient-to-br from-card to-muted/30">
            <CardHeader>
              <CardTitle>إنجازاتك</CardTitle>
            </CardHeader>
            <CardContent>
              <div className="flex justify-center gap-6">
                <div className="text-center group">
                  <div className="relative mb-2 transition-transform group-hover:scale-110 duration-300">
                    <img src="/images/badge-bronze.png" alt="Bronze" className="h-16 w-16 object-contain drop-shadow-md" />
                    <div className="absolute -bottom-1 -right-1 bg-secondary text-white text-[10px] h-5 w-5 flex items-center justify-center rounded-full border-2 border-card">
                      <CheckCircle2 className="h-3 w-3" />
                    </div>
                  </div>
                  <span className="text-xs font-medium text-muted-foreground">مستكشف</span>
                </div>
                <div className="text-center group">
                  <div className="relative mb-2 transition-transform group-hover:scale-110 duration-300">
                    <img src="/images/badge-silver.png" alt="Silver" className="h-20 w-20 object-contain drop-shadow-lg" />
                    <div className="absolute -bottom-1 -right-1 bg-secondary text-white text-[10px] h-6 w-6 flex items-center justify-center rounded-full border-2 border-card">
                      <CheckCircle2 className="h-3 w-3" />
                    </div>
                  </div>
                  <span className="text-sm font-bold text-primary">موثق (حالي)</span>
                </div>
                <div className="text-center group opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                  <div className="relative mb-2">
                    <img src="/images/badge-gold.png" alt="Gold" className="h-16 w-16 object-contain" />
                    <div className="absolute -bottom-1 -right-1 bg-muted-foreground text-white text-[10px] h-5 w-5 flex items-center justify-center rounded-full border-2 border-card">
                      <AlertCircle className="h-3 w-3" />
                    </div>
                  </div>
                  <span className="text-xs font-medium text-muted-foreground">سفير</span>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </DashboardLayout>
  );
}
