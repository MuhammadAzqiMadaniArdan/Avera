"use client";

import { useSearchParams } from "next/navigation";
import { Navbar } from "@/components/Navbar";
import { useEffect, useState } from "react";
import { ProductHomepage } from "@/features/product/types";
import ProductCard from "@/features/product/components/Homepage/ProductCard";
import { getProductSearch } from "@/features/product/services";

export default function Search() {
  const searchParams = useSearchParams();
  const q = searchParams.get("q") ?? "";

  const [products, setProducts] = useState<ProductHomepage[]>([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let cancelled = false;
    const fetchProducts = async () => {
      setLoading(true);
      try {
        const res = await getProductSearch({ keyword:q });
        if (!cancelled) setProducts(res.data);
      } catch (err) {
        if (!cancelled) setProducts([]);
      } finally {
        if (!cancelled) setLoading(false);
      }
    };
    fetchProducts();
    return () => {
      cancelled = true;
    };
  }, [q]);

  return (
    <div className="bg-background min-h-screen text-foreground">
      <Navbar />
      <main className="pt-24 px-4">
        <h2 className="text-xl font-semibold mb-4">
          {loading ? "Loading..." : `${products.length} Products found`}
        </h2>
        <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
          {products.map((p) => (
            <ProductCard
              key={p.id}
              id={p.id}
              storeId={p.store_id}
              name={p.name}
              price={p.price}
              image={p.primaryImage}
              sold={p.sold}
              slug={p.slug}
              status="public"
            />
          ))}
        </div>
      </main>
    </div>
  );
}
