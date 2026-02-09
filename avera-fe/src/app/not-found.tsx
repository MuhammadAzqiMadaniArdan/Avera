import Bubble from '@/components/common/Bubble';
import Link from 'next/link'
 
export default function NotFound() {
  return (
    <div className="relative min-h-screen overflow-hidden bg-gradient-to-b from-primary to-primary/90 text-white flex items-center justify-center">
      
      {/* BIG GRADIENT CIRCLES */}
      <div className="absolute -z-10 w-[105vw] h-[105vw] rounded-full bg-gradient-to-b from-primary/90 to-primary opacity-60" />
      <div className="absolute -z-10 w-[80vw] h-[80vw] rounded-full bg-gradient-to-b from-primary to-primary/80 opacity-60" />
      <div className="absolute -z-10 w-[60vw] h-[60vw] rounded-full bg-gradient-to-b from-primary/80 to-primary opacity-60" />
      <div className="absolute -z-10 w-[40vw] h-[40vw] rounded-full bg-gradient-to-b from-primary to-primary/70 opacity-60" />

      {/* BUBBLES */}
      <Bubble className="top-[15vh] left-[15vw] w-[22vmin] h-[22vmin]" />
      <Bubble className="top-[20vh] left-[38vw] w-[10vmin] h-[10vmin]" />
      <Bubble className="top-[12vh] right-[30vw] w-[13vmin] h-[13vmin]" />
      <Bubble className="top-[25vh] right-[18vw] w-[18vmin] h-[18vmin]" />
      <Bubble className="top-[60vh] right-[18vw] w-[28vmin] h-[28vmin]" />

      {/* CONTENT */}
      <div className="text-center z-10 drop-shadow-[0_30px_10px_rgba(0,0,0,0.15)]">
        <h1 className="text-[96px] font-bold leading-none">404</h1>
        <p className="mt-2 text-lg opacity-90">
          It looks like you&apos;re lost...
          <br />
          That&apos;s a trouble?
        </p>

        <Link
          href="/"
          className="inline-block mt-6 rounded-full bg-gradient-to-r from-secondary to-accent px-6 py-2 text-sm font-semibold uppercase shadow-[0_30px_15px_rgba(0,0,0,0.15)] hover:scale-105 transition"
        >
          Go Back
        </Link>
      </div>
    </div>
  );
}
