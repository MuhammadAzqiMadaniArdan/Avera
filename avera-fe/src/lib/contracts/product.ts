
export type ProductStatus = "draft" | "active" | "inactive" | "archived";
export type AgeRating = "all" | "13+" | "18+";
export type ImageModerationStatus =
  | "pending"
  | "approved"
  | "warning"
  | "rejected";
export type ProductVisibility = "public" | "limited" | "adult" | "hidden";
export interface ProductImage {
  id: string;
  url: string;
  moderation_status: ImageModerationStatus;
  is_primary: boolean;
}
export interface ProductModeration {
  visibility: ProductVisibility;
  moderated_at: string | null;
  has_blocked_images: boolean;
}
export interface StoreSummary {
  id: string;
  name: string;
  slug: string;
}

export interface CategorySummary {
  id: string;
  name: string;
  slug: string;
}

export type ModerationStatus = "limited" | "public" | "adult" | "hidden";
export type ProductStatusKey = "action" | "review" | "hidden";
export type PrimaryImage = [""] | null;
export interface Product {
  id: string;

  name: string;
  slug: string;
  description: string | null;

  price: number;
  stock: number;

  status: ProductStatus;
  age_rating: AgeRating;

  moderation: ProductModeration;

  store: StoreSummary;
  category: CategorySummary | null;

  primary_image: ProductImage | null;
  images: ProductImage[];

  created_at: string;
}