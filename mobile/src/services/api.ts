import axios from "axios";

// Recuerda cambiar 'localhost' por tu IP local (ej. 192.168.1.X) si pruebas en un celular real
const api = axios.create({
    baseURL: "http://192.168.100.10:8000/api/v1",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

export default api;
