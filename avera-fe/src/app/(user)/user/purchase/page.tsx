"use client";

import { OrderCard } from "@/features/purchase/components/OrderCard";
import { PurchaseTab, useMyPurchase } from "@/features/purchase/hooks";

const tabs: { label: string; value: PurchaseTab }[] = [
  { label: "All", value: "all" },
  { label: "To Pay", value: "topay" },
  { label: "Processing", value: "toship" },
  { label: "To Receive", value: "toreceive" },
  { label: "Completed", value: "completed" },
  { label: "Cancelled", value: "cancelled" },
];

export default function MyPurchasePage() {
  const { loading, activeTab, setActiveTab, purchases } = useMyPurchase();

  return (
    <div className="flex-1 bg-white rounded-xl p-6 shadow space-y-6">
      <h2 className="font-semibold text-lg">My Purchase</h2>

      {/* TABS */}
      <div className="flex flex-wrap gap-2">
        {tabs.map((tab) => (
          <button
            key={tab.value}
            onClick={() => setActiveTab(tab.value)}
            className={`px-4 py-2 rounded-lg text-sm ${
              activeTab === tab.value
                ? "bg-black text-white"
                : "bg-gray-100 text-gray-700"
            }`}
          >
            {tab.label}
          </button>
        ))}
      </div>

      {/* CONTENT */}
      {loading ? (
        <p className="text-gray-500">Loading orders...</p>
      ) : purchases.length === 0 ? (
        <p className="text-gray-500">No orders found.</p>
      ) : (
        <div className="space-y-4">
          {purchases.map((order) => (
            <OrderCard key={order.id} order={order} />
          ))}
        </div>
      )}
    </div>
  );
}
