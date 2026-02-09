import { categories } from "@/lib/categories";
import clsx from "clsx";

const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");

export default function CategoryAZNav() {
  const availableLetters = new Set(
    categories.map((c) => c.name[0].toUpperCase())
  );

  return (
    <nav className="flex justify-center flex-wrap items-center gap-x-3 gap-y-2 mb-14 text-sm">
      {alphabet.map((letter) => {
        const isActive = availableLetters.has(letter);

        return (
          <a
            key={letter}
            href={isActive ? `#category-${letter}` : undefined}
            className={clsx(
              "font-medium transition",
              isActive
                ? "text-foreground hover:text-primary"
                : "text-muted-foreground/40 cursor-default"
            )}
          >
            {letter}
          </a>
        );
      })}
    </nav>
  );
}
