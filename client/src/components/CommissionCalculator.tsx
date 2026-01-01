import { useState, useEffect, useRef } from "react";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { RadioGroup, RadioGroupItem } from "@/components/ui/radio-group";
import { Calculator, Coins, Info, Share2, Loader2, Save } from "lucide-react";
import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from "@/components/ui/tooltip";
import { Button } from "@/components/ui/button";
import { toPng } from "html-to-image";
import { toast } from "sonner";

export default function CommissionCalculator() {
  const [price, setPrice] = useState<string>("");
  const [clientName, setClientName] = useState<string>("");
  const [sellerType, setSellerType] = useState<"individual" | "showroom">("individual");
  const [commission, setCommission] = useState<number>(0);
  const [platformFee, setPlatformFee] = useState<number>(0);
  const [baseFee, setBaseFee] = useState<number>(0);
  const [isExporting, setIsExporting] = useState(false);
  const exportRef = useRef<HTMLDivElement>(null);

  const calculateCommission = (priceValue: number, type: "individual" | "showroom") => {
    let calculatedBaseFee = 0;

    // Determine Base Fee (C) based on price tiers
    if (priceValue <= 50000) calculatedBaseFee = 350;
    else if (priceValue <= 100000) calculatedBaseFee = 700;
    else if (priceValue <= 150000) calculatedBaseFee = 1000;
    else if (priceValue <= 200000) calculatedBaseFee = 1500;
    else {
      // Tier 5: 2500 + 1000 for every additional 100k
      calculatedBaseFee = 2500;
      const excess = priceValue - 200000;
      if (excess > 0) {
        const additionalUnits = Math.ceil(excess / 100000);
        calculatedBaseFee += additionalUnits * 1000;
      }
    }

    setBaseFee(calculatedBaseFee);

    let calculatedPlatformFee = 0;
    let calculatedCommission = 0;

    if (type === "individual") {
      // Individual: Platform gets 2 * C (Buyer + Seller)
      calculatedPlatformFee = calculatedBaseFee * 2;
      // Marketer gets max 20% of Platform Fee
      calculatedCommission = calculatedPlatformFee * 0.20;
    } else {
      // Showroom: Platform gets 20% of C (Buyer only, Seller is free)
      calculatedPlatformFee = calculatedBaseFee * 0.20;
      // Marketer gets max 20% of Platform Fee
      calculatedCommission = calculatedPlatformFee * 0.20;
    }

    setPlatformFee(calculatedPlatformFee);
    setCommission(calculatedCommission);
  };

  useEffect(() => {
    const numericPrice = parseFloat(price.replace(/,/g, ""));
    if (!isNaN(numericPrice) && numericPrice > 0) {
      calculateCommission(numericPrice, sellerType);
    } else {
      setCommission(0);
      setPlatformFee(0);
      setBaseFee(0);
    }
  }, [price, sellerType]);

  const saveQuote = () => {
    const quote = {
      id: Date.now(),
      clientName: clientName || "عميل بدون اسم",
      price: parseFloat(price || "0"),
      sellerType,
      commission,
      date: new Date().toISOString(),
    };

    const existingQuotes = JSON.parse(localStorage.getItem("dasme_quotes") || "[]");
    localStorage.setItem("dasme_quotes", JSON.stringify([quote, ...existingQuotes]));
    return quote;
  };

  const handleExport = async () => {
    if (!exportRef.current) return;
    
    setIsExporting(true);
    try {
      // Save to archive first
      saveQuote();
      
      const dataUrl = await toPng(exportRef.current, { cacheBust: true });
      const link = document.createElement('a');
      link.download = `dasme-quote-${clientName || 'client'}-${Date.now()}.png`;
      link.href = dataUrl;
      link.click();
      toast.success("تم حفظ العرض وتصدير الصورة بنجاح");
    } catch (err) {
      console.error(err);
      toast.error("حدث خطأ أثناء تصدير الصورة");
    } finally {
      setIsExporting(false);
    }
  };

  return (
    <>
      <Card className="dashboard-card bg-gradient-to-br from-card to-muted/30">
        <CardHeader>
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2">
              <div className="p-2 bg-primary/10 rounded-lg">
                <Calculator className="h-5 w-5 text-primary" />
              </div>
              <div>
                <CardTitle className="text-lg">حاسبة الأرباح</CardTitle>
                <CardDescription>احسب عمولتك فوراً</CardDescription>
              </div>
            </div>
            <Button 
              variant="outline" 
              size="icon" 
              onClick={handleExport}
              disabled={!price || isExporting}
              title="حفظ وتصدير عرض سعر"
            >
              {isExporting ? <Loader2 className="h-4 w-4 animate-spin" /> : <Share2 className="h-4 w-4" />}
            </Button>
          </div>
        </CardHeader>
        <CardContent className="space-y-6">
          <div className="space-y-2">
            <Label htmlFor="clientName">اسم العميل (اختياري)</Label>
            <Input
              id="clientName"
              placeholder="مثلاً: محمد العتيبي"
              value={clientName}
              onChange={(e) => setClientName(e.target.value)}
            />
          </div>

          <div className="space-y-2">
            <Label htmlFor="price">سعر السيارة المتوقع (ر.س)</Label>
            <div className="relative">
              <Input
                id="price"
                type="number"
                placeholder="مثلاً: 60000"
                value={price}
                onChange={(e) => setPrice(e.target.value)}
                className="pl-16 text-lg font-bold"
              />
              <span className="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground text-sm font-medium">
                ر.س
              </span>
            </div>
          </div>

          <div className="space-y-3">
            <Label>نوع البائع</Label>
            <RadioGroup 
              defaultValue="individual" 
              value={sellerType} 
              onValueChange={(v) => setSellerType(v as "individual" | "showroom")}
              className="grid grid-cols-2 gap-4"
            >
              <div>
                <RadioGroupItem value="individual" id="individual" className="peer sr-only" />
                <Label
                  htmlFor="individual"
                  className="flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary peer-data-[state=checked]:bg-primary/5 cursor-pointer transition-all"
                >
                  <span className="text-sm font-bold">بائع فرد</span>
                  <span className="text-[10px] text-muted-foreground mt-1">عمولة أعلى</span>
                </Label>
              </div>
              <div>
                <RadioGroupItem value="showroom" id="showroom" className="peer sr-only" />
                <Label
                  htmlFor="showroom"
                  className="flex flex-col items-center justify-between rounded-md border-2 border-muted bg-popover p-4 hover:bg-accent hover:text-accent-foreground peer-data-[state=checked]:border-primary peer-data-[state=checked]:bg-primary/5 cursor-pointer transition-all"
                >
                  <span className="text-sm font-bold">معرض شريك</span>
                  <span className="text-[10px] text-muted-foreground mt-1">عمولة رمزية</span>
                </Label>
              </div>
            </RadioGroup>
          </div>

          <div className="bg-muted/50 rounded-xl p-4 space-y-3 border border-border/50">
            <div className="flex items-center justify-between text-sm">
              <div className="flex items-center gap-1 text-muted-foreground">
                <span>دخل المنصة المقدر</span>
                <TooltipProvider>
                  <Tooltip>
                    <TooltipTrigger>
                      <Info className="h-3 w-3 cursor-help" />
                    </TooltipTrigger>
                    <TooltipContent>
                      <p>إجمالي الرسوم التي تحصلها المنصة من الصفقة</p>
                    </TooltipContent>
                  </Tooltip>
                </TooltipProvider>
              </div>
              <span className="font-medium">{platformFee.toLocaleString()} ر.س</span>
            </div>
            
            <div className="h-px bg-border"></div>
            
            <div className="flex items-center justify-between">
              <span className="font-bold text-primary flex items-center gap-2">
                <Coins className="h-4 w-4" />
                عمولتك المتوقعة
              </span>
              <span className="text-2xl font-bold text-secondary">
                {commission.toLocaleString()} <span className="text-sm font-medium text-muted-foreground">ر.س</span>
              </span>
            </div>
            <p className="text-[10px] text-muted-foreground text-center pt-1">
              * تمثل 20% من دخل المنصة كحد أقصى
            </p>
          </div>
        </CardContent>
      </Card>

      {/* Hidden Export Template */}
      <div className="fixed left-[-9999px] top-[-9999px]">
        <div 
          ref={exportRef} 
          className="w-[600px] bg-white p-8 rounded-xl border border-gray-200 shadow-sm font-sans"
          style={{ direction: 'rtl' }}
        >
          <div className="flex items-center justify-between mb-8 border-b pb-4">
            <div>
              <h1 className="text-2xl font-bold text-[#003366]">عرض رسوم الخدمة</h1>
              <p className="text-gray-500 mt-1">منصة DASMe لخدمات السيارات</p>
            </div>
            <img src="/images/dasmai-logo-official.jpg" alt="DASMe Logo" className="h-16 object-contain" />
          </div>

          <div className="space-y-6">
            {clientName && (
              <div className="bg-blue-50 p-4 rounded-lg border border-blue-100 mb-4">
                <p className="text-sm text-blue-600 mb-1">عرض خاص للعميل</p>
                <p className="text-xl font-bold text-[#003366]">{clientName}</p>
              </div>
            )}

            <div className="bg-gray-50 p-6 rounded-lg">
              <p className="text-sm text-gray-500 mb-2">سعر السيارة المقدر</p>
              <p className="text-3xl font-bold text-[#003366]">{parseFloat(price || "0").toLocaleString()} ر.س</p>
            </div>

            <div className="grid grid-cols-2 gap-6">
              <div className="border p-4 rounded-lg">
                <p className="text-sm font-bold text-gray-700 mb-1">رسوم المشتري</p>
                <p className="text-xl font-bold text-[#00B050]">
                  {(baseFee + (baseFee * 0.15)).toLocaleString()} ر.س
                </p>
                <p className="text-xs text-gray-400 mt-1">شامل الضريبة 15%</p>
              </div>
              
              <div className="border p-4 rounded-lg">
                <p className="text-sm font-bold text-gray-700 mb-1">رسوم البائع ({sellerType === 'individual' ? 'فرد' : 'معرض'})</p>
                <p className="text-xl font-bold text-[#00B050]">
                  {sellerType === 'individual' 
                    ? (baseFee + (baseFee * 0.15)).toLocaleString() 
                    : "0.00"
                  } ر.س
                </p>
                <p className="text-xs text-gray-400 mt-1">
                  {sellerType === 'individual' ? 'شامل الضريبة 15%' : 'معفى من الرسوم'}
                </p>
              </div>
            </div>

            <div className="mt-8 pt-6 border-t text-center text-gray-400 text-sm">
              <p>تم إصدار هذا العرض تلقائياً عبر نظام جيش المسوقين</p>
              <p className="mt-1">www.dasme.com</p>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
