# Contoh Penggunaan API Notes

## Base URL
```
http://localhost:8000/api
```

## 1. Authentication

### Register User Baru
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Wildan Miladji",
    "email": "wildan@example.com",
    "password": "Password123",
    "password_confirmation": "Password123"
  }'
```

**Response Sukses (201):**
```json
{
  "success": true,
  "message": "Pendaftaran user berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Wildan Miladji",
      "email": "wildan@example.com",
      "created_at": "2025-08-24T10:07:47.000000Z"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}
```

### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "wildan@example.com",
    "password": "Password123"
  }'
```

**Response Sukses (200):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "data": {
    "user": {
      "id": 1,
      "name": "Wildan Miladji",
      "email": "wildan@example.com"
    },
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
  }
}
```

### Get Profile
```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Logout
```bash
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

## 2. Notes CRUD

### Get All Notes
```bash
curl -X GET http://localhost:8000/api/notes \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

**Response Sukses (200):**
```json
{
  "success": true,
  "message": "Daftar catatan berhasil diambil",
  "data": {
    "notes": [
      {
        "id": 1,
        "title": "Catatan Pertama",
        "content": "Ini adalah isi catatan pertama",
        "user_id": 1,
        "created_at": "2025-08-24T10:10:00.000000Z",
        "updated_at": "2025-08-24T10:10:00.000000Z"
      }
    ],
    "total": 1
  }
}
```

### Create Note
```bash
curl -X POST http://localhost:8000/api/notes \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "title": "Catatan Pertama Saya",
    "content": "Ini adalah isi dari catatan pertama saya. Catatan ini dibuat menggunakan API Notes yang telah saya buat untuk test Backend Developer di Gencidev."
  }'
```

### Get Single Note
```bash
curl -X GET http://localhost:8000/api/notes/1 \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Update Note
```bash
curl -X PUT http://localhost:8000/api/notes/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "title": "Judul Catatan yang Diperbarui",
    "content": "Ini adalah isi catatan yang telah diperbarui."
  }'
```

### Delete Note
```bash
curl -X DELETE http://localhost:8000/api/notes/1 \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

## 3. Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Data yang dikirim tidak valid",
  "errors": {
    "email": ["Email wajib diisi"],
    "password": ["Password minimal 8 karakter", "Password harus mengandung huruf besar, huruf kecil, dan angka"]
  }
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Token sudah kedaluwarsa",
  "errors": {
    "token": ["Silakan login ulang untuk mendapatkan token baru"]
  }
}
```

### Not Found (404)
```json
{
  "success": false,
  "message": "Catatan tidak ditemukan",
  "errors": {
    "note": ["Catatan dengan ID tersebut tidak ditemukan atau bukan milik Anda"]
  }
}
```

### Rate Limiting (429)
```json
{
  "message": "Too Many Attempts.",
  "exception": "Illuminate\\Http\\Exceptions\\ThrottleRequestsException"
}
```

## 4. Security & Rate Limits

- **Login/Register**: Maksimal 5 requests per menit
- **API Endpoints**: Maksimal 60 requests per menit
- **Password Policy**: Minimal 8 karakter dengan huruf besar, kecil, dan angka
- **Security Headers**: XSS protection, clickjacking prevention

## 5. Testing dengan Postman

1. Import file `postman_collection.json` ke Postman
2. Jalankan request "Register" atau "Login" untuk mendapatkan token
3. Token akan otomatis tersimpan di collection variable
4. Test semua endpoint Notes dengan token yang sudah tersimpan

## 5. Testing dengan Swagger UI

Akses dokumentasi interaktif di: `http://localhost:8000/api/documentation`

1. Klik "Authorize" di pojok kanan atas
2. Masukkan token dengan format: `Bearer YOUR_JWT_TOKEN`
3. Test semua endpoint langsung dari browser