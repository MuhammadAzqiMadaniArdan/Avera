"use client";

import { CartCheckoutBar } from "@/features/cart/components/CartCheckoutBar";
import { CartDeleteDialog } from "@/features/cart/components/CartDeleteDialog";
import { CartHeader } from "@/features/cart/components/CartHeader";
import { CartStoreSection } from "@/features/cart/components/CartStoreSection";
import { StoreVoucherModal } from "@/features/cart/components/StoreVoucherModal";
import { useCart } from "@/features/cart/hooks";
import { Voucher } from "@/features/cart/types";
import { useState } from "react";

// Mock vouchers - replace dengan actual data nanti
const storeVouchers: Record<string, Voucher[]> = {
  "pluto-gadget-store": [
    {
      id: 1,
      code: "PLUTO50",
      value: 50000,
      minSpend: 5000000,
      validUntil: "2026-12-31",
    },
  ],
  "another-store": [
    {
      id: 2,
      code: "ANOTHER10",
      value: 10000,
      minSpend: 5000000,
      validUntil: "2026-12-31",
    },
  ],
};

export default function CartPage() {
  const {
    cart,
    selectedIds,
    loading,
    search,
    setSearch,
    toggleSelect,
    toggleSelectAll,
    toggleSelectStore,
    updateQuantity,
    deleteItem,
    checkout,
    deleteDialog,
    closeDeleteDialog,
    confirmDelete,
  } = useCart();

  const [selectedVouchers, setSelectedVouchers] = useState<
    Record<string, number>
  >({});

  // State untuk voucher modal
  const [voucherModalStore, setVoucherModalStore] = useState<string | null>(
    null,
  );

  const calculateStoreSubtotal = (storeSlug: string) => {
    const storeGroup = cart.find((g) => g.store.slug === storeSlug);
    if (!storeGroup) return 0;

    const total = storeGroup.items
      .filter((item) => selectedIds.includes(item.cart_item_id))
      .reduce((acc, item) => {
        return acc + item.product.price * item.quantity;
      }, 0);

    const voucherId = selectedVouchers[storeSlug];
    const voucher = storeVouchers[storeGroup.store.name]?.find(
      (v) => v.id === voucherId,
    );

    const discount = voucher ? voucher?.value : 0;
    return Math.max(0, total - discount);
  };

  const totalSelectedItems = cart.reduce((acc, group) => {
    return (
      acc +
      group.items
        .filter((i) => selectedIds.includes(i.cart_item_id))
        .reduce((a, i) => a + i.quantity, 0)
    );
  }, 0);

  const totalSelectedPrice = cart.reduce((acc, group) => {
    return acc + calculateStoreSubtotal(group.store.slug);
  }, 0);

  const totalSelectedSaved = 100

  console.log(cart)
  // const totalSelectedSaved = cart
  //   .filter((item) => selectedIds.includes(item.id))
  //   .reduce((acc, item) => acc + (item.saved || 0) * item.quantity, 0);

  const isAllSelected = selectedIds.length === cart.length && cart.length > 0;

  return (
    <div className="min-h-screen bg-gray-50 pb-32">
      <CartHeader search={search} onSearchChange={setSearch} />

      <div className="space-y-6 px-20 py-6">
        {/* Table header untuk desktop */}
        <div className="hidden md:grid grid-cols-[0.5fr_3fr_1fr_1fr_1fr_2fr] gap-4 border-b bg-white p-4 font-medium text-gray-600">
          <input
            type="checkbox"
            checked={isAllSelected}
            onChange={toggleSelectAll}
            className="cursor-pointer"
          />
          <span>Product</span>
          <span>Unit Price</span>
          <span>Quantity</span>
          <span>Total Price</span>
          <span>Actions</span>
        </div>

        {loading ? (
          <div className="flex justify-center items-center w-full h-72 bg-slate-100">
            <p>Loading...</p>
          </div>
        ) : cart.length > 0 ? (
          cart.map((group) => {
            const vouchers = storeVouchers[group.store.name];

            return (
              <CartStoreSection
                key={group.store.slug}
                store={group.store}
                items={group.items}
                selectedIds={selectedIds}
                vouchers={vouchers}
                selectedVoucherId={selectedVouchers[group.store.slug]}
                onToggleStore={() => toggleSelectStore(group.store.slug)}
                onToggleItem={toggleSelect}
                onQtyChange={updateQuantity}
                onDeleteItem={deleteItem}
                onSelectVoucher={() => setVoucherModalStore(group.store.slug)}
              />
            );
          })
        ) : (
          <div className="rounded-lg bg-white p-8 text-center text-gray-500">
            Your cart is empty
          </div>
        )}
      </div>

      {/* Checkout bar */}
      <CartCheckoutBar
        totalItems={totalSelectedItems}
        totalPrice={totalSelectedPrice}
        totalSaved={totalSelectedSaved}
        isAllSelected={isAllSelected}
        onSelectAll={toggleSelectAll}
        onCheckout={checkout}
      />

      {/* Delete confirmation dialog */}
      <CartDeleteDialog
        open={deleteDialog.open}
        onClose={closeDeleteDialog}
        onConfirm={confirmDelete}
      />

      {voucherModalStore && (
        <StoreVoucherModal
          store={voucherModalStore}
          vouchers={storeVouchers[voucherModalStore] || []}
          open
          onClose={() => setVoucherModalStore(null)}
          selectedVoucherId={selectedVouchers[voucherModalStore]}
          onSelect={(voucherId) => {
            setSelectedVouchers((prev) => ({
              // ...prev,
              // [voucherModalStore]: voucherId,
            }));
            setVoucherModalStore(null);
          }}
        />
      )}
    </div>
  );
}
