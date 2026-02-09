import Image from "next/image";
import { Navbar } from "@/components/Navbar";
import ProductGrid from "@/features/product/components/ProductGrid";
import CategorySortBar from "@/features/category/components/slug/CategorySortBar";
import CategorySidebar from "@/features/category/components/slug/CategorySidebar";
import {
  Store,
  Users,
  Star,
  Package,
  Calendar,
  Clock,
  MessageCircle,
  UserPlus,
} from "lucide-react";

export default async function StorePage({
  params,
  searchParams,
}: {
  params: Promise<{ slug: string }>;
  searchParams: Promise<{
    page?: string;
    min?: string;
    max?: string;
  }>;
}) {
  const { slug: storeSlug } = await params;
  const resolvedSearchParams = await searchParams;

  const store = {
    name: "Avera Official Store",
    logo: "https://loremflickr.com/120/120",
    banner: "https://loremflickr.com/1400/400",
    products: 124,
    followers: 3200,
    following: 18,
    rating: 4.7,
    joined: "Jan 2023",
    lastActive: "2 jam lalu",
  };

  return (
    <div className="min-h-screen bg-background">
      <Navbar />

      {/* ================= STORE PROFILE ================= */}
      <section className="container px-20 py-6">
        <div className="grid grid-cols-12 gap-6 border rounded-xl p-6">
          {/* LEFT - PROFILE */}
          <div className="col-span-8 flex items-center gap-6">
            <Image
              src={store.logo}
              alt={store.name}
              width={96}
              height={96}
              className="rounded-xl border"
            />

            <div className="flex-1 space-y-2">
              <h1 className="text-xl font-semibold">{store.name}</h1>

              <div className="flex items-center gap-2 text-xs text-muted-foreground">
                <Clock size={14} />
                Active {store.lastActive}
              </div>

              <div className="flex gap-3 pt-2">
                <button className="flex items-center gap-2 px-4 py-2 text-sm rounded-lg border hover:bg-muted">
                  <UserPlus size={16} /> Follow
                </button>
                <button className="flex items-center gap-2 px-4 py-2 text-sm rounded-lg border hover:bg-muted">
                  <MessageCircle size={16} /> Chat
                </button>
              </div>
            </div>
          </div>

          {/* RIGHT - STATS */}
          <div className="col-span-4 grid grid-cols-3 grid-rows-2 gap-4 text-sm">
            <Stat
              icon={<Package size={14} />}
              label="Products"
              value={store.products}
            />
            <Stat icon={<Users size={14} />} label="Followers" value="3.2K" />
            <Stat
              icon={<Store size={14} />}
              label="Following"
              value={store.following}
            />
            <Stat
              icon={<Star size={14} />}
              label="Rating"
              value={store.rating}
            />
            <Stat
              icon={<Calendar size={14} />}
              label="Joined"
              value={store.joined}
            />
          </div>
        </div>
      </section>

      {/* ================= BANNER ================= */}
      <section className="relative h-[320px] w-full">
        <Image
          src={store.banner}
          alt="Store Banner"
          fill
          priority
          className="object-cover"
        />
      </section>
      {/* Recommended Product */}
      <div className="border rounded-xl p-4">
        <h3 className="font-semibold mb-3 text-sm">Recommended</h3>
        <ProductGrid
          storeSlug={storeSlug}
          searchParams={{ page: "1" }}
          limit={4}
        />
      </div>
      {/* ================= MAIN CONTENT ================= */}
      <main className="container px-20 py-8 grid grid-cols-12 gap-8">
        {/* LEFT ASIDE */}
        <aside className="col-span-3 space-y-6">
          {/* FILTER */}
          <CategorySidebar
            slug={storeSlug}
            searchParams={resolvedSearchParams}
          />
        </aside>

        {/* RIGHT PRODUCTS */}
        <section className="col-span-9 space-y-4">
          <CategorySortBar />
          <ProductGrid
            storeSlug={storeSlug}
            searchParams={resolvedSearchParams}
          />
        </section>
      </main>
    </div>
  );
}

/* ================= SMALL COMPONENT ================= */
function Stat({
  icon,
  label,
  value,
}: {
  icon: React.ReactNode;
  label: string;
  value: string | number;
}) {
  return (
    <div className="flex flex-col gap-1 rounded-lg border p-3">
      <div className="flex items-center gap-2 text-xs text-muted-foreground">
        {icon}
        <span>{label}</span>
      </div>
      <span className="text-sm font-semibold">{value}</span>
    </div>
  );
}
