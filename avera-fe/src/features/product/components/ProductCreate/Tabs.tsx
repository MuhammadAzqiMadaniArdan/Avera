"use client";

interface Tab {
  key: string;
  label: string;
}

const tabs: Tab[] = [
  { key: "info", label: "Informasi Produk" },
  { key: "sales", label: "Informasi Penjualan" },
  { key: "shipping", label: "Pengiriman" },
  { key: "others", label: "Lainnya" },
];

export function Tabs({
  activeTab,
  setActiveTab,
}: {
  activeTab: string;
  setActiveTab: (key: string) => void;
}) {
  return (
    <div className="flex gap-4 border-b text-sm">
      {tabs.map((tab) => (
        <button
          key={tab.key}
          onClick={() => setActiveTab(tab.key)}
          className={`pb-2 font-medium transition ${
            activeTab === tab.key
              ? "text-primary border-b-2 border-primary"
              : "text-muted-foreground hover:text-foreground"
          }`}
        >
          {tab.label}
        </button>
      ))}
    </div>
  );
}
