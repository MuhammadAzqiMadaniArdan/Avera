"use client";

import { getStatusColor, getStatusLabel } from "@/lib/utils/purchaseStatus";
import { UserPurchase } from "../types";
import { Button } from "@/components/ui/button";
import Link from "next/link";
import { formatCurrency } from "@/lib/utils/formatCurrency";

interface Props {
  order: UserPurchase;
}

export function OrderCard({ order }: Props) {
  return (
    <div className="border rounded-xl bg-white p-4 space-y-4">
      {/* HEADER */}
      <div className="flex justify-between items-center">
        <p className="text-sm text-gray-500">
          Order ID: <span className="font-medium">{order.id}</span>
        </p>
        <span
          className={`text-sm font-semibold ${getStatusColor(order.status)}`}
        >
          {getStatusLabel(order.status)}
        </span>
      </div>

      {/* ITEMS */}
      <div className="space-y-2">
        {order.order_items.map((item) => (
          <div key={item.id} className="flex justify-between text-sm">
            <span>
              Product : {item.product?.name} × {item.quantity}
            </span>
            <span className="font-medium"> 
              {formatCurrency(item.price)}
              </span>
          </div>
        ))}
      </div>

      {/* FOOTER */}
      <div className="flex justify-between items-center border-t pt-3">
        <p className="font-semibold">
          Total: {formatCurrency(order.total_price)}
        </p>

        <div className="flex gap-2">
          {order.status === "unpaid" && order.payment?.payment_url && (
            <Button
              onClick={() => window.open(order.payment?.payment_url, "_blank")}
            >
              Pay Now
            </Button>
          )}
          {order.status === "completed" && (
            <Button variant="outline">Leave Review</Button>
          )}
          <Button variant="ghost" asChild>
            <Link href={`/order/${order.id}`}>View Detail</Link>
          </Button>{" "}
        </div>
      </div>
    </div>
  );
}
