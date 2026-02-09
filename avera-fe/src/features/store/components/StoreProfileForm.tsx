import React from 'react'

export default function StoreProfileForm() {
  return (
<div className="space-y-6">
      <h2 className="font-semibold text-lg">My Profile</h2>
      <p className="text-gray-500 text-sm">Manage and protect your account</p>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label className="block text-xs font-medium">Username</label>
          <input
            className="w-full border rounded px-2 py-1"
            defaultValue="llala"
          />
          <p className="text-xs text-gray-400 mt-1">
            Username can only be changed once.
          </p>
        </div>

        <div>
          <label className="block text-xs font-medium">Name</label>
          <input
            className="w-full border rounded px-2 py-1"
            defaultValue="yohohoa"
          />
        </div>

        <div>
          <label className="block text-xs font-medium">Email</label>
          <input
            className="w-full border rounded px-2 py-1"
            defaultValue="lala@gmail.com"
          />
          <button className="text-sm text-blue-600 mt-1">Change</button>
        </div>

        <div>
          <label className="block text-xs font-medium">Phone Number</label>
          <input className="w-full border rounded px-2 py-1" placeholder="Add" />
        </div>

        <div>
          <label className="block text-xs font-medium">Shop Name</label>
          <input
            className="w-full border rounded px-2 py-1"
            defaultValue="lalala"
          />
        </div>

        <div>
          <label className="block text-xs font-medium">Gender</label>
          <select className="w-full border rounded px-2 py-1">
            <option>Male</option>
            <option>Female</option>
            <option>Other</option>
          </select>
        </div>

        <div>
          <label className="block text-xs font-medium">Date of Birth</label>
          <input
            className="w-full border rounded px-2 py-1"
            type="date"
            defaultValue="2025-01-01"
          />
          <button className="text-sm text-blue-600 mt-1">Change</button>
        </div>
      </div>

      <button className="px-4 py-2 bg-black text-white rounded hover:opacity-90">
        Save
      </button>
    </div>  )
}


