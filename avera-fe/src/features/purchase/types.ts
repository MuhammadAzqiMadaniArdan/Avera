import { ProductBase } from "../product/types";
import { ReviewBase } from "../reviews/types";

export interface User {
  id: string;
  username: string;
  avatar: string;
}

export interface UserVoucher
{
  id: string;
}

export interface OrderItem {
  id: string;
  order_id: string;
  product_id: string;
  quantity: number;
  price: number;
  discount: number;
  weight: number;
  user_voucher?: UserVoucher;
  review?: ReviewBase;
  product?: ProductBase;
}

export interface Shipment {
  id: string;
  order_id: string;
  courier: string;
  tracking_number: string;
  status: string;
  shipping_cost: number;
  recipient_name: string;
  recipient_phone: string;
  recipient_address: string;
  order?: UserPurchase;
}

export interface Payment {
  id: string;
  order_id: string;
  payment_gateway: string;
  transaction_id: string;
  payment_type: string;
  gross_amount: number;
  status: string;
  payment_url: string;
  signature_key: string;
  paid_at: string;
  order?: UserPurchase;
}

export interface UserPurchase {
  id: string;
  subtotal: number;
  shipping_cost: number;
  total_price: number;
  status: string;
  order_items: OrderItem[];
  shipment?: Shipment;
  payment?: Payment;
}
