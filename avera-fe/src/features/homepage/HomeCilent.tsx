"use client";

import { Navbar } from "@/components/Navbar";
import { HeroCarousel } from "@/components/HeroCarousel";
import { Suspense } from "react";
import ProductGrid from "@/features/product/components/Homepage/ProductGrid";
import CategoriesBaseCarousel from "@/features/category/components/Homepage/CategoriesBaseCarousel";
import useHomepage from "./hooks";
import { TopProductsSection } from "../product/components/Homepage/TopProductSection";

export default function HomeClient() {
  const {
    categories,
    topProducts,
    dailyProducts,
    categoriesLoading,
    topProductsLoading,
    dailyProductsLoading,
  } = useHomepage();

  return (
    <div className="bg-background min-h-screen text-foreground">
      <Navbar />

      <main className="pt-6 px-10">
        <Suspense
          fallback={
            <div className="w-full h-80 bg-gray-300 rounded-lg animate-pulse" />
          }
        >
          <HeroCarousel />
        </Suspense>

        <div className="py-10">
            <h2 className="text-lg px-6 text-gray-400 font-semibold mb-4 uppercase">Categories</h2>
          <CategoriesBaseCarousel
            categories={categories}
            loading={categoriesLoading}
          />
        </div>

          <TopProductsSection
          products={topProducts}
          loading={topProductsLoading}
          />

        <ProductGrid
          title="Daily Discover"
          products={dailyProducts}
          loading={dailyProductsLoading}
          seeMoreUrl="/daily-discover"
        />
      </main>
    </div>
  );
}
