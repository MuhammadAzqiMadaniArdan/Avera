import CategoryPageClient from "./CategoryPageClient";
import { Navbar } from "@/components/Navbar";

export default async function CategoryPage({
  params,
  searchParams,
}: {
  params: Promise<{ slug: string }>;
  searchParams: Promise<{
    page?: string;
    sort?: string;
    rating?: string;
    min?: string;
    max?: string;
  }>;
}) {
  const { slug } = await params;
  const resolvedSearchParams = await searchParams;

  return (
    <div className="min-h-screen bg-background">
      <Navbar />
      <CategoryPageClient
        slug={slug}
        searchParams={resolvedSearchParams}
      />
    </div>
  );
}
