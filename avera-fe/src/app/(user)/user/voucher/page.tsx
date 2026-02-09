"use client";

import UserLayout from "../layout";

export default function MyVouchersPage() {
  return (
    <UserLayout>
      <div className="flex-1 bg-white rounded-xl p-6 shadow space-y-6">
        <h2 className="font-semibold text-lg">My Vouchers</h2>
        <p>Manage your vouchers here.</p>
      </div>
    </UserLayout>
  );
}
