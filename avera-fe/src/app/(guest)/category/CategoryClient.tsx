"use client";
import CategoryAZNav from "@/features/category/components/CategoryAZNav";
import CategoryDirectory from "@/features/category/components/CategoryDirectory";
import MainCategoryGrid from "@/features/category/components/MainCategoryGrid";
import { Navbar } from "@/components/Navbar";
import { useCategory } from "@/features/category/hooks";
import { Breadcrumbs } from "@/components/Layout/BreadCumbs";

export default function CategoryClient() {
  const { categories, loading } = useCategory();
  return (
    <div>
      <Navbar />
      <div className="container py-10 px-20">
        <div className="text-sm mb-8 text-muted-foreground">
          <Breadcrumbs/>
        </div>
        {loading ? (
          <p>loading...</p>
        ) : (
          <div>
            <MainCategoryGrid categories={categories} />
            <CategoryAZNav categories={categories}/>
            <CategoryDirectory categories={categories} />
          </div>
        )}
      </div>
    </div>
  );
}
