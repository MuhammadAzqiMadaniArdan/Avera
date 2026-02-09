import Image from "next/image";
import Link from "next/link";
import { categories } from "@/lib/categories";

export default function MainCategoryGrid() {
  return (
    <section className="mb-14">
      <h2 className="text-lg font-semibold mb-6">Browse Categories</h2>

      <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 gap-x-6 gap-y-8">
        {categories.slice(0, 35).map((cat) => (
          <Link
            key={cat.slug}
            href={`/category/${cat.slug}`}
            className="group flex flex-col items-center text-center"
          >
            <div className="relative w-16 h-16 rounded-xl bg-muted flex items-center justify-center
                            group-hover:bg-primary/10 transition">
              <Image
                src={cat.image}
                alt={cat.name}
                width={cat.imageWidth}
                height={cat.imageHeight}
                className="object-contain"
              />
            </div>

            <span className="mt-3 text-sm leading-tight line-clamp-2
                             group-hover:text-primary">
              {cat.name}
            </span>
          </Link>
        ))}
      </div>
    </section>
  );
}
