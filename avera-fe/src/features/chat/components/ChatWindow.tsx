import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { chatMessages } from "@/lib/dummyData";

export default function ChatWindow({
  onBack,
  onMinimize,
}: {
  contactId: string;
  onBack: () => void;
  onMinimize: () => void;
}) {
  return (
    <div className="w-96 h-[520px] bg-white rounded-lg shadow-xl flex flex-col">
      {/* Header */}
      <div className="flex justify-between items-center p-3 border-b">
        <button onClick={onBack}>←</button>
        <p className="font-medium">Avera Official Store</p>
        <button onClick={onMinimize}>_</button>
      </div>

      {/* Messages */}
      <div className="flex-1 overflow-y-auto p-3 space-y-2">
        {chatMessages.map((m) => (
          <div
            key={m.id}
            className={`max-w-[70%] p-2 rounded text-sm ${
              m.sender === "me"
                ? "bg-primary text-white ml-auto"
                : "bg-gray-100"
            }`}
          >
            {m.message}
          </div>
        ))}
      </div>

      {/* Input */}
      <div className="p-3 border-t flex gap-2">
        <Input placeholder="Type a message..." />
        <Button>Send</Button>
      </div>
    </div>
  );
}
