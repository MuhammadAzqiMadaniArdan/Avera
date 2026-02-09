// components/orders/orders-table.tsx
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { Badge } from "@/components/ui/badge";
import { OrderStatus } from "@/lib/dummyData";

const statusColor: Record<OrderStatus, string> = {
  pending: "bg-yellow-100 text-yellow-800",
  paid: "bg-green-100 text-green-800",
  shipped: "bg-blue-100 text-blue-800",
  cancelled: "bg-red-100 text-red-800",
};
export function OrdersTable({
  orders,
  sort,
  onSortChange,
}: {
  orders: any[];
  sort: "asc" | "desc";
  onSortChange: (v: "asc" | "desc") => void;
}) {
  return (
    <div className="rounded-xl border bg-white">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Order ID</TableHead>
            <TableHead>Customer</TableHead>
            <TableHead>Status</TableHead>
            <TableHead>Total</TableHead>
            <TableHead>Date</TableHead>
            <TableHead
              onClick={() => onSortChange(sort === "asc" ? "desc" : "asc")}
              className="cursor-pointer select-none"
            >
              Total {sort === "asc" ? "↑" : "↓"}
            </TableHead>
          </TableRow>
        </TableHeader>

        <TableBody>
          {orders.map((order) => (
            <TableRow key={order.id}>
              <TableCell className="font-medium">{order.id}</TableCell>
              <TableCell>{order.customer}</TableCell>
              <TableCell>
                <Badge className={statusColor[order.status]}>
                  {order.status}
                </Badge>
              </TableCell>
              <TableCell>Rp {order.total.toLocaleString("id-ID")}</TableCell>
              <TableCell>{order.date}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  );
}
