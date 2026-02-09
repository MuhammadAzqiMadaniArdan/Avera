"use client";

import { Navbar } from "@/components/Navbar";
import AddressManager from "@/features/profile/component/AddressManager";
import React, { useState } from "react";

type SidebarItem =
  | "profile"
  | "banks"
  | "addresses"
  | "changePassword"
  | "notifications"
  | "privacy"
  | "myPurchase"
  | "myVouchers"
  | "myCoins";

type PurchaseTab =
  | "all"
  | "topay"
  | "toship"
  | "toreceive"
  | "completed"
  | "cancelled"
  | "return";
interface Address {
  id: number;
  fullName: string;
  phone: string;
  street: string;
  district: string;
  city: string;
  province: string;
  country: string;
  postalCode: string;
  label: "Home" | "Work";
  default: boolean;
  other?: string;
}
export default function ProfilePage() {
  const [activeSidebar, setActiveSidebar] = useState<SidebarItem>("profile");
  const [activePurchaseTab, setActivePurchaseTab] =
    useState<PurchaseTab>("all");
  const [sidebarOpen, setSidebarOpen] = useState(false);

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
              // { key: "changePassword", label: "Change Password" },
              // { key: "notifications", label: "Notification Settings" },
              // { key: "privacy", label: "Privacy Settings" },
            ].map((item) => (
              <li key={item.key}>
                <button
                  onClick={() => {
                    setActiveSidebar(item.key as SidebarItem);
                    setSidebarOpen(false); // close on mobile
                  }}
                  className={`w-full text-left px-3 py-2 rounded hover:bg-gray-100 ${
                    activeSidebar === item.key
                      ? "bg-gray-200 font-semibold"
                      : ""
                  }`}
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
                onClick={() => {
                  setActiveSidebar("myPurchase");
                  setSidebarOpen(false);
                }}
                className={`w-full text-left px-3 py-2 rounded hover:bg-gray-100 ${
                  activeSidebar === "myPurchase"
                    ? "bg-gray-200 font-semibold"
                    : ""
                }`}
              >
                My Purchase
              </button>
            </li>
          </ul>

          <h2 className="font-semibold text-lg mt-6">Others</h2>
          <ul className="space-y-2">
            {[
              { key: "myVouchers", label: "My Vouchers" },
              // { key: "myCoins", label: "My Shopee Coins" },
            ].map((item) => (
              <li key={item.key}>
                <button
                  onClick={() => {
                    setActiveSidebar(item.key as SidebarItem);
                    setSidebarOpen(false);
                  }}
                  className={`w-full text-left px-3 py-2 rounded hover:bg-gray-100 ${
                    activeSidebar === item.key
                      ? "bg-gray-200 font-semibold"
                      : ""
                  }`}
                >
                  {item.label}
                </button>
              </li>
            ))}
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
          {/* CONTENT */}
          <div className="flex-1 bg-white rounded-xl p-6 shadow space-y-6">
            {activeSidebar === "profile" && <ProfileForm />}
            {activeSidebar === "myPurchase" && (
              <PurchaseTabContent
                activeTab={activePurchaseTab}
                setActiveTab={setActivePurchaseTab}
              />
            )}
            {activeSidebar === "addresses" && <AddressManager />}
          </div>

          {/* PHOTO USER */}
          {activeSidebar === "profile" && (
            <div className="w-full lg:w-48 flex-shrink-0 flex flex-col items-center bg-white rounded-xl p-4 shadow mt-6 lg:mt-0">
              <div className="w-32 h-32 bg-gray-200 rounded-full mb-4" />
              <button className="px-4 py-2 border rounded bg-gray-100 hover:bg-gray-200">
                Select Image
              </button>
              <p className="text-xs text-gray-500 mt-2 text-center">
                File size: maximum 1 MB
                <br />
                File extension: .JPEG, .PNG
              </p>
            </div>
          )}
        </main>
      </div>
    </>
  );
}

// ------------------ PROFILE FORM ----------------


// ------------------ PURCHASE TAB ----------------
function PurchaseTabContent({
  activeTab,
  setActiveTab,
}: {
  activeTab: PurchaseTab;
  setActiveTab: React.Dispatch<React.SetStateAction<PurchaseTab>>;
}) {
  const tabs: { label: string; value: PurchaseTab }[] = [
    { label: "All", value: "all" },
    { label: "To Pay", value: "topay" },
    { label: "To Ship", value: "toship" },
    { label: "To Receive", value: "toreceive" },
    { label: "Completed", value: "completed" },
    { label: "Cancelled", value: "cancelled" },
    { label: "Return/Refund", value: "return" },
  ];

  return (
    <div>
      <div className="flex flex-wrap gap-2 mb-4">
        {tabs.map((t) => (
          <button
            key={t.value}
            onClick={() => setActiveTab(t.value)}
            className={`px-3 py-2 rounded ${
              activeTab === t.value
                ? "bg-black text-white"
                : "bg-gray-100 text-gray-700"
            }`}
          >
            {t.label}
          </button>
        ))}
      </div>

      <div className="border p-4 rounded bg-gray-50">
        <p>
          Showing content for tab: <strong>{activeTab}</strong>
        </p>
      </div>
    </div>
  );
}
