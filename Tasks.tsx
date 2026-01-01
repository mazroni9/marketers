import DashboardLayout from "@/components/DashboardLayout";
import { MapView } from "@/components/Map";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from "@/components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs";
import { cn } from "@/lib/utils";
import { Camera, Car, CheckCircle2, Clock, MapPin, Navigation } from "lucide-react";
import { useRef, useState } from "react";

export default function Tasks() {
  const mapRef = useRef<google.maps.Map | null>(null);
  const [showMap, setShowMap] = useState(false);

  // الرياض - المركز الافتراضي
  const riyadhCenter = { lat: 24.7136, lng: 46.6753 };

  const tasks = [
    { 
      id: "TSK-101", 
      type: "photography", 
      title: "تصوير سيارة للعرض", 
      location: "حي الملقا، الرياض", 
      distance: "2.5 كم", 
      reward: "150 ر.س", 
      urgent: true,
      car: "تويوتا لاندكروزر 2023",
      coords: { lat: 24.8136, lng: 46.6153 }
    },
    { 
      id: "TSK-102", 
      type: "inspection", 
      title: "فحص مبدئي ومعاينة", 
      location: "حي النرجس، الرياض", 
      distance: "5.0 كم", 
      reward: "200 ر.س", 
      urgent: false,
      car: "هيونداي سوناتا 2022",
      coords: { lat: 24.8336, lng: 46.6553 }
    },
    { 
      id: "TSK-103", 
      type: "photography", 
      title: "تصوير سيارة للعرض", 
      location: "حي الياسمين، الرياض", 
      distance: "3.2 كم", 
      reward: "150 ر.س", 
      urgent: false,
      car: "مرسيدس E200 2021",
      coords: { lat: 24.8236, lng: 46.6353 }
    },
    { 
      id: "TSK-104", 
      type: "transfer", 
      title: "تمثيل في نقل ملكية", 
      location: "معارض القادسية", 
      distance: "15 كم", 
      reward: "300 ر.س", 
      urgent: true,
      car: "فورد تورس 2023",
      coords: { lat: 24.7936, lng: 46.8153 }
    },
    { 
      id: "TSK-105", 
      type: "inspection", 
      title: "فحص مبدئي ومعاينة", 
      location: "حي العقيق، الرياض", 
      distance: "4.1 كم", 
      reward: "200 ر.س", 
      urgent: false,
      car: "مازدا CX-9 2024",
      coords: { lat: 24.7736, lng: 46.6253 }
    },
  ];

  const handleMapReady = (map: google.maps.Map) => {
    mapRef.current = map;
    
    // إضافة علامات للمهام
    tasks.forEach(task => {
      const markerContent = document.createElement("div");
      markerContent.className = cn(
        "flex items-center justify-center w-8 h-8 rounded-full border-2 border-white shadow-lg text-white",
        task.type === "photography" ? "bg-blue-600" :
        task.type === "inspection" ? "bg-purple-600" : "bg-orange-600"
      );
      
      const icon = document.createElement("div");
      icon.innerHTML = task.type === "photography" ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>' : 
                       task.type === "inspection" ? '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>' :
                       '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>';
      markerContent.appendChild(icon);

      new google.maps.marker.AdvancedMarkerElement({
        map,
        position: task.coords,
        title: task.title,
        content: markerContent,
      });
    });
  };

  return (
    <DashboardLayout>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h1 className="text-2xl font-bold text-primary">المهام الميدانية</h1>
          <p className="text-muted-foreground">أنجز المهام القريبة منك لزيادة دخلك وترقية رتبتك</p>
        </div>
        <div className="flex gap-2">
          <Button 
            variant={showMap ? "secondary" : "outline"} 
            className="gap-2"
            onClick={() => setShowMap(!showMap)}
          >
            <MapPin className="h-4 w-4" />
            {showMap ? "إخفاء الخريطة" : "عرض الخريطة"}
          </Button>
        </div>
      </div>

      {showMap && (
        <Card className="dashboard-card mb-6 overflow-hidden animate-in fade-in slide-in-from-top-4 duration-500">
          <CardHeader className="pb-0">
            <CardTitle>خريطة المهام</CardTitle>
            <CardDescription>استكشف المهام المتاحة في منطقتك</CardDescription>
          </CardHeader>
          <CardContent className="p-0 mt-4 h-[400px] relative">
            <MapView 
              initialCenter={riyadhCenter}
              initialZoom={11}
              onMapReady={handleMapReady}
              className="h-full w-full"
            />
            <div className="absolute bottom-4 right-4 bg-card/90 backdrop-blur p-2 rounded-lg shadow-lg border border-border text-xs space-y-1">
              <div className="flex items-center gap-2">
                <div className="w-3 h-3 rounded-full bg-blue-600"></div>
                <span>تصوير</span>
              </div>
              <div className="flex items-center gap-2">
                <div className="w-3 h-3 rounded-full bg-purple-600"></div>
                <span>فحص</span>
              </div>
              <div className="flex items-center gap-2">
                <div className="w-3 h-3 rounded-full bg-orange-600"></div>
                <span>نقل ملكية</span>
              </div>
            </div>
          </CardContent>
        </Card>
      )}

      <Tabs defaultValue="available" className="w-full">
        <TabsList className="mb-6 w-full md:w-auto justify-start bg-muted/50 p-1">
          <TabsTrigger value="available">المهام المتاحة (5)</TabsTrigger>
          <TabsTrigger value="active">قيد التنفيذ (1)</TabsTrigger>
          <TabsTrigger value="completed">المكتملة (12)</TabsTrigger>
        </TabsList>

        <TabsContent value="available" className="space-y-6">
          <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            {tasks.map((task) => (
              <Card key={task.id} className="dashboard-card hover:border-primary/50 transition-colors group">
                <CardHeader className="pb-3">
                  <div className="flex justify-between items-start">
                    <div className={cn(
                      "px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider",
                      task.type === "photography" ? "bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300" :
                      task.type === "inspection" ? "bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300" :
                      "bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-300"
                    )}>
                      {task.type === "photography" ? "تصوير" : task.type === "inspection" ? "فحص" : "نقل ملكية"}
                    </div>
                    {task.urgent && (
                      <span className="flex h-2 w-2 rounded-full bg-destructive animate-pulse"></span>
                    )}
                  </div>
                  <CardTitle className="mt-2 text-lg">{task.title}</CardTitle>
                  <CardDescription className="flex items-center gap-1 mt-1">
                    <Car className="h-3 w-3" /> {task.car}
                  </CardDescription>
                </CardHeader>
                <CardContent className="pb-3">
                  <div className="space-y-2 text-sm text-muted-foreground">
                    <div className="flex items-center gap-2">
                      <MapPin className="h-4 w-4 text-primary" />
                      <span>{task.location}</span>
                    </div>
                    <div className="flex items-center gap-2">
                      <Navigation className="h-4 w-4 text-primary" />
                      <span>يبعد عنك {task.distance}</span>
                    </div>
                  </div>
                </CardContent>
                <CardFooter className="pt-3 border-t border-border flex items-center justify-between">
                  <div className="font-bold text-lg text-secondary">{task.reward}</div>
                  <Button size="sm" className={cn(
                    task.urgent ? "bg-destructive hover:bg-destructive/90" : ""
                  )} onClick={() => {
                    if (showMap && mapRef.current) {
                      mapRef.current.panTo(task.coords);
                      mapRef.current.setZoom(15);
                      window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                      setShowMap(true);
                      setTimeout(() => {
                        if (mapRef.current) {
                          mapRef.current.panTo(task.coords);
                          mapRef.current.setZoom(15);
                          window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                      }, 100);
                    }
                  }}>
                    عرض الموقع
                  </Button>
                </CardFooter>
              </Card>
            ))}
          </div>
        </TabsContent>

        <TabsContent value="active">
          <Card className="dashboard-card border-primary/50 bg-primary/5">
            <CardHeader>
              <div className="flex justify-between items-start">
                <div>
                  <CardTitle>تصوير سيارة للعرض - حي حطين</CardTitle>
                  <CardDescription className="mt-1">لكزس ES 2023 • لون تيتانيوم</CardDescription>
                </div>
                <div className="bg-primary text-primary-foreground px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                  جاري التنفيذ
                </div>
              </div>
            </CardHeader>
            <CardContent>
              <div className="grid md:grid-cols-3 gap-6">
                <div className="space-y-4">
                  <div className="flex items-center gap-2 text-sm">
                    <Clock className="h-4 w-4 text-muted-foreground" />
                    <span>الوقت المتبقي: <span className="font-bold text-destructive">01:45:00</span></span>
                  </div>
                  <div className="flex items-center gap-2 text-sm">
                    <MapPin className="h-4 w-4 text-muted-foreground" />
                    <span>الموقع: شارع الأمير تركي الأول</span>
                  </div>
                  <Button variant="outline" className="w-full gap-2">
                    <Navigation className="h-4 w-4" />
                    توجيه للموقع
                  </Button>
                </div>
                
                <div className="md:col-span-2 bg-background rounded-lg p-4 border border-border">
                  <h4 className="font-medium mb-3 flex items-center gap-2">
                    <Camera className="h-4 w-4 text-primary" />
                    متطلبات التصوير
                  </h4>
                  <ul className="space-y-2 text-sm text-muted-foreground">
                    <li className="flex items-center gap-2">
                      <CheckCircle2 className="h-4 w-4 text-secondary" />
                      صورة أمامية بزاوية 45 درجة
                    </li>
                    <li className="flex items-center gap-2">
                      <CheckCircle2 className="h-4 w-4 text-secondary" />
                      صورة خلفية بزاوية 45 درجة
                    </li>
                    <li className="flex items-center gap-2">
                      <CheckCircle2 className="h-4 w-4 text-secondary" />
                      صور للمقصورة الداخلية (طبلون، مقاعد)
                    </li>
                    <li className="flex items-center gap-2">
                      <div className="h-4 w-4 rounded-full border-2 border-muted"></div>
                      صورة للعداد والممشى
                    </li>
                  </ul>
                  <Button className="w-full mt-4">رفع الصور وإنهاء المهمة</Button>
                </div>
              </div>
            </CardContent>
          </Card>
        </TabsContent>
      </Tabs>
    </DashboardLayout>
  );
}
