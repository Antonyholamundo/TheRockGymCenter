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
} from "@ionic/react";
import { add, pencil, trash, create, close } from "ionicons/icons";
import { useEffect, useState } from "react";
import api from "../services/api";

const Home: React.FC = () => {
    const [products, setProducts] = useState<any[]>([]);
    const [categories, setCategories] = useState<any[]>([]);
    const [loading, setLoading] = useState(true);
    const [showModal, setShowModal] = useState(false);
    const [toastMessage, setToastMessage] = useState("");
    const [editingId, setEditingId] = useState<number | null>(null);
    const [showAlert, setShowAlert] = useState(false);
    const [deleteId, setDeleteId] = useState<number | null>(null);

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
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchData();
    }, []);

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
            return;
        }

        try {
            if (editingId) {
                await api.put(`/products/${editingId}`, formData);
                setToastMessage("Producto actualizado exitosamente");
            } else {
                await api.post("/products", formData);
                setToastMessage("Producto creado exitosamente");
            }
            setShowModal(false);
            fetchData();
        } catch (error: any) {
            console.error("Error saving product:", error);
            setToastMessage(`Error al guardar: ${error.message}`);
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
            setToastMessage("Producto eliminado exitosamente");
            fetchData();
        } catch (error: any) {
            console.error("Error deleting product:", error);
            setToastMessage(`Error al eliminar: ${error.message}`);
        } finally {
            setShowAlert(false);
            setDeleteId(null);
        }
    };

    return (
        <IonPage>
            <IonHeader>
                <IonToolbar>
                    <IonTitle>The Rock Gym - Inventario</IonTitle>
                </IonToolbar>
            </IonHeader>
            <IonContent fullscreen>
                <IonHeader collapse="condense">
                    <IonToolbar>
                        <IonTitle size="large">Inventario</IonTitle>
                    </IonToolbar>
                </IonHeader>

                {loading ? (
                    <IonLoading isOpen={loading} message={"Cargando..."} />
                ) : (
                    <IonList>
                        {products
                            .sort((a, b) => b.id - a.id)
                            .map((p) => (
                                <IonItemSliding key={p.id}>
                                    <IonItem>
                                        <IonLabel>
                                            <h2>{p.nombre}</h2>
                                            <p>
                                                {p.categoria?.nombre ||
                                                    "Sin categoría"}
                                            </p>
                                        </IonLabel>
                                        <IonNote slot="end" color="primary">
                                            ${p.precio}
                                        </IonNote>
                                    </IonItem>
                                    <IonItemOptions side="end">
                                        <IonItemOption
                                            color="primary"
                                            onClick={() => handleOpenModal(p)}
                                        >
                                            <IonIcon
                                                slot="icon-only"
                                                icon={create}
                                            />
                                        </IonItemOption>
                                        <IonItemOption
                                            color="danger"
                                            onClick={() => confirmDelete(p.id)}
                                        >
                                            <IonIcon
                                                slot="icon-only"
                                                icon={trash}
                                            />
                                        </IonItemOption>
                                    </IonItemOptions>
                                </IonItemSliding>
                            ))}
                    </IonList>
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
                        <IonToolbar>
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
                            <IonLabel position="stacked">Nombre</IonLabel>
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
                            <IonLabel position="stacked">Precio</IonLabel>
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
                            <IonLabel position="stacked">Stock</IonLabel>
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
                            <IonLabel position="stacked">Categoría</IonLabel>
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
                            <IonLabel position="stacked">Descripción</IonLabel>
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

                        <div className="ion-padding">
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

                <IonToast
                    isOpen={!!toastMessage}
                    message={toastMessage}
                    duration={2000}
                    onDidDismiss={() => setToastMessage("")}
                />
            </IonContent>
        </IonPage>
    );
};

export default Home;
