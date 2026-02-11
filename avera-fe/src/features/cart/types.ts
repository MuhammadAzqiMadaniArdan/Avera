import { ProductBase } from "../product/types";

export interface Store {
  id: string;
  name: string;
  slug: string;
  logo: string;
}

export interface ratingStore {
  avg:number,
  count:number,
}
export interface StoreDetail extends Store {
  product_count: number;
  rating: ratingStore;
  logo: string;
}


export interface CartItem {
  id: number;
  quantity: number;
  product: ProductBase;
}

export interface StoreCartItemForm {
  productId: string;
  qty: number;
}

export interface StoreCartItemPayload {
  product_id: string,
  quantity: number,
}

export interface CartResponse {
  data: CartItem[];
}

export interface Voucher {
  id: number;
  code: string;
  value: number;
  minSpend: number;
  validUntil: string;
}

export interface CartItemData {
  cart_item_id: string;
  quantity: number;
  product: ProductBase;
}

export interface CartStoreGroup
{
  store: Store;
  items: CartItemData[];
  subtotal: number;
}