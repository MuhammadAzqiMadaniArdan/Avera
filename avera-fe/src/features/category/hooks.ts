"use client"
import { useEffect, useState } from "react";
import { CategoryTree } from "./types";
import { getCategoryTree } from "./services";

export function useCategory() {
  const [categories, setCategories] = useState<CategoryTree[]>([]);
  const [loading, setLoading] = useState(true);
  useEffect(() => {
    const getCategoryTreeList = async () => {
      try {
        const res = await getCategoryTree();
        setCategories(res.data);
      } finally {
        setLoading(false);
      }
    };
    getCategoryTreeList()
  }, []);

  return {
    categories,
    loading
  };
}
