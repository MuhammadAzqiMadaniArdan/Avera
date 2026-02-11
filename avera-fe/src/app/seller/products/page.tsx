"use client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { ProductGridCard } from "@/features/product/components/ProductDraft/ProductGridCard";
import { getProductSeller } from "@/features/product/services";
import { ProductSellerIndex } from "@/features/product/types";
import { TABS } from "@/lib/componentData";
import { notify } from "@/lib/toast/notify";
import { LayoutGrid, List } from "lucide-react";
import Link from "next/link";
import { useEffect, useState } from "react";

export default function ProductsPage() {
  const [view, setView] = useState<"list" | "grid">("list");
  const [search, setSearch] = useState("");
  const [categoryId, setCategory] = useState("");
  const [label, setLabel] = useState("");
const [products, setProducts] = useState<ProductSellerIndex[]>([]);
  const [loading, setLoading] = useState(true);

  const [appliedFilter, setAppliedFilter] = useState({
    search: "",
    categoryId: "",
    label: "",
  });

  const handleProductData = async () => {
    const res = await getProductSeller();
    try {
      if (res.success === true) {
        setProducts(res.data);
        notify.success(res?.message ?? "Berhasil mengambil data produk");
      } else {
        notify.error(res?.message ?? "Gagal Mengambil data produk");
      }
    } catch (error) {
      notify.error(
        error?.response?.data?.message ?? "Gagal Mengambil data produk",
      );
    } finally {
      setLoading(false);
    }
  };
  useEffect(() => {
    handleProductData();
  }, []);

  const [activeTab, setActiveTab] = useState<
    "all" | "draft" | "active" | "inactive"
  >("all");
  const filteredProducts = products.filter((p) => {
    // Filter berdasarkan tab
    const matchTab = activeTab === "all" ? true : p.status === activeTab;

    // Filter berdasarkan search, category, label
    const matchSearch =
      appliedFilter.search === "" ||
      p.name.toLowerCase().includes(appliedFilter.search.toLowerCase());

    const matchCategory =
      appliedFilter.categoryId === "" ||
      p.categoryId === appliedFilter.categoryId;

    const matchLabel =
      appliedFilter.label === "" || p.status === appliedFilter.label;

    // Semua harus true
    return matchTab && matchSearch && matchCategory && matchLabel;
  });

  // ✅ COUNTER
  const tabCounts = {
    draft: products.filter((p) => p.status === "draft").length,
    active: products.filter((p) => p.status === "active").length,
    inactive: products.filter((p) => p.status === "inactive").length,
  };

  return (
    <div className="space-y-6">
      {/* HEADER */}
      <div className="flex items-center justify-between">
        <h1 className="text-xl font-semibold">Produk Saya</h1>

        <div className="flex gap-2">
          <Button variant="outline">Pengaturan Produk</Button>
          <Button variant="outline">Pengaturan Massal</Button>
          <Link href="/seller/products/create">
            <Button className="bg-primary text-white">
              + Tambah Produk Baru
            </Button>
          </Link>
        </div>
      </div>

      {/* TABS */}
      <div className="flex gap-6 border-b text-sm">
        {TABS.map((tab) => {
          const isActive = activeTab === tab.key;
          if(loading) return (<p key={tab.key}>Loading...</p>)
          return (
            <button
              key={tab.key}
              onClick={() => setActiveTab(tab.key)}
              className={`pb-3 font-medium transition
                ${
                  isActive
                    ? "border-b-2 border-primary text-primary"
                    : "text-muted-foreground hover:text-foreground"
                }
              `}
            >
              {tab.label}

              {tab.key !== "all" && tabCounts[tab.key] > 0 && (
                <span className="ml-2 rounded-full bg-muted px-2 py-0.5 text-xs">
                  {tabCounts[tab.key]}
                </span>
              )}
            </button>
          );
        })}
      </div>
      {/* FILTER ROW */}
      <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        {/* LEFT: Filters */}
        <div className="flex flex-wrap items-center gap-2">
          {/* SEARCH */}
          <Input
            placeholder="Cari produk..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-56"
          />

          {/* CATEGORY */}
          <Input
            placeholder="Kategori"
            value={categoryId}
            onChange={(e) => setCategory(e.target.value)}
            className="w-40"
          />

          {/* LABEL PRODUK */}
          <div className="flex items-center gap-2 rounded-md border px-3 h-10">
            <span className="text-sm text-muted-foreground">Label Produk</span>
            <select
              value={label}
              onChange={(e) => setLabel(e.target.value)}
              className="bg-transparent text-sm outline-none"
            >
              <option value="" hidden>
                Pilih
              </option>
              <option value="public">Public</option>
              <option value="limited">Limited</option>
            </select>
          </div>

          {/* APPLY */}
          <Button
            variant="outline"
            onClick={() =>
              setAppliedFilter({
                search,
                categoryId,
                label,
              })
            }
          >
            Terapkan
          </Button>

          {/* RESET */}
          <Button
            variant="ghost"
            onClick={() => {
              setSearch("");
              setCategory("");
              setLabel("");
              setAppliedFilter({
                search: "",
                categoryId: "",
                label: "",
              });
            }}
          >
            Atur Ulang
          </Button>
        </div>

        {/* RIGHT: Info + View Toggle */}
        <div className="flex items-center gap-2">
          <span className="text-sm">{filteredProducts.length} Produk</span>
          <span className="rounded-full bg-muted px-2 py-1 text-xs">
            Batas max 100 produk
          </span>

          {/* VIEW TOGGLE */}
          <div className="flex gap-1">
            <button
              onClick={() => setView("list")}
              className={`p-2 rounded hover:bg-muted transition ${
                view === "list" ? "bg-muted" : ""
              }`}
            >
              <List className="h-4 w-4" />
            </button>

            <button
              onClick={() => setView("grid")}
              className={`p-2 rounded hover:bg-muted transition ${
                view === "grid" ? "bg-muted" : ""
              }`}
            >
              <LayoutGrid className="h-4 w-4" />
            </button>
          </div>
        </div>
      </div>

      {view === "list" ? (
        <div className="space-y-4">
          {filteredProducts.map((p) => (
            <Link
            href={`/product/${p.slug}`}
              key={p.id}
              className="grid grid-cols-5 items-center rounded-lg border px-4 py-3"
            >
              {/* PRODUCT */}
              <div className="flex gap-3">
                <div className="h-12 w-12 rounded bg-muted" />
                <div>
                  <span className="rounded bg-gray-200 px-2 text-xs">
                    {p.status}
                  </span>
                  <p className="font-medium">{p.name}</p>
                  <p className="text-xs text-muted-foreground">
                    Sold: {p.sold}
                  </p>
                </div>
              </div>

              <div>
                <p>{p.sold}</p>
                <p className="text-sm text-muted-foreground">
                  Rp {p.price.toLocaleString("id-ID")}
                </p>
              </div>

              <div>{p.stock}</div>
              <div>Baik</div>
              <div className="flex gap-2 text-sm">
                <button className="text-primary">Ubah</button>
                <button className="text-muted-foreground">Lainnya</button>
              </div>
            </Link>
          ))}
        </div>
      ) : (
        <div className="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
          {filteredProducts.map((p) => (
            <ProductGridCard key={p.id} product={p} />
          ))}
        </div>
      )}
    </div>
  );
}
