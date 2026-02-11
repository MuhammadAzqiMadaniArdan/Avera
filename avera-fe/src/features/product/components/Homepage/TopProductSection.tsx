import TopProductsCarouselSkeleton from "@/components/Skeleton/TopProductCarouselSkeleton";
import TopProductCard from "./TopProductCard";
import { SectionWrapper } from "@/components/common/section/SectionWrapper";
import { BaseCarousel } from "@/components/common/carousel/BaseCarousel";
import { ProductBase } from "../../types";

interface Props {
  products: ProductBase[];
  loading: boolean;
}

export function TopProductsSection({ products, loading }: Props) {

  return (
    <SectionWrapper title="Top Products" seeMoreHref="/top-products">
      <BaseCarousel
        loading={loading}
        skeleton={<TopProductsCarouselSkeleton />}
      >
        {products.map((p) => (
          <div
            key={p.id}
            className="flex-shrink-0 w-[calc((100%-5rem)/6)]"
          >
            <TopProductCard
              id={p.id}
              storeId={p.store_id}
              name={p.name}
              image={p.primaryImage}
              sold={p.sold}
              slug={p.slug}
            />
          </div>
        ))}
      </BaseCarousel>
    </SectionWrapper>
  );
}
