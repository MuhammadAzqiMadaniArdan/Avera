import { SellerNavbar } from "@/components/Layout/SellerNavbar";

export default function SellerLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="min-h-screen bg-gray-50">
      <SellerNavbar />

      {/* OFFSET HARUS ADA */}
      <div className="pt-14">{children}</div>
    </div>
  );
}