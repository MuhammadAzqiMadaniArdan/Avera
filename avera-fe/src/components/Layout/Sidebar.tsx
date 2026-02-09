"use client";

import Link from "next/link";
import { usePathname } from "next/navigation";
import { useState } from "react";
import { ChevronDown } from "lucide-react";
import { cn } from "@/lib/utils";
import { iconMap } from "./IconMap";
import { SidebarItem } from "@/lib/componentData";

export function Sidebar({ menu }: { menu: SidebarItem[] }) {
  const pathname = usePathname();
  const [open, setOpen] = useState<Record<string, boolean>>({});

  return (
    <aside className="w-64 border-r bg-white p-4">
      <div className="mb-6 text-xl font-bold">Avera</div>

      <nav className="space-y-1">
        {menu.map((item) => {
          const isActive =
            item.href && pathname.startsWith(item.href);

          const hasChildren = !!item.children;

          return (
            <div key={item.label}>
              {/* MAIN ITEM */}
              {item.href ? (
                <Link
                  href={item.href}
                  className={cn(
                    "flex items-center gap-3 rounded-lg px-3 py-2 text-sm",
                    isActive
                      ? "bg-primary text-primary-foreground"
                      : "text-muted-foreground hover:bg-muted"
                  )}
                >
                  {item.icon && iconMap[item.icon]}
                  {item.label}
                </Link>
              ) : (
                <button
                  onClick={() =>
                    setOpen((o) => ({
                      ...o,
                      [item.label]: !o[item.label],
                    }))
                  }
                  className="flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm text-muted-foreground hover:bg-muted"
                >
                  <div className="flex items-center gap-3">
                    {item.icon && iconMap[item.icon]}
                    {item.label}
                  </div>
                  <ChevronDown
                    className={cn(
                      "h-4 w-4 transition",
                      open[item.label] && "rotate-180"
                    )}
                  />
                </button>
              )}

              {/* CHILDREN */}
              {hasChildren && open[item.label] && (
                <div className="ml-9 mt-1 space-y-1">
                  {item.children!.map((child) => {
                    const active = pathname === child.href;

                    return (
                      <Link
                        key={child.href}
                        href={child.href}
                        className={cn(
                          "block rounded px-2 py-1 text-sm",
                          active
                            ? "text-primary font-medium"
                            : "text-muted-foreground hover:text-foreground"
                        )}
                      >
                        {child.label}
                      </Link>
                    );
                  })}
                </div>
              )}
            </div>
          );
        })}
      </nav>
    </aside>
  );
}
