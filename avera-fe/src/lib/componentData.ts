export type SidebarItem = {
  label: string;
  icon?: string;
  href?: string;
  children?: {
    label: string;
    href: string;
  }[];
};
export const adminMenu: SidebarItem[] = [
  {
    label: "Dashboard",
    href: "/admin/dashboard",
    icon: "dashboard",
  },
  {
    label: "Orders",
    href: "/admin/orders",
    icon: "orders",
  },
  {
    label: "Products",
    icon: "products",
    children: [
      { label: "All Products", href: "/admin/products" },
      { label: "Categories", href: "/admin/categories" },
    ],
  },
  {
    label: "Users",
    href: "/admin/users",
    icon: "users",
  },
];

export const sellerMenu: SidebarItem[] = [
  {
    label: "Dashboard",
    href: "/seller/dashboard",
    icon: "dashboard",
  },
  {
    label: "Orders",
    href: "/seller/orders",
    icon: "orders",
  },
  {
    label: "Products",
    icon: "products",
    children: [
      { label: "Produk Saya", href: "/seller/products" },
      { label: "Tambah Produk", href: "/seller/products/new" },
    ],
  },
];

export const chartData = {
  "3m": [
    { date: "Apr 6", value: 120 },
    { date: "Apr 12", value: 210 },
    { date: "Apr 18", value: 180 },
    { date: "Apr 24", value: 260 },
    { date: "Apr 30", value: 300 },
    { date: "May 6", value: 320 },
    { date: "May 12", value: 290 },
    { date: "May 18", value: 340 },
    { date: "May 24", value: 360 },
    { date: "May 30", value: 390 },
    { date: "Jun 5", value: 420 },
    { date: "Jun 11", value: 460 },
    { date: "Jun 17", value: 480 },
    { date: "Jun 23", value: 520 },
    { date: "Jun 30", value: 560 },
  ],
};
export const TABS = [
  { key: "all", label: "Semua" },
  { key: "action", label: "Perlu Tindakan" },
  { key: "review", label: "Sedang Ditinjau Avera" },
  { key: "hidden", label: "Belum Ditampilkan" },
] as const;
