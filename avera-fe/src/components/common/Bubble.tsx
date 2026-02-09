export default function Bubble({ className }: { className: string }) {
  return (
    <div
      className={`absolute rounded-full bg-gradient-to-r from-secondary to-accent shadow-[0_30px_15px_rgba(0,0,0,0.15)] ${className}`}
    />
  );
}
