import http from "k6/http";
import { check, sleep } from "k6";

export const options = {
    stages: [
        { duration: "30s", target: 20 }, // Ramp up to 20 users
        { duration: "1m", target: 20 }, // Stay at 20 users
        { duration: "30s", target: 0 }, // Ramp down to 0
    ],
    thresholds: {
        http_req_duration: ["p(95)<500"], // 95% of requests must complete below 500ms
    },
};

const BASE_URL = "http://localhost:8000/api/v1";

export default function () {
    // 1. Login to get token
    const loginRes = http.post(`${BASE_URL}/login`, {
        email: "admin@example.com", // Replace with valid test user
        password: "password",
    });

    check(loginRes, {
        "logged in successfully": (r) => r.status === 200,
        "has token": (r) => r.json("token") !== undefined,
    });

    const token = loginRes.json("token");
    const params = {
        headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "application/json",
        },
    };

    // 2. Get Assets List
    const assetsRes = http.get(`${BASE_URL}/assets`, params);
    check(assetsRes, {
        "assets retrieved": (r) => r.status === 200,
    });

    sleep(1);
}
