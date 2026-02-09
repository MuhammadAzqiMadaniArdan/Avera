"use client";

import { useEffect } from "react";
import { useRouter } from "next/navigation";

declare global {
  interface Window {
    snap: any;
  }
}

export default function PaymentPage({
  params,
}: {
  params: { orderId: string };
}) {
  const router = useRouter();

  useEffect(() => {
    const script = document.createElement("script");
    script.src = "https://app.sandbox.midtrans.com/snap/snap.js";
    script.setAttribute(
      "data-client-key",
      process.env.NEXT_PUBLIC_MIDTRANS_CLIENT_KEY!
    );
    script.onload = () => {
      window.snap.pay("SNAP_TOKEN_DARI_BE", {
        onSuccess: () => router.push(`/orders/${params.orderId}`),
        onPending: () => router.push(`/orders/${params.orderId}`),
        onError: () => router.push(`/orders/${params.orderId}`),
        onClose: () => router.push(`/orders/${params.orderId}`),
      });
    };

    document.body.appendChild(script);
  }, [params.orderId, router]);

  return (
    <div className="p-6 text-center">
      <p className="text-sm text-gray-500">
        Redirecting to payment…
      </p>
    </div>
  );
}
