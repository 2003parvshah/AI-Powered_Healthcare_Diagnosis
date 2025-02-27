// src/config/sidebarItems.ts
import {
  LucideIcon,
  Home,
  Activity,
  BriefcaseMedical,
  Clock,
} from "lucide-react";

// Define the type for menu items
export interface SidebarItem {
  title: string;
  url: string;
  icon: LucideIcon;
}

// Sidebar menu items array
export const sidebarItems: SidebarItem[] = [
  { title: "Home", url: "/patient", icon: Home },
  { title: "Diagnose", url: "/patient/diagnose", icon: Activity },
  {
    title: "Specialists",
    url: "/patient/specialist",
    icon: BriefcaseMedical,
  },
];
export const doctorSidebarItems: SidebarItem[] = [
  { title: "Home", url: "/doctor", icon: Home },
  { title: "Appointments", url: "/doctor/appointments", icon: Activity },
  { title: "Set Availability", url: "/doctor/set-availability", icon: Clock },
];
