"use client";
import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import axios from "axios";
import StepLayout from "@/features/product/components/ProductCreate/StepLayout";
import RecommendCard from "@/features/product/components/ProductCreate/RecommendCard";
import { Tabs } from "@/features/product/components/ProductCreate/Tabs";
import { StepFormsUpdate } from "@/features/product/components/ProductDraft/StepFormsUpdate";
import { getProductByCompound } from "@/features/product/services";
import { StepNavbar } from "@/features/product/components/StepNavbar";
import { Product, ProductDraft } from "@/features/product/types";
import { notify } from "@/lib/toast/notify";

export default function DraftProductPage() {
  const params = useParams();
  const compound = params?.compound;

  const [step, setStep] = useState(1);

  const [activeTab, setActiveTab] = useState("info");
  const [product, setProduct] = useState<Product>([]);
  const [draft, setDraft] = useState<ProductDraft>({
  id: "",
  storeId: "",
  categoryId: "",
  name: "",
  description: "",
  status: "draft",
});

  // ======================
  // State Form (terpusat)
  // ======================
  const [name, setName] = useState("");
  const [description, setDescription] = useState("");
  const [category, setCategory] = useState("");
  const [images, setImages] = useState<string[]>([]);

  const [price, setPrice] = useState(0);
  const [stock, setStock] = useState(0);
  const [minBuy, setMinBuy] = useState(1);
  const [wholesalePrice, setWholesalePrice] = useState(0);
  const [weight, setWeight] = useState(0);
  const [length, setLength] = useState(0);
  const [width, setWidth] = useState(0);
  const [height, setHeight] = useState(0);
  const [sku, setSku] = useState("");
  const [loading, setLoading] = useState(true);

  // ======================
  // RecommendedCard logic
  // ======================
  const isPhotoUploaded = images.length >= 3;
  const isNameValid = name.length >= 25 && name.length <= 100;
  const isDescriptionValid = description.length >= 100;

  // cek apakah semua kondisi valid
  const isAllValid = isPhotoUploaded && isNameValid && isDescriptionValid;

  const getProduct = async () => {
    try {
      const res = await getProductByCompound(compound);
      setDraft(res.data);
      notify.success(res.message ?? "Berhasll mengambil produk");
    } catch (err) {
      notify.error(err?.response?.message ?? "Gagal mengambil produk");
    } finally {
      setLoading(false);
    }
  };
  // ======================
  // Fetch dummy product data
  // ======================
  useEffect(() => {
    if (!compound) return;
    getProduct()
  }, [compound]);

  // ======================
  // Submit ke server dummy
  // ======================
  const handleSubmit = (status: "draft" | "published" | "archived") => {
    // Validasi sebelum publish
    if (status === "published" && !isAllValid) {
      alert(
        "Produk belum lengkap. Pastikan semua rekomendasi terpenuhi sebelum publish.",
      );
      return;
    }

    const payload = {
      name,
      description,
      category,
      images,
      price,
      stock,
      weight,
      status,
    };

    axios
      .post(`/api/products/${compound}`, payload)
      .then(() => alert(`Berhasil simpan status: ${status}`))
      .catch(() => alert("Gagal simpan, ini dummy aja"));
  };

  return (
    <div className="space-y-6">
      <StepLayout
        left={
          <RecommendCard
            isPhotoUploaded={isPhotoUploaded}
            isNameValid={isNameValid}
            isDescriptionValid={isDescriptionValid}
          />
        }
        right={
          <>
            <Tabs activeTab={activeTab} setActiveTab={setActiveTab} />
            <StepFormsUpdate
              activeTab={activeTab}
              name={name}
              setName={setName}
              description={description}
              setDescription={setDescription}
              category={category}
              setCategory={setCategory}
              images={images}
              setImages={setImages}
              price={price}
              setPrice={setPrice}
              stock={stock}
              setStock={setStock}
              minBuy={minBuy}
              setMinBuy={setMinBuy}
              wholesalePrice={wholesalePrice}
              setWholesalePrice={setWholesalePrice}
              weight={weight}
              setWeight={setWeight}
              length={length}
              setLength={setLength}
              width={width}
              setWidth={setWidth}
              height={height}
              setHeight={setHeight}
              sku={sku}
              setSku={setSku}
            />
          </>
        }
      />

      <StepNavbar
        step={step}
        onBack={() => setStep(step === 1 ? 1 : step - 1)}
        onSaveDraft={() => handleSubmit("draft")}
        onArchive={() => handleSubmit("archived")}
        onPublish={() => handleSubmit("published")}
        publishDisabled={!isAllValid}
      />
    </div>
  );
}
