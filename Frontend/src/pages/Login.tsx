import { LoginForm } from "@/components/LoginForm";
import { ScanHeart } from "lucide-react";
import { NavLink } from "react-router";

export const Login = () => {
  return (
    <section className="bg-muted flex h-screen flex-col items-center justify-center gap-8 px-12">
      <NavLink
        to="/"
        className="flex items-center gap-2 self-center font-medium"
      >
        <div className="bg-primary text-primary-foreground flex h-6 w-6 items-center justify-center rounded-md">
          <ScanHeart className="size-4" />
        </div>
        Health.ai
      </NavLink>
      <LoginForm />
    </section>
  );
};
