"use client";

import { useEffect, useState } from "react";
import { notify } from "@/lib/toast/notify";
import { useRouter } from "next/navigation";
import { Checkout } from "./types";
import { getCheckout, orderCheckout, updateCheckout } from "./services";

export function useCheckout() {
  const router = useRouter();
  const [checkout, setCheckout] = useState<Checkout | null>(null);
  const [loading, setLoading] = useState(true);
  // Load cart items on mount
  useEffect(() => {
    const loadCheckout = async () => {
      try {
        const items = await getCheckout();
        setCheckout(items.data);
        notify.success("berhasil ambil data checkout");
      } catch (error) {
        notify.error("failed to get data checkout");
        console.error("Failed to load data checkout:", error);
      } finally {
        setLoading(false);
      }
    };
    loadCheckout();
  }, []);

  // useCheckout.ts
  const selectAddress = async (addressId: string) => {
    try {
      const promise = updateCheckout(checkout?.id, {
        user_address_id: addressId,
      });
      notify.promise(promise, {
        loading: "Melakukan update address...",
        success: "Berhasil Melakukan update address !",
        error: (err) =>
          err?.response?.data?.message ?? "Gagal Melakukan update address",
      });
      setLoading(true);
      const updated = await getCheckout();
      setCheckout(updated.data);
    } finally {
      setLoading(false);
    }
  };

  const handlePlaceOrder = async () => {
    try {
      const response = await orderCheckout(checkout?.id); // panggil API store di backend

      const { order_id, snap_token } = response.data;

      if (!snap_token) {
        alert("Order berhasil dibuat! Silakan tunggu pengiriman COD");
        router.push(`/user/purchase`); // redirect ke halaman order list
      }

      if (snap_token) {
        router.push(`/payment/${order_id}`);
      }
    } catch (error) {
      notify.error(error?.response?.data?.message ?? "Gagal Membuat order");
    }
  };

  const handleUpdatePaymentMethod = async () => {
    try {
      setLoading(true);
      const res = await updateCheckout(checkout.id, {
        payment_method: checkout?.payment_method,
      });
      setCheckout(res.data);
      notify.success(res.message ?? "Berhasil mengupdate checkout");
    } catch (error) {
      notify.error(error?.response?.data?.message);
    } finally {
      setLoading(false);
    }
  };

  const selectShipment = async (
    storeId: string,
    shipmentId: string,
    activeShipmentId: string,
  ) => {
    try {
      if (shipmentId === activeShipmentId) {
        return;
      }
      setLoading(true);
      const promise = updateCheckout(checkout?.id, {
        shipment_id: shipmentId,
        store_id: storeId,
      });

      notify.promise(promise, {
        loading: "Updating shipment...",
        success: "Shipment updated",
        error: "Failed to update shipment",
      });

      const res = await promise;
      setCheckout(res.data);
    } catch (error) {
      console.error(error);
    } finally {
      setLoading(false);
    }
  };

  return {
    checkout,
    setCheckout,
    loading,
    handlePlaceOrder,
    selectAddress,
    handleUpdatePaymentMethod,
    selectShipment,
  };
}
