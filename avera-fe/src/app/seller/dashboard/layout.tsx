import { Sidebar } from "@/components/Layout/Sidebar";
import { sellerMenu } from "@/lib/componentData";

export default function DashboardLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <div className="flex">
      <Sidebar menu={sellerMenu} />
      <main className="flex-1 p-6">{children}</main>
    </div>
  );
}
