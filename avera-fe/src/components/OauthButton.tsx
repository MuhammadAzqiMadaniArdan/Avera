"use client";
import { generateCodeChallenge, generateCodeVerifier } from "@/lib/pkce";
import { Button } from "@/components/ui/button";

interface OauthButtonProps {
  label?: string; // bisa diisi "Login" / "Register"
}

export default function OauthButton({ label = "Login" }: OauthButtonProps) {
  const login = async () => {
    const codeVerifier = generateCodeVerifier();
    const codeChallenge = await generateCodeChallenge(codeVerifier);
    const state = crypto.randomUUID();

    sessionStorage.setItem("code_verifier", codeVerifier);
    sessionStorage.setItem("oauth_state", state);

    const params = new URLSearchParams({
      response_type: "code",
      client_id: process.env.NEXT_PUBLIC_CLIENT_ID!,
      redirect_uri: `${window.location.origin}/api/v1/auth/callback`,
      code_challenge: codeChallenge,
      code_challenge_method: "S256",
      state: state,
      scope: "avera.read",
    });

    window.location.href = `${process.env.NEXT_PUBLIC_AUTH_API_BASE_URL}/oauth/start?${params.toString()}`;
  };

  return (
    <Button onClick={login} className="bg-primary text-white">
      {label}
    </Button>
  );
}
