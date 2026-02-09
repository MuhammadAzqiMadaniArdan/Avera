"use client";

import { useEffect, useMemo, useState } from "react";
import { notify } from "@/lib/toast/notify";
import { getUserPurchase } from "./services";
import { UserPurchase } from "./types";

export type PurchaseTab =
  | "all"
  | "topay"
  | "toship"
  | "toreceive"
  | "completed"
  | "cancelled";

export function useMyPurchase() {
  const [purchases, setPurchases] = useState<UserPurchase[]>([]);
  const [loading, setLoading] = useState(true);
  const [activeTab, setActiveTab] = useState<PurchaseTab>("all");

  useEffect(() => {
    (async () => {
      try {
        const res = await getUserPurchase();
        setPurchases(res.data);
      } catch (error: any) {
        notify.error(
          error?.response?.data?.message ?? "Gagal mengambil data order"
        );
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  const filteredPurchases = useMemo(() => {
  if (activeTab === "all") return purchases;

  return purchases.filter((order) => {
    switch (activeTab) {
      case "topay":
        return order.status === "awaiting_payment";

      case "toship":
        return ["paid", "processing"].includes(order.status);

      case "toreceive":
        return order.status === "shipped";

      case "completed":
        return order.status === "completed";

      case "cancelled":
        return order.status === "cancelled";

      default:
        return true;
    }
  });
}, [purchases, activeTab]);


  return {
    loading,
    activeTab,
    setActiveTab,
    purchases: filteredPurchases,
  };
}
