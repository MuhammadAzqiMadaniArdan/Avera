import { StatCard } from "@/features/dashboard/components/StatCard";

export default function AdminDashboard() {
  return (
    <div className="space-y-6">
      <div className="grid gap-4 md:grid-cols-4">
        <StatCard title="Total Users" value="1,240" />
        <StatCard title="Orders" value="8,392" />
        <StatCard title="Revenue" value="Rp 124 jt" />
        <StatCard title="Products" value="342" />
      </div>
    </div>
  );
}
