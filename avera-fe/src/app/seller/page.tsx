import { StatCard } from "@/features/dashboard/components/StatCard";

export default function SellerDashboard() {
  return (
    <div className="space-y-6">
      <div className="grid gap-4 md:grid-cols-3">
        <StatCard title="Orders" value="312" />
        <StatCard title="Revenue" value="Rp 18 jt" />
        <StatCard title="Products" value="24" />
      </div>
    </div>
  );
}
