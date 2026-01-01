import DashboardLayout from "@/components/DashboardLayout";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { cn } from "@/lib/utils";
import { ArrowDownLeft, ArrowUpRight, CreditCard, Download, Info } from "lucide-react";
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from "@/components/ui/tooltip";
import CommissionCalculator from "@/components/CommissionCalculator";

export default function Wallet() {
  return (
    <DashboardLayout>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-2xl font-bold text-primary">المحفظة الرقمية</h1>
          <p className="text-muted-foreground">إدارة رصيدك وعمولاتك وسحب الأرباح</p>
        </div>
        <Button className="gap-2">
          <Download className="h-4 w-4" />
          تصدير التقرير المالي
        </Button>
      </div>

      <div className="grid gap-6 lg:grid-cols-3 mb-8">
        {/* Calculator Widget */}
        <div className="lg:col-span-1">
          <CommissionCalculator />
        </div>

        {/* Balance Cards Container */}
        <div className="lg:col-span-2 grid gap-6 md:grid-cols-2">
        <Card className="bg-primary text-primary-foreground border-none shadow-lg relative overflow-hidden">
          <div className="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-primary-foreground/80">الرصيد القابل للسحب</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold mb-4">4,250.00 ر.س</div>
            <Button variant="secondary" className="w-full font-bold shadow-sm">
              <CreditCard className="ml-2 h-4 w-4" />
              طلب سحب رصيد
            </Button>
          </CardContent>
        </Card>

        <Card className="dashboard-card">
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">الرصيد المعلق (للضمان)</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold text-foreground mb-1">1,800.00 ر.س</div>
            <p className="text-xs text-muted-foreground">يتحرر بعد اكتمال فترة الضمان (7 أيام)</p>
          </CardContent>
        </Card>

        <Card className="dashboard-card">
          <CardHeader className="pb-2">
            <CardTitle className="text-sm font-medium text-muted-foreground">قيد التحقق</CardTitle>
          </CardHeader>
          <CardContent>
            <div className="text-3xl font-bold text-foreground mb-1">6,400.00 ر.س</div>
            <p className="text-xs text-muted-foreground">بانتظار تأكيد استلام السيارات</p>
          </CardContent>
        </Card>
        </div>
      </div>

      {/* Transactions History */}
      <Card className="dashboard-card">
        <CardHeader>
          <CardTitle>سجل العمليات</CardTitle>
          <CardDescription>كشف حساب تفصيلي لجميع الحركات المالية (الحد الأقصى للعمولة 20% من رسوم المنصة)</CardDescription>
        </CardHeader>
        <CardContent>
          <Tabs defaultValue="all" className="w-full">
            <TabsList className="mb-4 w-full justify-start bg-muted/50 p-1">
              <TabsTrigger value="all">الكل</TabsTrigger>
              <TabsTrigger value="income">العمولات (دائن)</TabsTrigger>
              <TabsTrigger value="withdrawal">السحوبات (مدين)</TabsTrigger>
              <TabsTrigger value="pending">المعلقة</TabsTrigger>
            </TabsList>
            
            <TabsContent value="all" className="space-y-4">
              {[
                { 
                  id: "TRX-9823", 
                  type: "income", 
                  title: "عمولة بيع - تويوتا كامري 2023", 
                  amount: "+280.00", 
                  details: "بائع فرد - الشريحة الثانية (700 ر.س)",
                  date: "31 ديسمبر 2025", 
                  time: "10:30 ص", 
                  status: "completed" 
                },
                { 
                  id: "TRX-9822", 
                  type: "withdrawal", 
                  title: "سحب رصيد إلى بنك الراجحي", 
                  amount: "-5,000.00", 
                  details: "تحويل بنكي مباشر",
                  date: "30 ديسمبر 2025", 
                  time: "04:15 م", 
                  status: "processing" 
                },
                { 
                  id: "TRX-9821", 
                  type: "income", 
                  title: "عمولة بيع - هيونداي سوناتا (معرض)", 
                  amount: "+28.00", 
                  details: "معرض شريك - الشريحة الثانية (700 ر.س)",
                  date: "28 ديسمبر 2025", 
                  time: "09:00 ص", 
                  status: "completed" 
                },
                { 
                  id: "TRX-9820", 
                  type: "income", 
                  title: "مكافأة إحالة عميل جديد", 
                  amount: "+50.00", 
                  details: "تسجيل وتفعيل حساب جديد",
                  date: "25 ديسمبر 2025", 
                  time: "02:45 م", 
                  status: "completed" 
                },
                { 
                  id: "TRX-9819", 
                  type: "income", 
                  title: "عمولة بيع - فورد تورس 2022", 
                  amount: "+400.00", 
                  details: "بائع فرد - الشريحة الثالثة (1000 ر.س)",
                  date: "20 ديسمبر 2025", 
                  time: "11:20 ص", 
                  status: "completed" 
                },
              ].map((trx) => (
                <div key={trx.id} className="flex items-center justify-between p-4 border border-border rounded-lg hover:bg-muted/30 transition-colors">
                  <div className="flex items-center gap-4">
                    <div className={cn(
                      "h-12 w-12 rounded-full flex items-center justify-center",
                      trx.type === "withdrawal" ? "bg-destructive/10 text-destructive" : "bg-secondary/10 text-secondary"
                    )}>
                      {trx.type === "withdrawal" ? <ArrowUpRight className="h-6 w-6" /> : <ArrowDownLeft className="h-6 w-6" />}
                    </div>
                    <div>
                      <div className="flex items-center gap-2">
                        <p className="font-bold text-foreground">{trx.title}</p>
                        <span className="text-[10px] text-muted-foreground bg-muted px-2 py-0.5 rounded-full">{trx.id}</span>
                      </div>
                      <div className="flex items-center gap-2 mt-1">
                        <p className="text-sm text-muted-foreground">{trx.date} • {trx.time}</p>
                        {trx.type === "income" && (
                          <TooltipProvider>
                            <Tooltip>
                              <TooltipTrigger>
                                <Info className="h-3 w-3 text-muted-foreground hover:text-primary cursor-help" />
                              </TooltipTrigger>
                              <TooltipContent>
                                <p>{trx.details}</p>
                              </TooltipContent>
                            </Tooltip>
                          </TooltipProvider>
                        )}
                      </div>
                    </div>
                  </div>
                  <div className="text-right">
                    <p className={cn(
                      "text-lg font-bold",
                      trx.type === "withdrawal" ? "text-foreground" : "text-secondary"
                    )}>
                      {trx.amount} ر.س
                    </p>
                    <p className={cn(
                      "text-xs mt-1 font-medium",
                      trx.status === "completed" ? "text-secondary" : "text-yellow-600"
                    )}>
                      {trx.status === "completed" ? "مكتمل" : "قيد المعالجة"}
                    </p>
                  </div>
                </div>
              ))}
            </TabsContent>
          </Tabs>
        </CardContent>
      </Card>
    </DashboardLayout>
  );
}
