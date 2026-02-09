"use client";

import Image from "next/image";
import Link from "next/link";
import useEmblaCarousel from "embla-carousel-react";
import { ChevronLeft, ChevronRight } from "lucide-react";

const stores = Array.from({ length: 14 }, (_, i) => ({
  id: i,
  name: `Store ${i + 1}`,
  slug: `store-${i + 1}`,
  image: "https://loremflickr.com/80/80",
}));

export default function CategoryIconCarousel() {
  const [emblaRef, emblaApi] = useEmblaCarousel({
    align: "start",
    dragFree: true,
  });

  return (
    <div className="relative mb-8">
      <button
        onClick={() => emblaApi?.scrollPrev()}
        className="absolute left-0 top-1/2 -translate-y-1/2 z-10
                   bg-background border rounded-full p-2 shadow-sm"
      >
        <ChevronLeft size={18} />
      </button>

      <button
        onClick={() => emblaApi?.scrollNext()}
        className="absolute right-0 top-1/2 -translate-y-1/2 z-10
                   bg-background border rounded-full p-2 shadow-sm"
      >
        <ChevronRight size={18} />
      </button>

      <div ref={emblaRef} className="overflow-hidden px-10">
        <div className="flex gap-6">
          {stores.map((s) => (
            <Link
              key={s.id}
              href={`/store/${s.slug}`}
              className="min-w-[88px] flex flex-col items-center text-center
                         hover:text-primary"
            >
              <Image
                src={s.image}
                alt={s.name}
                width={56}
                height={56}
                className="rounded-lg border"
              />
              <span className="mt-2 text-xs truncate">{s.name}</span>
            </Link>
          ))}
        </div>
      </div>
    </div>
  );
}
