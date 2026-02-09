import {
  LayoutDashboard,
  ShoppingCart,
  Package,
  Users,
} from "lucide-react";

export const iconMap: Record<string, JSX.Element> = {
  dashboard: <LayoutDashboard className="h-4 w-4" />,
  orders: <ShoppingCart className="h-4 w-4" />,
  products: <Package className="h-4 w-4" />,
  users: <Users className="h-4 w-4" />,
};
