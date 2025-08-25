# Notes API - Dokumentasi Teknis
**Backend Developer Test - PT. Gencidev Prisma Teknologi**

**Dibuat oleh:** Wildan Miladji  
**Tanggal:** 25 Agustus 2025  
**Framework:** Laravel 12.x  
**PHP Version:** 8.2+

---

## 1. OVERVIEW

### 1.1 Deskripsi Proyek
Notes API adalah REST API sederhana untuk sistem CRUD (Create, Read, Update, Delete) Notes dengan JWT Authentication. API ini dibuat menggunakan Laravel framework dengan mengikuti standar PSR-12 dan best practices untuk keamanan dan performa.

### 1.2 Fitur Utama
- [x] **CRUD Operations** untuk Notes
- [x] **JWT Authentication** dengan token expiry
- [x] **User Authorization** (data isolation)
- [x] **Security Features** (rate limiting, security headers)
- [x] **Input Validation** dengan Form Requests
- [x] **Error Handling** comprehensive
- [x] **API Documentation** dengan Swagger UI
- [x] **PSR-12 Compliance**

### 1.3 Tech Stack
- **Framework:** Laravel 12.x
- **Database:** MySQL 8.0+
- **Authentication:** JWT (tymon/jwt-auth)
- **Documentation:** Swagger (darkaonline/l5-swagger)
- **Frontend:** Blade Templates + Tailwind CSS

---

## 2. ARSITEKTUR SISTEM

### 2.1 Struktur Direktori
```
backend-note/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── NoteController.php
│   │   │   └── Controller.php
│   │   ├── Requests/
│   │   │   ├── RegisterRequest.php
│   │   │   ├── LoginRequest.php
│   │   │   ├── StoreNoteRequest.php
│   │   │   └── UpdateNoteRequest.php
│   │   └── Middleware/
│   │       └── SecurityHeaders.php
│   └── Models/
│       ├── User.php
│       └── Note.php
├── database/
│   └── migrations/
├── routes/
│   ├── api.php
│   └── web.php
└── resources/
    └── views/
        └── welcome.blade.php
```

### 2.2 Database Schema

#### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### Notes Table
```sql
CREATE TABLE notes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 3. API ENDPOINTS

### 3.1 Authentication Endpoints

#### POST /api/register
**Deskripsi:** Mendaftarkan user baru  
**Request Body:**
```json
{
    "name": "Wildan Miladji",
    "email": "wildan@example.com",
    "password": "Password123",
    "password_confirmation": "Password123"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "Pendaftaran user berhasil",
    "data": {
        "user": {
            "id": 1,
            "name": "Wildan Miladji",
            "email": "wildan@example.com",
            "created_at": "2025-08-25T10:00:00.000000Z"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
    }
}
```

#### POST /api/login
**Deskripsi:** Login user dan mendapatkan JWT token  
**Request Body:**
```json
{
    "email": "wildan@example.com",
    "password": "Password123"
}
```

**Response (200):**
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

#### GET /api/me
**Deskripsi:** Mendapatkan profil user yang sedang login  
**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Data profil berhasil diambil",
    "data": {
        "user": {
            "id": 1,
            "name": "Wildan Miladji",
            "email": "wildan@example.com",
            "created_at": "2025-08-25T10:00:00.000000Z",
            "updated_at": "2025-08-25T10:00:00.000000Z"
        }
    }
}
```

#### PUT /api/update-profile
**Deskripsi:** Update profil user  
**Headers:** `Authorization: Bearer {token}`  
**Request Body:**
```json
{
    "name": "Wildan Miladji Updated",
    "email": "wildan.updated@example.com",
    "password": "NewPassword123",
    "password_confirmation": "NewPassword123"
}
```

#### POST /api/logout
**Deskripsi:** Logout user dan invalidate token  
**Headers:** `Authorization: Bearer {token}`

#### DELETE /api/delete-account
**Deskripsi:** Hapus akun user dan semua data terkait  
**Headers:** `Authorization: Bearer {token}`

### 3.2 Notes CRUD Endpoints

#### GET /api/notes
**Deskripsi:** Mendapatkan semua notes milik user yang login  
**Headers:** `Authorization: Bearer {token}`

**Response (200):**
```json
{
    "success": true,
    "message": "Daftar catatan berhasil diambil",
    "data": {
        "notes": [
            {
                "id": 1,
                "title": "Catatan Pertama Saya",
                "content": "Ini adalah isi catatan pertama saya",
                "user_id": 1,
                "created_at": "2025-08-25T10:10:00.000000Z",
                "updated_at": "2025-08-25T10:10:00.000000Z"
            }
        ],
        "total": 1
    }
}
```

#### POST /api/notes
**Deskripsi:** Membuat note baru  
**Headers:** `Authorization: Bearer {token}`  
**Request Body:**
```json
{
    "title": "Catatan Pertama Saya",
    "content": "Ini adalah isi dari catatan pertama saya untuk test Backend Developer di Gencidev"
}
```

#### GET /api/notes/{id}
**Deskripsi:** Mendapatkan detail note berdasarkan ID  
**Headers:** `Authorization: Bearer {token}`

#### PUT /api/notes/{id}
**Deskripsi:** Update note berdasarkan ID  
**Headers:** `Authorization: Bearer {token}`  
**Request Body:**
```json
{
    "title": "Judul Catatan yang Diperbarui",
    "content": "Ini adalah isi catatan yang telah diperbarui"
}
```

#### DELETE /api/notes/{id}
**Deskripsi:** Hapus note berdasarkan ID  
**Headers:** `Authorization: Bearer {token}`

---

## 4. IMPLEMENTASI KODE

### 4.1 AuthController.php
```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran user berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at,
                ],
                'token' => $token,
            ],
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password tidak valid',
                'errors' => [
                    'credentials' => ['Kombinasi email dan password tidak ditemukan']
                ]
            ], 401);
        }

        $user = auth()->user();
        
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'token' => $token,
            ],
        ]);
    }

    // ... method lainnya
}
```

### 4.2 NoteController.php
```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\JsonResponse;

class NoteController extends Controller
{
    public function index(): JsonResponse
    {
        $notes = auth()->user()->notes()->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar catatan berhasil diambil',
            'data' => [
                'notes' => $notes,
                'total' => $notes->count(),
            ],
        ]);
    }

    public function store(StoreNoteRequest $request): JsonResponse
    {
        try {
            $note = auth()->user()->notes()->create([
                'title' => $request->title,
                'content' => $request->content,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil dibuat',
                'data' => [
                    'note' => $note,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat catatan',
                'errors' => [
                    'server' => ['Terjadi kesalahan pada server']
                ]
            ], 500);
        }
    }

    // ... method lainnya
}
```

### 4.3 Models

#### User.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }
}
```

#### Note.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

### 4.4 Form Requests

#### RegisterRequest.php
```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Http\Exceptions\HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Data yang dikirim tidak valid',
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
```

---

## 5. KEAMANAN (SECURITY)

### 5.1 Fitur Keamanan Implementasi

#### JWT Authentication
- **Token Expiry:** 1 jam (configurable)
- **Token Blacklist:** Untuk logout dan security
- **Secure Headers:** Authorization Bearer token

#### Rate Limiting
```php
// Login/Register: 5 requests per minute
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// API Endpoints: 60 requests per minute
Route::middleware(['auth:api', 'throttle:60,1'])->group(function () {
    // Protected routes
});
```

#### Security Headers Middleware
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
```

#### Password Policy
- **Minimum:** 8 karakter
- **Complexity:** Huruf besar, huruf kecil, dan angka
- **Hashing:** bcrypt dengan salt

### 5.2 Data Protection
- **SQL Injection:** Eloquent ORM protection
- **XSS Protection:** Security headers dan input sanitization
- **CSRF Protection:** API menggunakan stateless authentication
- **Data Isolation:** User hanya bisa akses data miliknya sendiri

---

## 6. ERROR HANDLING

### 6.1 HTTP Status Codes
- **200:** OK - Request berhasil
- **201:** Created - Resource berhasil dibuat
- **401:** Unauthorized - Authentication required
- **404:** Not Found - Resource tidak ditemukan
- **422:** Validation Error - Input tidak valid
- **429:** Too Many Requests - Rate limit exceeded
- **500:** Internal Server Error - Server error

### 6.2 Response Format
**Success Response:**
```json
{
    "success": true,
    "message": "Pesan sukses dalam bahasa Indonesia",
    "data": {
        // data response
    }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Pesan error dalam bahasa Indonesia",
    "errors": {
        "field": ["Pesan error spesifik"]
    }
}
```

### 6.3 Exception Handling
```php
// JWT Token Expired
$exceptions->render(function (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
    return response()->json([
        'success' => false,
        'message' => 'Token sudah kedaluwarsa',
        'errors' => [
            'token' => ['Silakan login ulang untuk mendapatkan token baru']
        ]
    ], 401);
});

// JWT Token Invalid
$exceptions->render(function (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
    return response()->json([
        'success' => false,
        'message' => 'Token tidak valid',
        'errors' => [
            'token' => ['Format token tidak sesuai atau token rusak']
        ]
    ], 401);
});
```

---

## 7. TESTING & DOKUMENTASI

### 7.1 Swagger UI Documentation
- **URL:** `http://localhost:8000/api/documentation`
- **Interactive Testing:** Semua endpoint bisa ditest langsung
- **Authorization:** Built-in Bearer token authentication
- **Examples:** Request/response examples untuk setiap endpoint

### 7.2 Postman Collection
- **File:** `postman_collection.json`
- **Environment Variables:** Automatic token management
- **Pre-request Scripts:** Token refresh handling
- **Test Scripts:** Response validation

### 7.3 Frontend Landing Page
- **URL:** `http://localhost:8000`
- **Features:**
  - API overview dan quick links
  - Interactive API connection test
  - Download Postman collection
  - Responsive design dengan Tailwind CSS

---

## 8. DEPLOYMENT

### 8.1 Requirements
- **PHP:** 8.2 atau lebih tinggi
- **Database:** MySQL 8.0+
- **Web Server:** Nginx/Apache
- **SSL Certificate:** Untuk production

### 8.2 Environment Configuration
```env
APP_NAME="Notes API"
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_note
DB_USERNAME=your_username
DB_PASSWORD=your_password

JWT_SECRET=your_jwt_secret
JWT_TTL=60

CACHE_STORE=file
```

### 8.3 Deployment Steps
1. **Clone Repository**
2. **Install Dependencies:** `composer install --optimize-autoloader --no-dev`
3. **Environment Setup:** Configure `.env` file
4. **Generate Keys:** `php artisan key:generate` dan `php artisan jwt:secret`
5. **Database Migration:** `php artisan migrate`
6. **Generate Documentation:** `php artisan l5-swagger:generate`
7. **Optimize:** `php artisan config:cache` dan `php artisan route:cache`

---

## 9. KESIMPULAN

### 9.1 Pencapaian
[x] **API Lengkap:** 11 endpoints dengan full CRUD functionality  
[x] **Security:** Production-ready dengan multiple security layers  
[x] **Documentation:** Comprehensive dengan Swagger UI dan Postman  
[x] **Code Quality:** PSR-12 compliant dengan clean architecture  
[x] **Error Handling:** Robust error handling di semua layer  
[x] **Testing:** Multiple testing methods tersedia  

### 9.2 Best Practices Implemented
- **RESTful API Design**
- **JWT Authentication dengan proper token management**
- **Input validation dan sanitization**
- **Rate limiting untuk security**
- **Comprehensive error handling**
- **Security headers implementation**
- **Database relationship dengan foreign keys**
- **Consistent response format**
- **Indonesian error messages untuk user experience**

### 9.3 Performance & Scalability
- **Eloquent ORM** untuk database optimization
- **Caching strategy** untuk improved performance
- **Rate limiting** untuk prevent abuse
- **Stateless authentication** untuk horizontal scaling
- **Clean architecture** untuk maintainability

---

**Dokumentasi ini menunjukkan implementasi Notes API yang professional, secure, dan production-ready untuk Backend Developer Test di PT. Gencidev Prisma Teknologi.**

**Dibuat oleh Wildan Miladji**