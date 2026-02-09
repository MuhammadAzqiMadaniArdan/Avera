"use client";

import Image from "next/image";
import ProfileForm from "@/features/profile/component/ProfileForm";
import { useProfile } from "@/features/profile/hooks";
import StoreModal from "@/features/store/components/StoreModal";

export default function ProfilePage() {
  const {
    profile,
    setProfile,
    loading,
    loadingAvatar,
    previewAvatar,
    handleUpdateProfile,
    handleUploadAvatar,
    handleSelectAvatar,
  } = useProfile();

  if (loading) {
    return (
      <div className="flex justify-center items-center h-[50vh]">
        <p className="text-gray-500">Loading profile...</p>
      </div>
    );
  }

  if (!profile) return null;

  return (
    <div className="max-w-screen-xl mx-auto px-4 lg:px-0 py-8 space-y-6">
      {/* ===== PROFILE HEADER ===== */}
      <div className="bg-white rounded-xl p-6 shadow flex items-center gap-4">
        <Image
          src={profile.avatar ?? "/avera-logo.svg"}
          alt="avatar"
          width={64}
          height={64}
          className="rounded-full object-cover"
        />
        <div>
          <h1 className="text-lg font-semibold">{profile.name}</h1>
          <p className="text-sm text-gray-500">{profile.email}</p>
        </div>
      </div>

      {/* ===== MAIN CONTENT ===== */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* ===== AVATAR CARD ===== */}
        <div className="bg-white rounded-xl p-6 shadow flex flex-col items-center gap-4">
          <Image
            src={previewAvatar ?? profile.avatar ?? "/avera-logo.svg"}
            alt="avatar"
            width={140}
            height={140}
            className="rounded-full object-cover"
          />

          <label className="w-full">
            <input
              type="file"
              accept="image/*"
              onChange={(e) =>
                e.target.files?.[0] &&
                handleSelectAvatar(e.target.files[0])
              }
              className="hidden"
            />
            <div className="w-full text-center px-4 py-2 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
              Change Avatar
            </div>
          </label>

          {previewAvatar && (
            <button
              disabled={loadingAvatar}
              onClick={handleUploadAvatar}
              className="w-full px-4 py-2 bg-black text-white rounded-lg disabled:opacity-50"
            >
              {loadingAvatar ? "Uploading..." : "Save Avatar"}
            </button>
          )}
        </div>

        {/* ===== PROFILE FORM ===== */}
        <div className="lg:col-span-2 bg-white rounded-xl p-6 shadow space-y-6">
          <ProfileForm
            profile={profile}
            setProfile={setProfile}
            onSubmit={handleUpdateProfile}
          />

          <div className="pt-4 border-t">
            <StoreModal />
          </div>
        </div>
      </div>
    </div>
  );
}
