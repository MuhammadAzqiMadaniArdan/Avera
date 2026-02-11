import clsx from "clsx";
import { CategoryTree } from "../types";

const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");

export default function CategoryAZNav({categories} : {categories : CategoryTree[]}) {
  const availableLetters = new Set(
    categories.map((c) => c.name[0].toUpperCase())
  );

  return (
    <nav className="flex justify-center flex-wrap items-center gap-2 gap-y-2 mb-14 text-sm py-10">
      {alphabet.map((letter) => {
        const isActive = availableLetters.has(letter);

        return (
          <a
            key={letter}
            href={isActive ? `#category-${letter}` : undefined}
            className={clsx(
              "font-medium transition",
              isActive
                ? "text-primary hover:text-secondary"
                : "text-gray-400 cursor-default"
            )}
          >
            {letter}
          </a>
        );
      })}
    </nav>
  );
}
