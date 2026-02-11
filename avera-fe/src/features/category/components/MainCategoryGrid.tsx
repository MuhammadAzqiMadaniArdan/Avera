import Link from "next/link";
import { CategoryTree } from "../types";
import Image from "next/image";

export default function MainCategoryGrid({
  categories,
}: {
  categories: CategoryTree[];
}) {
  return (
    <section className="mb-14">
      <h2 className="text-lg font-semibold mb-6">Browse Categories</h2>

      <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-5 gap-x-6 gap-y-6">
        {categories.map((cat) => (
          <Link
            key={cat.slug}
            href={`/category/${cat.slug}`}
            className="group flex flex-col items-center text-center p-4 border 
                 hover:border-primary transition"
          >
            <div
              className="relative w-20 h-20 rounded-full bg-muted flex items-center justify-center
                   group-hover:bg-primary/10 transition"
            >
              <Image
                src={cat.image ?? "http://loremflickr.com/200/200"}
                alt={cat.name}
                fill
                className="object-contain px-2 py-2"
              />
            </div>

            <span
              className="mt-3 text-sm leading-tight line-clamp-2
                   group-hover:text-primary"
            >
              {cat.name}
            </span>
          </Link>
        ))}
      </div>
    </section>
  );
}
