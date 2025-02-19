import { Button } from "@/components/ui/button";
import { NavLink } from "react-router";

export const Hero = () => {
  return (
    <section id="hero" className="bg-white py-36">
      <div className="container mx-auto px-4">
        <div className="grid items-start gap-12 lg:grid-cols-2">
          <div className="mt-16">
            <h1 className="text-primary-background mb-6 text-4xl font-bold lg:text-5xl">
              Transform Your Healthcare Experience with AI
            </h1>
            <p className="text-secondary-background mb-8 text-xl">
              Get instant preliminary diagnoses, book appointments, and manage
              your health journey - all in one place. Powered by advanced AI
              technology for accurate and reliable healthcare guidance.
            </p>
            <div className="mb-8 flex flex-wrap gap-4">
              <NavLink to="/register">
                <Button variant="default" size="cta">
                  Sign Up
                </Button>
              </NavLink>
              <NavLink to="/login">
                <Button variant="outline" size="cta">
                  Login
                </Button>
              </NavLink>
            </div>
            {/* <div className="flex items-center gap-8">
              <div className="text-center">
                <div className="text-primary mb-2 text-3xl font-bold">98%</div>
                <div className="text-gray-600">Accuracy Rate</div>
              </div>
              <div className="text-center">
                <div className="text-primary mb-2 text-3xl font-bold">24/7</div>
                <div className="text-gray-600">Availability</div>
              </div>
              <div className="text-center">
                <div className="text-primary mb-2 text-3xl font-bold">50k+</div>
                <div className="text-gray-600">Users</div>
              </div>
            </div> */}
          </div>
          <div className="relative">
            <div className="from-primary/10 w-full rounded-2xl border border-gray-200 bg-gradient-to-br to-[#2C5282]/10 p-8 backdrop-blur-sm">
              <div className="mb-6 rounded-xl bg-white p-6 shadow-lg">
                <div className="mb-4 flex items-center gap-4">
                  <div className="bg-primary flex h-12 w-12 items-center justify-center rounded-full">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      className="h-6 w-6 text-white"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
                      ></path>
                    </svg>
                  </div>
                  <div>
                    <h3 className="font-semibold text-neutral-900">
                      AI Diagnosis
                    </h3>
                    <p className="text-gray-600">Processing symptoms...</p>
                  </div>
                </div>
                <div className="space-y-3">
                  <div className="h-2 overflow-hidden rounded-full bg-gray-200">
                    <div className="bg-primary h-full w-3/4 animate-pulse"></div>
                  </div>
                  <div className="h-2 overflow-hidden rounded-full bg-gray-200">
                    <div className="bg-primary h-full w-1/2 animate-pulse"></div>
                  </div>
                </div>
              </div>
              <div className="space-y-4">
                <div className="flex items-center gap-4 rounded-lg bg-white p-4 shadow-lg">
                  <div className="bg-primary flex h-8 w-8 items-center justify-center rounded-full">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      className="h-4 w-4 text-white"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                      ></path>
                    </svg>
                  </div>
                  <div className="text-neutral-900">Quick Diagnosis</div>
                </div>
                <div className="flex items-center gap-4 rounded-lg bg-white p-4 shadow-lg">
                  <div className="bg-primary flex h-8 w-8 items-center justify-center rounded-full">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      className="h-4 w-4 text-white"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                      ></path>
                    </svg>
                  </div>
                  <div className="text-neutral-900">Accurate Results</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};
