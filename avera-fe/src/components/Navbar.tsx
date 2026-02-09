"use client";

import Link from "next/link";
import Image from "next/image";
import { Button } from "@/components/ui/button";
import { User, ShoppingCart } from "lucide-react";
import OauthButton from "./OauthButton";
import { useAuth } from "@/context/AuthContext";
import { SearchInput } from "@/features/search/components/SearchInput";

interface NavbarProps {
  onToggleMenu?: () => void;
}

export function Navbar({ onToggleMenu }: NavbarProps) {
  const { isAuth, user, loading } = useAuth();

  return (
    <nav className="bg-white shadow sticky top-0 z-50">
      <div className="px-4 lg:px-10 py-4 flex items-center justify-between gap-4">
        {/* LEFT */}
        <div className="flex items-center gap-2">
          {onToggleMenu && (
            <button
              className="md:hidden p-2 border rounded-md"
              onClick={onToggleMenu}
            >
              ☰
            </button>
          )}

          <Link href="/">
            <Image src="/avera-nav.svg" alt="Avera" width={150} height={40} />
          </Link>
        </div>

        {/* SEARCH DESKTOP */}
        <div className="hidden md:flex flex-1 max-w-2xl gap-2 mx-4">
          <SearchInput />
        </div>

        {/* RIGHT */}
        <div className="flex items-center gap-5">
          {/* CART */}
          <Link href="/cart">
            <Button variant="outline" size="icon">
              <ShoppingCart size={20} />
            </Button>
          </Link>

          {/* MOBILE */}
          <div className="md:hidden">
            {!loading &&
              (isAuth ? (
                <Link href="/user/account/profile">
                  <Button variant="outline" size="icon">
                    <User size={20} />
                  </Button>
                </Link>
              ) : (
                <OauthButton label="Login" />
              ))}
          </div>

          {/* DESKTOP */}
          <div className="hidden md:flex items-center gap-2">
            {!loading &&
              (isAuth ? (
                <Link href="/user/account/profile">
                  <div className="flex items-center gap-3 cursor-pointer rounded text-gray-600 hover:text-gray-800 px-2 py-1">
                    {/* <Button className="flex items-center gap-2 bg-background text-gray-800"> */}
                    {user?.avatar ? (
                      <Image
                        src={user.avatar}
                        alt={user?.username}
                        width={32}
                        height={32}
                        className="rounded-full"
                      />
                    ) : (
                      <User size={32} />
                    )}
                    <span className="px-2 py-1 bg-secondary text-white rounded text-sm">
                      {user?.name}
                    </span>
                    {/* </Button> */}
                  </div>
                </Link>
              ) : (
                <>
                  <OauthButton label="Login" />
                  <OauthButton label="Register" />
                </>
              ))}
          </div>
        </div>
      </div>

      {/* SEARCH MOBILE */}
      <div className="md:hidden px-4 pb-4">
        <div className="flex gap-2">
          <SearchInput />
        </div>
      </div>
    </nav>
  );
}
