# ION Inventory API Documentation

## 🚀 Base URL

```
Production: https://ion-app-120e60a9275c.herokuapp.com/api/v2
Local: http://localhost:8000/api/v2
```

## 🔐 Authentication

The API uses Laravel Sanctum for authentication. You need to obtain a token by logging in.

### Login

```http
POST /api/v2/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "your-password"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "token": "1|abc123...",
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com",
            "role": "admin",
            "company_id": 1
        }
    }
}
```

### Using the Token

Include the token in all subsequent requests:

```http
Authorization: Bearer 1|abc123...
```

### Logout

```http
POST /api/v2/auth/logout
Authorization: Bearer {your-token}
```

### Get Current User

```http
GET /api/v2/auth/user
Authorization: Bearer {your-token}
```

### Refresh Token

```http
POST /api/v2/auth/refresh
Authorization: Bearer {your-token}
```

---

## 📦 Assets API

### List Assets

```http
GET /api/v2/assets
Authorization: Bearer {your-token}

Query Parameters:
- page (integer): Page number
- per_page (integer): Items per page (default: 15)
- search (string): Search term
```

**Response:**

```json
{
    "success": true,
    "message": "Assets retrieved successfully",
    "data": [
        {
            "id": 1,
            "name": "Laptop Dell",
            "code": "ASSET-001",
            "serial_number": "SN123456",
            "status": "available",
            "category": {
                "id": 1,
                "name": "Electronics"
            },
            "location": {
                "id": 1,
                "name": "Office A"
            }
        }
    ],
    "meta": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 75
    },
    "links": {
        "first": "...",
        "last": "...",
        "prev": null,
        "next": "..."
    }
}
```

### Get Single Asset

```http
GET /api/v2/assets/{id}
Authorization: Bearer {your-token}
```

### Create Asset

```http
POST /api/v2/assets
Authorization: Bearer {your-token}
Content-Type: application/json

{
  "name": "Laptop Dell",
  "code": "ASSET-001",
  "category_id": 1,
  "subcategory_id": 2,
  "location_id": 1,
  "serial_number": "SN123456",
  "model": "Latitude 5420",
  "brand": "Dell",
  "purchase_date": "2024-01-15",
  "purchase_price": 1200.00,
  "status": "available",
  "description": "New laptop for development"
}
```

**Required Fields:**

-   name (string)
-   code (string, unique)
-   category_id (integer)

**Optional Fields:**

-   subcategory_id (integer)
-   location_id (integer)
-   serial_number (string)
-   model (string)
-   brand (string)
-   purchase_date (date: YYYY-MM-DD)
-   purchase_price (number)
-   status (string: available, in_use, maintenance, retired)
-   description (string)

### Update Asset

```http
PUT /api/v2/assets/{id}
Authorization: Bearer {your-token}
Content-Type: application/json

{
  "name": "Updated Name",
  "status": "in_use",
  "location_id": 2
}
```

### Delete Asset

```http
DELETE /api/v2/assets/{id}
Authorization: Bearer {your-token}
```

---

## 📊 Response Format

All API responses follow this structure:

### Success Response

```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response

```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Error detail"]
    }
}
```

---

## 🔢 HTTP Status Codes

-   `200` - OK: Request successful
-   `201` - Created: Resource created successfully
-   `400` - Bad Request: Invalid request
-   `401` - Unauthorized: Invalid or missing token
-   `403` - Forbidden: Insufficient permissions
-   `404` - Not Found: Resource not found
-   `422` - Unprocessable Entity: Validation failed
-   `500` - Internal Server Error: Server error

---

## 🛡️ Rate Limiting

API requests are rate-limited to prevent abuse:

-   60 requests per minute per user

When rate limit is exceeded, you'll receive a `429 Too Many Requests` response.

---

## 💡 Examples

### cURL Example

```bash
# Login
curl -X POST https://ion-app-120e60a9275c.herokuapp.com/api/v2/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Get Assets
curl -X GET https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Create Asset
curl -X POST https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "New Laptop",
    "code": "ASSET-100",
    "category_id": 1
  }'
```

### JavaScript Example

```javascript
// Login
const login = async () => {
    const response = await fetch(
        "https://ion-app-120e60a9275c.herokuapp.com/api/v2/auth/login",
        {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({
                email: "user@example.com",
                password: "password",
            }),
        }
    );

    const data = await response.json();
    const token = data.data.token;
    return token;
};

// Get Assets
const getAssets = async (token) => {
    const response = await fetch(
        "https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets",
        {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: "application/json",
            },
        }
    );

    const data = await response.json();
    return data.data;
};

// Usage
const token = await login();
const assets = await getAssets(token);
console.log(assets);
```

### Python Example

```python
import requests

# Login
def login():
    response = requests.post(
        'https://ion-app-120e60a9275c.herokuapp.com/api/v2/auth/login',
        json={
            'email': 'user@example.com',
            'password': 'password'
        }
    )
    data = response.json()
    return data['data']['token']

# Get Assets
def get_assets(token):
    response = requests.get(
        'https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets',
        headers={
            'Authorization': f'Bearer {token}',
            'Accept': 'application/json'
        }
    )
    data = response.json()
    return data['data']

# Usage
token = login()
assets = get_assets(token)
print(assets)
```

---

## 🔄 Versioning

The API uses URL versioning:

-   **v1**: Legacy API (existing system)
-   **v2**: New Sanctum-based API (recommended)

---

## 📞 Support

For API support, please contact the development team or create a support ticket in the system.

---

## 📝 Changelog

### v2.0.0 (2024-12-16)

-   Initial release of Sanctum-based API
-   Authentication endpoints
-   Asset CRUD operations
-   Standardized response format
-   Comprehensive documentation
