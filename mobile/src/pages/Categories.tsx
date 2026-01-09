import {
    IonContent,
    IonHeader,
    IonPage,
    IonTitle,
    IonToolbar,
    IonList,
    IonItem,
    IonLabel,
    IonFab,
    IonFabButton,
    IonIcon,
    IonModal,
    IonButton,
    IonButtons,
    IonInput,
    IonTextarea,
    IonToast,
    IonAlert,
    IonRefresher,
    IonRefresherContent,
    IonSkeletonText,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardContent,
    RefresherEventDetail,
} from "@ionic/react";
import { add, create, close, trash, arrowBack } from "ionicons/icons";
import { useEffect, useState } from "react";
import { useHistory } from "react-router-dom";
import api from "../services/api";
import "./Categories.css";

const Categories: React.FC = () => {
    const history = useHistory();
    const [categories, setCategories] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [toastMessage, setToastMessage] = useState("");
    const [toastColor, setToastColor] = useState<
        "success" | "danger" | "warning"
    >("success");
    const [editingId, setEditingId] = useState<number | null>(null);
    const [showAlert, setShowAlert] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);

    const initialCategoryState = {
        nombre: "",
        descripcion: "",
    };
    const [formData, setFormData] = useState(initialCategoryState);

    const fetchData = async () => {
        try {
            const response = await api.get("/categories");
            setCategories(response.data);
        } catch (error: any) {
            console.error("Error fetching categories:", error);
            setToastMessage(`Error: ${error.message}`);
            setToastColor("danger");
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchData();
    }, []);

    const handleRefresh = async (event: CustomEvent<RefresherEventDetail>) => {
        await fetchData();
        event.detail.complete();
    };

    const handleOpenModal = (category?: any) => {
        if (category) {
            setEditingId(category.id);
            setFormData({
                nombre: category.nombre,
                descripcion: category.descripcion || "",
            });
        } else {
            setEditingId(null);
            setFormData(initialCategoryState);
        }
        setShowModal(true);
    };

    const handleSave = async () => {
        if (!formData.nombre) {
            setToastMessage("Por favor ingresa el nombre de la categoría");
            setToastColor("warning");
            return;
        }

        try {
            if (editingId) {
                await api.put(`/categories/${editingId}`, formData);
                setToastMessage("✓ Categoría actualizada");
                setToastColor("success");
            } else {
                await api.post("/categories", formData);
                setToastMessage("✓ Categoría creada");
                setToastColor("success");
            }
            setShowModal(false);
            fetchData();
        } catch (error: any) {
            console.error("Error saving category:", error);
            setToastMessage(`Error al guardar: ${error.message}`);
            setToastColor("danger");
        }
    };

    const confirmDelete = (id: number) => {
        setDeleteId(id);
        setShowAlert(true);
    };

    const handleDelete = async () => {
        if (!deleteId) return;
        try {
            await api.delete(`/categories/${deleteId}`);
            setToastMessage("✓ Categoría eliminada");
            setToastColor("success");
            fetchData();
        } catch (error: any) {
            console.error("Error deleting category:", error);
            setToastMessage(`Error al eliminar: ${error.message}`);
            setToastColor("danger");
        } finally {
            setShowAlert(false);
            setDeleteId(null);
        }
    };

    const renderSkeleton = () => (
        <>
            {[1, 2, 3, 4, 5].map((i) => (
                <IonCard key={i}>
                    <IonCardHeader>
                        <IonSkeletonText animated style={{ width: "60%" }} />
                    </IonCardHeader>
                    <IonCardContent>
                        <IonSkeletonText animated style={{ width: "80%" }} />
                    </IonCardContent>
                </IonCard>
            ))}
        </>
    );

    return (
        <IonPage>
            <IonHeader>
                <IonToolbar color="primary">
                    <IonButtons slot="start">
                        <IonButton onClick={() => history.push("/home")}>
                            <IonIcon icon={arrowBack} />
                        </IonButton>
                    </IonButtons>
                    <IonTitle>Categorías</IonTitle>
                </IonToolbar>
            </IonHeader>
            <IonContent fullscreen>
                <IonRefresher slot="fixed" onIonRefresh={handleRefresh}>
                    <IonRefresherContent />
                </IonRefresher>

                {loading ? (
                    renderSkeleton()
                ) : (
                    <div className="categories-grid">
                        {categories
                            .sort((a, b) => b.id - a.id)
                            .map((c) => (
                                <IonCard key={c.id} className="category-card">
                                    <IonCardHeader>
                                        <IonCardTitle>{c.nombre}</IonCardTitle>
                                    </IonCardHeader>
                                    <IonCardContent>
                                        <p className="category-description">
                                            {c.descripcion || "Sin descripción"}
                                        </p>
                                        <div className="category-actions">
                                            <IonButton
                                                size="small"
                                                fill="outline"
                                                onClick={() =>
                                                    handleOpenModal(c)
                                                }
                                            >
                                                <IonIcon
                                                    slot="start"
                                                    icon={create}
                                                />
                                                Editar
                                            </IonButton>
                                            <IonButton
                                                size="small"
                                                fill="outline"
                                                color="danger"
                                                onClick={() =>
                                                    confirmDelete(c.id)
                                                }
                                            >
                                                <IonIcon
                                                    slot="start"
                                                    icon={trash}
                                                />
                                                Eliminar
                                            </IonButton>
                                        </div>
                                    </IonCardContent>
                                </IonCard>
                            ))}
                    </div>
                )}

                <IonFab vertical="bottom" horizontal="end" slot="fixed">
                    <IonFabButton onClick={() => handleOpenModal()}>
                        <IonIcon icon={add} />
                    </IonFabButton>
                </IonFab>

                <IonModal
                    isOpen={showModal}
                    onDidDismiss={() => setShowModal(false)}
                >
                    <IonHeader>
                        <IonToolbar color="primary">
                            <IonTitle>
                                {editingId
                                    ? "Editar Categoría"
                                    : "Nueva Categoría"}
                            </IonTitle>
                            <IonButtons slot="end">
                                <IonButton onClick={() => setShowModal(false)}>
                                    <IonIcon icon={close} />
                                </IonButton>
                            </IonButtons>
                        </IonToolbar>
                    </IonHeader>
                    <IonContent className="ion-padding">
                        <IonItem>
                            <IonLabel position="floating">Nombre *</IonLabel>
                            <IonInput
                                value={formData.nombre}
                                onIonChange={(e) =>
                                    setFormData({
                                        ...formData,
                                        nombre: e.detail.value!,
                                    })
                                }
                            />
                        </IonItem>
                        <IonItem>
                            <IonLabel position="floating">Descripción</IonLabel>
                            <IonTextarea
                                value={formData.descripcion}
                                onIonChange={(e) =>
                                    setFormData({
                                        ...formData,
                                        descripcion: e.detail.value!,
                                    })
                                }
                            />
                        </IonItem>

                        <div className="ion-padding-top">
                            <IonButton expand="block" onClick={handleSave}>
                                {editingId ? "Actualizar" : "Guardar"}
                            </IonButton>
                        </div>
                    </IonContent>
                </IonModal>

                <IonAlert
                    isOpen={showAlert}
                    onDidDismiss={() => setShowAlert(false)}
                    header={"Confirmar eliminación"}
                    message={
                        "¿Estás seguro de que deseas eliminar esta categoría?"
                    }
                    buttons={[
                        {
                            text: "Cancelar",
                            role: "cancel",
                            handler: () => {
                                setDeleteId(null);
                            },
                        },
                        {
                            text: "Eliminar",
                            role: "confirm",
                            handler: handleDelete,
                        },
                    ]}
                />

                <IonToast
                    isOpen={!!toastMessage}
                    message={toastMessage}
                    duration={2000}
                    color={toastColor}
                    position="top"
                    onDidDismiss={() => setToastMessage("")}
                />
            </IonContent>
        </IonPage>
    );
};

export default Categories;
