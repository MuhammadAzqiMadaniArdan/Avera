"use client";

import { useEffect, useState } from "react";
import { UserAddress, UserProfile } from "./types";
import {
  createUserAddress,
  deleteUserAddress,
  getUserAddresses,
  getUserProfile,
  updateUserAddress,
  updateUserProfile,
  uploadAvatar,
} from "./services";
import { notify } from "@/lib/toast/notify";
import { useAuth } from "@/context/AuthContext";

export function useProfile() {
  const { updateUser } = useAuth(); // ambil setter dari context
  const [profile, setProfile] = useState<UserProfile | null>(null);
  const [originalProfile, setOriginalProfile] = useState<UserProfile>([]);
  const [loading, setLoading] = useState(true);
  const [loadingAvatar, setLoadingAvatar] = useState(false);

  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [previewAvatar, setPreviewAvatar] = useState<string | null>(null);

  // ================= FETCH PROFILE =================
  useEffect(() => {
    (async () => {
      try {
        const res = await getUserProfile();
        setProfile(res.data);
        setOriginalProfile(res.data);
      } catch (error: any) {
        notify.error(error?.response?.data?.message ?? "Gagal ambil profile");
      } finally {
        setLoading(false);
      }
    })();
  }, []);

  // ================= FORM UPDATE =================
  const handleUpdateProfile = async () => {
    if (!profile) return;

    const payload: Partial<typeof profile> = {};
    if (profile.username !== originalProfile.username)
      payload.username = profile.username;
    if (profile.name !== originalProfile.name) payload.name = profile.name;
    if (profile.email !== originalProfile.email) payload.email = profile.email;
    if (profile.gender !== originalProfile.gender)
      payload.gender = profile.gender;
    if (profile.phone_number !== originalProfile.phone_number)
      payload.phone_number = profile.phone_number;

    try {
      setLoading(true);
      const promise = updateUserProfile(payload);

      notify.promise(promise, {
        loading: "Mengupdate profile...",
        success: "Berhasil Mengupdate Profile",
        error: "Gagal Mengupdate Profile user",
      });

      const res = await promise;
      setProfile(res.data);
      updateUser(res.data);

      notify.success("Profile berhasil diperbarui");
    } catch (error: any) {
      notify.error(error?.response?.data?.message ?? "Update profile gagal");
    } finally {
      setLoading(false);
    }
  };

  // ================= AVATAR =================
  const handleSelectAvatar = (file: File) => {
    setAvatarFile(file);
    setPreviewAvatar(URL.createObjectURL(file));
  };

  const handleUploadAvatar = async () => {
    if (!avatarFile) return;

    try {
      setLoadingAvatar(true);
      const res = await uploadAvatar(avatarFile);
      setAvatarFile(null);
      setPreviewAvatar(null);

      const profileRes = await getUserProfile();
      setProfile(profileRes.data);
      notify.success("Avatar berhasil diperbarui");
    } catch (error: any) {
      notify.error(error?.response?.data?.message ?? "Upload avatar gagal");
    } finally {
      setLoadingAvatar(false);
    }
  };

  return {
    profile,
    loading,
    loadingAvatar,
    previewAvatar,

    setProfile,
    handleUpdateProfile,
    handleSelectAvatar,
    handleUploadAvatar,
  };
}

export function useAddress() {
  const [addresses, setAddresses] = useState<UserAddress[]>([]);
  const [loading, setLoading] = useState(true);
  const [formOpen, setFormOpen] = useState(false);
  const [editing, setEditing] = useState<UserAddress | null>(null);
  const [deleting, setDeleting] = useState<UserAddress | null>(null);

  useEffect(() => {
    const getAddress = async () => {
      try {
        const res = await getUserAddresses();
        notify.success(res.message ?? "Berhasil mengambil data user address");
        setAddresses(res.data);
      } catch (error) {
        notify.error(
          error?.response?.data?.message ?? "Gagal mengambil data user address",
        );
      } finally {
        setLoading(false);
      }
    };
    getAddress();
  }, []);

  const save = async (data: UserAddress): Promise<UserAddress> => {
    try {
      setLoading(true);
      let updatedAddress: UserAddress;

      if (editing) {
        const promise = updateUserAddress(data.id!, data);

        notify.promise(promise, {
          loading: "Memproses edit address...",
          success: "Berhasil mengubah data address",
          error: (err) =>
            err?.response?.data?.message ?? "Gagal Mengubah data address",
        });

        const res = await promise;
        updatedAddress = res.data;

        setAddresses((prev) =>
          prev.map((a) => (a.id === updatedAddress.id ? updatedAddress : a)),
        );
      } else {
        const promise = createUserAddress(data);

        notify.promise(promise, {
          loading: "Memproses create address...",
          success: "Berhasil Menambahkan data address",
          error: (err) =>
            err?.response?.data?.message ?? "Gagal Menambahkan data address",
        });

        const res = await promise;
        updatedAddress = res.data;

        setAddresses((prev) => [...prev, updatedAddress]);
      }

      return updatedAddress; // ✅ INI KUNCINYA
    } catch (error: any) {
      notify.error(
        error?.response?.data?.message ?? "Gagal mengubah / membuat data",
      );
      throw error; // ✅ penting supaya caller tahu gagal
    } finally {
      setLoading(false);
      setEditing(null);
      // ❌ JANGAN setFormOpen(false) di sini
    }
  };

  const remove = async () => {
    if (!deleting) return;
    try {
      setLoading(true);
      await deleteUserAddress(deleting.id);
      setAddresses((prev) => prev.filter((a) => a.id !== deleting.id));
      notify.success("Berhasil Menghapus data");
    } catch (error) {
      notify.error(error?.response?.data?.message ?? "Gagal menghapus data");
    } finally {
      setLoading(false);
      setDeleting(null);
    }
  };
  return {
    addresses,
    loading,
    formOpen,
    setFormOpen,
    editing,
    setEditing,
    deleting,
    setDeleting,
    save,
    remove,
  };
}
