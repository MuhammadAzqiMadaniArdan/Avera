import { Badge } from "@/components/ui/badge";
import { Order } from "@/lib/dummyData";

export function OrderStatusBadge({ status }: { status: Order["status"] }) {
  const variant =
    status === "paid"
      ? "default"
      : status === "pending"
      ? "secondary"
      : status === "shipped"
      ? "outline"
      : "destructive";

  return <Badge variant={variant}>{status.toUpperCase()}</Badge>;
}
