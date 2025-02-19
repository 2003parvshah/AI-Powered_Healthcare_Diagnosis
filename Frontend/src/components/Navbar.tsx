import { ScanHeart } from "lucide-react";

export const Navbar = () => {
  return (
    <nav className="fixed z-50 w-full bg-white/70 px-4 py-3 shadow-sm backdrop-blur-sm lg:px-6">
      <div className="container mx-auto">
        <div className="flex items-center justify-between">
          <div className="flex items-center justify-center gap-2 text-2xl font-bold text-neutral-900">
            <ScanHeart />
            HealthAI
          </div>
        </div>
      </div>
    </nav>
  );
};
