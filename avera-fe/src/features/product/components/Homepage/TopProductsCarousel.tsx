"use client";

import TopProductsCarouselSkeleton from "@/components/Skeleton/TopProductCarouselSkeleton";
import { ProductBase } from "@/features/product/types";
import useEmblaCarousel from "embla-carousel-react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useEffect, useState } from "react";
import TopProductCard from "./TopProductCard";

const SCROLL_COUNT = 6;
interface Props {
  products: ProductBase[];
  loading: boolean;
}
export function TopProductsCarousel({ products, loading }: Props) {
  const [emblaRef, emblaApi] = useEmblaCarousel({
    align: "start",
    containScroll: "trimSnaps",
  });

  const [canPrev, setCanPrev] = useState(false);
  const [canNext, setCanNext] = useState(false);

  useEffect(() => {
    if (!emblaApi) return;

    const update = () => {
      setCanPrev(emblaApi.canScrollPrev());
      setCanNext(emblaApi.canScrollNext());
    };

    update();
    emblaApi.on("select", update);
    emblaApi.on("reInit", update);

    return () => {
      emblaApi.off("select", update);
      emblaApi.off("reInit", update);
    };
  }, [emblaApi]);

  const scrollNext = () => {
    if (!emblaApi) return;
    const index = emblaApi.selectedScrollSnap();
    emblaApi.scrollTo(index + SCROLL_COUNT);
  };

  const scrollPrev = () => {
    if (!emblaApi) return;
    const index = emblaApi.selectedScrollSnap();
    emblaApi.scrollTo(index - SCROLL_COUNT);
  };

  if (loading) return <TopProductsCarouselSkeleton />;
  return (
    <div className="relative">
      {/* Prev */}
      <button
        onClick={scrollPrev}
        disabled={!canPrev}
        className="absolute left-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2 disabled:opacity-30"
      >
        <ChevronLeft size={20} />
      </button>

      {/* Next */}
      <button
        onClick={scrollNext}
        disabled={!canNext}
        className="absolute right-0 top-1/2 -translate-y-1/2 z-10 bg-white shadow rounded-full p-2 disabled:opacity-30"
      >
        <ChevronRight size={20} />
      </button>

      {/* Carousel */}
      <div ref={emblaRef} className="overflow-hidden">
        <div className="flex gap-4">
          {products.map((p) => (
            <div
              key={p.id}
              className="
          flex-shrink-0
          w-[calc((100%-5rem)/6)]
        "
            >
              <TopProductCard
                key={p.id}
                id={p.id}
                storeId={p.store_id}
                name={p.name}
                image={p.primaryImage}
                sold={p.sold}
                slug={p.slug}
              />
            </div>
          ))}
        </div>
      </div>
    </div>
  );
}
