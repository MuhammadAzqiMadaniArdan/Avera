"use client";
import { Sidebar } from "@/components/Layout/Sidebar";
import { sellerMenu } from "@/lib/componentData";
import { usePathname } from "next/navigation";

export default function ProductsLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const pathname = usePathname();

  // Tentukan halaman yang tidak mau sidebar
  const hideSidebarRoutes = [
    "/seller/products/create",
    "/seller/products/draft/[id]",
    "/seller/products/[id]", // bisa pakai regex nanti
  ];

  const shouldHideSidebar = hideSidebarRoutes.some((route) =>
    pathname.startsWith(route.replace("[id]", "")),
  );

  return (
    <>
      {!shouldHideSidebar ? (
        <div className="flex pt-14">
          <Sidebar menu={sellerMenu} />
          <main className="flex-1 p-6">{children}</main>
        </div>
      ) : (
         <div className="flex">
          <main className="flex-1 p-6">{children}</main>
        </div>
      )}
    </>
  );
}
