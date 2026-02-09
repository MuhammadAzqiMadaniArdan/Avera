"use client"
import { useEffect, useState } from "react";
import { CategoryHomepage } from "../category/types";
import { getCategoryParent } from "../category/services";
import { notify } from "@/lib/toast/notify";
import { ProductHomepage } from "../product/types";
import { getProductTop, getRandomProduct } from "../product/services";

export default function useHomepage() {
  const [categories, setCategories] = useState<CategoryHomepage[]>([]);
  const [topProducts, setTopProducts] = useState<ProductHomepage[]>([]);
  const [dailyProducts, setDailyProducts] = useState<ProductHomepage[]>([]);
  const [categoriesLoading, setCategoriesLoading] = useState(true);
  const [topProductsLoading, setTopProductsLoading] = useState(true);
  const [dailyProductsLoading, setDailyProductsLoading] = useState(true);

  const getCategoryData = async () => {
    try {
      const res = await getCategoryParent();
      setCategories(res.data);
    } catch (error: any) {
      notify.error(
        error.response?.data?.message ?? "Failed to load categories",
      );
    } finally {
      setCategoriesLoading(false);
    }
  };

  const getTopProducts = async () => {
    try {
      const res = await getProductTop();
      setTopProducts(res.data);
    } catch (error) {
      console.log(error);
    } finally {
      setTopProductsLoading(false);
    }
  };

    const getDailyProducts = async () => {
      try {
        const res = await getRandomProduct();
        setDailyProducts(res.data);
      } catch (error: any) {
        const apiError = error.response?.data;
        notify.error(apiError?.message ?? "Failed to load products");
      } finally {
        setDailyProductsLoading(false);
      }
    };
  

  useEffect(() => {
    getCategoryData();
    getTopProducts();
    getDailyProducts();
  }, []);

  return {
    categories,
    topProducts,
    dailyProducts,
    categoriesLoading,
    topProductsLoading,
    dailyProductsLoading,
  };
}
