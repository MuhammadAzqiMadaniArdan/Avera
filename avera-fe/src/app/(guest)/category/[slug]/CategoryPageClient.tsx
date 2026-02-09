"use client";

import CategoryIconCarousel from "@/features/category/components/slug/CategoryIconCarousel";
import CategorySidebar from "@/features/category/components/slug/CategorySidebar";
import CategorySortBar from "@/features/category/components/slug/CategorySortBar";
import ProductGrid from "@/features/product/components/ProductGrid";

type Props = {
  slug: string;
  searchParams: {
    page?: string;
    sort?: string;
    rating?: string;
    min?: string;
    max?: string;
  };
};

export default function CategoryPageClient({ slug, searchParams }: Props) {
  return (
    <main className="container py-8 px-20">
      <CategoryIconCarousel slug={slug} />

      <div className="mt-8 grid grid-cols-12 gap-8">
        <aside className="col-span-3">
          <CategorySidebar slug={slug} searchParams={searchParams} />
        </aside>

        <section className="col-span-9 space-y-4">
          <CategorySortBar />
          <ProductGrid
            categorySlug={slug}
            searchParams={searchParams}
          />
        </section>
      </div>
    </main>
  );
}
