"use client";

import { UserProfile } from "../types";

interface Props {
  profile: UserProfile;
  setProfile: (data: UserProfile) => void;
  onSubmit: () => void;
}

export default function ProfileForm({
  profile,
  setProfile,
  onSubmit,
}: Props) {
  return (
    <div className="space-y-6">
      {/* HEADER */}
      <div>
        <h2 className="text-lg font-semibold">My Profile</h2>
        <p className="text-sm text-gray-500">
          Manage your personal information
        </p>
      </div>

      {/* FORM */}
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input
          value={profile.username}
          onChange={(e) =>
            setProfile({ ...profile, username: e.target.value })
          }
          className="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-black outline-none"
          placeholder="Username"
        />

        <input
          value={profile.name}
          onChange={(e) =>
            setProfile({ ...profile, name: e.target.value })
          }
          className="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-black outline-none"
          placeholder="Full Name"
        />

        <input
          value={profile.email}
          onChange={(e) =>
            setProfile({ ...profile, email: e.target.value })
          }
          className="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-black outline-none"
          placeholder="Email"
        />

        <input
          value={profile.phone_number ?? ""}
          onChange={(e) =>
            setProfile({
              ...profile,
              phone_number: e.target.value,
            })
          }
          className="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-black outline-none"
          placeholder="Phone Number"
        />

        <select
          value={profile.gender}
          onChange={(e) =>
            setProfile({
              ...profile,
              gender: e.target.value as UserProfile["gender"],
            })
          }
          className="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-black outline-none"
        >
          <option value="male">Male</option>
          <option value="female">Female</option>
          <option value="other">Other</option>
        </select>
      </div>

      {/* ACTION */}
      <div className="flex justify-end">
        <button
          onClick={onSubmit}
          className="px-6 py-2 bg-black text-white rounded-lg"
        >
          Save Changes
        </button>
      </div>
    </div>
  );
}
