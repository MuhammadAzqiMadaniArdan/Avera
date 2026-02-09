"use client";

import { useAuth } from "@/context/AuthContext";
import { exchangeAuthorizationCode } from "@/lib/auth/service";
import { setAccessToken } from "@/lib/auth/token";
import { useRouter, useSearchParams } from "next/navigation";
import { useEffect, useState } from "react";
import { Loader2, AlertCircle } from "lucide-react";

export default function CallbackPage() {
  const params = useSearchParams();
  const router = useRouter();
  const { login } = useAuth();

  const [status, setStatus] = useState<{
    type: "loading" | "error" | "success";
    message: string;
  }>({ type: "loading", message: "Signing you in..." });

  useEffect(() => {
    const code = params.get("code");
    const state = params.get("state");
    const error = params.get("error");

    if (error === "access_denied") {
      setStatus({ type: "error", message: "Access denied by provider." });
      setTimeout(() => router.replace("/auth/login?error=access_denied"), 2000);
      return;
    }

    const storedState = sessionStorage.getItem("oauth_state");
    if (!code || !state || state !== storedState) {
      setStatus({ type: "error", message: "Invalid callback parameters." });
      setTimeout(() => router.replace("/auth/login?error=invalid_callback"), 2000);
      return;
    }

    const codeVerifier = sessionStorage.getItem("code_verifier");
    if (!codeVerifier) {
      setStatus({ type: "error", message: "Missing code verifier." });
      setTimeout(() => router.replace("/auth/login?error=missing_verifier"), 2000);
      return;
    }

    handleExchangeToken(code, codeVerifier);
  }, []);

  const handleExchangeToken = async (code: string, verifier: string) => {
    try {
      setStatus({ type: "loading", message: "Exchanging token..." });

      const redirectUri = "http://localhost:3000/api/v1/auth/callback";
      const tokenData = await exchangeAuthorizationCode(code, verifier, redirectUri);

      const accessToken = tokenData.access_token;

      // Set token ke global store & context
      setAccessToken(accessToken);
      login(accessToken);

      // Clear temporary storage
      sessionStorage.removeItem("oauth_state");
      sessionStorage.removeItem("code_verifier");

      setStatus({ type: "success", message: "Redirecting to your store..." });
      setTimeout(() => router.replace("/user/account/profile"), 1000);
    } catch (err) {
      console.error("Token exchange failed:", err);
      setStatus({ type: "error", message: "Token exchange failed." });
      setTimeout(() => router.replace("/auth/login?error=token_exchange_failed"), 2000);
    }
  };

  return (
    <div className="flex flex-col items-center justify-center min-h-screen bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-white px-4">
      {status.type === "loading" && (
        <div className="flex flex-col items-center gap-4">
          <Loader2 className="w-12 h-12 animate-spin text-primary" />
          <p className="text-lg font-medium">{status.message}</p>
        </div>
      )}

      {status.type === "success" && (
        <div className="flex flex-col items-center gap-4 text-green-600">
          <p className="text-lg font-medium">{status.message}</p>
        </div>
      )}

      {status.type === "error" && (
        <div className="flex flex-col items-center gap-4 text-red-600">
          <AlertCircle className="w-12 h-12" />
          <p className="text-lg font-medium">{status.message}</p>
        </div>
      )}
    </div>
  );
}
