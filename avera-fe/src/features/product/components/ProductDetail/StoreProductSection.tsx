import TopProductsCarouselSkeleton from "@/components/Skeleton/TopProductCarouselSkeleton";
import { SectionWrapper } from "@/components/common/section/SectionWrapper";
import { BaseCarousel } from "@/components/common/carousel/BaseCarousel";
import { ProductHomepage } from "../../types";
import ProductCard from "../Homepage/ProductCard";

interface Props {
  products: ProductHomepage[];
  loading: boolean;
}

export function StoreProductSection({ products, loading }: Props) {

  return (
    <SectionWrapper title="From This Store" seeMoreHref="/store">
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
