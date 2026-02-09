import ProductCard from "@/features/product/components/ProductCard";
import { products } from "@/lib/dummyData";

type Props = {
  categorySlug: string;
  searchParams: {
    page?: string;
  };
};

const PER_PAGE = 9;

export default function ProductGrid({ categorySlug, searchParams }: Props) {
  const page = Math.max(1, Number(searchParams.page) || 1);

  const filtered = products.filter((p) => p.category === categorySlug);

  const totalPages = Math.ceil(filtered.length / PER_PAGE);

  const paginated = filtered.slice((page - 1) * PER_PAGE, page * PER_PAGE);

  return (
    <>
      <div className="grid grid-cols-3 gap-6">
        {paginated.map((p) => (
          <ProductCard key={p.id} {...p} />
        ))}
      </div>

      {/* PAGINATION */}
      <div className="flex justify-center gap-2 mt-10">
        {Array.from({ length: totalPages }).map((_, i) => {
          const query = new URLSearchParams(
            Object.entries(searchParams).filter(([key]) => key !== "page"),
          );

          query.set("page", String(i + 1));

          return (
            <a
              key={i}
              href={`?${query.toString()}`}
              className={`px-3 py-1.5 rounded-md border text-sm
        ${page === i + 1 ? "bg-primary text-white" : "hover:bg-muted"}`}
            >
              {i + 1}
            </a>
          );
        })}
      </div>
    </>
  );
}
