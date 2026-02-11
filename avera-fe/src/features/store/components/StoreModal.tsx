"use client";

import { useState, useEffect } from "react";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
  DialogDescription,
} from "@/components/ui/dialog";
import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";
import { createStore, getStoreBySeller, StoreResponse } from "@/features/store/services";
import { StoreCreatedForm } from "@/features/store/components/StoreCreatedForm";
import { useRouter } from "next/navigation";
import { notify } from "@/lib/toast/notify";

export default function StoreModal() {
  const [store, setStore] = useState<StoreResponse | null>(null);
  const [loading, setLoading] = useState(true);
  const router = useRouter();

  useEffect(() => {
    const fetchStore = async () => {
      try {
        const res = await getStoreBySeller();
        setStore(res.data);
      } catch (err) {
        if (err.response?.status === 404 || err.response?.status === 401)
          setStore(null);
      } finally {
        setLoading(false);
      }
    };
    fetchStore();
  }, []);

  const handleStoreCreate = async (data: {
    name: string;
    description: string;
  }) => {
    try {
      const res = await createStore(data);
      if (res.success) {
        setStore(res.data);
        notify.success(res.message ?? "Success to make store");
      } else {
        notify.error(res.message ?? "Failed to make store");
      }
    } catch (err) {
      notify.error(err.response?.data?.message ?? "Failed to make store");
      alert(err.response?.data?.message ?? "Gagal membuat store");
    }
  };

  if (loading) return <Skeleton className="h-10 w-32 rounded" />;

  return (
    <>
      {!store ? (
        <Dialog>
          <DialogTrigger asChild>
            <Button>Buat Store</Button>
          </DialogTrigger>
          <DialogContent className="sm:max-w-lg">
            <DialogHeader>
              <DialogTitle>Buat Store</DialogTitle>
              <DialogDescription>
                Lengkapi informasi store kamu untuk memulai berjualan.
              </DialogDescription>
            </DialogHeader>

            <StoreCreatedForm onSubmit={handleStoreCreate} />
          </DialogContent>
        </Dialog>
      ) : (
        <Button onClick={() => router.push("/seller/dashboard")}>
          Menuju Seller Dashboard
        </Button>
      )}
    </>
  );
}
