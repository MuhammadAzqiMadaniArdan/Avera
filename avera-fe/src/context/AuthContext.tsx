"use client";

import { createContext, useContext, useEffect, useState } from "react";
import { setAccessToken } from "@/lib/auth/token";
import { getUserProfile } from "@/features/profile/services";
import { UserProfile } from "@/features/profile/types";

type AuthContextType = {
  isAuth: boolean;
  user: UserProfile | null;
  updateUser: (data: UserProfile) => void;
  loading: boolean;
  login: (token: string) => void;
  logout: () => Promise<void>;
  refreshProfile: () => Promise<void>;
};

const AuthContext = createContext<AuthContextType | null>(null);

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
  const [isAuth, setIsAuth] = useState(false);
  const [user, setUser] = useState<UserProfile | null>(null);
  const [loading, setLoading] = useState(true);

  // dipanggil setelah login / refresh
  const fetchProfile = async () => {
    try {
      const res = await getUserProfile();
      setUser(res?.data ?? null);
      setIsAuth(true);
    } catch {
      setUser(null);
      setIsAuth(false);
    }
  };

  const login = async (token: string) => {
    setAccessToken(token);
    setLoading(true);
    await fetchProfile();
    setLoading(false);
  };

  const logout = async () => {
    try {
      // optional: call logout API
      // await logoutUser();
    } finally {
      setAccessToken(null);
      setUser(null);
      setIsAuth(false);
    }
  };

  const updateUser = (data: UserProfile) => {
    setUser(data);
  };

  // INIT APP (cek session via refresh-token cookie)
  useEffect(() => {
    const initAuth = async () => {
      try {
        await fetchProfile(); // wajib dipanggil
      } catch {
        setUser(null);
        setIsAuth(false);
      } finally {
        setLoading(false);
      }
    };

    initAuth();
  }, []);

  return (
    <AuthContext.Provider
      value={{
        isAuth,
        user,
        loading,
        login,
        logout,
        updateUser,
        refreshProfile: fetchProfile,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const ctx = useContext(AuthContext);
  if (!ctx) {
    throw new Error("useAuth must be used inside AuthProvider");
  }
  return ctx;
};
