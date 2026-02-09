import useEmblaCarousel from "embla-carousel-react";
import { ChevronLeft, ChevronRight } from "lucide-react";
import Image from "next/image";

interface MainImageColumnProps {
  images: string[];
  activeImage: string;
  setActiveImage: (img: string) => void;
}

export default function MainImageColumn({
  images,
  activeImage,
  setActiveImage,
}: MainImageColumnProps) {
  const visibleCount = 5; // jumlah thumbnail terlihat di viewport
  const itemWidth = 80; // lebar tiap thumbnail
  const gap = 8; // jarak antar thumbnail
  const containerWidth = visibleCount * itemWidth + (visibleCount - 1) * gap;

  const [emblaRef, emblaApi] = useEmblaCarousel({
    align: "start",
    dragFree: true,
  });

  const scrollPrev = () => emblaApi?.scrollPrev();
  const scrollNext = () => emblaApi?.scrollNext();

  return (
    <div className="flex flex-col gap-4">
      {/* MAIN IMAGE */}
      <div className="relative w-72 h-80 border rounded-xl bg-white">
        <Image
          src={activeImage}
          alt="Product image"
          fill
          className="object-contain"
          priority
        />
      </div>

      <div className="relative flex items-center gap-2">
        {images.length > visibleCount && (
          <button
            onClick={scrollPrev}
            className="absolute -left-1.5 top-1/2 -translate-y-1/2 z-10 p-1 rounded bg-slate-100/80 hover:bg-slate-100 shadow-lg outline outline-1 outline-slate-300"
          >
            <ChevronLeft size={16} />
          </button>
        )}

        {/* VIEWPORT */}
        <div
          ref={emblaRef}
          className="overflow-hidden"
          style={{ width: `${containerWidth}px` }}
        >
          <div className="flex gap-2 ">
            {images.map((img, idx) => (
              <button
                key={`${img}-${idx}`}
                onClick={() => setActiveImage(img)}
                className={`relative w-20 h-20 border transition-all aspect-square duration-300 flex-shrink-0 ${
                  img === activeImage
                    ? "border-primary scale-105 shadow-md"
                    : "border-gray-200"
                }`}
              >
                <Image src={img} alt="" fill className="object-contain" />
              </button>
            ))}
          </div>
        </div>

        {/* RIGHT BUTTON */}
        {images.length > visibleCount && (
          <button
            onClick={scrollNext}
            className="absolute -right-1.5 top-1/2 -translate-y-1/2 z-10 p-1 rounded bg-slate-100/80 hover:bg-slate-100 shadow-lg outline outline-1 outline-slate-300"
          >
            <ChevronRight size={16} />
          </button>
        )}
      </div>
    </div>
  );
}
