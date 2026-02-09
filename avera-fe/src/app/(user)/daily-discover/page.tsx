"use client";

import { useState } from "react";
import { Navbar } from "@/components/Navbar";
import { Footer } from "@/components/Layout/Footer";
import ProductCard from "@/features/product/components/ProductCard";
import { products } from "@/lib/dummyData";

const PER_PAGE = 20;

export default function DailyDiscoverPage() {
  const [page, setPage] = useState(1);

  const totalPages = Math.ceil(products.length / PER_PAGE);
  const start = (page - 1) * PER_PAGE;
  const currentProducts = products.slice(start, start + PER_PAGE);

  return (
    <div className="bg-background min-h-screen text-foreground">
      <Navbar />

      <main className="pt-6 px-10">
        <h1 className="text-2xl font-semibold mb-4">
          Daily Discover
        </h1>

        {/* Product Grid */}
        <div className="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
          {currentProducts.map((p) => (
            <ProductCard
              key={p.id}
              name={p.name}
              price={p.price}
              image={p.image}
              sold={p.sold}
              slug={p.slug}
            />
          ))}
        </div>

        {/* Pagination */}
        <div className="flex justify-center items-center gap-2 mt-8">
          <button
            disabled={page === 1}
            onClick={() => setPage((p) => p - 1)}
            className="px-3 py-1 rounded border disabled:opacity-40"
          >
            Prev
          </button>

          {Array.from({ length: totalPages }).map((_, i) => (
            <button
              key={i}
              onClick={() => setPage(i + 1)}
              className={`px-3 py-1 rounded border ${
                page === i + 1
                  ? "bg-primary text-white"
                  : "hover:bg-muted"
              }`}
            >
              {i + 1}
            </button>
          ))}

          <button
            disabled={page === totalPages}
            onClick={() => setPage((p) => p + 1)}
            className="px-3 py-1 rounded border disabled:opacity-40"
          >
            Next
          </button>
        </div>
      </main>

      <Footer />
    </div>
  );
}
