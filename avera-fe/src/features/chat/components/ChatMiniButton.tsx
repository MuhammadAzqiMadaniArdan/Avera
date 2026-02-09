// components/chat/ChatMiniButton.tsx
import { MessageCircle } from "lucide-react";

export default function ChatMiniButton({
  onClick,
}: {
  onClick: () => void;
}) {
  return (
    <button
      onClick={onClick}
      className="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-full shadow-lg hover:opacity-90"
    >
      <MessageCircle size={18} />
      Chat
    </button>
  );
}
