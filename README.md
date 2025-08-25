# Notes API - Backend Developer Test

REST API sederhana untuk sistem CRUD Notes dengan JWT Authentication, dibuat menggunakan Laravel dengan standar PSR-12.

## Features

- [x] **CRUD Operations**: Create, Read, Update, Delete untuk Notes
- [x] **JWT Authentication**: Sistem autentikasi menggunakan JSON Web Tokens
- [x] **User Authorization**: Setiap user hanya bisa mengakses notes miliknya sendiri
- [x] **Security Features**: Rate limiting, security headers, strong password policy
- [x] **Error Handling**: Comprehensive error handling untuk semua endpoint
- [x] **API Documentation**: Swagger/OpenAPI documentation
- [x] **Postman Collection**: Ready-to-use Postman collection untuk testing
- [x] **PSR-12 Compliance**: Code mengikuti standar PSR-12
- [x] **Form Request Validation**: Proper validation menggunakan Laravel Form Requests

## Tech Stack

- **Framework**: Laravel 12.x
- **Database**: MySQL
- **Authentication**: JWT (tymon/jwt-auth)
- **Documentation**: Swagger (darkaonline/l5-swagger)
- **PHP Version**: 8.2+

## Installation

### Prerequisites

- PHP 8.2 atau lebih tinggi
- Composer
- MySQL
- Git

### Setup Instructions

1. **Clone Repository**

   ```bash
   git clone https://github.com/WiMProject/notes-backend.git
   cd notes-backend
   ```

2. **Install Dependencies**

   ```bash
   composer install
   ```

3. **Environment Configuration**

   ```bash
   cp .env.example .env
   ```

   Update konfigurasi database di `.env`:

   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=backend_note
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

4. **Generate Application Key**

   ```bash
   php artisan key:generate
   ```

5. **Setup JWT Secret**

   ```bash
   php artisan jwt:secret
   ```

6. **Create Database**

   ```bash
   mysql -u root -p -e "CREATE DATABASE backend_note;"
   ```

7. **Run Migrations**

   ```bash
   php artisan migrate
   ```

8. **Generate Swagger Documentation**

   ```bash
   php artisan l5-swagger:generate
   ```

9. **Start Development Server**

   ```bash
   php artisan serve
   ```

   API akan tersedia di: `http://localhost:8000`

## API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register user baru |
| POST | `/api/login` | Login user |
| POST | `/api/logout` | Logout user (requires auth) |
| GET | `/api/me` | Get user profile (requires auth) |
| PUT | `/api/update-profile` | Update user profile (requires auth) |
| DELETE | `/api/delete-account` | Delete user account (requires auth) |

### Notes

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/notes` | Get all notes untuk user yang login |
| POST | `/api/notes` | Create note baru |
| GET | `/api/notes/{id}` | Get specific note |
| PUT | `/api/notes/{id}` | Update note |
| DELETE | `/api/notes/{id}` | Delete note |

## API Documentation

### Swagger UI

Akses dokumentasi Swagger di: `http://localhost:8000/api/documentation`

### Postman Collection

Import file `postman_collection.json` ke Postman untuk testing API.

## Usage Examples

Lihat file `api_examples.md` untuk contoh lengkap penggunaan API.

### Quick Start

1. **Register User Baru**

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

2. **Login dan Dapatkan Token**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "wildan@example.com",
    "password": "Password123"
  }'
```

3. **Buat Catatan Baru**

```bash
curl -X POST http://localhost:8000/api/notes \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN" \
  -d '{
    "title": "Catatan Pertama Saya",
    "content": "Ini adalah isi catatan pertama saya"
  }'
```

## Error Handling

API menggunakan standard HTTP status codes:

- `200` - OK
- `201` - Created
- `401` - Unauthorized
- `404` - Not Found
- `422` - Validation Error
- `500` - Internal Server Error

### Format Response

**Response Sukses:**

```json
{
  "success": true,
  "message": "Pesan sukses dalam bahasa Indonesia",
  "data": {
    // data response
  }
}
```

**Response Error:**

```json
{
  "success": false,
  "message": "Pesan error dalam bahasa Indonesia",
  "errors": {
    "field": ["Pesan error spesifik"]
  }
}
```

## Database Schema

### Users Table

- `id` (Primary Key)
- `name` (String)
- `email` (String, Unique)
- `password` (Hashed)
- `created_at` (Timestamp)
- `updated_at` (Timestamp)

### Notes Table

- `id` (Primary Key)
- `title` (String)
- `content` (Text)
- `user_id` (Foreign Key to users.id)
- `created_at` (Timestamp)
- `updated_at` (Timestamp)

## Security Features

- **JWT Authentication**: Secure token-based authentication dengan expiry
- **Password Security**: Strong password policy (min 8 char, huruf besar/kecil/angka) + bcrypt hashing
- **Rate Limiting**: 5 requests/minute untuk login/register, 60 requests/minute untuk API
- **Security Headers**: XSS protection, clickjacking prevention, content type validation
- **User Authorization**: Users hanya bisa mengakses notes milik mereka sendiri
- **Input Validation**: Comprehensive validation dan sanitasi untuk semua input
- **SQL Injection Protection**: Menggunakan Eloquent ORM
- **Error Handling**: Tidak expose sensitive information
- **CORS Support**: Configured untuk cross-origin requests

## Testing

Untuk testing API, gunakan:

1. **Swagger UI**: `http://localhost:8000/api/documentation`
2. **Postman Collection**: Import `postman_collection.json`
3. **cURL**: Gunakan contoh cURL commands di atas

## Code Quality

- [x] **PSR-12 Compliance**: Code mengikuti standar PSR-12
- [x] **Clean Architecture**: Separation of concerns dengan Form Requests, Models, Controllers
- [x] **Error Handling**: Comprehensive error handling di semua layer
- [x] **Documentation**: Lengkap dengan Swagger annotations
- [x] **Security**: Best practices untuk authentication dan authorization

## Development Notes

- **JWT Tokens**: Memiliki expiry time (default: 1 hour) dengan blacklist support
- **Database**: Menggunakan foreign key constraints untuk data integrity
- **Response Format**: Consistent JSON format dengan field `success`, `message`, `data`, dan `errors`
- **Error Messages**: User-friendly dalam bahasa Indonesia
- **PSR-12 Compliance**: Semua kode mengikuti standar PSR-12
- **Validation**: Menggunakan Laravel Form Requests dengan pesan bahasa Indonesia
- **Exception Handling**: Comprehensive error handling untuk semua jenis error
- **Security Middleware**: Custom security headers dan rate limiting
- **Password Policy**: Minimum 8 karakter dengan kombinasi huruf besar, kecil, dan angka

## Author

**Wildan Miladji**  
Dibuat untuk Backend Developer Test - Gencidev

## License

MIT License
