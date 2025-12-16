# 🚀 ION Inventory API v2 - Quick Reference

## Base URL

```
https://ion-app-120e60a9275c.herokuapp.com/api/v2
```

## 🔐 Authentication

### Login

```bash
POST /auth/login
{
  "email": "user@example.com",
  "password": "password"
}
```

### Use Token

```bash
Authorization: Bearer {your-token}
```

---

## 📦 Available Endpoints

### 🔹 Assets

```
GET    /assets           # List assets
POST   /assets           # Create asset
GET    /assets/{id}      # Get asset
PUT    /assets/{id}      # Update asset
DELETE /assets/{id}      # Delete asset
```

### 🔹 Categories

```
GET    /categories       # List categories
POST   /categories       # Create category
GET    /categories/{id}  # Get category
PUT    /categories/{id}  # Update category
DELETE /categories/{id}  # Delete category
```

### 🔹 Locations

```
GET    /locations        # List locations
POST   /locations        # Create location
GET    /locations/{id}   # Get location
PUT    /locations/{id}   # Update location
DELETE /locations/{id}   # Delete location
```

### 🔹 Maintenances

```
GET    /maintenances           # List maintenances
POST   /maintenances           # Create maintenance
GET    /maintenances/{id}      # Get maintenance
PUT    /maintenances/{id}      # Update maintenance
DELETE /maintenances/{id}      # Delete maintenance
```

### 🔹 Users

```
GET    /users            # List users (admin only)
POST   /users            # Create user (admin only)
GET    /users/{id}       # Get user
PUT    /users/{id}       # Update user
DELETE /users/{id}       # Delete user (admin only)
```

### 🔹 Authentication

```
POST   /auth/login       # Login
POST   /auth/logout      # Logout
GET    /auth/user        # Get current user
POST   /auth/refresh     # Refresh token
```

---

## 💡 Quick Examples

### Create Asset

```bash
curl -X POST https://ion-app-120e60a9275c.herokuapp.com/api/v2/assets \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Laptop Dell",
    "code": "ASSET-001",
    "category_id": 1,
    "location_id": 1,
    "status": "available"
  }'
```

### List Maintenances for an Asset

```bash
curl "https://ion-app-120e60a9275c.herokuapp.com/api/v2/maintenances?asset_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Create Maintenance

```bash
curl -X POST https://ion-app-120e60a9275c.herokuapp.com/api/v2/maintenances \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "asset_id": 1,
    "type": "preventive",
    "scheduled_date": "2024-12-20",
    "description": "Regular maintenance"
  }'
```

---

## 📊 Response Format

### Success

```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

### Error

```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

---

## 🔒 Permissions

-   **User**: Can view and manage their assigned assets
-   **Admin**: Can manage all resources within their company
-   **Superadmin**: Full access to all resources

---

For complete documentation, see [API_DOCUMENTATION.md](./API_DOCUMENTATION.md)
