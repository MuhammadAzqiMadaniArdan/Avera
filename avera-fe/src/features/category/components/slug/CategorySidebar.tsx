import Link from "next/link";
import { Star } from "lucide-react";

type Props = {
  slug: string;
  searchParams: {
    min?: string;
    max?: string;
  };
};

const subCategories = ["Phones", "Laptops", "Accessories", "Gaming"];

export default function CategorySidebar({ slug, searchParams }: Props) {
  return (
    <aside className="space-y-8 text-sm">
      {/* CATEGORY */}
      <div>
        <h3 className="font-semibold text-base mb-3">Categories</h3>

        <Link
          href="/category"
          className="block mb-2 text-primary font-medium"
        >
          All Categories
        </Link>

        {subCategories.map((c) => (
          <Link
            key={c}
            href={`/category/${slug}?sub=${c}`}
            className="block py-1 text-muted-foreground hover:text-foreground"
          >
            {c}
          </Link>
        ))}
      </div>

      {/* RATING */}
      <div>
        <h3 className="font-semibold text-base mb-3">Rating</h3>

        {[4, 3, 2].map((r) => (
          <label key={r} className="flex items-center gap-2 py-1">
            <input type="checkbox" />
            <div className="flex items-center gap-1">
              {Array.from({ length: r }).map((_, i) => (
                <Star
                  key={i}
                  size={14}
                  className="fill-yellow-400 text-yellow-400"
                />
              ))}
              <span className="text-xs text-muted-foreground">& up</span>
            </div>
          </label>
        ))}
      </div>

      {/* PRICE */}
      <div>
        <h3 className="font-semibold text-base mb-3">Price</h3>

        <div className="flex gap-2">
          <input
            placeholder="Min"
            defaultValue={searchParams.min}
            className="w-full border rounded-md px-2 py-1"
          />
          <input
            placeholder="Max"
            defaultValue={searchParams.max}
            className="w-full border rounded-md px-2 py-1"
          />
        </div>

        <button className="mt-3 w-full rounded-md bg-primary py-2 text-white text-sm">
          Apply
        </button>
      </div>
    </aside>
  );
}
