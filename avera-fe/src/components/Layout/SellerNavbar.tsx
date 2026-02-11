"use client";
import { Store } from "lucide-react";
import { Breadcrumbs } from "./BreadCumbs";
import Image from "next/image";
import Link from "next/link";
import { getStoreBySeller } from "@/features/store/services";
import { useEffect, useState } from "react";
import { notify } from "@/lib/toast/notify";
export function SellerNavbar() {
  const [store, setStore] = useState([]);
  const [loading, setLoading] = useState(true);
  const getDataStore = async () => {
    try {
      const res = await getStoreBySeller();
      if (res.success === true) {
        setStore(res.data);
        notify.success(res?.message ?? "Berhasil masuk ke dashboard seller");
      } else {
        notify.error(res?.message ?? "Failed to load store");
      }
    } catch (error) {
      notify.error(error.response?.data?.message ?? "Failed to load store");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    getDataStore()
  },[]);
  
  return (
    <header className="fixed top-0 left-0 right-0 z-50 flex h-14 items-center justify-between border-b bg-white px-6">
      {/* LEFT */}
      <div className="flex items-center gap-4">
        <div className="text-lg font-bold flex items-center">
          <Link href="/">
            <Image src="/avera-logo.svg" alt="Avera" width={40} height={40} />
          </Link>{" "}
          <span className="mx-2 text-muted-foreground">|</span>
          <span className="text-sm font-medium">Seller Center</span>
        </div>
        <Breadcrumbs />
      </div>

      {/* RIGHT */}
      <div className="flex items-center gap-2">
        <Store className="h-5 w-5" />
        <Link href={"/seller/profile"}>
          <span className="text-sm font-medium">{loading ? "laoding..." : store?.name }</span>
        </Link>
      </div>
    </header>
  );
}
