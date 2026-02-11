import { StoreDetail } from "../cart/types";
import { BreadcrumbProps, Category } from "../category/types";

export type ProductStatus = "draft" | "active" | "inactive" | "archived";
export type ModerationVisibility = "public" | "limited" | "adult" | "hidden";

export interface ProductBase {
  id: string;
  store_id: string;
  name: string;
  slug: string;
  price: number;
  sold: number;
  stock: number;
  primaryImage: string | null;
}

export interface ProductSeller extends ProductBase{
  views: number;
  status: number;
}

export interface ImageBase {
  id: string;
  url: string;
  moderation_status: string;
  is_primary: boolean;
  position: number;
}

/**
 * Untuk detail product
 */
export interface ProductSellerIndex extends ProductBase {
  stock: number;
  categoryId: string;
  status: ProductStatus;
  moderationVisibility?: ModerationVisibility;
}
/**
 * Untuk detail product
 */
export interface Product extends ProductSellerIndex {
  images?: string[];
}


export interface ProductDetail {
   id: string;
   name: string;
   slug: string;
   description: string;
   price: number;
   stock: number;
   age_rating: number;
   sold: number;
   min_purchase: number;
   rating: number;
   reviews: number;
   store: StoreDetail;
   category: Category;
   category_path: BreadcrumbProps;
   images: ImageBase[];
}

export interface ProductCreatePayload {
  name: string;
  description: string;
  category_id: string;
}

export interface ProductDraft {
  id: string;
  storeId: string;
  categoryId: string;
  name: string;
  description: string;
  status: ProductStatus;
}

export interface ProductFull extends ProductDraft {
  price: number;
  stock: number;
  images: string[];
  weight: number;
  sku?: string;
}

