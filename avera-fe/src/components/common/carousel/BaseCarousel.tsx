"use client";

import useEmblaCarousel from "embla-carousel-react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import { useEffect, useState } from "react";

interface BaseCarouselProps {
  children: React.ReactNode;
  loading?: boolean;
  skeleton?: React.ReactNode;
  scrollCount?: number;
}

export function BaseCarousel({
  children,
  loading,
  skeleton,
  scrollCount = 6,
}: BaseCarouselProps) {
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

  const scrollPrev = () => {
    if (!emblaApi) return;
    emblaApi.scrollTo(emblaApi.selectedScrollSnap() - scrollCount);
  };

  const scrollNext = () => {
    if (!emblaApi) return;
    emblaApi.scrollTo(emblaApi.selectedScrollSnap() + scrollCount);
  };

  if (loading) return skeleton ?? null;

  return (
    <div className="relative mt-5">
      <button
        onClick={scrollPrev}
        disabled={!canPrev}
        className="absolute left-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white p-2 shadow disabled:opacity-30"
      >
        <ChevronLeft size={20} />
      </button>

      <button
        onClick={scrollNext}
        disabled={!canNext}
        className="absolute right-0 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white p-2 shadow disabled:opacity-30"
      >
        <ChevronRight size={20} />
      </button>

      <div ref={emblaRef} className="overflow-hidden">
        <div className="flex gap-4">{children}</div>
      </div>
    </div>
  );
}
