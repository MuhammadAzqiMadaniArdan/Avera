"use client";

import { useEffect, useState, use } from "react";
import { useRouter } from "next/navigation";
import { getSnapToken } from "@/features/checkout/services";
import { notify } from "@/lib/toast/notify";
import { MidtransSnap } from "@/features/payment/types";

declare global {
  interface Window {
    snap?: MidtransSnap;
  }
}

export default function PaymentPage({ params }: { params: { orderId: string } | Promise<{ orderId: string }> }) {
  const router = useRouter();
  const resolvedParams = use(params);
  const [snapToken, setSnapToken] = useState<string | null>(null);

  useEffect(() => {
    const fetchPayment = async () => {
      try {
        const res = await getSnapToken(resolvedParams.orderId);
        setSnapToken(res.data.snap_token);
        notify.success("Berhasil ambil snap token");
      } catch (error) {
        notify.error(error?.response?.data?.message ?? "Gagal mengambil snap token");
      }
    };

    fetchPayment();
  }, [resolvedParams.orderId]);
  
  useEffect(() => {
    if (!snapToken) return;

    const script = document.createElement("script");
    script.src = "https://app.sandbox.midtrans.com/snap/snap.js";
    script.setAttribute(
      "data-client-key",
      process.env.NEXT_PUBLIC_MIDTRANS_CLIENT_KEY!
    );

    script.onload = () => {
      if (!window.snap) {
        notify.error("Midtrans Snap not loaded");
        return;
      }

      window.snap.pay(snapToken, {
        onSuccess: (result) => {
          notify.success(`Payment success: ${result.order_id}`);
          router.push("/user/purchase");
        },
        onPending: (result) => {
          notify.info(`Payment pending: ${result.order_id}`);
          router.push("/user/purchase");
        },
        onError: (result) => {
          notify.error("Payment failed");
          console.error(result);
        },
        onClose: () => {
          notify.info("Payment popup closed");
        },
      });
    };

    document.body.appendChild(script);
  }, [snapToken, router]);

  return (
    <div className="p-6 text-center">
      <p className="text-sm text-gray-500">Redirecting to payment…</p>
    </div>
  );
}
