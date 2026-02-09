import { authApi } from "../api/axiosClient";

export interface TokenResponse {
  access_token: string;
  refresh_token?: string;
  expires_in?: number;
}

// Exchange code → access token
export async function exchangeAuthorizationCode(
  code: string,
  codeVerifier: string,
  redirectUri: string
): Promise<TokenResponse> {
  const body = new URLSearchParams({
    grant_type: "authorization_code",
    client_id: process.env.NEXT_PUBLIC_CLIENT_ID!,
    redirect_uri: redirectUri,
    code,
    code_verifier: codeVerifier,
  });

  const response = await authApi.post("/api/v1/oauth/token", body, {
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    withCredentials: true,
  });

  return response.data;
}

// Refresh token
export async function refreshAccessToken(): Promise<string> {
  const response = await authApi.post("/api/v1/oauth/token", {});
  return response.data.access_token;
}

// Logout
export async function logoutUser(): Promise<void> {
  await authApi.post("/api/v1/logout", {}, { withCredentials: true });
}
