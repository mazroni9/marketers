import DashboardLayout from "@/components/DashboardLayout";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Progress } from "@/components/ui/progress";
import { cn } from "@/lib/utils";
import { Award, Camera, CheckCircle2, Lock, Star, Trophy, Users, Zap } from "lucide-react";
import Leaderboard from "@/components/Leaderboard";

export default function Achievements() {
  return (
    <DashboardLayout>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-2xl font-bold text-primary">الإنجازات والرتب</h1>
          <p className="text-muted-foreground">تابع تقدمك واجمع الشارات لزيادة امتيازاتك</p>
        </div>
      </div>

      {/* Current Rank Progress */}
      <Card className="dashboard-card mb-8 bg-gradient-to-r from-primary/10 via-background to-background border-primary/20">
        <CardContent className="p-6 md:p-8">
          <div className="flex flex-col md:flex-row items-center gap-8">
            <div className="relative shrink-0">
              <div className="absolute inset-0 bg-secondary/20 blur-2xl rounded-full"></div>
              <img src="/images/badge-silver.png" alt="Silver Rank" className="relative h-32 w-32 object-contain drop-shadow-xl" />
            </div>
            
            <div className="flex-1 w-full space-y-4">
              <div className="flex justify-between items-end">
                <div>
                  <h2 className="text-2xl font-bold text-primary mb-1">الرتبة الحالية: فضي (موثق)</h2>
                  <p className="text-muted-foreground">أنت في الطريق للوصول للرتبة الذهبية!</p>
                </div>
                <div className="text-right">
                  <span className="text-3xl font-bold text-secondary">65%</span>
                </div>
              </div>
              
              <Progress value={65} className="h-4 bg-muted" />
              
              <div className="grid grid-cols-3 gap-4 text-center text-sm">
                <div className="p-3 bg-background rounded-lg border border-border">
                  <p className="text-muted-foreground mb-1">العمليات الناجحة</p>
                  <p className="font-bold text-foreground">32 / 50</p>
                </div>
                <div className="p-3 bg-background rounded-lg border border-border">
                  <p className="text-muted-foreground mb-1">التقييم العام</p>
                  <p className="font-bold text-foreground">4.8 / 5.0</p>
                </div>
                <div className="p-3 bg-background rounded-lg border border-border">
                  <p className="text-muted-foreground mb-1">اختبار المعرفة</p>
                  <p className="font-bold text-secondary">تم الاجتياز</p>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Tiers Info */}
      <div className="grid md:grid-cols-3 gap-6 mb-8">
        {/* Bronze */}
        <Card className="dashboard-card opacity-70 hover:opacity-100 transition-opacity">
          <CardHeader className="text-center pb-2">
            <img src="/images/badge-bronze.png" alt="Bronze" className="h-16 w-16 mx-auto mb-2 object-contain" />
            <CardTitle>مستكشف (برونزي)</CardTitle>
            <CardDescription>نقطة البداية لكل شريك</CardDescription>
          </CardHeader>
          <CardContent className="text-sm space-y-2">
            <div className="flex items-center gap-2 text-secondary">
              <CheckCircle2 className="h-4 w-4" />
              <span>إضافة سيارات للعرض</span>
            </div>
            <div className="flex items-center gap-2 text-secondary">
              <CheckCircle2 className="h-4 w-4" />
              <span>رابط إحالة خاص</span>
            </div>
            <div className="flex items-center gap-2 text-secondary">
              <CheckCircle2 className="h-4 w-4" />
              <span>عمولة أساسية 10%</span>
            </div>
          </CardContent>
        </Card>

        {/* Silver */}
        <Card className="dashboard-card border-primary ring-2 ring-primary/10 relative overflow-hidden">
          <div className="absolute top-0 right-0 bg-primary text-primary-foreground text-xs font-bold px-3 py-1 rounded-bl-lg">
            رتبتك الحالية
          </div>
          <CardHeader className="text-center pb-2">
            <img src="/images/badge-silver.png" alt="Silver" className="h-20 w-20 mx-auto mb-2 object-contain drop-shadow-md" />
            <CardTitle className="text-primary">موثق (فضي)</CardTitle>
            <CardDescription>للشركاء المعتمدين والموثوقين</CardDescription>
          </CardHeader>
          <CardContent className="text-sm space-y-2">
            <div className="flex items-center gap-2 text-secondary">
              <CheckCircle2 className="h-4 w-4" />
              <span>جميع مميزات البرونزي</span>
            </div>
            <div className="flex items-center gap-2 text-secondary">
              <CheckCircle2 className="h-4 w-4" />
              <span>مهام التصوير والمعاينة</span>
            </div>
            <div className="flex items-center gap-2 text-secondary">
              <CheckCircle2 className="h-4 w-4" />
              <span>عمولة إضافية للمهام</span>
            </div>
          </CardContent>
        </Card>

        {/* Gold */}
        <Card className="dashboard-card bg-muted/30">
          <CardHeader className="text-center pb-2">
            <div className="relative mx-auto mb-2 h-16 w-16 flex items-center justify-center">
              <img src="/images/badge-gold.png" alt="Gold" className="h-16 w-16 object-contain grayscale opacity-50" />
              <Lock className="absolute inset-0 m-auto h-6 w-6 text-muted-foreground" />
            </div>
            <CardTitle className="text-muted-foreground">سفير (ذهبي)</CardTitle>
            <CardDescription>للنخبة وقادة المجتمع</CardDescription>
          </CardHeader>
          <CardContent className="text-sm space-y-2 text-muted-foreground">
            <div className="flex items-center gap-2">
              <Lock className="h-3 w-3" />
              <span>تمثيل في نقل الملكية</span>
            </div>
            <div className="flex items-center gap-2">
              <Lock className="h-3 w-3" />
              <span>إدارة نزاعات البيع</span>
            </div>
            <div className="flex items-center gap-2">
              <Lock className="h-3 w-3" />
              <span>عمولة 20% + مكافآت</span>
            </div>
          </CardContent>
        </Card>
      </div>

      {/* Badges & Achievements */}
      <h2 className="text-xl font-bold mb-4 flex items-center gap-2">
        <Trophy className="h-5 w-5 text-secondary" />
        شارات الإنجاز
      </h2>
      <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-8">
        {[
          { title: "البداية القوية", desc: "أول 5 عمليات ناجحة", icon: Zap, earned: true },
          { title: "صائد المعارض", desc: "جلب 5 معارض", icon: Users, earned: true },
          { title: "المصور المحترف", desc: "50 صورة مقبولة", icon: Camera, earned: false },
          { title: "خمس نجوم", desc: "تقييم كامل لـ 10 مهام", icon: Star, earned: true },
          { title: "المليونير", desc: "مجموع عمولات 100 ألف", icon: Award, earned: false },
          { title: "الخبير التقني", desc: "اجتياز اختبار الفحص", icon: CheckCircle2, earned: false },
        ].map((badge, i) => (
          <Card key={i} className={cn(
            "text-center p-4 transition-all hover:shadow-md",
            badge.earned ? "bg-card border-secondary/30" : "bg-muted/50 border-transparent opacity-60 grayscale"
          )}>
            <div className={cn(
              "h-12 w-12 mx-auto rounded-full flex items-center justify-center mb-3",
              badge.earned ? "bg-secondary/10 text-secondary" : "bg-muted text-muted-foreground"
            )}>
              <badge.icon className="h-6 w-6" />
            </div>
            <h3 className="font-bold text-sm mb-1">{badge.title}</h3>
            <p className="text-[10px] text-muted-foreground">{badge.desc}</p>
          </Card>
        ))}
      </div>

      {/* Leaderboard */}
      <Leaderboard
        entries={[
          { rank: 1, name: "أحمد محمد", tier: "gold", totalCommission: 45000, completedDeals: 85, rating: 4.9 },
          { rank: 2, name: "خالد العتيبي", tier: "gold", totalCommission: 38000, completedDeals: 72, rating: 4.8 },
          { rank: 3, name: "فهد السالم", tier: "silver", totalCommission: 32000, completedDeals: 65, rating: 4.7 },
          { rank: 4, name: "سعود العتيبي", tier: "silver", totalCommission: 28000, completedDeals: 58, rating: 4.8, isCurrentUser: true },
          { rank: 5, name: "عبدالله القحطاني", tier: "silver", totalCommission: 25000, completedDeals: 52, rating: 4.6 },
          { rank: 6, name: "محمد الشمري", tier: "silver", totalCommission: 22000, completedDeals: 48, rating: 4.5 },
          { rank: 7, name: "يوسف الدوسري", tier: "bronze", totalCommission: 18000, completedDeals: 42, rating: 4.4 },
          { rank: 8, name: "عمر الزهراني", tier: "bronze", totalCommission: 15000, completedDeals: 38, rating: 4.3 },
          { rank: 9, name: "طارق الحربي", tier: "bronze", totalCommission: 12000, completedDeals: 35, rating: 4.2 },
          { rank: 10, name: "نواف المطيري", tier: "bronze", totalCommission: 10000, completedDeals: 32, rating: 4.1 },
        ]}
        region="الرياض"
      />
    </DashboardLayout>
  );
}
