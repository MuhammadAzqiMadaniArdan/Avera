export default function CategorySortBar() {
  return (
    <div className="flex items-center justify-between border-b pb-3">
      <div className="flex gap-2">
        {["Latest", "Popular", "Top Sales"].map((s) => (
          <button
            key={s}
            className="px-3 py-1.5 rounded-md border text-sm
                       hover:bg-muted font-medium"
          >
            {s}
          </button>
        ))}
      </div>

      <select className="border rounded-md px-3 py-1.5 text-sm">
        <option>Price: Low → High</option>
        <option>Price: High → Low</option>
      </select>
    </div>
  );
}
