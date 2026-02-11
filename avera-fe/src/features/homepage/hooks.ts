"use client"
import { useEffect, useState } from "react";
import { CategoryBase } from "../category/types";
import { getCategoryParent } from "../category/services";
import { notify } from "@/lib/toast/notify";
import { ProductBase } from "../product/types";
import { getProductTop, getRandomProduct } from "../product/services";

export default function useHomepage() {
  const [categories, setCategories] = useState<CategoryBase[]>([]);
  const [topProducts, setTopProducts] = useState<ProductBase[]>([]);
  const [dailyProducts, setDailyProducts] = useState<ProductBase[]>([]);
  const [categoriesLoading, setCategoriesLoading] = useState(true);
  const [topProductsLoading, setTopProductsLoading] = useState(true);
  const [dailyProductsLoading, setDailyProductsLoading] = useState(true);

  const getCategoryData = async () => {
    try {
      const res = await getCategoryParent();
      setCategories(res.data);
    } catch (error) {
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
      } catch (error) {
        const apiError = error?.response?.data;
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
