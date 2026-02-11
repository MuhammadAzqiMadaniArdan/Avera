import { averaApi } from "@/lib/api/axiosClient";
import { ApiResponse } from "@/lib/api/response";
import {
  CartResponse,
  CartStoreGroup,
  StoreCartItemForm,
  StoreCartItemPayload,
} from "./types";

export async function getCartItemList(): Promise<
  ApiResponse<CartStoreGroup[]>
> {
  const response = await averaApi.get("/api/v1/cart/");
  return response.data;
}

export async function storeProductCart(
  data: StoreCartItemForm,
): Promise<ApiResponse<CartResponse>> {
  const payload: StoreCartItemPayload = {
    product_id: data.productId,
    quantity: data.qty,
  };
  const response = await averaApi.post("/api/v1/cart", payload);
  return response.data;
}

interface updateQty {
  cartItemId: string;
  qty: number;
}

export async function updateCartQty(
  data: updateQty,
): Promise<ApiResponse<CartResponse>> {
  const response = await averaApi.patch(`/api/v1/cart/${data.cartItemId}`, data.qty);
  return response.data;
}

// const initialCart: CartItem[] = [
//   {
//     id: 1,
//     shop: "PLUTO GADGET STORE",
//     name: "SAMSUNG A55 5G RAM 8/256GB NEW 100% ORIGINAL FREE ADAPTOR 25W",
//     variations: "Blue, RAM 8/256GB",
//     price: 4899000,
//     quantity: 1,
//     image: "/products/samsung-a55.jpg",
//     saved: 5000,
//   },
//   {
//     id: 2,
//     shop: "PLUTO GADGET STORE",
//     name: "Apple Airpods Pro 2",
//     variations: "White",
//     price: 3499000,
//     quantity: 2,
//     image: "/products/airpods-pro.jpg",
//     saved: 10000,
//   },
//   {
//     id: 3,
//     shop: "ANOTHER STORE",
//     name: "Xiaomi Redmi Note 12",
//     variations: "Black",
//     price: 2999000,
//     quantity: 1,
//     image: "/products/redmi-note12.jpg",
//     saved: 2000,
//   },
// ];

// export async function getCartItems(): Promise<CartItem[]> {
//   // TODO: Replace dengan actual API call
//   // const response = await averaApi.get("/api/v1/cart/");
//   // return response.data;

//   return initialCart;
// }

export async function checkoutCart(payload: {
  carts: {
    id: string;
    promo_id?: string | null;
    user_voucher_id?: string | null;
  }[];
}): Promise<ApiResponse<CartResponse>> {
  const response = await averaApi.post("/api/v1/checkout", payload);
  return response.data;
}
