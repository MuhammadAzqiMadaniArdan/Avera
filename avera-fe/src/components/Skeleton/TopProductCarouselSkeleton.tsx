import TopProductCardSkeleton from "./TopProductCardSkeleton";

export default function TopProductsCarouselSkeleton() {
  return (
    <div className="overflow-hidden">
      <div className="flex gap-4 px-8">
        {Array.from({ length: 6 }).map((_, i) => (
          <TopProductCardSkeleton key={i} />
        ))}
      </div>
    </div>
  );
}
