"use client";

import { CategoryHomepage } from "../../types";
import CategorySkeleton from "@/components/Skeleton/CategoryCardSkeleton";
import { CategoriesCarousel } from "./CategoriesCarousel";
interface Props {
  categories : CategoryHomepage[],
  loading : boolean
}
const CategoriesBaseCarousel = ({categories,loading} : Props) => {

  if (loading) {
    return (
      <div className="flex gap-4 overflow-x-auto p-4">
        {Array.from({ length: 10 }).map((_, i) => (
          <CategorySkeleton key={i} />
        ))}
      </div>
    );
  }

  return <CategoriesCarousel categories={categories} visibleColumns={10} />;
};

export default CategoriesBaseCarousel;
