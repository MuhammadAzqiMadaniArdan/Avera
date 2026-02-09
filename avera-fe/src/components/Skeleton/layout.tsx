"use client";

import { ReactNode, useState } from "react";
import { usePathname, useRouter } from "next/navigation";
import { Navbar } from "@/components/Navbar";
import { Button } from "@/components/ui/button";

type SidebarItem =
  | "profile"
  | "addresses"
  | "banks"
  | "myPurchase"
  | "myVouchers";

export default function AccountLayout({ children }: { children: ReactNode }) {
  const router = useRouter();
  const pathname = usePathname();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  // Tentukan sidebar aktif berdasarkan URL
  const getActiveSidebar = (): SidebarItem => {
    if (pathname.includes("addresses")) return "addresses";
    if (pathname.includes("banks")) return "banks";
    if (pathname.includes("myPurchase")) return "myPurchase";
    if (pathname.includes("myVouchers")) return "myVouchers";
    return "profile";
  };

  const activeSidebar = getActiveSidebar();

  const handleSidebarClick = (item: SidebarItem) => {
    switch (item) {
      case "profile":
        router.push("/user/account/profile");
        break;
      case "addresses":
        router.push("/user/account/addresses");
        break;
      case "banks":
        router.push("/user/account/banks");
        break;
      case "myPurchase":
        router.push("/user/account/myPurchase");
        break;
      case "myVouchers":
        router.push("/user/account/myVouchers");
        break;
    }
  };

  return (
    <>
      <Navbar onToggleMenu={() => setSidebarOpen(!sidebarOpen)} />

      <div className="flex min-h-screen bg-gray-50">
        {/* ------------------ SIDEBAR ------------------ */}
        <aside
          className={`
            fixed lg:static top-0 left-0 h-full w-64 bg-white border-r p-6 space-y-6
            transform transition-transform duration-300 ease-in-out z-10
            ${sidebarOpen ? "translate-x-0" : "-translate-x-full"} 
            lg:translate-x-0
          `}
        >
          <h2 className="font-semibold text-lg">My Account</h2>
          <ul className="space-y-2">
            {[
              { key: "profile", label: "Profile" },
              { key: "banks", label: "Banks & Cards" },
              { key: "addresses", label: "Addresses" },
            ].map((item) => (
              <li key={item.key}>
                <button
                  className={`w-full text-left px-3 py-2 rounded hover:bg-gray-100 ${
                    activeSidebar === item.key ? "bg-gray-200 font-semibold" : ""
                  }`}
                  onClick={() => handleSidebarClick(item.key as SidebarItem)}
                >
                  {item.label}
                </button>
              </li>
            ))}
          </ul>

          <h2 className="font-semibold text-lg mt-6">My Purchase</h2>
          <ul className="space-y-2">
            <li>
              <button
                className={`w-full text-left px-3 py-2 rounded hover:bg-gray-100 ${
                  activeSidebar === "myPurchase" ? "bg-gray-200 font-semibold" : ""
                }`}
                onClick={() => handleSidebarClick("myPurchase")}
              >
                My Purchase
              </button>
            </li>
          </ul>

          <h2 className="font-semibold text-lg mt-6">Others</h2>
          <ul className="space-y-2">
            <li>
              <button
                className={`w-full text-left px-3 py-2 rounded hover:bg-gray-100 ${
                  activeSidebar === "myVouchers" ? "bg-gray-200 font-semibold" : ""
                }`}
                onClick={() => handleSidebarClick("myVouchers")}
              >
                My Vouchers
              </button>
            </li>
          </ul>
        </aside>

        {/* Overlay untuk mobile */}
        {sidebarOpen && (
          <div
            className="fixed inset-0 bg-black/30 z-40 lg:hidden"
            onClick={() => setSidebarOpen(false)}
          />
        )}

        {/* ------------------ CONTENT ------------------ */}
        <main className="flex-1 flex flex-col lg:flex-row gap-6 p-6 ml-0 lg:ml-0">
          {children}
        </main>
      </div>
    </>
  );
}
