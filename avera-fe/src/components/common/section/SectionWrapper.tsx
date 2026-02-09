import Link from "next/link";

interface SectionWrapperProps {
  title: string;
  seeMoreHref?: string;
  children: React.ReactNode;
}

export function SectionWrapper({
  title,
  seeMoreHref,
  children,
}: SectionWrapperProps) {
  return (
    <section className="mt-8 mb-6">
      <div className="mb-3 flex items-center justify-between">
        <h2 className="text-xl font-semibold">{title}</h2>

        {seeMoreHref && (
          <Link
            href={seeMoreHref}
            className="text-sm text-primary hover:underline"
          >
            See more →
          </Link>
        )}
      </div>

      {children}
    </section>
  );
}
