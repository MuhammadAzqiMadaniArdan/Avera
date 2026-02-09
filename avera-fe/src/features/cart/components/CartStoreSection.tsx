"use client";

import { Button } from "@/components/ui/button";
import { CartItemData, Voucher } from "../types";
import { CartItemRow } from "./CartItemRow";

interface Props {
  store: {
    name: string;
    slug: string;
  };
  items: CartItemData[];
  selectedIds: string[];
  vouchers?: Voucher[];
  selectedVoucherId?: number;
  onToggleStore: () => void;
  onToggleItem: (id: string) => void;
  onQtyChange: (id: string, qty: number) => void;
  onDeleteItem: (id: string) => void;
  onSelectVoucher: () => void;
}

export function CartStoreSection({
  store,
  items,
  selectedIds,
  vouchers,
  selectedVoucherId,
  onToggleStore,
  onToggleItem,
  onQtyChange,
  onDeleteItem,
  onSelectVoucher,
}: Props) {
  const isAllSelected =
    items.length > 0 &&
    items.every((item) =>
      selectedIds.includes(item.cart_item_id)
    );

  return (
    <div>
      <div className="mb-4 flex items-center gap-2">
        <input
          type="checkbox"
          checked={isAllSelected}
          onChange={onToggleStore}
          className="cursor-pointer"
        />
        <h3 className="font-bold">{store.name}</h3>
      </div>

      <div className="flex flex-col gap-4">
        {items.map((item) => (
          <CartItemRow
            key={item.cart_item_id}
            item={item}
            checked={selectedIds.includes(item.cart_item_id)}
            onToggle={() =>
              onToggleItem(item.cart_item_id)
            }
            onQtyChange={(qty) =>
              onQtyChange(item.cart_item_id, qty)
            }
            onDelete={() =>
              onDeleteItem(item.cart_item_id)
            }
          />
        ))}
      </div>

      {vouchers && vouchers.length > 0 && (
        <div className="mt-4 flex items-center gap-4">
          <Button size="sm" onClick={onSelectVoucher}>
            Select Voucher
          </Button>
          <span className="text-sm text-gray-600">
            {selectedVoucherId
              ? vouchers.find(
                  (v) => v.id === selectedVoucherId
                )?.code
              : "No voucher selected"}
          </span>
        </div>
      )}
    </div>
  );
}
