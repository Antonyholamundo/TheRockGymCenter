import {
    IonContent,
    IonHeader,
    IonPage,
    IonTitle,
    IonToolbar,
    IonList,
    IonItem,
    IonLabel,
    IonNote,
    IonFab,
    IonFabButton,
    IonIcon,
    IonModal,
    IonButton,
    IonButtons,
    IonInput,
    IonSelect,
    IonSelectOption,
    IonTextarea,
    IonLoading,
    IonToast,
    IonItemSliding,
    IonItemOptions,
    IonItemOption,
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
import { add, create, close, trash, logOut, pricetag } from "ionicons/icons";
import { useEffect, useState } from "react";
import { useHistory } from "react-router-dom";
import api from "../services/api";
import authService from "../services/auth.service";
import "./Home.css";

const Home: React.FC = () => {
    const history = useHistory();
    const [products, setProducts] = useState<any[]>([]);
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
    const [showLogoutAlert, setShowLogoutAlert] = useState(false);

    // Form State
    const initialProductState = {
        nombre: "",
        precio: "",
        stock: "",
        categoria_id: "",
        descripcion: "",
        estado: "Activo",
    };
    const [formData, setFormData] = useState(initialProductState);

    const fetchData = async () => {
        try {
            const [prodRes, catRes] = await Promise.all([
                api.get("/products"),
                api.get("/categories"),
            ]);
            setProducts(prodRes.data);
            setCategories(catRes.data);
        } catch (error: any) {
            console.error("Error fetching data:", error);
            setToastMessage(`Error: ${error.message}`);
            setToastColor("danger");
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        // Verificar autenticación
        if (!authService.isAuthenticated()) {
            history.push("/login");
            return;
        }
        fetchData();
    }, [history]);

    const handleRefresh = async (event: CustomEvent<RefresherEventDetail>) => {
        await fetchData();
        event.detail.complete();
    };

    const handleOpenModal = (product?: any) => {
        if (product) {
            setEditingId(product.id);
            setFormData({
                nombre: product.nombre,
                precio: product.precio,
                stock: product.stock,
                categoria_id: product.categoria_id,
                descripcion: product.descripcion,
                estado: product.estado,
            });
        } else {
            setEditingId(null);
            setFormData(initialProductState);
        }
        setShowModal(true);
    };

    const handleSave = async () => {
        if (!formData.nombre || !formData.precio || !formData.categoria_id) {
            setToastMessage("Por favor completa los campos obligatorios");
            setToastColor("warning");
            return;
        }

        try {
            if (editingId) {
                await api.put(`/products/${editingId}`, formData);
                setToastMessage("✓ Producto actualizado");
                setToastColor("success");
            } else {
                await api.post("/products", formData);
                setToastMessage("✓ Producto creado");
                setToastColor("success");
            }
            setShowModal(false);
            fetchData();
        } catch (error: any) {
            console.error("Error saving product:", error);
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
            await api.delete(`/products/${deleteId}`);
            setToastMessage("✓ Producto eliminado");
            setToastColor("success");
            fetchData();
        } catch (error: any) {
            console.error("Error deleting product:", error);
            setToastMessage(`Error al eliminar: ${error.message}`);
            setToastColor("danger");
        } finally {
            setShowAlert(false);
            setDeleteId(null);
        }
    };

    const handleLogout = () => {
        authService.logout();
        history.push("/login");
    };

    const renderSkeleton = () => (
        <>
            {[1, 2, 3, 4, 5].map((i) => (
                <IonCard key={i}>
                    <IonCardHeader>
                        <IonSkeletonText animated style={{ width: "60%" }} />
                    </IonCardHeader>
                    <IonCardContent>
                        <IonSkeletonText animated style={{ width: "40%" }} />
                    </IonCardContent>
                </IonCard>
            ))}
        </>
    );

    return (
        <IonPage>
            <IonHeader>
                <IonToolbar color="primary">
                    <IonTitle>The Rock Gym - Inventario</IonTitle>
                    <IonButtons slot="end">
                        <IonButton onClick={() => setShowLogoutAlert(true)}>
                            <IonIcon icon={logOut} />
                        </IonButton>
                    </IonButtons>
                </IonToolbar>
            </IonHeader>
            <IonContent fullscreen>
                <IonRefresher slot="fixed" onIonRefresh={handleRefresh}>
                    <IonRefresherContent />
                </IonRefresher>

                <IonHeader collapse="condense">
                    <IonToolbar>
                        <IonTitle size="large">Inventario</IonTitle>
                    </IonToolbar>
                </IonHeader>

                {loading ? (
                    renderSkeleton()
                ) : (
                    <div className="products-grid">
                        {products
                            .sort((a, b) => b.id - a.id)
                            .map((p) => (
                                <IonCard key={p.id} className="product-card">
                                    <IonCardHeader>
                                        <IonCardTitle>{p.nombre}</IonCardTitle>
                                        <p className="category-badge">
                                            <IonIcon icon={pricetag} />{" "}
                                            {p.categoria?.nombre ||
                                                "Sin categoría"}
                                        </p>
                                    </IonCardHeader>
                                    <IonCardContent>
                                        <div className="product-info">
                                            <div className="price">
                                                ${p.precio}
                                            </div>
                                            <div className="stock">
                                                Stock: {p.stock}
                                            </div>
                                        </div>
                                        <div className="product-actions">
                                            <IonButton
                                                size="small"
                                                fill="outline"
                                                onClick={() =>
                                                    handleOpenModal(p)
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
                                                    confirmDelete(p.id)
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
                                    ? "Editar Producto"
                                    : "Nuevo Producto"}
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
                            <IonLabel position="floating">Precio *</IonLabel>
                            <IonInput
                                type="number"
                                value={formData.precio}
                                onIonChange={(e) =>
                                    setFormData({
                                        ...formData,
                                        precio: e.detail.value!,
                                    })
                                }
                            />
                        </IonItem>
                        <IonItem>
                            <IonLabel position="floating">Stock</IonLabel>
                            <IonInput
                                type="number"
                                value={formData.stock}
                                onIonChange={(e) =>
                                    setFormData({
                                        ...formData,
                                        stock: e.detail.value!,
                                    })
                                }
                            />
                        </IonItem>
                        <IonItem>
                            <IonLabel position="floating">Categoría *</IonLabel>
                            <IonSelect
                                value={formData.categoria_id}
                                onIonChange={(e) =>
                                    setFormData({
                                        ...formData,
                                        categoria_id: e.detail.value!,
                                    })
                                }
                            >
                                {categories.map((c) => (
                                    <IonSelectOption key={c.id} value={c.id}>
                                        {c.nombre}
                                    </IonSelectOption>
                                ))}
                            </IonSelect>
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
                        "¿Estás seguro de que deseas eliminar este producto?"
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

                <IonAlert
                    isOpen={showLogoutAlert}
                    onDidDismiss={() => setShowLogoutAlert(false)}
                    header={"Cerrar sesión"}
                    message={"¿Estás seguro de que deseas cerrar sesión?"}
                    buttons={[
                        {
                            text: "Cancelar",
                            role: "cancel",
                        },
                        {
                            text: "Salir",
                            role: "confirm",
                            handler: handleLogout,
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

export default Home;
