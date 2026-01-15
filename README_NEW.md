# E-Commerce CRUD & Auth API

A complete Laravel-based RESTful API for e-commerce applications with user authentication, CRUD operations, and relational data management.

## 🚀 Features

✅ **Complete CRUD API** - Full Create, Read, Update, Delete operations for products and buyers
✅ **User Authentication** - Registration and login with token-based authentication (Laravel Sanctum)
✅ **Database Relationships** - One-to-Many relationship between Buyers (Pembelis) and Products (Produks)
✅ **Eloquent ORM** - Proper model relationships using hasMany and belongsTo
✅ **Protected Endpoints** - Secured endpoints requiring authentication tokens
✅ **Public Endpoints** - Open read endpoints for product browsing
✅ **Input Validation** - Comprehensive request validation
✅ **Error Handling** - Proper HTTP status codes and error messages
✅ **SQLite Database** - Configured and ready to use
✅ **Postman Collection** - Ready-to-use API testing collection

## 📋 Requirements

- PHP >= 8.2
- Composer
- SQLite (included with most systems)
- Postman or similar API testing tool (for testing)

## 🔧 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd E-commerce
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Setup
The project uses SQLite by default. Run migrations to create tables:
```bash
php artisan migrate
```

### 5. Start Development Server
```bash
php artisan serve
```

The API will be available at: `http://localhost:8000`

## 📊 Database Schema

### Pembelis Table (Buyers)
| Column | Type | Notes |
|--------|------|-------|
| id | BIGINT | Primary Key |
| nama | VARCHAR | Buyer's name |
| email | VARCHAR | Unique email |
| password | VARCHAR | Hashed password |
| telepon | VARCHAR | Phone number (nullable) |
| alamat | TEXT | Address (nullable) |
| timestamps | | created_at, updated_at |

### Produks Table (Products)
| Column | Type | Notes |
|--------|------|-------|
| id | BIGINT | Primary Key |
| nama | VARCHAR | Product name |
| kode | VARCHAR | Unique product code |
| deskripsi | TEXT | Product description (nullable) |
| harga | DECIMAL | Product price |
| stok | INTEGER | Stock quantity |
| pembeli_id | BIGINT | Foreign Key → pembelis.id |
| timestamps | | created_at, updated_at |

### Relationship
- **One Pembeli has Many Produks**
- Each buyer can have multiple products
- When a buyer is deleted, their products are cascade deleted

## 🔐 Authentication

The API uses **Laravel Sanctum** for token-based API authentication.

### Register
```http
POST /api/pembeli/register
Content-Type: application/json

{
  "nama": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "telepon": "08123456789",
  "alamat": "Jakarta, Indonesia"
}
```

### Login
```http
POST /api/pembeli/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

Both endpoints return a bearer token that should be used for protected endpoints:
```json
{
  "success": true,
  "message": "Login berhasil",
  "token": "your_token_here",
  "data": { ... }
}
```

## 📡 API Endpoints

### Public Endpoints (No Authentication Required)

#### GET /api/pembeli
List all buyers with their products
```bash
curl http://localhost:8000/api/pembeli
```

#### GET /api/pembeli/{id}
Get specific buyer with products
```bash
curl http://localhost:8000/api/pembeli/1
```

#### GET /api/produk
List all products with buyer information
```bash
curl http://localhost:8000/api/produk
```

#### GET /api/produk/{id}
Get specific product with buyer details
```bash
curl http://localhost:8000/api/produk/1
```

### Protected Endpoints (Requires Authentication)

Use header: `Authorization: Bearer YOUR_TOKEN`

#### POST /api/pembeli
Create new buyer
```bash
curl -X POST http://localhost:8000/api/pembeli \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Jane Doe",
    "email": "jane@example.com",
    "telepon": "08123456789",
    "alamat": "Bandung"
  }'
```

#### PATCH /api/pembeli/{id}
Update buyer information

#### DELETE /api/pembeli/{id}
Delete buyer (cascade deletes products)

#### POST /api/produk
Create new product
```bash
curl -X POST http://localhost:8000/api/produk \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Laptop",
    "kode": "LAP001",
    "deskripsi": "High-performance laptop",
    "harga": 15000000,
    "stok": 10,
    "pembeli_id": 1
  }'
```

#### PATCH /api/produk/{id}
Update product information

#### DELETE /api/produk/{id}
Delete product

## 🧪 Testing with Postman

### Quick Start
1. Import the provided `E-Commerce_API.postman_collection.json` into Postman
2. The collection includes:
   - Authentication endpoints (Register & Login)
   - All CRUD operations for Buyers and Products
   - Pre-configured variables (base_url, token, ids)

### Manual Testing Steps

#### 1. Register a New Account
```
Method: POST
URL: http://localhost:8000/api/pembeli/register
Body (JSON):
{
  "nama": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "telepon": "08123456789",
  "alamat": "Test City"
}
```
Response will include a token - copy it for next requests.

#### 2. Set Authorization
In Postman:
- Go to Authorization tab
- Select "Bearer Token" type
- Paste the token from registration response

#### 3. Create a Product
```
Method: POST
URL: http://localhost:8000/api/produk
Headers:
  Authorization: Bearer YOUR_TOKEN
Body (JSON):
{
  "nama": "Smartphone",
  "kode": "PHONE001",
  "deskripsi": "Latest smartphone",
  "harga": 5000000,
  "stok": 25,
  "pembeli_id": 1
}
```

#### 4. Test All CRUD Operations
- **GET** /api/produk - List all
- **GET** /api/produk/1 - Get specific
- **POST** /api/produk - Create new
- **PATCH** /api/produk/1 - Update
- **DELETE** /api/produk/1 - Delete

## 🔄 Eloquent Relationships

### Pembeli Model
```php
public function produk()
{
    return $this->hasMany(Produk::class, 'pembeli_id');
}
```

### Produk Model
```php
public function pembeli()
{
    return $this->belongsTo(Pembeli::class, 'pembeli_id');
}
```

## 📁 Project Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── Api/
│       │   └── AuthController.php        # Auth endpoints
│       ├── PembeliController.php         # Buyer CRUD
│       └── ProdukController.php          # Product CRUD
├── Models/
│   ├── Pembeli.php                       # Buyer model
│   ├── Produk.php                        # Product model
│   └── User.php                          # Default user model
│
database/
├── migrations/
│   ├── *_create_pembelis_table.php
│   ├── *_create_produks_table.php
│   └── *_create_personal_access_tokens_table.php
│
routes/
└── api.php                               # API route definitions

config/
├── auth.php                              # Authentication configuration
└── sanctum.php                           # Sanctum configuration
```

## 🔍 Response Format

### Success Response (200, 201, 202)
```json
{
  "success": true,
  "message": "Operation successful",
  "data": {
    "id": 1,
    "nama": "Product Name",
    ...
  }
}
```

### Error Response (4xx, 5xx)
```json
{
  "success": false,
  "message": "Error description"
}
```

## 🐛 Troubleshooting

### Database file not found
```bash
touch database/database.sqlite
php artisan migrate
```

### Permission denied errors
```bash
chmod -R 775 storage bootstrap/cache
```

### Clear application cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Composer lock file issues
```bash
composer update
```

## 📝 Key Technologies

- **Laravel 12** - Modern PHP framework
- **Laravel Sanctum** - API token authentication
- **SQLite** - Lightweight database
- **Eloquent ORM** - Object-relational mapping
- **PSR-4 Autoloading** - PHP autoloading standard

## 📄 License

This project is open-sourced software licensed under the MIT license.

## 👨‍💻 Development

### Start Development Server
```bash
php artisan serve
```

### Run Tests
```bash
php artisan test
```

### Run Migrations
```bash
php artisan migrate
php artisan migrate:rollback  # To rollback
php artisan migrate:refresh   # To reset
```

### Tinker Shell (Interactive)
```bash
php artisan tinker
# Then test models:
# > App\Models\Pembeli::all()
# > App\Models\Produk::with('pembeli')->get()
```

## 📞 Support

For issues or questions about the API, please check:
- [SETUP_INSTRUCTIONS.md](SETUP_INSTRUCTIONS.md) - Detailed setup guide
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum Documentation](https://laravel.com/docs/sanctum)

---

**Status:** ✅ Production Ready
**Last Updated:** January 15, 2026
**Author:** E-Commerce API Team
