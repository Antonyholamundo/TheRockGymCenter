import {
    IonContent,
    IonHeader,
    IonPage,
    IonTitle,
    IonToolbar,
    IonInput,
    IonButton,
    IonItem,
    IonLabel,
    IonToast,
    IonLoading,
    IonCard,
    IonCardContent,
    IonCardHeader,
    IonCardTitle,
    IonText,
} from "@ionic/react";
import { useState } from "react";
import { useHistory } from "react-router-dom";
import authService from "../services/auth.service";
import "./Login.css";

const Login: React.FC = () => {
    const history = useHistory();
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [loading, setLoading] = useState(false);
    const [toastMessage, setToastMessage] = useState("");
    const [toastColor, setToastColor] = useState<"success" | "danger">(
        "success"
    );

    const handleLogin = async () => {
        if (!email || !password) {
            setToastMessage("Por favor completa todos los campos");
            setToastColor("danger");
            return;
        }

        setLoading(true);
        try {
            console.log("Starting login process...");
            await authService.login({ email, password });
            console.log("Login successful!");

            setToastMessage("¡Bienvenido!");
            setToastColor("success");

            // Redirigir al home después de un breve delay
            setTimeout(() => {
                history.push("/home");
            }, 500);
        } catch (error: any) {
            console.error("Login error:", error);

            let errorMessage = "Error al iniciar sesión";

            if (error.response) {
                // Error de respuesta del servidor
                errorMessage =
                    error.response.data?.message ||
                    `Error del servidor (${error.response.status})`;
            } else if (error.request) {
                // No se recibió respuesta
                errorMessage =
                    "No se pudo conectar al servidor. Verifica tu conexión.";
            } else {
                // Error en la configuración de la petición
                errorMessage = error.message || "Error desconocido";
            }

            setToastMessage(errorMessage);
            setToastColor("danger");
        } finally {
            setLoading(false);
        }
    };

    return (
        <IonPage>
            <IonHeader>
                <IonToolbar color="primary">
                    <IonTitle>The Rock Gym</IonTitle>
                </IonToolbar>
            </IonHeader>
            <IonContent className="ion-padding login-content">
                <div className="login-container">
                    <IonCard>
                        <IonCardHeader>
                            <IonCardTitle className="ion-text-center">
                                Iniciar Sesión
                            </IonCardTitle>
                        </IonCardHeader>
                        <IonCardContent>
                            <IonItem>
                                <IonLabel position="floating">Email</IonLabel>
                                <IonInput
                                    type="email"
                                    value={email}
                                    onIonChange={(e) =>
                                        setEmail(e.detail.value!)
                                    }
                                    autocomplete="email"
                                />
                            </IonItem>

                            <IonItem>
                                <IonLabel position="floating">
                                    Contraseña
                                </IonLabel>
                                <IonInput
                                    type="password"
                                    value={password}
                                    onIonChange={(e) =>
                                        setPassword(e.detail.value!)
                                    }
                                    autocomplete="current-password"
                                    onKeyPress={(e) => {
                                        if (e.key === "Enter") handleLogin();
                                    }}
                                />
                            </IonItem>

                            <div className="ion-padding-top">
                                <IonButton
                                    expand="block"
                                    onClick={handleLogin}
                                    disabled={loading}
                                >
                                    Ingresar
                                </IonButton>
                            </div>

                            <div className="ion-padding-top ion-text-center">
                                <IonText color="medium">
                                    <small>
                                        Credenciales de prueba:
                                        <br />
                                        admin@therockgym.com / admin123
                                    </small>
                                </IonText>
                            </div>
                        </IonCardContent>
                    </IonCard>
                </div>

                <IonLoading isOpen={loading} message={"Iniciando sesión..."} />

                <IonToast
                    isOpen={!!toastMessage}
                    message={toastMessage}
                    duration={3000}
                    color={toastColor}
                    position="top"
                    onDidDismiss={() => setToastMessage("")}
                />
            </IonContent>
        </IonPage>
    );
};

export default Login;
