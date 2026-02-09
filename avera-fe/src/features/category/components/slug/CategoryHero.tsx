import Image from "next/image";

export default function CategoryHero({ slug }: { slug: string }) {
  return (
    <div className="flex items-center gap-4 mb-6">
      <div className="relative w-16 h-16 rounded-xl overflow-hidden bg-muted">
        <Image
          src="https://placehold.co/100x100"
          alt={slug}
          fill
          className="object-cover"
        />
      </div>

      <div>
        <h1 className="text-2xl font-semibold capitalize">
          {slug.replace("-", " ")}
        </h1>
        <p className="text-sm text-muted-foreground">
          12.345 products available
        </p>
      </div>
    </div>
  );
}
