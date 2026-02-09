"use client";

import Link from "next/link";
import ProductCard from "./ProductCard";
import ProductCardSkeleton from "@/components/Skeleton/ProductCardSkeleton";
import { ProductHomepage } from "../../types";

type ProductGridProps = {
  title?: string;
  seeMoreUrl?: string;

  products: ProductHomepage[];
  loading?: boolean;

  categorySlug?: string;
  storeSlug?: string;

  skeletonCount?: number;
  limit?: number;
};

export default function ProductGrid({
  title,
  seeMoreUrl,
  products,
  loading = false,
  skeletonCount = 12,
}: ProductGridProps) {

  return (
    <section className="max-w-7xl mx-auto px-4 py-10">
      {title && (
        <div
          className="border flex flex-col items-center gap-1 mb-6 text-center text-xl font-semibold text-primary"
        >
          <h2 className="py-3">{title}</h2>
          <span className="block w-full h-1 bg-primary " style={{ height:"5px" }} />
        </div>
      )}

      {/* GRID */}
      <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
        {loading
          ? Array.from({ length: skeletonCount }).map((_, i) => (
              <ProductCardSkeleton key={i} />
            ))
          : products.map((p) => (
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

      {/* SEE MORE */}
      {seeMoreUrl && !loading && (
        <div className="mt-8 flex justify-center">
          <Link
            href={seeMoreUrl}
            className="border px-10 py-2 text-sm font-medium text-primary  bg-background/20"
          >
            See more
          </Link>
        </div>
      )}
    </section>
  );
}
