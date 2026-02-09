import StoreProfileForm from '@/features/store/components/StoreProfileForm'
import React from 'react'

export default function ProfilePage() {
  return (
 <div className="flex flex-col-reverse lg:flex-row gap-6 justify-center max-w-screen-xl mx-auto">
      {/* ------------------ FORM ------------------ */}
      <div className="flex-1 min-w-[320px] lg:max-w-[900px] bg-white rounded-xl p-6 shadow space-y-6">
        <StoreProfileForm />
      </div>

      {/* ------------------ PROFILE IMAGE ------------------ */}
      <div className="flex-shrink-0 w-full lg:w-56 xl:w-60 flex flex-col items-center bg-white rounded-xl p-6 shadow mt-6 lg:mt-0">
        <div className="w-36 h-36 xl:w-40 xl:h-40 bg-gray-200 rounded-full mb-4" />
        <button className="px-4 py-2 border rounded bg-gray-100 hover:bg-gray-200 mb-2">
          Select Image
        </button>
        <p className="text-xs text-gray-500 text-center">
          File size: maximum 1 MB
          <br />
          File extension: .JPEG, .PNG
        </p>
      </div>
    </div>
  )
}
