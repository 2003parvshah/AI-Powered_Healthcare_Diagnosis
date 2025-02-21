import { useState, useEffect } from "react";
import { AuthContext } from "./AuthContext";
interface User {
  id: number;
  name: string;
  email: string;
  role: string;
  profile_photo_path: string | null;
}
const validateUser = (user: User) => {
  return (
    typeof user === "object" &&
    user !== null &&
    typeof user.id === "number" &&
    typeof user.name === "string" &&
    typeof user.email === "string" &&
    typeof user.role === "string"
  );
};

const getUserFromLocalStorage = () => {
  const user = localStorage.getItem("user");
  if (user) {
    try {
      const parsedUser = JSON.parse(user);
      if (validateUser(parsedUser)) {
        return parsedUser;
      } else {
        throw new Error("Invalid user data");
      }
    } catch (error) {
      console.error("Invalid user data found in localStorage:", error);
      localStorage.removeItem("token");
      localStorage.removeItem("user");
    }
  }
  return null;
};

export const AuthProvider = ({ children }: { children: React.ReactNode }) => {
  const [isAuthenticated, setIsAuthenticated] = useState<boolean>(false);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    const token = localStorage.getItem("token");
    const user = getUserFromLocalStorage();
    if (token && user) {
      setIsAuthenticated(true);
    } else {
      setIsAuthenticated(false);
    }
    setLoading(false);
  }, []);

  const login = (token: string, user: object) => {
    localStorage.setItem("token", token);
    localStorage.setItem("user", JSON.stringify(user));
    setIsAuthenticated(true);
  };

  const logout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("user");
    setIsAuthenticated(false);
  };

  return (
    <AuthContext.Provider value={{ isAuthenticated, loading, login, logout }}>
      {children}
    </AuthContext.Provider>
  );
};
