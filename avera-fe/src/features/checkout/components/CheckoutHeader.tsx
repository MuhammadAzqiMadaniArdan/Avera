import Image from "next/image";
import Link from "next/link";

export default function CheckoutHeader() {
  return (
    <div className="bg-white border-b">
      <div className="max-w-6xl mx-auto flex items-center gap-3 p-4">
        <Link href={'/'}>
        <Image src="/avera-logo.svg" alt="Avera" width={32} height={32} />
        </Link>
        <span className="text-gray-400">|</span>
        <h1 className="text-lg font-semibold">Checkout</h1>
      </div>
    </div>
  );
}
