import api from "./api";

interface LoginCredentials {
    email: string;
    password: string;
}

interface AuthResponse {
    token: string;
    user: any;
}

class AuthService {
    private TOKEN_KEY = "auth_token";
    private USER_KEY = "auth_user";

    async login(credentials: LoginCredentials): Promise<AuthResponse> {
        try {
            console.log("Attempting login with:", credentials.email);
            const response = await api.post("/login", credentials);
            console.log("Login response:", response.data);

            const { token, user } = response.data;

            if (!token) {
                throw new Error("No se recibió token del servidor");
            }

            // Guardar token y usuario en localStorage
            localStorage.setItem(this.TOKEN_KEY, token);
            localStorage.setItem(this.USER_KEY, JSON.stringify(user));

            // Configurar header de autorización para futuras peticiones
            api.defaults.headers.common["Authorization"] = `Bearer ${token}`;

            return { token, user };
        } catch (error: any) {
            console.error("Login error details:", {
                message: error.message,
                response: error.response?.data,
                status: error.response?.status,
            });
            throw error;
        }
    }

    async logout(): Promise<void> {
        try {
            await api.post("/logout");
        } catch (error) {
            console.error("Error during logout:", error);
        } finally {
            // Limpiar datos locales
            localStorage.removeItem(this.TOKEN_KEY);
            localStorage.removeItem(this.USER_KEY);
            delete api.defaults.headers.common["Authorization"];
        }
    }

    getToken(): string | null {
        return localStorage.getItem(this.TOKEN_KEY);
    }

    getUser(): any | null {
        const userStr = localStorage.getItem(this.USER_KEY);
        return userStr ? JSON.parse(userStr) : null;
    }

    isAuthenticated(): boolean {
        return !!this.getToken();
    }

    // Inicializar el token en el header si existe
    initializeAuth(): void {
        const token = this.getToken();
        if (token) {
            api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        }
    }
}

export default new AuthService();
