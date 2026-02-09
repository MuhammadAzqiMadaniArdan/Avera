import { Card, CardContent } from "@/components/ui/card";
import { TrendingUp, TrendingDown } from "lucide-react";
import { cn } from "@/lib/utils";

export function StatCard({
  title,
  value,
  change,
  description,
  trend,
}: {
  title: string;
  value: string;
  change: string;
  description: string;
  trend: "up" | "down";
}) {
  const TrendIcon = trend === "up" ? TrendingUp : TrendingDown;

  return (
    <Card>
      <CardContent className="p-5 space-y-3">
        <p className="text-sm text-muted-foreground">{title}</p>

        <div className="flex items-center justify-between">
          <p className="text-2xl font-bold">{value}</p>

          <div
            className={cn(
              "flex items-center gap-1 text-sm font-medium",
              trend === "up"
                ? "text-green-600"
                : "text-red-500"
            )}
          >
            <TrendIcon className="h-4 w-4" />
            {change}
          </div>
        </div>

        <p className="text-xs text-muted-foreground">
          {description}
        </p>
      </CardContent>
    </Card>
  );
}
