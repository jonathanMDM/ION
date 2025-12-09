# API Documentation - Paladin Asset Management System

## Overview

The Paladin API provides programmatic access to your asset management system. Use it to integrate with external tools, create status pages, or build custom applications.

**Base URL**: `https://your-domain.com/api`  
**API Version**: v1  
**Authentication**: Bearer Token

---

## Authentication

### Generating an API Token

API tokens must be generated from the web interface while logged in.

**Endpoint**: `POST /api/v1/auth/token/generate`  
**Authentication**: Web session (must be logged in)

**Request Body**:

```json
{
    "token_name": "My API Token",
    "expires_in_days": 90
}
```

**Response**:

```json
{
    "message": "API token generated successfully",
    "token": "your-api-token-here",
    "token_name": "My API Token",
    "expires_at": "2025-03-04T21:10:37Z",
    "warning": "Please save this token securely. You will not be able to see it again."
}
```

⚠️ **Important**: Save the token immediately. It cannot be retrieved again.

### Using the API Token

Include the token in the `Authorization` header of all API requests:

```bash
Authorization: Bearer your-api-token-here
```

### Checking Token Status

**Endpoint**: `GET /api/v1/auth/token/status`  
**Authentication**: Web session

**Response**:

```json
{
    "has_token": true,
    "is_expired": false,
    "expires_at": "2025-03-04T21:10:37Z",
    "status": "active"
}
```

### Revoking a Token

**Endpoint**: `DELETE /api/v1/auth/token/revoke`  
**Authentication**: Web session

**Response**:

```json
{
    "message": "API token revoked successfully"
}
```

---

## Health & Status Endpoints

These endpoints are **public** and don't require authentication. Perfect for status pages and monitoring tools.

### Health Check

Simple endpoint to verify the API is operational.

**Endpoint**: `GET /api/health`  
**Authentication**: None

**Response**:

```json
{
    "status": "ok",
    "timestamp": "2025-12-03T21:10:37Z"
}
```

### System Status

Detailed system status including database, cache, and statistics.

**Endpoint**: `GET /api/status`  
**Authentication**: None

**Response**:

```json
{
    "status": "operational",
    "timestamp": "2025-12-03T21:10:37Z",
    "version": "1.0.0",
    "environment": "production",
    "checks": {
        "database": {
            "status": "ok",
            "message": "Database connection successful"
        },
        "cache": {
            "status": "ok",
            "message": "Cache working"
        }
    },
    "statistics": {
        "total_companies": 15,
        "total_users": 127,
        "total_assets": 1543
    }
}
```

---

## Assets

### List Assets

Get a paginated list of assets with optional filtering.

**Endpoint**: `GET /api/v1/assets`  
**Authentication**: Required

**Query Parameters**:

-   `page` (integer): Page number (default: 1)
-   `per_page` (integer): Items per page (max: 100, default: 15)
-   `status` (string): Filter by status (`available`, `in_use`, `in_maintenance`, `retired`)
-   `condition` (string): Filter by condition (`excellent`, `good`, `fair`, `poor`)
-   `category_id` (integer): Filter by category ID
-   `location_id` (integer): Filter by location ID
-   `search` (string): Search by name, code, or serial number

**Example Request**:

```bash
curl -H "Authorization: Bearer your-token" \
  "https://your-domain.com/api/v1/assets?status=available&per_page=20"
```

**Response**:

```json
{
    "data": [
        {
            "id": 1,
            "name": "Dell Laptop XPS 15",
            "code": "LAPTOP-001",
            "serial_number": "SN123456789",
            "model": "XPS 15 9520",
            "brand": "Dell",
            "description": "High-performance laptop",
            "purchase_date": "2024-01-15",
            "purchase_price": 2500.0,
            "current_value": 2000.0,
            "status": "available",
            "condition": "excellent",
            "warranty_expiration": "2027-01-15",
            "notes": null,
            "qr_code_path": "https://your-domain.com/storage/qr_codes/asset_1.png",
            "image_path": null,
            "category": {
                "id": 1,
                "name": "Electronics"
            },
            "subcategory": {
                "id": 3,
                "name": "Laptops"
            },
            "location": {
                "id": 2,
                "name": "Main Office",
                "address": "123 Main St"
            },
            "supplier": {
                "id": 5,
                "name": "Tech Supplier Inc"
            },
            "employee": null,
            "created_at": "2024-01-15T10:30:00Z",
            "updated_at": "2024-12-03T21:10:37Z"
        }
    ],
    "links": {
        "first": "https://your-domain.com/api/v1/assets?page=1",
        "last": "https://your-domain.com/api/v1/assets?page=10",
        "prev": null,
        "next": "https://your-domain.com/api/v1/assets?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 10,
        "per_page": 15,
        "to": 15,
        "total": 150
    }
}
```

### Get Single Asset

**Endpoint**: `GET /api/v1/assets/{id}`  
**Authentication**: Required

**Response**: Same structure as individual asset in list response.

### Create Asset

**Endpoint**: `POST /api/v1/assets`  
**Authentication**: Required (Admin only)

**Request Body**:

```json
{
    "name": "New Laptop",
    "code": "LAPTOP-002",
    "serial_number": "SN987654321",
    "model": "ThinkPad X1",
    "brand": "Lenovo",
    "description": "Business laptop",
    "purchase_date": "2024-12-03",
    "purchase_price": 1800.0,
    "current_value": 1800.0,
    "status": "available",
    "condition": "excellent",
    "category_id": 1,
    "subcategory_id": 3,
    "location_id": 2,
    "supplier_id": 5
}
```

### Update Asset

**Endpoint**: `PUT /api/v1/assets/{id}` or `PATCH /api/v1/assets/{id}`  
**Authentication**: Required (Admin only)

**Request Body**: Same as create, all fields optional for PATCH.

### Delete Asset

**Endpoint**: `DELETE /api/v1/assets/{id}`  
**Authentication**: Required (Admin only)

**Response**:

```json
{
    "message": "Asset deleted successfully"
}
```

---

## Users

### List Users

**Endpoint**: `GET /api/v1/users`  
**Authentication**: Required

**Query Parameters**:

-   `page`, `per_page`: Pagination
-   `role` (string): Filter by role
-   `is_active` (boolean): Filter by active status
-   `search` (string): Search by name or email

**Response**:

```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "admin",
      "is_active": true,
      "company": {
        "id": 1,
        "name": "Acme Corp"
      },
      "created_at": "2024-01-01T00:00:00Z",
      "updated_at": "2024-12-03T21:10:37Z"
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

### Get Single User

**Endpoint**: `GET /api/v1/users/{id}`  
**Authentication**: Required

### Get Current User

**Endpoint**: `GET /api/v1/users/me`  
**Authentication**: Required

**Response**: Same structure as single user.

---

## Companies

### List Companies

**Endpoint**: `GET /api/v1/companies`  
**Authentication**: Required (Superadmin only)

**Query Parameters**:

-   `page`, `per_page`: Pagination
-   `is_active` (boolean): Filter by active status
-   `subscription_status` (string): Filter by subscription status
-   `search` (string): Search by name, NIT, or email

**Response**:

```json
{
  "data": [
    {
      "id": 1,
      "name": "Acme Corp",
      "nit": "900123456-7",
      "email": "contact@acme.com",
      "phone": "+57 300 1234567",
      "address": "123 Business Ave",
      "is_active": true,
      "subscription_status": "active",
      "subscription_expires_at": "2025-12-31",
      "statistics": {
        "users_count": 25,
        "assets_count": 150
      },
      "created_at": "2024-01-01T00:00:00Z",
      "updated_at": "2024-12-03T21:10:37Z"
    }
  ],
  "links": { ... },
  "meta": { ... }
}
```

### Get Single Company

**Endpoint**: `GET /api/v1/companies/{id}`  
**Authentication**: Required

### Get Company Statistics

**Endpoint**: `GET /api/v1/companies/{id}/stats`  
**Authentication**: Required

**Response**:

```json
{
    "company_id": 1,
    "company_name": "Acme Corp",
    "statistics": {
        "total_users": 25,
        "active_users": 23,
        "total_assets": 150,
        "assets_by_status": {
            "available": 45,
            "in_use": 95,
            "in_maintenance": 8,
            "retired": 2
        },
        "total_asset_value": 125000.0
    }
}
```

---

## Rate Limiting

API requests are limited to **60 requests per minute** per token.

When rate limited, you'll receive a `429 Too Many Requests` response:

```json
{
    "message": "Too Many Attempts."
}
```

Response headers include:

-   `X-RateLimit-Limit`: Total requests allowed
-   `X-RateLimit-Remaining`: Requests remaining
-   `Retry-After`: Seconds until limit resets

---

## Error Responses

### Standard Error Format

```json
{
    "message": "Error description"
}
```

### HTTP Status Codes

-   `200 OK`: Success
-   `201 Created`: Resource created
-   `400 Bad Request`: Invalid request data
-   `401 Unauthorized`: Missing or invalid token
-   `403 Forbidden`: Insufficient permissions
-   `404 Not Found`: Resource not found
-   `422 Unprocessable Entity`: Validation errors
-   `429 Too Many Requests`: Rate limit exceeded
-   `500 Internal Server Error`: Server error

### Validation Errors

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "name": ["The name field is required."],
        "email": ["The email must be a valid email address."]
    }
}
```

---

## Best Practices

1. **Store tokens securely**: Never commit tokens to version control
2. **Use HTTPS**: Always use HTTPS in production
3. **Handle rate limits**: Implement exponential backoff
4. **Cache responses**: Cache data when appropriate
5. **Monitor token expiration**: Renew tokens before they expire
6. **Use pagination**: Don't request all data at once
7. **Filter results**: Use query parameters to reduce payload size

---

## Support

For API support, contact your system administrator or visit the support portal.
