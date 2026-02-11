import { Store } from "../cart/types";
import { Order } from "../order/types";
import { ProductBase } from "../product/types";
import { UserAddress } from "../profile/types";

export interface Checkout {
  id: string;
  user_address_id: string | null;
  subtotal: number;
  shipping_cost: number;
  total_price: number;
  payment_method: 'cod' | 'midtrans';
  stores: CheckoutStore[]
  user_address: UserAddress | null;
  user_addresses: UserAddress[] | null
}

export interface CheckoutStore {
  store: Store
  items: CheckoutItem[]
  subtotal: number
  shipments: CheckoutShipment[] | null
  selected_shipment: CheckoutShipment | null
  shipping_cost: number
}


export interface CheckoutItem {
  id: string;
  price: number;
  quantity: number;
  subtotal: number;
  weight: number;
  discount: number;
  product: ProductBase;
}
export interface CheckoutShipment {
  id: string;
  store_id: string;
  user_address_id: string;
  courier_code: string;
  courier_name: string;
  service: string;
  description: string;
  etd: string;
  min_days: number;
  max_days: number;
  cost: number;
  is_selected: boolean;
}




export interface UpdateCheckoutPayload {
  user_address_id?: string;
  shipment?: string;
  payment_method?: "cod" | "midtrans";
  checkout?: CheckoutItemPayload[];
}

export interface CheckoutItemPayload {
  items_id: string;
  promotion_id?: string;
  user_vouchers_id?: string;
  user_address_id?: string;
}


export interface OrderCreated {
  order_id : string;
  snap_token: string | null;
}