// app/(admin)/orders/[id]/page.tsx
import { orders } from "@/lib/dummyData";
import { Badge } from "@/components/ui/badge";

export default function OrderDetailPage({
  params,
}: {
  params: { id: string };
}) {
  const order = orders.find((o) => o.id === params.id);

  if (!order) return <div>Order not found</div>;

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-2xl font-semibold">{order.id}</h1>
        <p className="text-muted-foreground">Order detail</p>
      </div>

      <div className="grid gap-6 md:grid-cols-3">
        <div className="rounded-xl border bg-white p-4">
          <h3 className="mb-2 font-medium">Customer</h3>
          <p>{order.customer}</p>
        </div>

        <div className="rounded-xl border bg-white p-4">
          <h3 className="mb-2 font-medium">Status</h3>
          <Badge>{order.status}</Badge>
        </div>

        <div className="rounded-xl border bg-white p-4">
          <h3 className="mb-2 font-medium">Total</h3>
          <p>Rp {order.total.toLocaleString("id-ID")}</p>
        </div>
      </div>
    </div>
  );
}
