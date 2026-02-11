export enum OrderStatus {
  pending = "pending",
  awaiting_payment = "awaiting_payment",
  paid = "paid",
  processing = "processing",
  shipped = "shipped",
  completed = "completed",
  cancelled = "cancelled",
}

export interface Order {
    id : string,
    subtotal : number,
    shipping_cost : number,
    total_price : number,
    status : OrderStatus,
    created_at : string,
}