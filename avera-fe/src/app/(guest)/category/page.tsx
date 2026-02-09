import CategoryAZNav from "@/features/category/components/CategoryAZNav";
import CategoryDirectory from "@/features/category/components/CategoryDirectory";
import MainCategoryGrid from "@/features/category/components/MainCategoryGrid";
import { Navbar } from "@/components/Navbar";

export default function CategoryPage() {
  return (
    <div>
      <Navbar />
      <div className="container py-10 px-20">
        <div className="text-sm mb-8 text-muted-foreground">
          Home /{" "}
          <span className="text-foreground font-medium">All Categories</span>
        </div>

        <MainCategoryGrid />
        <CategoryAZNav />
        <CategoryDirectory />
      </div>
    </div>
  );
}
