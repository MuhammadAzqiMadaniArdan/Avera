// enums / union
export type ProductStatus = "draft" | "active" | "inactive" | "archived";
export type ModerationStatus = "limited" | "public" | "adult" | "hidden";
export type ProductStatusKey = "action" | "review" | "hidden";

// main interface
export interface Product {
  id: number;
  name: string;
  slug: string;
  sku: string;

  category: {
    id: number;
    name: string;
  };

  storeSlug: string;

  price: number;
  stock: number;
  sold: number;
  weight: number; // gram

  image: string;       // primary image
  images: string[];    // gallery

  rating: number;      // 1–5
  reviews: number;     // total review

  description: string;

  status: ProductStatus;
  statusKey: ProductStatusKey;
  moderationStatus?: ModerationStatus;

  createdAt: string;
  updatedAt: string;
}
