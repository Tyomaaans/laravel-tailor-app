# Tailor API Documentation

Base URL: `http://localhost:8000/api`

## Headers

Protected endpoints require:

```
Authorization: Bearer {access_token}
Content-Type: application/json
Accept: application/json
```

Rate limit response headers:

- `X-RateLimit-Limit`
- `X-RateLimit-Remaining`
- `Retry-After` (when limited)

## Response format

```json
{
  "success": true,
  "message": "Success message",
  "data": {},
  "errors": null
}
```

Paginated list responses also include `meta` and `links`.

## Test users

| Role | Email | Password |
|------|-------|----------|
| super_admin | super_admin@example.com | password |
| admin | admin@example.com | password |
| staff | staff@example.com | password |
| customer | customer@example.com | password |

---

## Authentication

### POST /api/auth/login

Public. Roles: none.

Request:

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

Response:

```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "access_token": "eyJ...",
    "refresh_token": "random-64-char-string",
    "token_type": "bearer",
    "expires_in": 900
  },
  "errors": null
}
```

### POST /api/auth/refresh

Public. Roles: none.

Request:

```json
{
  "refresh_token": "your-refresh-token"
}
```

Response: same shape as login. Old refresh token is invalidated (rotation).

### POST /api/auth/logout

Protected. Roles: any authenticated user.

Request:

```json
{
  "refresh_token": "your-refresh-token"
}
```

Response:

```json
{
  "success": true,
  "message": "Logged out successfully",
  "data": null,
  "errors": null
}
```

### GET /api/auth/me

Protected. Roles: any authenticated user.

Response:

```json
{
  "success": true,
  "message": "Authenticated user retrieved",
  "data": {
    "id": "uuid",
    "name": "Admin User",
    "email": "admin@example.com",
    "roles": [{"id": 1, "name": "admin", "guard_name": "api"}],
    "permissions": []
  },
  "errors": null
}
```

---

## Resource endpoints

All resource routes support:

- `GET /{resource}` — paginated index (`?search=`, `?filter[field]=`)
- `POST /{resource}` — create
- `GET /{resource}/{id}` — show
- `PUT/PATCH /{resource}/{id}` — update
- `DELETE /{resource}/{id}` — soft delete (204)

### Users — `/api/users`

Roles: `super_admin`, `admin`

Store example:

```json
{
  "name": "New Staff",
  "email": "newstaff@example.com",
  "password": "password123",
  "phone": "+1234567890"
}
```

### Customers — `/api/customers`

Roles: `super_admin`, `admin` (full CRUD); `customer` (index/show own record only)

Store example:

```json
{
  "name": "Jane Doe",
  "email": "jane@example.com",
  "phone": "+1234567890",
  "address": "456 Fashion Ave"
}
```

### Measurements — `/api/measurements`

Roles: `super_admin`, `admin`, `staff` (full CRUD); `customer` (index/show own)

Store example:

```json
{
  "customer_id": "customer-uuid",
  "height": 170,
  "weight": 65,
  "chest": 90,
  "waist": 75,
  "hip": 95
}
```

### Categories — `/api/categories`

Roles: `super_admin`, `admin`, `staff`

Store example:

```json
{
  "name": "Suits",
  "type": "product"
}
```

### Products — `/api/products`

Roles: `super_admin`, `admin`, `staff`

Store example:

```json
{
  "category_id": "category-uuid",
  "name": "Custom Suit",
  "base_price": 499.99,
  "unit": "piece",
  "description": "Two-piece bespoke suit"
}
```

### Suppliers — `/api/suppliers`

Roles: `super_admin`, `admin`, `staff`

Store example:

```json
{
  "name": "Fabric Co",
  "contact_name": "John Supplier",
  "phone": "+1234567890",
  "address": "789 Warehouse Rd"
}
```

### Material categories — `/api/material-categories`

Roles: `super_admin`, `admin`, `staff`

Store example:

```json
{
  "name": "Silk",
  "description": "Premium silk fabrics"
}
```

### Material stocks — `/api/material-stocks`

Roles: `super_admin`, `admin`, `staff`

Store example:

```json
{
  "supplier_id": "supplier-uuid",
  "category_id": "material-category-uuid",
  "name": "Italian Silk Roll",
  "quantity": 120.5,
  "unit": "meters",
  "cost_per_unit": 45.00
}
```

### Orders — `/api/orders`

Roles: `super_admin`, `admin`, `staff` (full CRUD); `customer` (index/show own)

Store example:

```json
{
  "invoice": "INV-2026-001",
  "customer_id": "customer-uuid",
  "measurement_id": "measurement-uuid",
  "order_date": "2026-06-22",
  "due_date": "2026-07-22",
  "total_price": 899.99,
  "remaining_payment": 400.00
}
```

### Order items — `/api/order-items`

Roles: `super_admin`, `admin`, `staff` (full CRUD); `customer` (index/show own via order)

Store example:

```json
{
  "order_id": "order-uuid",
  "product_id": "product-uuid",
  "quantity": 1,
  "unit_price": 499.99,
  "notes": "Slim fit requested"
}
```

### Payments — `/api/payments`

Roles: `super_admin`, `admin` (full CRUD); `customer` (index/show own via order)

Store example:

```json
{
  "order_id": "order-uuid",
  "amount": 250.00,
  "method": "card",
  "paid_at": "2026-06-22T10:30:00Z",
  "reference": "PAY-12345"
}
```

### Production tasks — `/api/production-tasks`

Roles: `super_admin`, `admin`, `staff`

Store example:

```json
{
  "order_id": "order-uuid",
  "assigned_to": "user-uuid",
  "stage": "cutting",
  "status": "in_progress",
  "started_at": "2026-06-22T09:00:00Z",
  "notes": "Priority order"
}
```

---

## Error codes

| Status | Meaning |
|--------|---------|
| 401 | Unauthenticated or revoked token (`token_revoked`) |
| 403 | Forbidden (insufficient role) |
| 404 | Resource or route not found |
| 422 | Validation failed (`errors` contains field messages) |
| 429 | Rate limit exceeded |
| 500 | Server error (details hidden in production) |

---

## Running locally

```bash
docker compose up --build
docker compose exec app php artisan migrate:fresh --seed
docker compose exec app php artisan optimize
```

API available at `http://localhost:8000/api`.
