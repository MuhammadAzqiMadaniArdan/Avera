"use client";

import Image from "next/image";
import Link from "next/link";
import { useState } from "react";
import { Button } from "@/components/ui/button";
import { Trash2, ArrowLeft } from "lucide-react";
import { Input } from "@/components/ui/input";
import { StoreVoucherModal } from "@/features/cart/components/StoreVoucherModal";
import { Voucher } from "@/lib/types";

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogFooter,
} from "@/components/ui/dialog";

interface CartItem {
  id: number;
  shop: string;
  name: string;
  variations: string;
  price: number;
  quantity: number;
  image: string;
  saved?: number;
}

const initialCart: CartItem[] = [
  {
    id: 1,
    shop: "PLUTO GADGET STORE",
    name: "SAMSUNG A55 5G RAM 8/256GB NEW 100% ORIGINAL FREE ADAPTOR 25W",
    variations: "Blue, RAM 8/256GB",
    price: 4899000,
    quantity: 1,
    image: "/products/samsung-a55.jpg",
    saved: 5000,
  },
  {
    id: 2,
    shop: "PLUTO GADGET STORE",
    name: "Apple Airpods Pro 2",
    variations: "White",
    price: 3499000,
    quantity: 2,
    image: "/products/airpods-pro.jpg",
    saved: 10000,
  },
  {
    id: 3,
    shop: "ANOTHER STORE",
    name: "Xiaomi Redmi Note 12",
    variations: "Black",
    price: 2999000,
    quantity: 1,
    image: "/products/redmi-note12.jpg",
    saved: 2000,
  },
];

const storeVouchers: Record<string, Voucher[]> = {
  "PLUTO GADGET STORE": [
    {
      id: 1,
      code: "PLUTO50",
      value: 50,
      minSpend: 5000,
      validUntil: "2026-12-31",
    },
    {
      id: 2,
      code: "PLUTO200",
      value: 200,
      minSpend: 5000,
      validUntil: "2026-12-31",
    },
  ],
  "ANOTHER STORE": [
    {
      id: 3,
      code: "ANOTHER10",
      value: 10,
      minSpend: 5000,
      validUntil: "2026-12-31",
    },
    {
      id: 4,
      code: "ANOTHER500",
      value: 500,
      minSpend: 5000,
      validUntil: "2026-12-31",
    },
  ],
};

export default function CartPage() {
  const [cart, setCart] = useState<CartItem[]>(initialCart);
  const [search, setSearch] = useState("");
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  const [modalOpen, setModalOpen] = useState(false);
  const [deleteItemId, setDeleteItemId] = useState<number | null>(null);

  // state untuk menyimpan voucher yang dipilih per store
  const [selectedVoucher, setSelectedVoucher] = useState<
    Record<string, number>
  >({});

  // state untuk menyimpan store mana yang sedang membuka modal voucher
  const [voucherModalStore, setVoucherModalStore] = useState<string | null>(
    null,
  );

  const filteredCart = cart.filter((item) =>
    item.name.toLowerCase().includes(search.toLowerCase()),
  );

  const stores = Array.from(new Set(filteredCart.map((item) => item.shop)));

  const toggleSelect = (id: number) => {
    setSelectedIds((prev) =>
      prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id],
    );
  };

  const toggleSelectAll = () => {
    if (selectedIds.length === filteredCart.length) {
      setSelectedIds([]);
    } else {
      setSelectedIds(filteredCart.map((item) => item.id));
    }
  };

  const toggleSelectStore = (store: string) => {
    const storeIds = filteredCart
      .filter((i) => i.shop === store)
      .map((i) => i.id);
    const isAllSelected = storeIds.every((id) => selectedIds.includes(id));
    if (isAllSelected) {
      setSelectedIds((prev) => prev.filter((id) => !storeIds.includes(id)));
    } else {
      setSelectedIds((prev) => [...new Set([...prev, ...storeIds])]);
    }
  };

  const handleQuantityChange = (id: number, qty: number) => {
    if (qty < 1) {
      setDeleteItemId(id);
      setModalOpen(true);
      return;
    }
    setCart(
      cart.map((item) => (item.id === id ? { ...item, quantity: qty } : item)),
    );
  };

  const handleDelete = (id: number) => {
    setCart(cart.filter((item) => item.id !== id));
    setSelectedIds((prev) => prev.filter((x) => x !== id));
  };

  const calculateStoreSubtotal = (store: string) => {
    const storeItems = filteredCart.filter(
      (item) => item.shop === store && selectedIds.includes(item.id),
    );
    const total = storeItems.reduce(
      (acc, item) => acc + item.price * item.quantity,
      0,
    );

    const voucherId = selectedVoucher[store];
    const voucherValue =
      storeVouchers[store]?.find((v) => v.id === voucherId)?.value || 0;
    const discount = voucherId ? (voucherValue < 100 ? 1000 : voucherValue) : 0;

    return total - discount;
  };

  const totalSelectedItems = filteredCart
    .filter((item) => selectedIds.includes(item.id))
    .reduce((acc, item) => acc + item.quantity, 0);

  const totalSelectedPrice = stores.reduce(
    (acc, store) => acc + calculateStoreSubtotal(store),
    0,
  );

  const totalSelectedSaved = filteredCart
    .filter((item) => selectedIds.includes(item.id))
    .reduce((acc, item) => acc + (item.saved || 0) * item.quantity, 0);

  return (
    <div className="min-h-screen bg-gray-50 pb-32 ">
      {/* Header */}
      <div className="bg-white shadow p-4 px-20 flex flex-wrap items-center justify-between sticky top-0 z-50 gap-4">
        <div className="flex items-center gap-2 flex-1 min-w-0">
          <Link href="/">
            <Button variant="ghost" className="p-1">
              <ArrowLeft size={20} />
            </Button>
          </Link>
          <span className="text-gray-400">|</span>

          <Link href="/" className="flex items-center gap-2 min-w-0">
            <Image src="/avera-nav.svg" alt="Logo" width={100} height={30} />
          </Link>
          <span className="text-gray-400">|</span>

          <h2 className="font-medium text-lg truncate">Shopping Cart</h2>
        </div>

        <div className="flex-1 max-w-md">
          <Input
            className="w-full"
            placeholder="Search in your cart..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
          />
        </div>
      </div>

      <div className="px-20">
        {/* Table Header */}
        <div className="hidden md:grid grid-cols-[0.5fr_3fr_1fr_1fr_1fr_2fr] gap-4 p-4 bg-white border-b font-medium text-gray-600">
          <input
            type="checkbox"
            checked={selectedIds.length === filteredCart.length}
            onChange={toggleSelectAll}
          />
          <span>Product</span>
          <span>Unit Price</span>
          <span>Quantity</span>
          <span>Total Price</span>
          <span>Actions</span>
        </div>

        {/* Cart Items Grouped by Store */}
        <div className="flex flex-col gap-6 p-4">
          {stores.map((store) => {
            const storeItems = filteredCart.filter(
              (item) => item.shop === store,
            );
            const isStoreSelected = storeItems.every((item) =>
              selectedIds.includes(item.id),
            );

            return (
              <div key={store}>
                {/* Store Header */}
                <div className="flex items-center gap-2 mb-2">
                  <input
                    type="checkbox"
                    checked={isStoreSelected}
                    onChange={() => toggleSelectStore(store)}
                  />
                  <h3 className="font-bold">{store}</h3>
                </div>

                {storeItems.map((item) => (
                  <div
                    key={item.id}
                    className="bg-white rounded-lg shadow-sm p-4 flex flex-col md:grid md:grid-cols-[0.5fr_3fr_1fr_1fr_1fr_2fr] gap-4 items-center"
                  >
                    {/* Checkbox */}
                    <div className="flex justify-center">
                      <input
                        type="checkbox"
                        className="cursor-pointer"
                        checked={selectedIds.includes(item.id)}
                        onChange={() => toggleSelect(item.id)}
                      />
                    </div>

                    {/* Product */}
                    <div className="flex gap-4 items-center">
                      <div className="w-24 h-24 relative flex-shrink-0">
                        <Image
                          src={item.image}
                          alt={item.name}
                          fill
                          className="object-cover rounded"
                        />
                      </div>
                      <div>
                        <h3 className="font-medium">{item.name}</h3>
                        <p className="text-gray-500 text-sm">
                          Variations: {item.variations}
                        </p>
                        <p className="text-green-600 font-semibold mt-1">
                          Saved: Rp {item.saved?.toLocaleString()}
                        </p>
                      </div>
                    </div>

                    {/* Unit Price */}
                    <div className="text-green-600 font-semibold mt-2 md:mt-0">
                      Rp {item.price.toLocaleString()}
                    </div>

                    {/* Quantity */}
                    <div className="flex items-center gap-1 mt-2 md:mt-0">
                      <Button
                        size="sm"
                        variant="outline"
                        onClick={() =>
                          handleQuantityChange(item.id, item.quantity - 1)
                        }
                      >
                        -
                      </Button>
                      <Input
                        type="number"
                        className="w-16 text-center"
                        value={item.quantity}
                        onChange={(e) =>
                          handleQuantityChange(
                            item.id,
                            parseInt(e.target.value) || 1,
                          )
                        }
                        min={1}
                      />
                      <Button
                        size="sm"
                        variant="outline"
                        onClick={() =>
                          handleQuantityChange(item.id, item.quantity + 1)
                        }
                      >
                        +
                      </Button>
                    </div>

                    {/* Total Price */}
                    <div className="mt-2 md:mt-0 font-semibold">
                      Rp {(item.price * item.quantity).toLocaleString()}
                    </div>

                    {/* Actions */}
                    <div className="flex gap-2 mt-2 md:mt-0 justify-center">
                      <Button
                        variant="ghost"
                        size="sm"
                        onClick={() => handleDelete(item.id)}
                      >
                        <Trash2 size={16} /> Delete
                      </Button>
                    </div>
                  </div>
                ))}

                {/* Tombol pilih voucher + info voucher */}
                {storeVouchers[store] && storeVouchers[store].length > 0 && (
                  <div className="mt-2 flex items-center gap-4">
                    <Button
                      size="sm"
                      onClick={() => setVoucherModalStore(store)}
                    >
                      Select Voucher
                    </Button>

                    <span className="text-gray-600 text-sm">
                      {selectedVoucher[store]
                        ? storeVouchers[store].find(
                            (v) => v.id === selectedVoucher[store],
                          )?.code
                        : "No voucher selected"}
                    </span>
                  </div>
                )}
              </div>
            );
          })}
        </div>

        {/* Checkout bar fixed */}
        <div className="fixed bottom-0 left-0 w-full bg-white border-t p-4 flex flex-wrap justify-between items-center z-50 shadow-lg">
          <div className="flex items-center gap-4">
            <input
              type="checkbox"
              checked={selectedIds.length === filteredCart.length}
              onChange={toggleSelectAll}
            />
            <span>Select All ({filteredCart.length})</span>
            <Button variant="ghost" size="sm" onClick={() => setCart([])}>
              Delete
            </Button>
            <Button variant="ghost" size="sm">
              Move to My Likes
            </Button>
          </div>
          <div className="text-right">
            <p>
              Total ({totalSelectedItems} items):{" "}
              <span className="font-semibold">
                Rp {totalSelectedPrice.toLocaleString()}
              </span>
            </p>
            <p>
              Saved:{" "}
              <span className="text-green-600">
                {totalSelectedSaved.toLocaleString()}
              </span>
            </p>
          </div>
          <Button className="ml-4">Checkout</Button>
        </div>

        {/* Delete Confirmation Modal */}
        <Dialog open={modalOpen} onOpenChange={setModalOpen}>
          <DialogContent>
            <DialogHeader>
              <DialogTitle>Delete this product?</DialogTitle>
            </DialogHeader>
            <div className="py-2">
              Do you want to remove this product from your cart?
            </div>
            <DialogFooter className="flex gap-2 justify-end">
              <Button
                variant="outline"
                onClick={() => {
                  setModalOpen(false);
                  setDeleteItemId(null);
                }}
              >
                No
              </Button>
              <Button
                variant="destructive"
                onClick={() => {
                  if (deleteItemId) handleDelete(deleteItemId);
                  setModalOpen(false);
                  setDeleteItemId(null);
                }}
              >
                Yes
              </Button>
            </DialogFooter>
          </DialogContent>
        </Dialog>

        {voucherModalStore && storeVouchers[voucherModalStore] && (
          <StoreVoucherModal
            store={voucherModalStore}
            vouchers={storeVouchers[voucherModalStore]}
            open={!!voucherModalStore}
            onClose={() => setVoucherModalStore(null)}
            selectedVoucherId={selectedVoucher[voucherModalStore]}
            onSelect={(voucherId) => {
              // simpan voucher yang dipilih (atau null = tidak pakai)
              setSelectedVoucher((prev) => ({
                ...prev,
                [voucherModalStore]: voucherId || undefined,
              }));
              setVoucherModalStore(null);
            }}
            storeIcon="/path-to-store-icon.png"
          />
        )}
      </div>
    </div>
  );
}
