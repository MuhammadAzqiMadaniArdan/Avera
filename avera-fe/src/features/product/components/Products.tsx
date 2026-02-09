import Image from "next/image";

const products = Array.from({ length: 10 }).map((_, i) => ({
  name: `Product ${i + 1}`,
  price: `$${(i + 1) * 10}`,
  image: "/product-placeholder.png",
}));

export function Products() {
  return (
    <section className="max-w-7xl mx-auto py-8 px-4">
      <h2 className="text-xl font-bold mb-4">Products</h2>
      <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-4">
        {products.map((p) => (
          <div
            key={p.name}
            className="border border-border rounded-lg overflow-hidden bg-white shadow hover:shadow-md transition"
          >
            <Image  src={p.image} alt={p.name} className="w-full h-32 object-cover" />
            <div className="p-2">
              <h3 className="font-semibold text-sm">{p.name}</h3>
              <p className="text-xs text-muted-foreground">{p.price}</p>
            </div>  
          </div>
        ))}
      </div>
    </section>
  );
}
