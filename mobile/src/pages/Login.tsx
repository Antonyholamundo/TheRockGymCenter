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
            await authService.login({ email, password });
            setToastMessage("¡Bienvenido!");
            setToastColor("success");

            // Redirigir al home después de un breve delay
            setTimeout(() => {
                history.push("/home");
            }, 500);
        } catch (error: any) {
            console.error("Login error:", error);
            setToastMessage(
                error.response?.data?.message || "Credenciales incorrectas"
            );
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
                        </IonCardContent>
                    </IonCard>
                </div>

                <IonLoading isOpen={loading} message={"Iniciando sesión..."} />

                <IonToast
                    isOpen={!!toastMessage}
                    message={toastMessage}
                    duration={2000}
                    color={toastColor}
                    onDidDismiss={() => setToastMessage("")}
                />
            </IonContent>
        </IonPage>
    );
};

export default Login;
