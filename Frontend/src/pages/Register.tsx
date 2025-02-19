import { RegisterForm } from "@/components/Register";
import { ScanHeart } from "lucide-react";
import { NavLink } from "react-router";

export const Register = () => {
  return (
    <div className="bg-muted flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
      {/* <div className="flex w-full max-w-sm flex-col gap-6"> */}
      <NavLink
        to="/"
        className="flex items-center gap-2 self-center font-medium"
      >
        <div className="bg-primary text-primary-foreground flex h-6 w-6 items-center justify-center rounded-md">
          <ScanHeart className="size-4" />
        </div>
        Health.ai
      </NavLink>
      <RegisterForm />
      {/* </div> */}
    </div>
  );
};
