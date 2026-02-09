import { notFound } from "next/navigation";
import ProductClient from "./ProductClient";
import { getProductByCompound } from "@/features/product/services";

interface Props {
  params: { compound: string };
}
export default async function ProductPage({
  params,
}: {
  params: Promise<{ compound: string }>;
}) {
  const { compound } = await params;

  const product = await getProductByCompound(compound);

  if (!product) {
    notFound();
  }

  return <ProductClient product={product.data} />;
}
