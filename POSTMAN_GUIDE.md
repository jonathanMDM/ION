# 📮 Guía Completa de Postman - ION Inventory API v2

## 🚀 Configuración Inicial

### Paso 1: Crear un Entorno (Environment)

1. **Abrir Postman**
2. Click en "Environments" en la barra lateral izquierda
3. Click en el botón "+" para crear un nuevo entorno
4. Nombrar el entorno: `ION Inventory - Production`
5. Agregar las siguientes variables:

| Variable   | Initial Value                                       | Current Value                                       |
| ---------- | --------------------------------------------------- | --------------------------------------------------- |
| `base_url` | `https://ion-app-120e60a9275c.herokuapp.com/api/v2` | `https://ion-app-120e60a9275c.herokuapp.com/api/v2` |
| `token`    | (dejar vacío)                                       | (dejar vacío)                                       |
| `email`    | `tu@email.com`                                      | `tu@email.com`                                      |
| `password` | `tu-contraseña`                                     | `tu-contraseña`                                     |

6. Click en "Save"
7. Seleccionar el entorno en el dropdown superior derecho

---

## 📁 Paso 2: Crear una Colección

1. Click en "Collections" en la barra lateral
2. Click en el botón "+" para crear una nueva colección
3. Nombrar la colección: `ION Inventory API v2`
4. Click en "Save"

---

## 🔐 Paso 3: Configurar Autenticación

### Request 1: Login

1. **Crear nuevo request:**

    - Click derecho en la colección → "Add request"
    - Nombre: `Login`
    - Método: `POST`

2. **URL:**

    ```
    {{base_url}}/auth/login
    ```

3. **Headers:**

    - `Content-Type`: `application/json`
    - `Accept`: `application/json`

4. **Body:**

    - Seleccionar "raw" y "JSON"
    - Contenido:

    ```json
    {
        "email": "{{email}}",
        "password": "{{password}}"
    }
    ```

5. **Tests (Script para guardar el token automáticamente):**

    - Click en la pestaña "Tests"
    - Agregar este código:

    ```javascript
    // Verificar que la respuesta sea exitosa
    pm.test("Login successful", function () {
        pm.response.to.have.status(200);
    });

    // Verificar que el token existe
    pm.test("Token is present", function () {
        var jsonData = pm.response.json();
        pm.expect(jsonData.data.token).to.exist;
    });

    // Guardar el token en las variables de entorno
    if (pm.response.code === 200) {
        var jsonData = pm.response.json();
        pm.environment.set("token", jsonData.data.token);
        console.log(
            "Token guardado:",
            jsonData.data.token.substring(0, 20) + "..."
        );
    }
    ```

6. **Click en "Save"**

7. **Probar el request:**
    - Click en "Send"
    - Deberías ver una respuesta exitosa con el token
    - El token se guardará automáticamente en las variables de entorno

---

## 📦 Paso 4: Requests de Assets

### Request 2: Listar Activos

1. **Crear nuevo request:**

    - Nombre: `Get Assets`
    - Método: `GET`

2. **URL:**

    ```
    {{base_url}}/assets
    ```

3. **Headers:**

    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`

4. **Params (Query Parameters - Opcionales):**

    - `page`: `1`
    - `per_page`: `15`
    - `search`: (dejar vacío o agregar término de búsqueda)

5. **Tests:**

    ```javascript
    pm.test("Status code is 200", function () {
        pm.response.to.have.status(200);
    });

    pm.test("Response has data array", function () {
        var jsonData = pm.response.json();
        pm.expect(jsonData.data).to.be.an("array");
    });

    pm.test("Response has pagination meta", function () {
        var jsonData = pm.response.json();
        pm.expect(jsonData.meta).to.exist;
    });
    ```

6. **Save y Send**

---

### Request 3: Ver un Activo

1. **Crear nuevo request:**

    - Nombre: `Get Asset by ID`
    - Método: `GET`

2. **URL:**

    ```
    {{base_url}}/assets/1
    ```

    (Cambiar el `1` por el ID del activo que quieras ver)

3. **Headers:**

    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`

4. **Tests:**

    ```javascript
    pm.test("Status code is 200", function () {
        pm.response.to.have.status(200);
    });

    pm.test("Asset has required fields", function () {
        var jsonData = pm.response.json();
        pm.expect(jsonData.data).to.have.property("id");
        pm.expect(jsonData.data).to.have.property("name");
        pm.expect(jsonData.data).to.have.property("code");
    });
    ```

---

### Request 4: Crear Activo

1. **Crear nuevo request:**

    - Nombre: `Create Asset`
    - Método: `POST`

2. **URL:**

    ```
    {{base_url}}/assets
    ```

3. **Headers:**

    - `Authorization`: `Bearer {{token}}`
    - `Content-Type`: `application/json`
    - `Accept`: `application/json`

4. **Body (raw JSON):**

    ```json
    {
        "name": "Laptop Dell Latitude 5420",
        "code": "ASSET-{{$timestamp}}",
        "category_id": 1,
        "location_id": 1,
        "serial_number": "SN{{$randomInt}}",
        "model": "Latitude 5420",
        "brand": "Dell",
        "purchase_date": "2024-01-15",
        "purchase_price": 1200.0,
        "status": "available",
        "description": "Laptop para desarrollo"
    }
    ```

5. **Tests:**

    ```javascript
    pm.test("Status code is 201", function () {
        pm.response.to.have.status(201);
    });

    pm.test("Asset created successfully", function () {
        var jsonData = pm.response.json();
        pm.expect(jsonData.success).to.be.true;
        pm.expect(jsonData.data.id).to.exist;

        // Guardar el ID del activo creado para usarlo después
        pm.environment.set("last_created_asset_id", jsonData.data.id);
    });
    ```

---

### Request 5: Actualizar Activo

1. **Crear nuevo request:**

    - Nombre: `Update Asset`
    - Método: `PUT`

2. **URL:**

    ```
    {{base_url}}/assets/{{last_created_asset_id}}
    ```

3. **Headers:**

    - `Authorization`: `Bearer {{token}}`
    - `Content-Type`: `application/json`
    - `Accept`: `application/json`

4. **Body (raw JSON):**

    ```json
    {
        "status": "in_use",
        "location_id": 2,
        "description": "Activo actualizado mediante API"
    }
    ```

5. **Tests:**

    ```javascript
    pm.test("Status code is 200", function () {
        pm.response.to.have.status(200);
    });

    pm.test("Asset updated successfully", function () {
        var jsonData = pm.response.json();
        pm.expect(jsonData.success).to.be.true;
    });
    ```

---

### Request 6: Eliminar Activo

1. **Crear nuevo request:**

    - Nombre: `Delete Asset`
    - Método: `DELETE`

2. **URL:**

    ```
    {{base_url}}/assets/{{last_created_asset_id}}
    ```

3. **Headers:**

    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`

4. **Tests:**

    ```javascript
    pm.test("Status code is 200", function () {
        pm.response.to.have.status(200);
    });

    pm.test("Asset deleted successfully", function () {
        var jsonData = pm.response.json();
        pm.expect(jsonData.success).to.be.true;
    });
    ```

---

## 📁 Paso 5: Requests de Categorías

### Request 7: Listar Categorías

1. **Nombre:** `Get Categories`
2. **Método:** `GET`
3. **URL:** `{{base_url}}/categories`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`

---

### Request 8: Crear Categoría

1. **Nombre:** `Create Category`
2. **Método:** `POST`
3. **URL:** `{{base_url}}/categories`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Content-Type`: `application/json`
5. **Body:**
    ```json
    {
        "name": "Electrónica",
        "description": "Dispositivos electrónicos"
    }
    ```

---

## 📍 Paso 6: Requests de Ubicaciones

### Request 9: Listar Ubicaciones

1. **Nombre:** `Get Locations`
2. **Método:** `GET`
3. **URL:** `{{base_url}}/locations`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`

---

### Request 10: Crear Ubicación

1. **Nombre:** `Create Location`
2. **Método:** `POST`
3. **URL:** `{{base_url}}/locations`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Content-Type`: `application/json`
5. **Body:**
    ```json
    {
        "name": "Oficina Principal",
        "address": "Calle 123, Ciudad"
    }
    ```

---

## 🔧 Paso 7: Requests de Mantenimientos

### Request 11: Listar Mantenimientos

1. **Nombre:** `Get Maintenances`
2. **Método:** `GET`
3. **URL:** `{{base_url}}/maintenances`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`
5. **Params (opcionales):**
    - `asset_id`: ID del activo
    - `status`: `pending`, `in_progress`, `completed`, `cancelled`

---

### Request 12: Crear Mantenimiento

1. **Nombre:** `Create Maintenance`
2. **Método:** `POST`
3. **URL:** `{{base_url}}/maintenances`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Content-Type`: `application/json`
5. **Body:**
    ```json
    {
        "asset_id": 1,
        "type": "preventive",
        "scheduled_date": "2024-12-20",
        "description": "Mantenimiento preventivo mensual",
        "cost": 50.0
    }
    ```

---

## 👥 Paso 8: Requests de Usuarios (Solo Admin)

### Request 13: Listar Usuarios

1. **Nombre:** `Get Users`
2. **Método:** `GET`
3. **URL:** `{{base_url}}/users`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`

---

## 🔄 Paso 9: Otros Requests de Autenticación

### Request 14: Obtener Usuario Actual

1. **Nombre:** `Get Current User`
2. **Método:** `GET`
3. **URL:** `{{base_url}}/auth/user`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`

---

### Request 15: Refrescar Token

1. **Nombre:** `Refresh Token`
2. **Método:** `POST`
3. **URL:** `{{base_url}}/auth/refresh`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`
5. **Tests:**
    ```javascript
    if (pm.response.code === 200) {
        var jsonData = pm.response.json();
        pm.environment.set("token", jsonData.data.token);
        console.log("Token refrescado y guardado");
    }
    ```

---

### Request 16: Logout

1. **Nombre:** `Logout`
2. **Método:** `POST`
3. **URL:** `{{base_url}}/auth/logout`
4. **Headers:**
    - `Authorization`: `Bearer {{token}}`
    - `Accept`: `application/json`
5. **Tests:**
    ```javascript
    if (pm.response.code === 200) {
        pm.environment.unset("token");
        console.log("Token eliminado");
    }
    ```

---

## 🎯 Paso 10: Organizar la Colección

### Crear Carpetas:

1. **Click derecho en la colección → "Add folder"**
2. Crear las siguientes carpetas:

    - `Authentication`
    - `Assets`
    - `Categories`
    - `Locations`
    - `Maintenances`
    - `Users`

3. **Arrastrar los requests a sus carpetas correspondientes**

---

## 🧪 Paso 11: Ejecutar Pruebas en Secuencia

### Crear un Runner:

1. Click en la colección
2. Click en "Run"
3. Seleccionar los requests que quieres ejecutar
4. Orden sugerido:

    1. Login
    2. Get Current User
    3. Get Assets
    4. Create Asset
    5. Update Asset
    6. Get Asset by ID
    7. Delete Asset
    8. Logout

5. Click en "Run ION Inventory API v2"

---

## 💡 Tips y Trucos

### Variables Dinámicas de Postman:

-   `{{$timestamp}}` - Timestamp actual
-   `{{$randomInt}}` - Número aleatorio
-   `{{$guid}}` - GUID único
-   `{{$randomEmail}}` - Email aleatorio

### Ejemplo de uso:

```json
{
    "code": "ASSET-{{$timestamp}}",
    "serial_number": "SN-{{$randomInt}}"
}
```

### Pre-request Scripts:

Para ejecutar código antes de cada request, agregar en "Pre-request Script":

```javascript
// Verificar si el token existe
if (!pm.environment.get("token")) {
    console.log("⚠️ Token no encontrado. Ejecuta el request de Login primero.");
}
```

### Validaciones Comunes en Tests:

```javascript
// Verificar status code
pm.test("Status code is 200", function () {
    pm.response.to.have.status(200);
});

// Verificar tiempo de respuesta
pm.test("Response time is less than 2000ms", function () {
    pm.expect(pm.response.responseTime).to.be.below(2000);
});

// Verificar estructura de respuesta
pm.test("Response has success field", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property("success");
    pm.expect(jsonData.success).to.be.true;
});

// Verificar tipo de dato
pm.test("Data is an array", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData.data).to.be.an("array");
});
```

---

## 📤 Exportar e Importar Colección

### Exportar:

1. Click derecho en la colección
2. "Export"
3. Seleccionar "Collection v2.1"
4. Guardar el archivo JSON

### Importar:

1. Click en "Import"
2. Seleccionar el archivo JSON
3. Click en "Import"

---

## 🔒 Seguridad

**⚠️ IMPORTANTE:**

-   **NO** compartas tu token con nadie
-   **NO** subas archivos de Postman con tokens a repositorios públicos
-   Usa variables de entorno para credenciales sensibles
-   Mantén las "Initial Values" vacías para variables sensibles

---

## 📊 Monitoreo

Postman permite crear monitores para ejecutar la colección automáticamente:

1. Click en la colección
2. "Monitors" → "Create Monitor"
3. Configurar frecuencia de ejecución
4. Recibir notificaciones si algo falla

---

¡Listo para usar Postman con la API de ION Inventory! 🚀
