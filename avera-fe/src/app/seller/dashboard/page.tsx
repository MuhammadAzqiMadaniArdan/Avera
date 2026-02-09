"use client"
import { OrdersChart } from "@/features/dashboard/components/orders/OrdersChart";
import { StatCard } from "@/features/dashboard/components/StatCard";
import { getStoreBySeller } from "@/features/store/services";
import { useEffect, useState } from "react";

export default function DashboardPage() {
  const [store,setStore] = useState("");
  const getDataStore = async () => {
    const res = await getStoreBySeller();
    console.log('response 1',res);
    console.log('response 2',res?.data);
    setStore(res?.data);
  }
  useEffect(() => {
    getDataStore();
  },[store])

  return (
    <div className="space-y-8">
      {/* Header */}
      <div>
        <h1 className="text-3xl font-semibold tracking-tight">
          Dashboard
        </h1>
        <p className="text-sm text-muted-foreground">
          Overview of your store performance
        </p>
      </div>

      {/* Stats */}
      <section className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <StatCard
          title="Total Revenue"
          value="$1,250.00"
          change="+12.5%"
          description="Trending up this month"
          trend="up"
        />
        <StatCard
          title="New Customers"
          value="1,234"
          change="-20%"
          description="Acquisition needs attention"
          trend="down"
        />
        <StatCard
          title="Active Accounts"
          value="45,678"
          change="+12.5%"
          description="Strong user retention"
          trend="up"
        />
        <StatCard
          title="Growth Rate"
          value="4.5%"
          change="+4.5%"
          description="Meets growth projections"
          trend="up"
        />
      </section>

      {/* Chart */}
      <OrdersChart />
    </div>
  );
}
