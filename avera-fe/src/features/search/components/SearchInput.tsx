"use client";

import { useState, useEffect } from "react";
import { Search } from "lucide-react";
import { useRouter } from "next/navigation";
import { averaApi } from "@/lib/api/axiosClient"; // Axios instance ke Avera BE
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";

export function SearchInput() {
  const [search, setSearch] = useState("");
  const [showStoreOption, setShowStoreOption] = useState(false);
  const [storeMatch, setStoreMatch] = useState<{name: string, slug: string} | null>(null);
  const router = useRouter();

  const isSearchValid = search.trim().length >= 3;

  useEffect(() => {
    let cancelled = false;

    const fetchStore = async () => {
      if (!isSearchValid) {
        setStoreMatch(null);
        return;
      }

      try {
        const res = await averaApi.get("/api/stores", { params: { q: search } });
        if (!cancelled) {
          setStoreMatch(res.data[0] ?? null);
          setShowStoreOption(res.data.length > 0);
        }
      } catch {
        if (!cancelled) setStoreMatch(null);
      }
    };

    fetchStore();

    return () => { cancelled = true; };
  }, [search, isSearchValid]);

  const handleSearch = (type: "product" | "store") => {
    const path = type === "store" ? "/search_store" : "/search";
    router.push(`${path}?q=${encodeURIComponent(search)}`);
  };

  return (
    <div className="relative flex-1 w-full"> {/* flex-1 biar stretch sesuai container */}
      <Input
        placeholder="Search products..."
        value={search}
        onChange={(e) => setSearch(e.target.value)}
        className="w-full" // pastikan input selalu full-width
      />
      <Button
        className="absolute right-0 top-0 h-full px-4 rounded-r-xl"
        disabled={!isSearchValid}
        onClick={() => handleSearch("product")}
        style={{ 
          borderRadius: "0px 10px 10px 0px"
         }}
      >
        <Search size={16} />
      </Button>

      {showStoreOption && storeMatch && (
        <div className="absolute top-full left-0 w-full bg-white shadow-md rounded mt-1 z-10">
          <button
            className="flex items-center gap-2 px-3 py-2 w-full hover:bg-gray-100"
            onClick={() => handleSearch("store")}
          >
            <Search size={16} />
            <span>Search &quot;{search}&quot; in store</span>
          </button>
        </div>
      )}
    </div>
  );
}

