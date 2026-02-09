"use client";
import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import { CreateProductForm } from "@/features/product/components/ProductCreate/CreateProductForms";
import { StepNavbar } from "@/features/product/components/StepNavbar";
import { createProduct } from "@/features/product/services";
import { notify } from "@/lib/toast/notify";
import { getCategoryTree } from "@/features/category/services";
import { Category } from "@/features/category/types";

export default function CreateProductPage() {
  const router = useRouter();
  const [step, setStep] = useState(1);

  const [name, setName] = useState("");
  const [categoryId, setCategoryId] = useState("");
  const [categoryList, setCategoryList] = useState<Category[]>([]);
  const [categoryLoading, setCategoryLoading] = useState(true);
  const [description, setDescription] = useState("");

  // ======================
  // Form validasi
  // ======================
  const isNameFilled = name.trim().length > 0;
  const isCategoryFilled = categoryId.trim().length > 0;
  const isDescriptionFilled = description.trim().length > 0;
  const isFormValid = isNameFilled && isCategoryFilled && isDescriptionFilled;

  // ======================
  // GET category data
  // ======================

  const getCategoryData = async () => {
    try {
      const res = await getCategoryTree();
      if (res.success) {
        setCategoryList(res?.data);
      } else {
        notify.error(res?.message ?? "Gagal mengambil category data");
      }
    } catch (err) {
      notify.error(
        err?.response?.data?.message ?? "Gagal mengambil category data",
      );
    } finally {
      setCategoryLoading(false);
    }
  };

  // ======================
  // Dummy POST
  // ======================
  const handleContinue = async () => {
    if (!isFormValid) return;

    try {
      const payload = { name, categoryId, description };

      const promise = createProduct(payload);

      notify.promise(promise, {
        loading: "Membuat produk...",
        success: "Produk berhasil dibuat",
        error: (err) =>
          err?.response?.data?.message ?? "Gagal menambahkan produk",
      });

      const res = await promise;
      const productSlug = res.data?.slug;

      router.push(`/seller/products/draft/${productSlug}`);
    } catch (error: any) {
      notify.error(
        error?.response?.data?.message ?? "Gagal menambahkan produk",
      );
    }
  };

  const handleArchive = () => {
    alert("Produk diarsipkan (dummy)");
  };

  useEffect(() => {
    getCategoryData();
  }, []);

  return (
    <div className="space-y-6">
      <CreateProductForm
        name={name}
        setName={setName}
        categoryId={categoryId}
        categoryList={categoryList}
        setCategoryId={setCategoryId}
        categoryLoading={categoryLoading}
        description={description}
        setDescription={setDescription}
      />

      <StepNavbar
        step={step}
        onBack={() => setStep(step === 1 ? 1 : step - 1)}
        onNext={handleContinue}
        publishDisabled={!isFormValid}
        nextTooltip="Data spesifik akan dibahas di step berikutnya"
      />
    </div>
  );
}
