import { CartItem } from "@/features/cart/types";

export function calculateTotals(
  cart: CartItem[],
  selectedIds: number[],
) {
  const selectedItems = cart.filter((i) =>
    selectedIds.includes(i.id),
  );

  return {
    totalQty: selectedItems.reduce((a, i) => a + i.quantity, 0),
    totalPrice: selectedItems.reduce(
      (a, i) => a + i.price * i.quantity,
      0,
    ),
  };
}
