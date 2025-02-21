import { useLocation } from "react-router";
import {
  SidebarInset,
  SidebarProvider,
  SidebarTrigger,
} from "@/components/ui/sidebar";
import { AppSidebar } from "@/components/Sidebar/AppSidebar";
import { Separator } from "@/components/ui/separator";
import { Outlet } from "react-router";
import { sidebarItems } from "@/config/sidebarItems";

export const Dashboard = () => {
  const location = useLocation();

  // Find the matching title based on the current route
  const currentTitle =
    sidebarItems.find((item) => item.url === location.pathname)?.title ||
    "Dashboard";

  return (
    <SidebarProvider>
      <AppSidebar />
      <SidebarInset>
        <header className="bg-sidebar fixed flex h-16 w-full shrink-0 items-center gap-2 transition-[width,height] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12">
          <div className="flex items-center gap-2 px-4">
            <SidebarTrigger className="-ml-1" />
            <Separator orientation="vertical" className="mr-2 h-4" />
            <div>{currentTitle}</div>
          </div>
        </header>
        <main className="mt-16 p-4">
          <Outlet />
        </main>
      </SidebarInset>
    </SidebarProvider>
  );
};
