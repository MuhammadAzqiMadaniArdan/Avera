"use client";

import { ReactNode, useState } from "react";
import { usePathname, useRouter } from "next/navigation";
import { Navbar } from "@/components/Navbar";

type SidebarItem = "profile" | "address" | "purchase" | "voucher";

export default function UserLayout({ children }: { children: ReactNode }) {
  const router = useRouter();
  const pathname = usePathname();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const getActiveSidebar = (): SidebarItem => {
    if (pathname.includes("address")) return "address";
    if (pathname.includes("purchase")) return "purchase";
    if (pathname.includes("voucher")) return "voucher";
    return "profile";
  };

  const activeSidebar = getActiveSidebar();

  const handleSidebarClick = (item: SidebarItem) => {
    const map = {
      profile: "/user/account/profile",
      address: "/user/account/address",
      purchase: "/user/purchase",
      voucher: "/user/voucher",
    };

    router.push(map[item]);
    setSidebarOpen(false);
  };

  return (
    <div className="min-h-screen flex flex-col bg-gray-50">
      {/* NAVBAR */}
      <Navbar onToggleMenu={() => setSidebarOpen(!sidebarOpen)} />

      {/* BODY */}
      <div className="flex flex-1 relative">
        {/* SIDEBAR */}
        <aside
          className={`
            w-64 bg-white border-r p-6 space-y-6
            fixed lg:static inset-y-0 left-0 z-30
            transform transition-transform duration-300
            ${sidebarOpen ? "translate-x-0" : "-translate-x-full"}
            lg:translate-x-0
            overflow-y-auto
          `}
        >
          <h2 className="font-semibold text-lg">My Account</h2>

          <ul className="space-y-2">
            {[
              { key: "profile", label: "Profile" },
              { key: "address", label: "Address" },
            ].map((item) => (
              <li key={item.key}>
                <button
                  className={`w-full text-left px-3 py-2 rounded
                    ${
                      activeSidebar === item.key
                        ? "bg-gray-200 font-semibold"
                        : "hover:bg-gray-100"
                    }`}
                  onClick={() =>
                    handleSidebarClick(item.key as SidebarItem)
                  }
                >
                  {item.label}
                </button>
              </li>
            ))}
          </ul>

          <h2 className="font-semibold text-lg mt-6">My Purchase</h2>
          <button
            className={`w-full text-left px-3 py-2 rounded
              ${
                activeSidebar === "purchase"
                  ? "bg-gray-200 font-semibold"
                  : "hover:bg-gray-100"
              }`}
            onClick={() => handleSidebarClick("purchase")}
          >
            My Purchase
          </button>

          <h2 className="font-semibold text-lg mt-6">Others</h2>
          <button
            className={`w-full text-left px-3 py-2 rounded
              ${
                activeSidebar === "voucher"
                  ? "bg-gray-200 font-semibold"
                  : "hover:bg-gray-100"
              }`}
            onClick={() => handleSidebarClick("voucher")}
          >
            My Vouchers
          </button>
        </aside>

        {/* OVERLAY MOBILE */}
        {sidebarOpen && (
          <div
            className="fixed inset-0 bg-black/30 z-20 lg:hidden"
            onClick={() => setSidebarOpen(false)}
          />
        )}

        {/* CONTENT */}
        <main className="flex-1 overflow-y-auto p-6">
          {children}
        </main>
      </div>
    </div>
  );
}
