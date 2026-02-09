"use client";

import { useRouter } from "next/navigation";
import { useEffect, useMemo, useState } from "react";
import { ProductDetail, ProductHomepage } from "./types";
import { PaginatedReviews } from "../reviews/types";
import { storeProductCart } from "../cart/services";
import { notify } from "@/lib/toast/notify";
import { getProductReviews } from "../reviews/services";
import { CourierSla } from "../shipment/type";
import { getCourierSla } from "../shipment/service";
import { getProductCategory, getProductStore } from "./services";

export function useProductDetail(product: ProductDetail) {
  const placeholder = "https://placehold.co/420x420";
  const router = useRouter();

  const productId = product.id;

  // images
  const images = useMemo(() => {
    return (product.images ?? [])
      .filter((img) => img?.url)
      .sort((a, b) => a.position - b.position);
  }, [product.images]);

  const imageUrls = images.map((img) => img.url);

  const [activeImage, setActiveImage] = useState(
    imageUrls[0] ?? placeholder,
  );

  const [qty, setQty] = useState(1);
  const [color, setColor] = useState("Hitam");
  const [activeTab, setActiveTab] = useState<
    "spec" | "description" | "reviews"
  >("spec");

  const [loadingReviews, setLoadingReviews] = useState(true);
  const [loadingProductStore, setLoadingProductStore] = useState(true);
  const [loadingProductCategory, setLoadingProductCategory] = useState(true);
  const [productStore, setProductStore] = useState<ProductHomepage[]>([]);
  const [productCategory, setProductCategory] = useState<ProductHomepage[]>([]);
  const [courierSla, setCourierSla] = useState<CourierSla[]>([]);
  const [reviews, setReviews] = useState<PaginatedReviews>({
    data: [],
    meta: {
      current_page: 1,
      per_page: 6,
      total: 0,
      last_page: 1,
    },
  });

  const categoryPath = product.category_path;

  // actions
  const handleAddToCart = async () => {
    const payload = { productId, qty };
    const promise = storeProductCart(payload);

    notify.promise(promise, {
      loading: "Memasukkan Produk ke keranjang...",
      success: "Berhasil Memasukkan ke keranjang!",
      error: (err) =>
        err?.response?.data?.message ??
        "Gagal memasukkan produk ke keranjang",
    });
  };

  const handleBuyNow = async () => {
    try {
      const payload = { productId, qty };
      const res = await storeProductCart(payload);

      notify.success(res.message ?? "Berhasil Memasukkan ke keranjang");
      router.push(
        `/cart?itemsKey=${res.data.id}&storeId=${product.store.id}`,
      );
    } catch (error: any) {
      notify.error(
        error?.response?.data?.message ??
          "Gagal Memasukkan data ke keranjang",
      );
    }
  };

  const getReviewData = async (page = 1, rating = 0) => {
    try {
      const res = await getProductReviews(productId, {
        page,
        rating,
      });

      setReviews({
        data: res.data ?? [],
        meta: {
          current_page: res.meta?.current_page ?? 1,
          per_page: res.meta?.per_page ?? 6,
          total: res.meta?.total ?? 0,
          last_page: res.meta?.last_page ?? 1,
        },
      });
    } finally {
      setLoadingReviews(false);
    }
  };

  const getProductStoreData = async () => {
    try {
      const res = await getProductStore(product?.store?.slug);
      setProductStore(res.data);
    } finally {
      setLoadingProductStore(false);
    }
  };

  const getProductCategoryData = async () => {
    try {
      const res = await getProductCategory(product?.category?.slug);
      setProductCategory(res.data);
    } finally {
      setLoadingProductCategory(false);
    }
  };

  const getCourierSlaData = async () => {
    try {
      const res = await getCourierSla();
      setCourierSla(res.data);
    } catch(error) {
      notify.error(error?.response?.data?.message ?? "gagal mengambil data courier sla")
    }finally {
    }
  };

  useEffect(() => {
    getReviewData();
    getCourierSlaData();
    getProductStoreData();
    getProductCategoryData();
  }, []);

  return {
    // state
    imageUrls,
    productStore,
    productCategory,
    activeImage,
    qty,
    color,
    activeTab,
    reviews,
    loadingReviews,
    loadingProductStore,
    loadingProductCategory,
    categoryPath,
    courierSla,

    // setters
    setActiveImage,
    setQty,
    setColor,
    setActiveTab,

    // actions
    handleAddToCart,
    handleBuyNow,
    getReviewData,
  };
}
