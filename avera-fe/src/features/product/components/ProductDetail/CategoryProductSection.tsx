import TopProductsCarouselSkeleton from "@/components/Skeleton/TopProductCarouselSkeleton";
import { SectionWrapper } from "@/components/common/section/SectionWrapper";
import { BaseCarousel } from "@/components/common/carousel/BaseCarousel";
import { ProductBase } from "../../types";
import ProductCard from "../Homepage/ProductCard";

interface Props {
  products: ProductBase[];
  loading: boolean;
  categorySlug: string;
}

export function CategoryProductSection({ products, loading, categorySlug }: Props) {

  const linkCategory = `/category/${categorySlug}`
  return (
    <SectionWrapper title="You May Also Like" seeMoreHref={linkCategory}>
      <BaseCarousel
        loading={loading}
        skeleton={<TopProductsCarouselSkeleton />}
      >
        {products.map((p) => (
          <div
            key={p.id}
            className="flex-shrink-0 w-[calc((100%-5rem)/6)]"
          >
            <ProductCard
              id={p.id}
              storeId={p.store_id}
              name={p.name}
              price={p.price}
              image={p.primaryImage}
              sold={p.sold}
              slug={p.slug}
              status="public"
            />
          </div>
        ))}
      </BaseCarousel>
    </SectionWrapper>
  );
}
