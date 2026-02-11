// app/(admin)/orders/page.tsx
"use client";

import { useState } from "react";
import { orders as dummyOrders, orders } from "@/lib/dummyData";
import { OrdersFilter } from "@/features/dashboard/components/orders/OrdersFilter";
import { OrdersPagination } from "@/features/dashboard/components/orders/OrdersPagination";
import { OrdersTable } from "@/features/dashboard/components/orders/OrdersTable";
import { OrdersEmpty } from "@/features/dashboard/components/orders/OrdersEmpty";

const PAGE_SIZE = 10;

export default function OrdersPage() {
  const [search, setSearch] = useState("");
  const [status, setStatus] = useState("all");
  const [page, setPage] = useState(1);

  const filtered = dummyOrders.filter((o) => {
    const matchSearch =
      o.id.toLowerCase().includes(search.toLowerCase()) ||
      o.customer.toLowerCase().includes(search.toLowerCase());

    const matchStatus = status === "all" || o.status === status;

    return matchSearch && matchStatus;
  });

  const totalPages = Math.ceil(filtered.length / PAGE_SIZE);
  // const paginated = filtered.slice((page - 1) * PAGE_SIZE, page * PAGE_SIZE);
  const [sort, setSort] = useState<"asc" | "desc">("asc");

  const sortedOrders = [...orders].sort((a, b) =>
    sort === "asc" ? a.total - b.total : b.total - a.total,
  );
  return (
    <div className="space-y-6">
      <h1 className="text-2xl font-semibold">Orders</h1>

      <OrdersFilter
        search={search}
        setSearch={setSearch}
        status={status}
        setStatus={setStatus}
      />
      {sortedOrders.length === 0 ? (
        <OrdersEmpty />
      ) : (
        <OrdersTable orders={sortedOrders} sort={sort} onSortChange={setSort} />
      )}
      <OrdersPagination page={page} totalPages={totalPages} setPage={setPage} />
    </div>
  );
}
