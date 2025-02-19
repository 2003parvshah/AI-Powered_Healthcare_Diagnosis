import { Hero } from "@/components/Hero";
import { Hero1 } from "@/components/Hero1";
import { Navbar } from "@/components/Navbar";

export const Home = () => {
  return (
    <>
      <Navbar />
      <Hero1 />
      <section className="bg-primary-background mx-16 flex min-h-[60vh] flex-col items-center">
        <Hero />
      </section>
    </>
  );
};
