# Complete API Test Guide - All Endpoints

## Authentication APIs

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

### 2. Login API
**Endpoint:** `POST /api/login`

**Test successful login:**
```bash
curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "testlogin@example.com",
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
        "id": 4,
        "nama": "Test Login User",
        "email": "testlogin@example.com",
        "nik": "9876543210987654",
        "role_id": 2
    }
}
```

## Letter Application APIs

### 3. Get Letter Types List
**Endpoint:** `GET /api/jenis-surat`

**Test:**
```bash
curl -X GET http://localhost:8080/api/jenis-surat \
  -H "Accept: application/json"
```

**Expected response:**
```json
{
    "success": true,
    "message": "Data jenis surat berhasil dimuat",
    "data": [
        {
            "id": 1,
            "nama_surat": "Surat Keterangan Domisili",
            "file_template": "template_domisili.pdf",
            "updated_at": "2025-10-28T06:50:30.000000Z"
        }
    ]
}
```

### 4. Submit Letter Application
**Endpoint:** `POST /api/pengajuan`

**Test:**
```bash
curl -X POST http://localhost:8080/api/pengajuan \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "jenis_surat_id": 1,
    "data_pemohon": {
      "nama": "Test User2",
      "nik_pemohon": "1234567890123452",
      "alamat": "Jl. Test No. 123"
    },
    "keterangan": "Permohonan surat keterangan domisili"
  }'
```

**Expected successful response:**
```json
{
    "success": true,
    "message": "Pengajuan berhasil dikirim",
    "data": {
        "nik_pemohon": "1234567890123452",
        "jenis_surat_id": 1,
        "tanggal_pengajuan": "2025-10-28",
        "status": "menunggu_verifikasi",
        "data_isian": {
            "data_pemohon": {
                "nama": "Test User2",
                "nik_pemohon": "1234567890123452",
                "alamat": "Jl. Test No. 123"
            },
            "keterangan": "Permohonan surat keterangan domisili"
        },
        "file_syarat": []
    }
}
```

### 5. Get Application Details
**Endpoint:** `GET /api/pengajuan/{id}`

**Test:**
```bash
curl -X GET http://localhost:8080/api/pengajuan/1 \
  -H "Accept: application/json"
```

## Admin APIs (Require Authentication)

### 6. Get All Applications (Admin)
**Endpoint:** `GET /api/admin/pengajuan`

**Test with token:**
```bash
curl -X GET http://localhost:8080/api/admin/pengajuan \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token-here"
```

### 7. Approve Application
**Endpoint:** `POST /api/admin/pengajuan/{id}/approve`

**Test:**
```bash
curl -X POST http://localhost:8080/api/admin/pengajuan/1/approve \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token-here"
```

### 8. Reject Application
**Endpoint:** `POST /api/admin/pengajuan/{id}/reject`

**Test:**
```bash
curl -X POST http://localhost:8080/api/admin/pengajuan/1/reject \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token-here" \
  -d '{
    "alasan": "Dokumen tidak lengkap"
  }'
```

### 9. Generate Letter
**Endpoint:** `POST /api/admin/pengajuan/{id}/generate`

**Test:**
```bash
curl -X POST http://localhost:8080/api/admin/pengajuan/1/generate \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token-here"
```

## Letter Type Management (Admin)

### 10. Add Letter Type
**Endpoint:** `POST /api/tambah-jenis`

**Test:**
```bash
curl -X POST http://localhost:8080/api/tambah-jenis \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer your-token-here" \
  -d '{
    "nama_surat": "Surat Keterangan Belum Menikah",
    "file_template": "template_belum_menikah.pdf"
  }'
```

## Error Response Format

All error responses follow this format:
```json
{
    "success": false,
    "message": "Error description",
    "errors": {
        "field": ["validation message"]
    }
}
```

**Common HTTP Status Codes:**
- `200` - Success
- `201` - Created (successful registration/application)
- `400` - Bad Request
- `401` - Unauthorized
- `422` - Validation Error
- `404` - Not Found
- `500` - Internal Server Error

## Authentication Flow

1. **Register** → Get token
2. **Login** → Get token (if already registered)
3. **Use token** for all authenticated endpoints in header:
   ```
   Authorization: Bearer your-token-here
   ```

## Testing Tips

- Use the same token for multiple requests until it expires
- Test with invalid data to see error responses
- Check response structure consistency across all endpoints
- Verify file uploads work correctly
- Test pagination for list endpoints