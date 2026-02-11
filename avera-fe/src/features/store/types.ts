export type ProductStatus = "draft" | "active" | "inactive" | "archived";
export type ModerationVisibility = "public" | "limited" | "adult" | "hidden";

export interface ProductBase {
  id: string;
  name: string;
  slug: string;
  price: number;
  sold: number;
  primaryImage: string | null; // URL saja
}

/**
 * Untuk detail product
 */
export interface Product extends ProductBase {
  stock: number;
  images?: string[];
  status?: ProductStatus;
  moderationVisibility?: ModerationVisibility;
}
