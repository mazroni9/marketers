import DashboardLayout from "@/components/DashboardLayout";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Textarea } from "@/components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { Camera, Car, MapPin, Phone, User, Upload, CheckCircle2 } from "lucide-react";
import { useState } from "react";
import { toast } from "sonner";

export default function AddClient() {
  const [formData, setFormData] = useState({
    ownerName: "",
    ownerPhone: "",
    ownerLocation: "",
    carBrand: "",
    carModel: "",
    carYear: "",
    carPrice: "",
    carMileage: "",
    carDescription: "",
    carType: "individual", // individual or showroom
  });

  const [uploadedImages, setUploadedImages] = useState<string[]>([]);

  const handleInputChange = (field: string, value: string) => {
    setFormData(prev => ({ ...prev, [field]: value }));
  };

  const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const files = e.target.files;
    if (files) {
      const newImages = Array.from(files).map(file => URL.createObjectURL(file));
      setUploadedImages(prev => [...prev, ...newImages]);
      toast.success(`تم رفع ${files.length} صورة بنجاح`);
    }
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Here you would normally send data to backend
    toast.success("تم إضافة العميل والمركبة بنجاح! سيتم ربطها بكودك تلقائياً");
    // Reset form
    setFormData({
      ownerName: "",
      ownerPhone: "",
      ownerLocation: "",
      carBrand: "",
      carModel: "",
      carYear: "",
      carPrice: "",
      carMileage: "",
      carDescription: "",
      carType: "individual",
    });
    setUploadedImages([]);
  };

  return (
    <DashboardLayout>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-2xl font-bold text-primary">أداة القنص - إضافة عميل/مركبة</h1>
          <p className="text-muted-foreground">أضف بيانات العميل والمركبة لربطها بكودك والحصول على العمولة</p>
        </div>
      </div>

      <form onSubmit={handleSubmit}>
        <div className="grid gap-6 md:grid-cols-2">
          {/* Owner Information */}
          <Card className="dashboard-card md:col-span-2">
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <User className="h-5 w-5 text-primary" />
                بيانات المالك
              </CardTitle>
              <CardDescription>معلومات المالك الأصلي للسيارة (إلزامي)</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid gap-4 md:grid-cols-2">
                <div className="space-y-2">
                  <Label htmlFor="ownerName">اسم المالك <span className="text-destructive">*</span></Label>
                  <Input
                    id="ownerName"
                    value={formData.ownerName}
                    onChange={(e) => handleInputChange("ownerName", e.target.value)}
                    placeholder="اسم المالك الكامل"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="ownerPhone">رقم الجوال <span className="text-destructive">*</span></Label>
                  <Input
                    id="ownerPhone"
                    type="tel"
                    value={formData.ownerPhone}
                    onChange={(e) => handleInputChange("ownerPhone", e.target.value)}
                    placeholder="05xxxxxxxx"
                    required
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="ownerLocation">موقع المالك <span className="text-destructive">*</span></Label>
                <Input
                  id="ownerLocation"
                  value={formData.ownerLocation}
                  onChange={(e) => handleInputChange("ownerLocation", e.target.value)}
                  placeholder="المدينة، الحي، الشارع"
                  required
                />
              </div>
              <div className="space-y-2">
                <Label htmlFor="carType">نوع المالك</Label>
                <Select value={formData.carType} onValueChange={(value) => handleInputChange("carType", value)}>
                  <SelectTrigger>
                    <SelectValue />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="individual">فرد</SelectItem>
                    <SelectItem value="showroom">معرض</SelectItem>
                  </SelectContent>
                </Select>
              </div>
            </CardContent>
          </Card>

          {/* Car Information */}
          <Card className="dashboard-card md:col-span-2">
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Car className="h-5 w-5 text-primary" />
                بيانات المركبة
              </CardTitle>
              <CardDescription>تفاصيل المركبة المراد عرضها على المنصة</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="grid gap-4 md:grid-cols-3">
                <div className="space-y-2">
                  <Label htmlFor="carBrand">الماركة <span className="text-destructive">*</span></Label>
                  <Input
                    id="carBrand"
                    value={formData.carBrand}
                    onChange={(e) => handleInputChange("carBrand", e.target.value)}
                    placeholder="مثال: تويوتا"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="carModel">الموديل <span className="text-destructive">*</span></Label>
                  <Input
                    id="carModel"
                    value={formData.carModel}
                    onChange={(e) => handleInputChange("carModel", e.target.value)}
                    placeholder="مثال: كامري"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="carYear">سنة الصنع <span className="text-destructive">*</span></Label>
                  <Input
                    id="carYear"
                    type="number"
                    value={formData.carYear}
                    onChange={(e) => handleInputChange("carYear", e.target.value)}
                    placeholder="2023"
                    min="1900"
                    max={new Date().getFullYear() + 1}
                    required
                  />
                </div>
              </div>
              <div className="grid gap-4 md:grid-cols-2">
                <div className="space-y-2">
                  <Label htmlFor="carPrice">السعر المتوقع (ريال) <span className="text-destructive">*</span></Label>
                  <Input
                    id="carPrice"
                    type="number"
                    value={formData.carPrice}
                    onChange={(e) => handleInputChange("carPrice", e.target.value)}
                    placeholder="مثال: 50000"
                    required
                  />
                </div>
                <div className="space-y-2">
                  <Label htmlFor="carMileage">الممشى (كم) <span className="text-destructive">*</span></Label>
                  <Input
                    id="carMileage"
                    type="number"
                    value={formData.carMileage}
                    onChange={(e) => handleInputChange("carMileage", e.target.value)}
                    placeholder="مثال: 50000"
                    required
                  />
                </div>
              </div>
              <div className="space-y-2">
                <Label htmlFor="carDescription">وصف المركبة</Label>
                <Textarea
                  id="carDescription"
                  value={formData.carDescription}
                  onChange={(e) => handleInputChange("carDescription", e.target.value)}
                  placeholder="وصف تفصيلي لحالة المركبة، المميزات، العيوب..."
                  rows={4}
                />
              </div>
            </CardContent>
          </Card>

          {/* Image Upload */}
          <Card className="dashboard-card md:col-span-2">
            <CardHeader>
              <CardTitle className="flex items-center gap-2">
                <Camera className="h-5 w-5 text-primary" />
                صور المركبة
              </CardTitle>
              <CardDescription>ارفع صور واضحة للمركبة (الحد الأدنى: 3 صور)</CardDescription>
            </CardHeader>
            <CardContent className="space-y-4">
              <div className="border-2 border-dashed border-border rounded-lg p-8 text-center hover:border-primary transition-colors">
                <Upload className="h-12 w-12 mx-auto mb-4 text-muted-foreground" />
                <Label htmlFor="images" className="cursor-pointer">
                  <span className="text-primary font-medium hover:underline">اضغط لاختيار الصور</span>
                  <span className="text-muted-foreground"> أو اسحبها هنا</span>
                </Label>
                <Input
                  id="images"
                  type="file"
                  accept="image/*"
                  multiple
                  onChange={handleImageUpload}
                  className="hidden"
                />
                <p className="text-xs text-muted-foreground mt-2">PNG, JPG حتى 10MB لكل صورة</p>
              </div>
              
              {uploadedImages.length > 0 && (
                <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                  {uploadedImages.map((img, idx) => (
                    <div key={idx} className="relative group">
                      <img src={img} alt={`Car ${idx + 1}`} className="w-full h-32 object-cover rounded-lg border border-border" />
                      <button
                        type="button"
                        onClick={() => setUploadedImages(prev => prev.filter((_, i) => i !== idx))}
                        className="absolute top-2 left-2 bg-destructive text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity"
                      >
                        ×
                      </button>
                    </div>
                  ))}
                </div>
              )}
            </CardContent>
          </Card>

          {/* Info Card */}
          <Card className="dashboard-card md:col-span-2 bg-primary/5 border-primary/20">
            <CardContent className="p-6">
              <div className="flex items-start gap-3">
                <CheckCircle2 className="h-5 w-5 text-primary mt-0.5 shrink-0" />
                <div className="space-y-2 text-sm">
                  <p className="font-medium text-primary">معلومات مهمة:</p>
                  <ul className="space-y-1 text-muted-foreground list-disc list-inside">
                    <li>سيتم ربط هذه المركبة بكودك تلقائياً عند إتمام البيع</li>
                    <li>ستحصل على عمولة {formData.carType === "individual" ? "20%" : "4%"} من رسوم المنصة عند البيع</li>
                    <li>تأكد من صحة بيانات المالك لضمان استحقاقك للعمولة</li>
                    <li>يمكنك متابعة حالة المركبة من صفحة "الإحالات"</li>
                  </ul>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <div className="flex justify-end gap-4 mt-6">
          <Button type="button" variant="outline">إلغاء</Button>
          <Button type="submit" className="gap-2">
            <CheckCircle2 className="h-4 w-4" />
            إضافة المركبة والعميل
          </Button>
        </div>
      </form>
    </DashboardLayout>
  );
}

