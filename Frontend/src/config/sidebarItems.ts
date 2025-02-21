// src/config/sidebarItems.ts
import { LucideIcon, Home, Activity, BriefcaseMedical } from "lucide-react";

// Define the type for menu items
export interface SidebarItem {
  title: string;
  url: string;
  icon: LucideIcon;
}

// Sidebar menu items array
export const sidebarItems: SidebarItem[] = [
  { title: "Home", url: "/dashboard", icon: Home },
  { title: "Diagnose", url: "/dashboard/diagnose", icon: Activity },
  {
    title: "Specialists",
    url: "/dashboard/specialist",
    icon: BriefcaseMedical,
  },
];
