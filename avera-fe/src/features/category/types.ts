export type CategoryStatus = "active" | "inactive";

export interface CategoryBase {
  name: string;
  slug: string;
  image: string | null; // URL saja
}

/**
 * Untuk homepage / listing
 */
export interface CategoryHomepage extends CategoryBase {}

/**
 * Untuk detail product
 */
export interface Category extends CategoryBase {
  id: string;
  description: string;
  status: string;
  parent?: CategoryStatus;
  children?: CategoryBase;
  adult?:boolean ;
}

export interface FlatCategory {
  value: Category["id"]; // 🔒 biar TS nangkap kalau salah
  label: string;
}

export function flattenCategories(
  categories: Category[],
  parentPath: string[] = []
): FlatCategory[] {
  return categories.flatMap((cat) => {
    const currentPath = [...parentPath, cat.name];

    const currentItem = {
      value: cat.id, // ✅ PAKAI ID
      label: currentPath.join(" > "),
    };

    const children = cat.children?.length
      ? flattenCategories(cat.children, currentPath)
      : [];

    return [currentItem, ...children];
  });
}

export interface BreadcrumbProps {
  path: { name: string; slug: string }[];
  productName: string;
}