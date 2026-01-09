import axios from "axios";

// API en producción (Render)
const api = axios.create({
    baseURL: "https://therockgymcenter-web.onrender.com/api/v1",
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
    },
});

export default api;
