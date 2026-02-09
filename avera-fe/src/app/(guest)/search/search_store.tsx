"use client";

import { useSearchParams } from "next/navigation";
import { Navbar } from "@/components/Navbar";
import { useEffect, useState } from "react";
import { averaApi } from "@/lib/api/axiosClient";
import { StoreDetail } from "@/features/cart/types";
import Image from "next/image";

export default function SearchStore() {
  const searchParams = useSearchParams();
  const q = searchParams.get("q") ?? "";

  const [stores, setStores] = useState<StoreDetail[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let cancelled = false;
    const fetchStores = async () => {
      setLoading(true);
      try {
        const res = await averaApi.get("/api/stores", { params: { q } });
        if (!cancelled) setStores(res.data);
      } catch {
        if (!cancelled) setStores([]);
      } finally {
        if (!cancelled) setLoading(false);
      }
    };
    fetchStores();
    return () => {
      cancelled = true;
    };
  }, [q]);

  return (
    <div className="bg-background min-h-screen text-foreground">
      <Navbar />
      <main className="pt-24 px-4">
        <h2 className="text-xl font-semibold mb-4">
          {loading ? "Loading..." : `${stores.length} Stores found`}
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {stores.map((s) => (
            <div
              key={s.slug}
              className="border rounded p-3 flex items-center gap-2"
            >
              <div className="relative w-12 h-12 object-cover rounded">
                <Image
                  src={s.logo ?? "http://loremflickr.com/12/12"}
                  alt={s.name ?? "store-logo"}
                  fill
                />
              </div>
              <div>
                <h3 className="font-medium">{s.name}</h3>
                <p>{s.product_count} products</p>
              </div>
            </div>
          ))}
        </div>
      </main>
    </div>
  );
}
