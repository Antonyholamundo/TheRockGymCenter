import type { CapacitorConfig } from "@capacitor/cli";

const config: CapacitorConfig = {
    appId: "com.therockgym.app",
    appName: "mobile",
    webDir: "dist",
    plugins: {
        CapacitorHttp: {
            enabled: true,
        },
    },
};

export default config;
