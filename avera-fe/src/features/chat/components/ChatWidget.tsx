// components/chat/ChatWidget.tsx
"use client";

import { useState } from "react";
import { useAuth } from "@/context/AuthContext";
import ChatMiniButton from "./ChatMiniButton";
import ChatContactList from "./ChatContactList";
import ChatWindow from "./ChatWindow";

export type ChatMode = "mini" | "list" | "chat";

export default function ChatWidget() {
  const { accessToken } = useAuth(); // ✅ BENAR
  const [mode, setMode] = useState<ChatMode>("mini");
  const [activeChat, setActiveChat] = useState<string | null>(null);

  // ❗ kalau belum login, jangan render apa-apa
  if (!accessToken) return null;

  return (
    <div className="fixed bottom-6 right-6 z-50">
      {mode === "mini" && (
        <ChatMiniButton onClick={() => setMode("list")} />
      )}

      {mode === "list" && (
        <ChatContactList
          onClose={() => setMode("mini")}
          onSelect={(id) => {
            setActiveChat(id);
            setMode("chat");
          }}
        />
      )}

      {mode === "chat" && activeChat && (
        <ChatWindow
          contactId={activeChat}
          onBack={() => setMode("list")}
          onMinimize={() => setMode("mini")}
        />
      )}
    </div>
  );
}
