# Complete API Test Guide

## Authentication API Endpoints

### 1. Register API
**Endpoint:** `POST /api/register`

**Test successful registration:**
```bash
curl -X POST http://localhost:8080/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nik": "9876543210987654",
    "nama": "New User",
    "email": "newuser@example.com",
    "password": "password123"
  }'
```

**Expected successful response:**
```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "token": "your-sanctum-token-here",
    "user": {
        "id": 3,
        "nik": "9876543210987654",
        "nama": "New User",
        "email": "newuser@example.com",
        "role_id": 2
    }
}
```

**Test validation error:**
```bash
curl -X POST http://localhost:8080/api/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "nik": "1122334455667788",
    "nama": "",
    "email": "invalid-email",
    "password": "123"
  }'
```

### 2. Login API
**Endpoint:** `POST /api/login`

**Test successful login:**
```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "postman@test.com",
    "password": "password123"
  }'
```

**Expected successful response:**
```json
{
    "success": true,
    "message": "Login berhasil",
    "token": "your-sanctum-token-here",
    "user": {
        "id": 1,
        "nama": "Postman Test User",
        "email": "postman@test.com",
        "nik": "1122334455667788",
        "role_id": 2
    }
}
```

**Test failed login:**
```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "wrongpassword"
  }'
```

### 3. User API (requires authentication)
**Endpoint:** `GET /api/user`

**Test with valid token:**
```bash
curl -X GET http://localhost:8080/api/user \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token-here"
```

### 4. Logout API (requires authentication)
**Endpoint:** `POST /api/logout`

**Test logout:**
```bash
curl -X POST http://localhost:8080/api/logout \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token-here"
```

## Error Response Format

All error responses follow this format:
```json
{
    "message": "Error description",
    "errors": {
        "field": ["validation message"]
    }
}
```

## Empty Data Response
```json
{
    "message": "Data tidak boleh kosong"
}