"use client";

export default function StepLayout({
  left,
  right,
}: {
  left: React.ReactNode;
  right: React.ReactNode;
}) {
  return (
    <div className="flex flex-col lg:flex-row gap-6">
      {/* LEFT RECOMMENDATION */}
      <div className="lg:w-1/4 w-full">
        <div className="bg-white border rounded p-4 shadow-sm space-y-2">
          {left}
        </div>
      </div>

      {/* RIGHT FORM */}
      <div className="lg:w-3/4 w-full">
        <div className="bg-white border rounded p-6 shadow-sm space-y-6">{right}</div>
      </div>
    </div>
  );
}
