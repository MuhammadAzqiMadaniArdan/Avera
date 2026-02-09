
export default function ReviewReportModal({
  open,
  onClose,
}: {
  open: boolean;
  onClose: () => void;
}) {
  if (!open) return null;
  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div className="bg-white p-6 rounded-lg w-96">
        <h3 className="text-lg font-semibold mb-4">Report Review</h3>
        <p className="text-sm text-gray-600 mb-4">
          Ini dummy modal untuk melaporkan review. Tambahkan form sesuai
          kebutuhan.
        </p>
        <button
          onClick={onClose}
          className="px-4 py-2 bg-black text-white rounded hover:bg-gray-800"
        >
          Close
        </button>
      </div>
    </div>
  );
}