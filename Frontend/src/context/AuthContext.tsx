import { createContext } from "react";

interface AuthContextType {
  isAuthenticated: boolean;
  loading: boolean;
  login: (token: string, user: object) => void;
  logout: () => void;
}

export const AuthContext = createContext<AuthContextType | undefined>(
  undefined,
);
