# 🧪 Guía de Pruebas - ION Inventory API v2

## 📋 Requisitos Previos

1. Tener un usuario registrado en el sistema
2. Conocer el email y contraseña del usuario
3. Tener instalado cURL, Postman, o cualquier cliente HTTP

---

## 🚀 Método 1: Usando cURL (Terminal)

### 1️⃣ Login y Obtener Token

```bash
curl -X POST https://ion-app-120e60a9275c.herokuapp.com/api/v2/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "tu@email.com",
    "password": "tu-contraseña"
  }'
```

**Respuesta Esperada:**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "1|abc123xyz...",
        "user": {
            "id": 1,
            "name": "Tu Nombre",
            "email": "tu@email.com",
            "role": "admin",
            "company_id": 1
        }
    }
}
```

**Guarda el token** - Lo necesitarás para todas las demás peticiones.

---

### 2️⃣ Listar Activos

```bash
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

**Con paginación:**

```bash
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets?page=1&per_page=10" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

**Con búsqueda:**

```bash
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets?search=laptop" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

### 3️⃣ Ver un Activo Específico

```bash
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets/1" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

### 4️⃣ Crear un Activo

```bash
curl -X POST "https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop Dell Latitude 5420",
    "code": "ASSET-001",
    "category_id": 1,
    "location_id": 1,
    "serial_number": "SN123456789",
    "model": "Latitude 5420",
    "brand": "Dell",
    "purchase_date": "2024-01-15",
    "purchase_price": 1200.00,
    "status": "available",
    "description": "Laptop para desarrollo"
  }'
```

---

### 5️⃣ Actualizar un Activo

```bash
curl -X PUT "https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets/1" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "status": "in_use",
    "location_id": 2
  }'
```

---

### 6️⃣ Eliminar un Activo

```bash
curl -X DELETE "https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets/1" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

### 7️⃣ Listar Categorías

```bash
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/categories" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

### 8️⃣ Crear Categoría

```bash
curl -X POST "https://ion-app-120e60a9275c.herokuapp.com/api/v2/categories" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Electrónica",
    "description": "Dispositivos electrónicos"
  }'
```

---

### 9️⃣ Listar Ubicaciones

```bash
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/locations" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

### 🔟 Crear Mantenimiento

```bash
curl -X POST "https://ion-app-120e60a9275c.herokuapp.com/api/v2/maintenances" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Content-Type: application/json" \
  -d '{
    "asset_id": 1,
    "type": "preventive",
    "scheduled_date": "2024-12-20",
    "description": "Mantenimiento preventivo mensual",
    "cost": 50.00
  }'
```

---

### 1️⃣1️⃣ Listar Mantenimientos de un Activo

```bash
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/maintenances?asset_id=1" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

### 1️⃣2️⃣ Obtener Usuario Actual

```bash
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/auth/user" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

### 1️⃣3️⃣ Refrescar Token

```bash
curl -X POST "https://ion-app-120e60a9275c.herokuapp.com/api/v2/auth/refresh" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

### 1️⃣4️⃣ Logout

```bash
curl -X POST "https://ion-app-120e60a9275c.herokuapp.com/api/v2/auth/logout" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -H "Accept: application/json"
```

---

## 🎯 Método 2: Script Bash Automatizado

Crea un archivo `test-api.sh`:

```bash
#!/bin/bash

# Configuración
API_URL="https://ion-app-120e60a9275c.herokuapp.com/api/v2"
EMAIL="tu@email.com"
PASSWORD="tu-contraseña"

echo "🔐 1. Login..."
LOGIN_RESPONSE=$(curl -s -X POST "$API_URL/auth/login" \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"$EMAIL\",\"password\":\"$PASSWORD\"}")

TOKEN=$(echo $LOGIN_RESPONSE | jq -r '.data.token')

if [ "$TOKEN" == "null" ]; then
  echo "❌ Error en login"
  echo $LOGIN_RESPONSE | jq
  exit 1
fi

echo "✅ Token obtenido: ${TOKEN:0:20}..."

echo ""
echo "📦 2. Listando activos..."
curl -s -X GET "$API_URL/assets" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | jq

echo ""
echo "📁 3. Listando categorías..."
curl -s -X GET "$API_URL/categories" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | jq

echo ""
echo "📍 4. Listando ubicaciones..."
curl -s -X GET "$API_URL/locations" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | jq

echo ""
echo "👤 5. Obteniendo usuario actual..."
curl -s -X GET "$API_URL/auth/user" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | jq

echo ""
echo "🚪 6. Logout..."
curl -s -X POST "$API_URL/auth/logout" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | jq

echo ""
echo "✅ Pruebas completadas!"
```

**Ejecutar:**

```bash
chmod +x test-api.sh
./test-api.sh
```

---

## 🔧 Método 3: Usando Postman

### Configuración Inicial:

1. **Crear una nueva colección** llamada "ION Inventory API"

2. **Crear variable de entorno:**

    - `base_url`: `https://ion-app-120e60a9275c.herokuapp.com/api/v2`
    - `token`: (se llenará automáticamente)

3. **Request de Login:**

    - Método: `POST`
    - URL: `{{base_url}}/auth/login`
    - Body (JSON):
        ```json
        {
            "email": "tu@email.com",
            "password": "tu-contraseña"
        }
        ```
    - En la pestaña "Tests", agregar:
        ```javascript
        pm.test("Login successful", function () {
            pm.response.to.have.status(200);
            var jsonData = pm.response.json();
            pm.environment.set("token", jsonData.data.token);
        });
        ```

4. **Request de Listar Activos:**

    - Método: `GET`
    - URL: `{{base_url}}/assets`
    - Headers:
        - `Authorization`: `Bearer {{token}}`
        - `Accept`: `application/json`

5. **Repetir para otros endpoints**

---

## 🐍 Método 4: Script Python

Crea un archivo `test_api.py`:

```python
import requests
import json

# Configuración
BASE_URL = "https://ion-app-120e60a9275c.herokuapp.com/api/v2"
EMAIL = "tu@email.com"
PASSWORD = "tu-contraseña"

def login():
    """Login y obtener token"""
    response = requests.post(
        f"{BASE_URL}/auth/login",
        json={"email": EMAIL, "password": PASSWORD}
    )
    data = response.json()
    if data['success']:
        print("✅ Login exitoso")
        return data['data']['token']
    else:
        print("❌ Error en login:", data)
        return None

def get_assets(token):
    """Obtener lista de activos"""
    headers = {
        "Authorization": f"Bearer {token}",
        "Accept": "application/json"
    }
    response = requests.get(f"{BASE_URL}/assets", headers=headers)
    return response.json()

def create_asset(token):
    """Crear un activo"""
    headers = {
        "Authorization": f"Bearer {token}",
        "Content-Type": "application/json"
    }
    asset_data = {
        "name": "Laptop Test",
        "code": "TEST-001",
        "category_id": 1,
        "status": "available"
    }
    response = requests.post(
        f"{BASE_URL}/assets",
        headers=headers,
        json=asset_data
    )
    return response.json()

def main():
    print("🚀 Iniciando pruebas de API...")

    # 1. Login
    token = login()
    if not token:
        return

    print(f"Token: {token[:20]}...\n")

    # 2. Listar activos
    print("📦 Listando activos...")
    assets = get_assets(token)
    print(json.dumps(assets, indent=2))

    # 3. Crear activo
    print("\n➕ Creando activo...")
    new_asset = create_asset(token)
    print(json.dumps(new_asset, indent=2))

    print("\n✅ Pruebas completadas!")

if __name__ == "__main__":
    main()
```

**Ejecutar:**

```bash
pip install requests
python test_api.py
```

---

## 🌐 Método 5: JavaScript/Node.js

Crea un archivo `test-api.js`:

```javascript
const axios = require("axios");

const BASE_URL = "https://ion-app-120e60a9275c.herokuapp.com/api/v2";
const EMAIL = "tu@email.com";
const PASSWORD = "tu-contraseña";

async function login() {
    try {
        const response = await axios.post(`${BASE_URL}/auth/login`, {
            email: EMAIL,
            password: PASSWORD,
        });
        console.log("✅ Login exitoso");
        return response.data.data.token;
    } catch (error) {
        console.error("❌ Error en login:", error.response?.data);
        return null;
    }
}

async function getAssets(token) {
    try {
        const response = await axios.get(`${BASE_URL}/assets`, {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        });
        return response.data;
    } catch (error) {
        console.error("❌ Error obteniendo activos:", error.response?.data);
        return null;
    }
}

async function createAsset(token) {
    try {
        const response = await axios.post(
            `${BASE_URL}/assets`,
            {
                name: "Laptop Test",
                code: "TEST-001",
                category_id: 1,
                status: "available",
            },
            {
                headers: {
                    Authorization: `Bearer ${token}`,
                    "Content-Type": "application/json",
                },
            }
        );
        return response.data;
    } catch (error) {
        console.error("❌ Error creando activo:", error.response?.data);
        return null;
    }
}

async function main() {
    console.log("🚀 Iniciando pruebas de API...\n");

    // 1. Login
    const token = await login();
    if (!token) return;

    console.log(`Token: ${token.substring(0, 20)}...\n`);

    // 2. Listar activos
    console.log("📦 Listando activos...");
    const assets = await getAssets(token);
    console.log(JSON.stringify(assets, null, 2));

    // 3. Crear activo
    console.log("\n➕ Creando activo...");
    const newAsset = await createAsset(token);
    console.log(JSON.stringify(newAsset, null, 2));

    console.log("\n✅ Pruebas completadas!");
}

main();
```

**Ejecutar:**

```bash
npm install axios
node test-api.js
```

---

## 📊 Códigos de Respuesta HTTP

-   `200` - OK: Operación exitosa
-   `201` - Created: Recurso creado exitosamente
-   `400` - Bad Request: Solicitud inválida
-   `401` - Unauthorized: Token inválido o faltante
-   `403` - Forbidden: Sin permisos
-   `404` - Not Found: Recurso no encontrado
-   `422` - Unprocessable Entity: Error de validación
-   `500` - Internal Server Error: Error del servidor

---

## 🔍 Solución de Problemas

### Error 401 - Unauthorized

```bash
# Verifica que el token sea válido
curl -X GET "https://ion-app-120e60a9275c.herokuapp.com/api/v2/auth/user" \
  -H "Authorization: Bearer TU_TOKEN" \
  -H "Accept: application/json"
```

### Error 422 - Validation Error

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "code": ["The code field is required."]
    }
}
```

**Solución:** Revisa los campos requeridos en la documentación

### Error 404 - Not Found

**Solución:** Verifica que el ID del recurso exista

---

## 💡 Tips

1. **Guarda el token** en una variable de entorno
2. **Usa jq** para formatear JSON en terminal: `| jq`
3. **Revisa los logs** si algo falla
4. **Lee la documentación** completa en `API_DOCUMENTATION.md`

---

¡Listo para probar! 🚀
