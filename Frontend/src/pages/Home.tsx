import { TransformSection } from "@/components/Home/TransformSection";
import { Hero1 } from "@/components/Home/Hero1";
import { Navbar } from "@/components/Home/Navbar";
import { useNavigate } from "react-router";
import { useEffect } from "react";
import { useAuth } from "@/hooks/useAuth";

export const Home = () => {
  const { isAuthenticated, loading } = useAuth();
  const navigate = useNavigate();

  useEffect(() => {
    if (isAuthenticated && !loading) {
      navigate("/dashboard", { replace: true });
    }
  }, [isAuthenticated, loading, navigate]);
  return (
    <>
      <Navbar />
      <main className="bg-primary-background flex flex-col items-center justify-center">
        <Hero1 />
        <TransformSection />
      </main>
    </>
  );
};
