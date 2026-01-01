import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Trophy, Medal, Award } from "lucide-react";
import { cn } from "@/lib/utils";

interface LeaderboardEntry {
  rank: number;
  name: string;
  tier: "bronze" | "silver" | "gold";
  totalCommission: number;
  completedDeals: number;
  rating: number;
  isCurrentUser?: boolean;
}

interface LeaderboardProps {
  entries: LeaderboardEntry[];
  region?: string;
}

export default function Leaderboard({ entries, region = "المنطقة" }: LeaderboardProps) {
  const getTierBadge = (tier: string) => {
    switch (tier) {
      case "gold":
        return <Badge className="bg-yellow-500/10 text-yellow-700 border-yellow-500/20">ذهبي</Badge>;
      case "silver":
        return <Badge className="bg-gray-400/10 text-gray-700 border-gray-400/20">فضي</Badge>;
      default:
        return <Badge className="bg-orange-600/10 text-orange-700 border-orange-600/20">برونزي</Badge>;
    }
  };

  const getRankIcon = (rank: number) => {
    if (rank === 1) return <Trophy className="h-6 w-6 text-yellow-500" />;
    if (rank === 2) return <Medal className="h-6 w-6 text-gray-400" />;
    if (rank === 3) return <Award className="h-6 w-6 text-orange-600" />;
    return <span className="text-lg font-bold text-muted-foreground">#{rank}</span>;
  };

  return (
    <Card className="dashboard-card">
      <CardHeader>
        <CardTitle className="flex items-center gap-2">
          <Trophy className="h-5 w-5 text-secondary" />
          لوحة الشرف - {region}
        </CardTitle>
        <CardDescription>أفضل 10 شركاء حسب الأداء والإنجازات</CardDescription>
      </CardHeader>
      <CardContent>
        <div className="space-y-3">
          {entries.map((entry) => (
            <div
              key={entry.rank}
              className={cn(
                "flex items-center gap-4 p-4 rounded-lg border transition-all",
                entry.isCurrentUser
                  ? "bg-primary/5 border-primary/30 ring-2 ring-primary/10"
                  : "bg-card border-border hover:bg-muted/30"
              )}
            >
              <div className="flex items-center justify-center w-12 h-12 shrink-0">
                {getRankIcon(entry.rank)}
              </div>
              
              <div className="flex-1 min-w-0">
                <div className="flex items-center gap-2 mb-1">
                  <span className={cn(
                    "font-bold truncate",
                    entry.isCurrentUser ? "text-primary" : "text-foreground"
                  )}>
                    {entry.name}
                  </span>
                  {entry.isCurrentUser && (
                    <Badge variant="outline" className="text-xs">أنت</Badge>
                  )}
                  {getTierBadge(entry.tier)}
                </div>
                <div className="flex items-center gap-4 text-xs text-muted-foreground">
                  <span>{entry.completedDeals} عملية</span>
                  <span>•</span>
                  <span>⭐ {entry.rating.toFixed(1)}</span>
                </div>
              </div>
              
              <div className="text-left shrink-0">
                <div className="text-lg font-bold text-secondary">
                  {entry.totalCommission.toLocaleString()} ر.س
                </div>
                <div className="text-xs text-muted-foreground">إجمالي العمولات</div>
              </div>
            </div>
          ))}
        </div>
      </CardContent>
    </Card>
  );
}

