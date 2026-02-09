// "use client";

// import { User } from "lucide-react";
// import Link from "next/link";
// import { usePathname } from "next/navigation";

// export function SellerNavbar() {
//   const pathname = usePathname();

//   const showBreadcrumb = pathname !== "/seller/dashboard";

//   return (
//     <header className="sticky top-0 z-40 border-b bg-white">
//       <div className="flex h-14 items-center justify-between px-6">
        
//         {/* LEFT */}
//         <div className="flex items-center gap-3">
//           <Link href="/seller/dashboard" className="font-bold">
//             Avera
//           </Link>
//           <span className="text-muted-foreground">|</span>
//           <span className="text-sm font-medium text-muted-foreground">
//             Seller Center
//           </span>

//           {showBreadcrumb && (
//             <div className="ml-6 text-sm text-muted-foreground">
//               Beranda &gt; Produk Saya &gt; Tambah Produk
//             </div>
//           )}
//         </div>

//         {/* RIGHT */}
//         <div className="flex items-center gap-2">
//           <User className="h-5 w-5" />
//           <span className="text-sm font-medium">
//             Azqi
//           </span>
//         </div>
//       </div>
//     </header>
//   );
// }
