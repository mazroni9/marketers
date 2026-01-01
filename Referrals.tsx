import DashboardLayout from "@/components/DashboardLayout";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Copy, Share2, Users, UserPlus, Search, Filter } from "lucide-react";
import { toast } from "sonner";
import { cn } from "@/lib/utils";

export default function Referrals() {
  const referralCode = "SAUD-2025";
  const referralLink = "https://dasme.com/register?ref=SAUD-2025";

  const copyToClipboard = (text: string, message: string) => {
    navigator.clipboard.writeText(text);
    toast.success(message);
  };

  return (
    <DashboardLayout>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-2xl font-bold text-primary">الإحالات والعملاء</h1>
          <p className="text-muted-foreground">إدارة شبكة عملائك ومتابعة نشاطهم</p>
        </div>
        <Button className="gap-2">
          <UserPlus className="h-4 w-4" />
          تسجيل عميل جديد
        </Button>
      </div>

      {/* Referral Tools */}
      <Card className="dashboard-card mb-8 bg-primary/5 border-primary/20">
        <CardHeader>
          <CardTitle className="text-primary">أدوات التسويق الخاصة بك</CardTitle>
          <CardDescription>شارك الرابط أو الكود مع عملائك لتحصل على العمولة تلقائياً</CardDescription>
        </CardHeader>
        <CardContent>
          <div className="grid gap-6 md:grid-cols-2">
            <div className="space-y-2">
              <label className="text-sm font-medium text-muted-foreground">رابط الإحالة المباشر</label>
              <div className="flex gap-2">
                <Input value={referralLink} readOnly className="bg-background font-mono text-sm" />
                <Button variant="outline" size="icon" onClick={() => copyToClipboard(referralLink, "تم نسخ الرابط بنجاح")}>
                  <Copy className="h-4 w-4" />
                </Button>
                <Button variant="secondary" size="icon">
                  <Share2 className="h-4 w-4" />
                </Button>
              </div>
            </div>
            <div className="space-y-2">
              <label className="text-sm font-medium text-muted-foreground">كود الخصم الخاص بك</label>
              <div className="flex gap-2">
                <div className="flex-1 bg-background border border-border rounded-md flex items-center justify-center font-mono font-bold text-lg tracking-wider text-primary">
                  {referralCode}
                </div>
                <Button variant="outline" size="icon" onClick={() => copyToClipboard(referralCode, "تم نسخ الكود بنجاح")}>
                  <Copy className="h-4 w-4" />
                </Button>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      {/* Referrals List */}
      <Card className="dashboard-card">
        <CardHeader className="flex flex-row items-center justify-between">
          <div>
            <CardTitle>قائمة العملاء</CardTitle>
            <CardDescription>45 عميل مسجل عن طريقك</CardDescription>
          </div>
          <div className="flex gap-2">
            <div className="relative w-64">
              <Search className="absolute right-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input placeholder="بحث بالاسم أو الجوال..." className="pr-9" />
            </div>
            <Button variant="outline" size="icon">
              <Filter className="h-4 w-4" />
            </Button>
          </div>
        </CardHeader>
        <CardContent>
          <div className="rounded-md border border-border overflow-hidden">
            <table className="w-full text-sm text-right">
              <thead className="bg-muted/50 text-muted-foreground font-medium">
                <tr>
                  <th className="p-4">العميل</th>
                  <th className="p-4">تاريخ التسجيل</th>
                  <th className="p-4">عدد السيارات</th>
                  <th className="p-4">إجمالي العمولات</th>
                  <th className="p-4">الحالة</th>
                  <th className="p-4">الإجراءات</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-border">
                {[
                  { name: "محمد عبدالله", phone: "055xxxxxxx", date: "2025-12-28", cars: 3, commission: "450 ر.س", status: "active" },
                  { name: "شركة الأفق للسيارات", phone: "050xxxxxxx", date: "2025-12-25", cars: 12, commission: "3,200 ر.س", status: "active" },
                  { name: "خالد العتيبي", phone: "056xxxxxxx", date: "2025-12-20", cars: 1, commission: "150 ر.س", status: "inactive" },
                  { name: "معرض القمة", phone: "054xxxxxxx", date: "2025-12-15", cars: 8, commission: "1,800 ر.س", status: "active" },
                  { name: "سعد المالكي", phone: "059xxxxxxx", date: "2025-12-10", cars: 0, commission: "0 ر.س", status: "new" },
                ].map((client, i) => (
                  <tr key={i} className="hover:bg-muted/30 transition-colors">
                    <td className="p-4">
                      <div className="flex items-center gap-3">
                        <div className="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                          {client.name.charAt(0)}
                        </div>
                        <div>
                          <p className="font-medium">{client.name}</p>
                          <p className="text-xs text-muted-foreground">{client.phone}</p>
                        </div>
                      </div>
                    </td>
                    <td className="p-4 text-muted-foreground">{client.date}</td>
                    <td className="p-4 font-medium">{client.cars}</td>
                    <td className="p-4 font-bold text-secondary">{client.commission}</td>
                    <td className="p-4">
                      <span className={cn(
                        "px-2 py-1 rounded-full text-xs font-medium",
                        client.status === "active" ? "bg-secondary/10 text-secondary" :
                        client.status === "new" ? "bg-blue-500/10 text-blue-600" :
                        "bg-muted text-muted-foreground"
                      )}>
                        {client.status === "active" ? "نشط" : client.status === "new" ? "جديد" : "غير نشط"}
                      </span>
                    </td>
                    <td className="p-4">
                      <Button variant="ghost" size="sm">التفاصيل</Button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </CardContent>
      </Card>
    </DashboardLayout>
  );
}
