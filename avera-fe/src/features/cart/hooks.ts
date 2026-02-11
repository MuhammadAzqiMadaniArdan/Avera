"use client";

import { useEffect, useState } from "react";
import { checkoutCart, getCartItemList, updateCartQty } from "./services";
import { CartStoreGroup } from "./types";
import { notify } from "@/lib/toast/notify";
import { useRouter } from "next/navigation";

export function useCart() {
  const router = useRouter();
  const [cart, setCart] = useState<CartStoreGroup[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedIds, setSelectedIds] = useState<string[]>([]);
  const [search, setSearch] = useState("");
  const [deleteDialog, setDeleteDialog] = useState<{
    open: boolean;
    itemId?: string;
  }>({ open: false });

  // Load cart items on mount
  useEffect(() => {
    const loadCart = async () => {
      try {
        const items = await getCartItemList();
        setCart(items.data);
        notify.success("berhasil ambil cart");
      } catch (error) {
        notify.error("failed to get cart");
        console.error("Failed to load cart:", error);
      } finally {
        setLoading(false);
      }
    };
    loadCart();
  }, []);

  // Filter cart berdasarkan search
  const filteredCart = cart
    .map((group) => ({
      ...group,
      items: group.items.filter((item) =>
        item.product.name.toLowerCase().includes(search.toLowerCase()),
      ),
    }))
    .filter((group) => group.items.length > 0);

  // Get unique stores dari filtered cart
  const stores = Array.from(new Set(filteredCart.map((item) => item.store)));

  // Toggle select single item
  const toggleSelect = (id: string) => {
    setSelectedIds((prev) =>
      prev.includes(id) ? prev.filter((x) => x !== id) : [...prev, id],
    );
  };

  // Select/deselect all items
  const toggleSelectAll = () => {
    const allIds = filteredCart.flatMap((group) =>
      group.items.map((item) => item.cart_item_id),
    );

    if (allIds.every((id) => selectedIds.includes(id))) {
      setSelectedIds([]);
    } else {
      setSelectedIds(allIds);
    }
  };

  // Select/deselect all items dari store tertentu
  const toggleSelectStore = (storeSlug: string) => {
    const storeGroup = filteredCart.find((g) => g.store.slug === storeSlug);
    if (!storeGroup) return;

    const ids = storeGroup.items.map((i) => i.cart_item_id);
    const allSelected = ids.every((id) => selectedIds.includes(id));

    setSelectedIds((prev) =>
      allSelected
        ? prev.filter((id) => !ids.includes(id))
        : [...new Set([...prev, ...ids])],
    );
  };

  // Update quantity
  const updateQuantity = async (cartItemId: string, qty: number) => {
    if (qty < 1) {
      openDeleteDialog(cartItemId);
      return;
    }
    setCart((prev) =>
      prev.map((group) => ({
        ...group,
        items: group.items.map((item) =>
          item.cart_item_id === cartItemId ? { ...item, quantity: qty } : item,
        ),
      })),
    );
    try {
      const res = await updateCartQty({ cartItemId, qty });
      notify.success(res.message ?? "Berhasil Mengupdate Produk quantity");
    } catch (error) {
      notify.error(
        error?.response?.data?.message ?? "Gagal mengupdate quantity",
      );
    }
  };

  // Delete item
  const deleteItem = (cartItemId: string) => {
    setCart((prev) =>
      prev
        .map((group) => ({
          ...group,
          items: group.items.filter((item) => item.cart_item_id !== cartItemId),
        }))
        .filter((group) => group.items.length > 0),
    );
    setSelectedIds((prev) => prev.filter((id) => id !== cartItemId));
  };

  const checkout = async () => {
    if (selectedIds.length === 0) {
      notify.error("Pilih minimal 1 produk !");
      return;
    }
    try {
      const promise = checkoutCart({
        carts: selectedIds.map((id) => ({
          id,
          promo_id: null,
          user_voucher_id: null,
        })),
      });
      notify.promise(promise, {
        loading: "Melakukan Checkout...",
        success: "Berhasil Melakukan Checkout !",
        error: (err) =>
          err?.response?.data?.message ?? "Gagal Melakukan Checkout",
      });
      router.push("/checkout");
    } catch {}
  };

  // Delete dialog functions
  const openDeleteDialog = (cartItemId: string) => {
    setDeleteDialog({ open: true, itemId: cartItemId });
  };

  const closeDeleteDialog = () => {
    setDeleteDialog({ open: false });
  };

  const confirmDelete = () => {
    if (deleteDialog.itemId) {
      deleteItem(deleteDialog.itemId);
    }
    closeDeleteDialog();
  };

  return {
    cart: filteredCart,
    stores,
    selectedIds,
    search,
    setSearch,
    loading,

    toggleSelect,
    toggleSelectAll,
    toggleSelectStore,

    updateQuantity,
    deleteItem,

    checkout,

    deleteDialog,
    openDeleteDialog,
    closeDeleteDialog,
    confirmDelete,
  };
}
