"use client";
import { useAuth } from "@/context/AuthContext";
import { logoutUser } from "@/lib/auth/service";
import { setAccessToken } from "@/lib/auth/token";
import { useRouter } from "next/navigation";

export default function LogoutButton() {
  const router = useRouter();
  const { logout } = useAuth();

  const handleLogout = async () => {
    try {
      await logoutUser();
      setAccessToken(null);
      logout();
      router.push("/");
    } catch (err) {
      console.error("Logout failed:", err);
    }
  };

  return <button className="cursor-pointer" onClick={handleLogout}>Logout</button>;
}
