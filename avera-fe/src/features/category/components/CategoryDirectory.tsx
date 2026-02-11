import Link from "next/link";
import { CategoryTree } from "../types";

export default function CategoryDirectory({categories} : {categories : CategoryTree[]}) {
  const grouped = categories.reduce<Record<string, typeof categories>>(
    (acc, cat) => {
      const letter = cat.name[0].toUpperCase();
      acc[letter] ||= [];
      acc[letter].push(cat);
      return acc;
    },
    {}
  );

  return (
    <section className="space-y-16">
      {Object.keys(grouped)
        .sort()
        .map((letter) => (
          <div key={letter} id={`category-${letter}`}>
            {/* LETTER HEADER */}
            <h2 className="text-3xl font-semibold tracking-tight mb-8">
              {letter}
            </h2>

            <div className="space-y-12">
              {grouped[letter].map((cat) => (
                <div key={cat.slug}>
                  <Link
                    href={`/category/${cat.slug}`}
                    className="text-lg font-semibold hover:text-primary"
                  >
                    {cat.name}
                  </Link>

                  {/* SUB CATEGORY */}
                  <ul className="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-y-2 gap-x-4 text-sm text-muted-foreground">
                    {cat?.children.map((sub) => (
                      <li key={sub.slug}>
                        <Link
                          href={`/category/${sub.slug}`}
                          className="hover:text-foreground"
                        >
                          {sub.name}
                        </Link>
                      </li>
                    ))}
                  </ul>
                </div>
              ))}
            </div>
          </div>
        ))}
    </section>
  );
}
