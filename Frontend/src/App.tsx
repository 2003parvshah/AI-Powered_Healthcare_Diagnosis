import { BrowserRouter, Routes, Route } from "react-router";
import { AuthProvider } from "@/context/AuthProvider";
import { Home } from "./pages/Home";
import { Login } from "./pages/Login";
import { Register } from "./pages/Register";
import { Dashboard } from "./pages/DashboardLayout";
import ScrollToTop from "@/components/ScrollToTop";
import { Diagnose } from "@/components/PatientDashboard/Diagnose";
import { PatientHome } from "@/components/PatientDashboard/PatientHome";
import { Profile } from "./components/PatientDashboard/Profile";
import ProtectedRoute from "./components/ProtectedRoute";
import { Specialist } from "./components/PatientDashboard/Specialist";

function App() {
  return (
    <AuthProvider>
      <BrowserRouter>
        <ScrollToTop />
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/login" element={<Login />} />
          <Route path="/register" element={<Register />} />
          <Route path="dashboard" element={<ProtectedRoute />}>
            <Route path="" element={<Dashboard />}>
              <Route index element={<PatientHome />} />
              <Route path="diagnose" element={<Diagnose />} />
              <Route path="profile" element={<Profile />} />
              <Route path="specialist" element={<Specialist />} />
            </Route>
          </Route>
        </Routes>
      </BrowserRouter>
    </AuthProvider>
  );
}

export default App;
