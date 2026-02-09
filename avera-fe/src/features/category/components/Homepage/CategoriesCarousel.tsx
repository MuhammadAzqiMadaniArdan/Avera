"use client";

import Image from "next/image";
import Link from "next/link";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useState, useEffect } from "react";
import useEmblaCarousel from "embla-carousel-react";
import { CategoryHomepage } from "../../types";

interface CategoriesCarouselProps {
  categories: CategoryHomepage[];
}

export function CategoriesCarousel({ categories }: CategoriesCarouselProps) {
  // Embla setup
  const [emblaRef, emblaApi] = useEmblaCarousel({
    loop: false,
    align: "start",
    skipSnaps: false,
  });
  const [canScrollPrev, setCanScrollPrev] = useState(false);
  const [canScrollNext, setCanScrollNext] = useState(false);

  useEffect(() => {
    if (!emblaApi) return;

    const onSelect = () => {
      setCanScrollPrev(emblaApi.canScrollPrev());
      setCanScrollNext(emblaApi.canScrollNext());
    };

    emblaApi.on("select", onSelect);
    onSelect();
    return () => emblaApi.off("select", onSelect);
  }, [emblaApi]);

  // Responsive: max visible columns
  const getVisibleColumns = () => {
    if (typeof window === "undefined") return 10;
    const width = window.innerWidth;
    if (width >= 1024) return 10; // Desktop
    if (width >= 640) return 5;   // Tablet
    return 2;                     // Mobile
  };

  const visibleColumns = getVisibleColumns();

  // Combine every 2 items to 1 column (2 rows)
  const columns: (CategoryHomepage | null)[][] = [];
  for (let i = 0; i < categories.length; i += 2) {
    columns.push(categories.slice(i, i + 2));
  }

  const renderColumn = (column: (CategoryHomepage | null)[], idx: number) => (
    <div key={idx} className="flex flex-col gap-2 items-center">
      {column.map((cat, i) =>
        cat ? (
          <Link
            key={cat.slug}
            href={`/category/${cat.slug}`}
            className="flex flex-col items-center"
          >
            <div className="relative w-20 sm:w-24 lg:w-28 aspect-square">
              <Image
                src={cat.image ?? "https://placehold.co/80x80"}
                alt={cat.name}
                fill
                // width={80}
                // height={80}
                className="object-cover rounded-lg"
              />
            </div>
            <span className="mt-1 text-xs sm:text-sm text-center truncate w-full">
              {cat.name}
            </span>
          </Link>
        ) : (
          <div key={i} className="w-20 sm:w-24 lg:w-28 h-[80px]" />
        )
      )}
    </div>
  );

  return (
    <div className="relative my-6">
      {/* Prev / Next Buttons */}
      <button
        onClick={() => emblaApi?.scrollPrev()}
        disabled={!canScrollPrev}
        className="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2 disabled:opacity-30"
      >
        <ChevronLeft size={24} />
      </button>
      <button
        onClick={() => emblaApi?.scrollNext()}
        disabled={!canScrollNext}
        className="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2 disabled:opacity-30"
      >
        <ChevronRight size={24} />
      </button>

      {/* Embla viewport */}
      <div ref={emblaRef} className="overflow-hidden">
        <div className="flex gap-2">
          {columns.map((col, idx) => (
            <div
              key={idx}
              className="flex-shrink-0"
              style={{ width: `${100 / visibleColumns}%` }} // Per column
            >
              {renderColumn(col, idx)}
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
