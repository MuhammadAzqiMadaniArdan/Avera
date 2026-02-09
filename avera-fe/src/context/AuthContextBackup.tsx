"use client"
import React, { createContext, useContext, useState } from "react";

type AuthContext = {
  accessToken: string | null;
  login: (token: string) => void;
  logout: () => void;
};

const AuthContext = createContext<AuthContext | null>(null);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [accessToken, _setAccessToken] = useState<string | null>(null);
  const login = (token : string) => {
    _setAccessToken(token)
  }
  const logout = () => {
    _setAccessToken(null)
  }
  return (
    <AuthContext.Provider value={{ accessToken, login,logout }}>
      {children}
    </AuthContext.Provider>
  );
}
export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error("useAuth must be used inside AuthProvider");
  return ctx;
}
