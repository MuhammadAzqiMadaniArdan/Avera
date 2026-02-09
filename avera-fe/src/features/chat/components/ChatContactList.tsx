// components/chat/ChatContactList.tsx
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { chatContacts } from "@/lib/dummyData";

export default function ChatContactList({
  onClose,
  onSelect,
}: {
  onClose: () => void;
  onSelect: (id: string) => void;
}) {
  return (
    <div className="w-80 h-[420px] bg-white rounded-lg shadow-xl flex flex-col">
      {/* Header */}
      <div className="flex justify-between items-center p-3 border-b">
        <h3 className="font-semibold text-primary">Chat</h3>
        <div className="flex gap-2">
          <Button size="icon" variant="ghost">
            ⤢
          </Button>
          <Button size="icon" variant="ghost" onClick={onClose}>
            _
          </Button>
        </div>
      </div>

      {/* Search */}
      <div className="p-3">
        <Input placeholder="Search contact..." />
      </div>

      {/* Filter */}
      <div className="flex gap-2 px-3 text-xs">
        <Button size="sm" variant="secondary">All</Button>
        <Button size="sm" variant="ghost">Unread</Button>
        <Button size="sm" variant="ghost">Read</Button>
      </div>

      {/* Contacts */}
      <div className="flex-1 overflow-y-auto p-3 space-y-2">
        {chatContacts.map((c) => (
          <div
            key={c.id}
            onClick={() => onSelect(c.id)}
            className="p-2 rounded hover:bg-gray-100 cursor-pointer flex justify-between"
          >
            <div>
              <p className="text-sm font-medium">{c.name}</p>
              <p className="text-xs text-gray-500 truncate">
                {c.lastMessage}
              </p>
            </div>

            {c.unread > 0 && (
              <span className="text-xs bg-red-500 text-white px-2 rounded-full h-fit">
                {c.unread}
              </span>
            )}
          </div>
        ))}
      </div>
    </div>
  );
}
