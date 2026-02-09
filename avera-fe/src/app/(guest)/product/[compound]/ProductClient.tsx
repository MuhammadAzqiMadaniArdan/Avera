"use client";

import React from "react";
import { Navbar } from "@/components/Navbar";
import { products } from "@/lib/dummyData";
import ProductCard from "@/features/product/components/ProductCard";
import Link from "next/link";
import { ProductDetail } from "@/features/product/types";

import StoreInfo from "@/features/product/components/ProductDetail/StoreInfo";
import ProductInfo from "@/features/product/components/ProductDetail/ProductInfo";
import MainImageColumn from "@/features/product/components/ProductDetail/MainImageColumn";
import Breadcrumb from "@/features/product/components/ProductDetail/BreadCrumb";
import ProductTabs from "@/features/product/components/ProductDetail/ProductTabs";
import { useProductDetail } from "@/features/product/hooks";
import { StoreProductSection } from "@/features/product/components/ProductDetail/StoreProductSection";
import { CategoryProductSection } from "@/features/product/components/ProductDetail/CategoryProductSection";
import ProductGrid from "@/features/product/components/Homepage/ProductGrid";

/* -------------------------- MAIN PAGE -------------------------- */

type ProductGridProps = {
  title: string;
  seeMoreUrl?: string; // URL untuk tombol See More
  storeSlug?: string; // optional untuk filter From This Store
  categorySlug?: string; // optional untuk filter You May Also Like
  perPage?: number;
};

export default function ProductPage({ product }: ProductDetail) {
  const {
    productStore,
    productCategory,
    loadingProductStore,
    loadingProductCategory,
    imageUrls,
    activeImage,
    setActiveImage,
    qty,
    setQty,
    color,
    setColor,
    activeTab,
    setActiveTab,
    reviews,
    getReviewData,
    categoryPath,
    handleAddToCart,
    handleBuyNow,
  } = useProductDetail(product);
  const CategorProductLink = `/category/${product?.category?.slug}`;
  return (
    <>
      <Navbar />
      <div className="bg-background min-h-screen px-4 lg:px-20">
        <Breadcrumb path={categoryPath} productName={product.name} />

        <section className="max-w-7xl mx-auto px-4 py-8 grid grid-cols-1 lg:grid-cols-[420px_1fr] gap-8">
          <MainImageColumn
            images={imageUrls}
            activeImage={activeImage}
            setActiveImage={setActiveImage}
          />

          <ProductInfo
            product={product}
            qty={qty}
            setQty={setQty}
            color={color}
            setColor={setColor}
            handleAddToCart={handleAddToCart}
            handleBuyNow={handleBuyNow}
          />
        </section>

        <StoreInfo store={product.store} />

        <ProductTabs
          activeTab={activeTab}
          setActiveTab={setActiveTab}
          description={product.description}
          reviews={reviews}
          getReviewData={getReviewData}
        />

        <StoreProductSection
          products={productStore}
          loading={loadingProductStore}
        />
        <ProductGrid
        title="You May Also Like"
        loading={loadingProductCategory}
        products={productCategory}
        seeMoreUrl={CategorProductLink}
        />
      </div>
    </>
  );
}

