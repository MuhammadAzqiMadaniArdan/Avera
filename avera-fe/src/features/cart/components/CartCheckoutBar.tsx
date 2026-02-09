"use client";

import { Button } from "@/components/ui/button";

interface Props {
  totalItems: number;
  totalPrice: number;
  totalSaved: number;
  isAllSelected: boolean;
  onSelectAll: () => void;
  onCheckout: () => void;
}

export function CartCheckoutBar({
  totalItems,
  totalPrice,
  totalSaved,
  isAllSelected,
  onSelectAll,
  onCheckout,
}: Props) {
  return (
    <div className="fixed bottom-0 left-0 right-0 z-50 flex flex-wrap justify-between gap-4 border-t bg-white p-4 shadow-lg">
      <div className="flex items-center gap-4">
        <input
          type="checkbox"
          checked={isAllSelected}
          onChange={onSelectAll}
          className="cursor-pointer"
        />
        <span>Select All ({totalItems})</span>
        <Button variant="ghost" size="sm">
          Delete
        </Button>
        <Button variant="ghost" size="sm">
          Move to My Likes
        </Button>
      </div>

      <div className="text-right">
        <p>
          Total ({totalItems} items):{" "}
          <span className="font-semibold">
            Rp {totalPrice.toLocaleString()}
          </span>
        </p>
        <p className="text-green-600">
          Saved: Rp {totalSaved.toLocaleString()}
        </p>
      </div>

      <Button onClick={onCheckout}>Checkout</Button>
    </div>
  );
}
