import { PackageX } from "lucide-react";
import { Button } from "@/components/ui/button";

export function OrdersEmpty() {
  return (
    <div className="flex flex-col items-center justify-center rounded-xl border bg-white py-20 text-center">
      <PackageX className="mb-4 h-10 w-10 text-muted-foreground" />
      <h3 className="text-lg font-semibold">No orders found</h3>
      <p className="mb-4 text-sm text-muted-foreground">
        Orders will appear here once customers place them.
      </p>
      <Button variant="outline">Refresh</Button>
    </div>
  );
}
