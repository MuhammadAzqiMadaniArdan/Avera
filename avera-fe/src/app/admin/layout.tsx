import { SellerNavbar } from "@/components/Layout/SellerNavbar";
import { Sidebar } from "@/components/Layout/Sidebar";
import { sellerMenu } from "@/lib/componentData";

export default function ProductsLayout({
  children,
}: {
  children: React.ReactNode;
}) {


  return (
    <>
      <div className="min-h-screen bg-gray-50">
          <SellerNavbar />
    
          {/* OFFSET HARUS ADA */}
        <div className="flex pt-14">
          <Sidebar menu={sellerMenu} />
          <main className="flex-1 p-6">{children}</main>
        </div>
        </div>
    </>
  );
}
