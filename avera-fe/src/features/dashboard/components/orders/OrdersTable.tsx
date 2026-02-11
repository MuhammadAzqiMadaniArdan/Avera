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
import { Order, OrderStatus } from "@/features/order/types";
import { formatCurrency } from "@/lib/utils/formatCurrency";

const statusColor: Record<OrderStatus, string> = {
  [OrderStatus.awaiting_payment]: "bg-yellow-100 text-yellow-800",
  [OrderStatus.paid]: "bg-green-100 text-green-800",
  [OrderStatus.shipped]: "bg-blue-100 text-blue-800",
  [OrderStatus.cancelled]: "bg-red-100 text-red-800",
  [OrderStatus.pending]: "bg-gray-100 text-gray-800",
  [OrderStatus.processing]: "bg-purple-100 text-purple-800",
  [OrderStatus.completed]: "bg-teal-100 text-teal-800",
};

export function OrdersTable({
  orders,
  sort,
  onSortChange,
}: {
  orders: Order[];
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
              <TableCell>{order.id}</TableCell>
              <TableCell>
                <Badge className={statusColor[order.status]}>
                  {order.status}
                </Badge>
              </TableCell>
              <TableCell>{formatCurrency(order.total_price)}</TableCell>
              <TableCell>{order.created_at}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  );
}
