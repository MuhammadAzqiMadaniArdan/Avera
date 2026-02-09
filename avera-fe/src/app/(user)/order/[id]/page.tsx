"use client";

import { useParams, useRouter } from "next/navigation";
import { useEffect, useState } from "react";
import { getOrderDetail } from "@/features/purchase/services";
import { UserPurchase } from "@/features/purchase/types";
import { Button } from "@/components/ui/button";
import { notify } from "@/lib/toast/notify";
import { Navbar } from "@/components/Navbar";
import { getStatusColor, getStatusLabel } from "@/lib/utils/purchaseStatus";
import { ReviewBase } from "@/features/reviews/types";
import Image from "next/image";
import { formatCurrency } from "@/lib/utils/formatCurrency";
import Link from "next/link";

export default function OrderDetailPage() {
  const { id } = useParams();
  const router = useRouter();

  const [order, setOrder] = useState<UserPurchase | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    (async () => {
      try {
        const res = await getOrderDetail(id as string);
        setOrder(res.data);
      } catch {
        notify.error("Gagal mengambil order detail");
      } finally {
        setLoading(false);
      }
    })();
  }, [id]);

  if (loading) {
    return (
      <>
        <Navbar />
        <div className="flex justify-center py-20 text-gray-500">
          Loading order detail...
        </div>
      </>
    );
  }

  if (!order) return null;

  return (
    <>
      <Navbar />

      <div className="max-w-5xl mx-auto px-4 py-8 space-y-6">
        {/* ===== HEADER ===== */}
        <div className="flex justify-between items-center">
          <h1 className="text-xl font-semibold">Order Detail</h1>

          <span
            className={`text-sm font-semibold ${getStatusColor(order.status)}`}
          >
            {getStatusLabel(order.status)}
          </span>
        </div>

        {/* ===== SUMMARY ===== */}
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 bg-white rounded-xl p-6 shadow">
          <SummaryItem label="Subtotal" value={order.subtotal} />
          <SummaryItem label="Shipping" value={order.shipping_cost} />
          <SummaryItem label="Total" value={order.total_price} bold />
          <SummaryItem label="Items" value={order.order_items.length} />
        </div>

        {/* ===== ITEMS ===== */}
        <div className="bg-white rounded-xl shadow divide-y">
          {order.order_items.map((item) => (
            <div key={item.id} className="p-6 flex flex-col gap-4">
              {/* === PRODUCT INFO === */}
              <div className="flex gap-4">
                <Link
                  href={`/product/${item.product?.slug}-i.${item.product?.store_id}.${item.product?.id}`}
                  className="relative w-16 h-16 sm:w-20 sm:h-20 rounded-lg border overflow-hidden bg-gray-100 shrink-0"
                >
                  <Image
                    src={item.product?.primaryImage || "loremflickr.com/20/20"}
                    alt={item.product?.name || "product"}
                    fill
                    sizes="80px"
                    className="object-cover"
                  />
                </Link>

                {/* === INFO === */}
                <div className="flex-1">
                  <Link
                    href={`/product/${item.product?.id}`}
                    className="font-semibold leading-snug hover:underline"
                  >
                    {item.product?.name}
                  </Link>

                  <p className="text-sm text-gray-500 mt-0.5">
                    Qty {item.quantity}
                  </p>

                  <p className="mt-1 font-semibold">
                    Rp {Number(item.price).toLocaleString("id-ID")}
                  </p>
                </div>
              </div>

              {/* === REVIEW === */}
              {order.status === "complete" && (
                <>
                  {!item.review ? (
                    <Button
                      size="sm"
                      variant="outline"
                      className="w-fit"
                      onClick={() =>
                        router.push(`/review n2qf7 /${item.id}`)
                      }
                    >
                      Write Review
                    </Button>
                  ) : (
                    <ReviewedBox review={item.review} />
                  )}
                </>
              )}
            </div>
          ))}
        </div>

        {/* ===== PAYMENT ===== */}
        {order.status === "unpaid" && order.payment?.payment_url && (
          <div className="flex justify-end">
            <Button
              onClick={() => window.open(order.payment!.payment_url, "_blank")}
            >
              Pay Now
            </Button>
          </div>
        )}
      </div>
    </>
  );
}

/* ================= SUB COMPONENTS ================= */

function SummaryItem({
  label,
  value,
  bold,
}: {
  label: string;
  value: number;
  bold?: boolean;
}) {
  return (
    <div>
      <p className="text-sm text-gray-500">{label}</p>
      <p className={`text-base ${bold ? "font-bold" : "font-medium"}`}>
        {label === "Items" ? value : formatCurrency(value)}
      </p>
    </div>
  );
}

function ReviewedBox({ review }: { review: ReviewBase }) {
  return (
    <div className="bg-gray-50 border rounded-lg p-4 space-y-2">
      <div className="flex items-center gap-2">
        <span className="text-yellow-500 font-semibold">
          {"★".repeat(review.rating)}
        </span>
        <span className="text-sm text-gray-500">{review.rating}/5</span>
      </div>

      <p className="text-sm text-gray-700 leading-relaxed">{review.comment}</p>

      <p className="text-xs text-gray-400">
        Reviewed on {new Date(review.updated_at).toLocaleDateString("id-ID")}
      </p>
    </div>
  );
}
