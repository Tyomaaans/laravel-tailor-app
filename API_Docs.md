#### Auth Tailor-Api

## POST http://localhost:8000/api/auth/login (public access no needed token or role)

# Request
```json
{
  "email": "email@example.com",
  "password": "your-password"
}
```

# Response 200
```json
{
  "success": true,
  "message": "Login successfully",
  "data": {
    "access_token": "eyJ...",
    "refresh_token": "random-64-char-string",
    "token_type": "bearer",
    "expires_in": 900
  },
  "errors": null
}
```

# Response 422
```json
{
  "message": "string",
  "errors": {
    "additionalProp1": [
      "string"
    ],
    "additionalProp2": [
      "string"
    ],
    "additionalProp3": [
      "string"
    ]
  }
}
```

# Response 500
```json
{
  "success": true,
  "message": "Unable to login.",
  "data": null,
  "errors": null
}
```

## POST http://localhost:8000/api/auth/logout

# Request
```json
{
  "refresh_token": "your-refresh-token"
}
```

# Response 200
```json
{
  "success": true,
  "message": "Logged out successfully",
  "data": null,
  "errors": null
}
```

# Response 401
```json
{
  "message": "string"
}
```

# Response 422
```json
{
  "message": "string",
  "errors": {
    "additionalProp1": [
      "string"
    ],
    "additionalProp2": [
      "string"
    ],
    "additionalProp3": [
      "string"
    ]
  }
}
```

# Response 500
```json
{
  "success": true,
  "message": "Unable to logout.",
  "data": null,
  "errors": null
}
```

## GET http://localhost:8000/api/auth/me

# Request
```json
{
  "no-needed-request-body": "no-needed-request-body"
}
```

# Response 200
```json
{
  "success": true,
  "message": "Authenticated user retrieved",
  "data": {
    "id": "uuid",
    "name": "User Name",
    "email": "email@example.com",
    "roles": [{"id": 1, "name": "your-role", "guard_name": "api"}],
    "permissions": []
  },
  "errors": null
}
```

# Response 401
```json
{
  "message": "string"
}
```

# Response 500
```json
{
  "success": true,
  "message": "Unable to retrieve authenticated user.",
  "data": null,
  "errors": null
}
```

## POST http://localhost:8000/api/auth/refresh 


# Request
```json
{
  "refresh_token": "your-refresh_token"
}
```

# Response 200
```json
{
  "success": true,
  "message": "Refresh token successfully",
  "data": {
    "access_token": "eyJ...",
    "refresh_token": "random-64-char-string",
    "token_type": "bearer",
    "expires_in": 900
  },
  "errors": null
}
```

# Response 422
```json
{
  "message": "string",
  "errors": {
    "additionalProp1": [
      "string"
    ],
    "additionalProp2": [
      "string"
    ],
    "additionalProp3": [
      "string"
    ]
  }
}
```

# Response 500
```json
{
  "success": true,
  "message": "Unable to refresh token.",
  "data": null,
  "errors": null
}
```

