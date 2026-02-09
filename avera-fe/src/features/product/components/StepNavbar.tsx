"use client";

import { Button } from "@/components/ui/button";
import {
  Tooltip,
  TooltipContent,
  TooltipTrigger,
} from "@/components/ui/tooltip";

interface StepNavbarProps {
  step: number;

  onBack: () => void;
  onNext?: () => void;

  onSaveDraft?: () => void;
  onArchive?: () => void;
  onPublish?: () => void;

  publishDisabled?: boolean;
  nextTooltip?: string;
}

export function StepNavbar({
  step,
  onBack,
  onNext,
  onSaveDraft,
  onArchive,
  onPublish,
  publishDisabled = false,
  nextTooltip,
}: StepNavbarProps) {
  const isCreateStep = step === 1;

  return (
    <div className="fixed bottom-0 left-0 right-0 bg-white border-t p-4 flex justify-end gap-2 shadow-md z-50">
      {/* Back */}
      <Button variant="outline" onClick={onBack}>
        Kembali
      </Button>

      {/* CREATE MODE */}
      {isCreateStep && onNext && (
        nextTooltip ? (
          <Tooltip>
            <TooltipTrigger asChild>
              <span>
                <Button
                  className="bg-primary text-white"
                  onClick={onNext}
                  disabled={publishDisabled}
                >
                  Lanjutkan
                </Button>
              </span>
            </TooltipTrigger>

            <TooltipContent className="bg-gray-800 text-white text-xs p-2 rounded shadow-lg">
              {nextTooltip}
            </TooltipContent>
          </Tooltip>
        ) : (
          <Button
            className="bg-primary text-white"
            onClick={onNext}
            disabled={publishDisabled}
          >
            Lanjutkan
          </Button>
        )
      )}

      {/* EDIT MODE */}
      {!isCreateStep && (
        <>
          {onSaveDraft && (
            <Button variant="outline" onClick={onSaveDraft}>
              Simpan Draft
            </Button>
          )}

          {onArchive && (
            <Button variant="outline" onClick={onArchive}>
              Arsipkan
            </Button>
          )}

          {onPublish && (
            <Button
              className="bg-primary text-white"
              onClick={onPublish}
              disabled={publishDisabled}
              title={
                publishDisabled
                  ? "Produk belum lengkap, lengkapi semua rekomendasi"
                  : ""
              }
            >
              Simpan & Publish
            </Button>
          )}
        </>
      )}
    </div>
  );
}
