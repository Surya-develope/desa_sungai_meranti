# API Dokumentasi - CRUD Jenis Surat

## Overview
Admin API now includes full CRUD operations for managing document types (jenis_surat).

## Base URL
```
https://yourdomain.com/api
```

## Authentication
All admin endpoints require Bearer token in Authorization header:
```
Authorization: Bearer {token}
```

## Available Endpoints

### 1. GET /admin/jenis-surat
**Description:** List all document types
**Method:** GET
**Auth:** Required (Admin)
**Response:**
```json
{
  "success": true,
  "message": "Daftar jenis surat berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama_surat": "Surat Keterangan Domicile",
      "file_template": "sk_domicile.docx",
      "deskripsi": "Surat keterangan tempat tinggal",
      "is_active": true,
      "created_at": "2025-10-28T05:57:48.000000Z"
    }
  ]
}
```

### 2. POST /admin/jenis-surat
**Description:** Create new document type
**Method:** POST
**Auth:** Required (Admin)
**Body:**
```json
{
  "nama_surat": "Surat Keterangan usaha",
  "file_template": "sk_usaha.docx",
  "deskripsi": "Surat keterangan untuk keperluan usaha",
  "is_active": true
}
```
**Response:**
```json
{
  "success": true,
  "message": "Jenis surat berhasil dibuat",
  "data": {
    "id": 2,
    "nama_surat": "Surat Keterangan usaha",
    "file_template": "sk_usaha.docx",
    "deskripsi": "Surat keterangan untuk keperluan usaha",
    "is_active": true,
    "created_at": "2025-10-28T05:57:48.000000Z"
  }
}
```

### 3. GET /admin/jenis-surat/{id}
**Description:** Get specific document type
**Method:** GET
**Auth:** Required (Admin)
**Response:**
```json
{
  "success": true,
  "message": "Detail jenis surat berhasil diambil",
  "data": {
    "id": 1,
    "nama_surat": "Surat Keterangan Domicile",
    "file_template": "sk_domicile.docx",
    "deskripsi": "Surat keterangan tempat tinggal",
    "is_active": true,
    "created_at": "2025-10-28T05:57:48.000000Z"
  }
}
```

### 4. PUT /admin/jenis-surat/{id}
**Description:** Update document type
**Method:** PUT
**Auth:** Required (Admin)
**Body:**
```json
{
  "nama_surat": "Surat Keterangan Domicile (Updated)",
  "file_template": "sk_domicile_new.docx",
  "deskripsi": "Updated description",
  "is_active": false
}
```
**Response:**
```json
{
  "success": true,
  "message": "Jenis surat berhasil diperbarui",
  "data": {
    "id": 1,
    "nama_surat": "Surat Keterangan Domicile (Updated)",
    "file_template": "sk_domicile_new.docx",
    "deskripsi": "Updated description",
    "is_active": false,
    "created_at": "2025-10-28T05:57:48.000000Z"
  }
}
```

### 5. DELETE /admin/jenis-surat/{id}
**Description:** Delete or deactivate document type
**Method:** DELETE
**Auth:** Required (Admin)
**Behavior:** 
- If document type is used in any applications, it will be deactivated instead of deleted
- If not used, it will be permanently deleted
**Response:**
```json
{
  "success": true,
  "message": "Jenis surat berhasil dinonaktifkan (tidak dapat dihapus karena masih digunakan)",
  "data": {
    "id": 1,
    "nama_surat": "Surat Keterangan Domicile",
    "is_active": false
  }
}
```

### 6. PATCH /admin/jenis-surat/{id}/toggle-status
**Description:** Toggle active/inactive status
**Method:** PATCH
**Auth:** Required (Admin)
**Response:**
```json
{
  "success": true,
  "message": "Status jenis surat berhasil diperbarui",
  "data": {
    "id": 1,
    "nama_surat": "Surat Keterangan Domicile",
    "is_active": false
  }
}
```

### 7. GET /jenis-surat/active (Public)
**Description:** Get only active document types (for public use)
**Method:** GET
**Auth:** Optional (citizens can access this)
**Response:**
```json
{
  "success": true,
  "message": "Daftar jenis surat aktif berhasil diambil",
  "data": [
    {
      "id": 1,
      "nama_surat": "Surat Keterangan Domicile",
      "file_template": "sk_domicile.docx",
      "deskripsi": "Surat keterangan tempat tinggal",
      "is_active": true
    }
  ]
}
```

## Error Responses

### 422 Validation Error
```json
{
  "success": false,
  "message": "Validasi gagal",
  "errors": {
    "nama_surat": ["The nama surat field is required."]
  }
}
```

### 403 Access Denied
```json
{
  "message": "Access denied. Required role: admin, User role: warga"
}
```

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\JenisSurat]."
}
```

## Field Descriptions

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `nama_surat` | string | Yes | Name of the document type (max 150 chars) |
| `file_template` | string | No | Template filename for document generation |
| `deskripsi` | text | No | Description of the document type |
| `is_active` | boolean | No | Whether the document type is active (default: true) |

## Business Logic

### Delete Protection
When attempting to delete a document type:
- System checks if it's referenced in any `pengajuan_surat` records
- If referenced: sets `is_active = false` instead of deleting
- If not referenced: performs permanent deletion

### Validation Rules
- `nama_surat` must be unique across all document types
- `file_template` is optional but recommended for document generation
- `is_active` defaults to `true` if not provided

## Security
- All admin endpoints require admin role
- User role is determined through `roleModel` relationship
- Sanctum token-based authentication
- Input validation and sanitization