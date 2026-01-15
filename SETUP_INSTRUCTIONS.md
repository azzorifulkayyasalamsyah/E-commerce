# E-Commerce API Setup Instructions

## Project Overview
This is a Laravel-based CRUD and Authentication API for an e-commerce application. The project includes:
- Two related tables: `pembelis` (Buyers) and `produks` (Products) with a One-to-Many relationship
- Complete CRUD API endpoints for both resources
- User authentication and registration using Laravel Sanctum
- RESTful API endpoints with proper response formatting

## Requirements
- PHP >= 8.2
- Composer
- SQLite (or MySQL)
- Postman or similar API testing tool

## Installation Steps

### 1. Clone the Repository
```bash
git clone <repository-url>
cd E-commerce
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Create SQLite Database
The database file is automatically created at `database/database.sqlite`

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Start the Development Server
```bash
php artisan serve
```

The API will be available at `http://localhost:8000`

## Database Schema

### Pembelis Table (Buyers)
- id (Primary Key)
- nama (string)
- email (string, unique)
- password (string, hashed)
- telepon (string, nullable)
- alamat (text, nullable)
- timestamps

### Produks Table (Products)
- id (Primary Key)
- nama (string)
- kode (string, unique)
- deskripsi (text, nullable)
- harga (decimal)
- stok (integer)
- pembeli_id (Foreign Key → pembelis.id)
- timestamps

## API Endpoints

### Authentication Endpoints (No Authentication Required)

#### Register
- **POST** `/api/pembeli/register`
- Request Body:
```json
{
  "nama": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "telepon": "08123456789",
  "alamat": "Jakarta, Indonesia"
}
```
- Response (201 Created):
```json
{
  "success": true,
  "message": "Pembeli berhasil disimpan",
  "data": "John Doe",
  "token": "token_here"
}
```

#### Login
- **POST** `/api/pembeli/login`
- Request Body:
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```
- Response (202 Accepted):
```json
{
  "success": true,
  "message": "Login berhasil",
  "token": "token_here",
  "data": {
    "id": 1,
    "nama": "John Doe",
    "email": "john@example.com",
    ...
  }
}
```

### Pembeli (Buyer) Endpoints

#### List All Pembeli (Public)
- **GET** `/api/pembeli`

#### Get Pembeli by ID (Public)
- **GET** `/api/pembeli/{id}`

#### Create Pembeli (Protected)
- **POST** `/api/pembeli`
- Requires: Authorization header with Bearer token
- Request Body:
```json
{
  "nama": "Jane Doe",
  "email": "jane@example.com",
  "telepon": "08198765432",
  "alamat": "Bandung, Indonesia"
}
```

#### Update Pembeli (Protected)
- **PATCH** `/api/pembeli/{id}`
- Requires: Authorization header with Bearer token

#### Delete Pembeli (Protected)
- **DELETE** `/api/pembeli/{id}`
- Requires: Authorization header with Bearer token

### Produk (Product) Endpoints

#### List All Produk (Public)
- **GET** `/api/produk`

#### Get Produk by ID (Public)
- **GET** `/api/produk/{id}`

#### Create Produk (Protected)
- **POST** `/api/produk`
- Requires: Authorization header with Bearer token
- Request Body:
```json
{
  "nama": "Laptop",
  "kode": "LAP001",
  "deskripsi": "High-performance laptop",
  "harga": 15000000,
  "stok": 10,
  "pembeli_id": 1
}
```

#### Update Produk (Protected)
- **PATCH** `/api/produk/{id}`
- Requires: Authorization header with Bearer token

#### Delete Produk (Protected)
- **DELETE** `/api/produk/{id}`
- Requires: Authorization header with Bearer token

## Testing with Postman

### Step 1: Register a New Buyer
1. Create a new POST request to `http://localhost:8000/api/pembeli/register`
2. In Body (raw JSON), add:
```json
{
  "nama": "Test User",
  "email": "test@example.com",
  "password": "password123",
  "password_confirmation": "password123",
  "telepon": "08123456789",
  "alamat": "Test Address"
}
```
3. Send the request and copy the token from the response

### Step 2: Login
1. Create a new POST request to `http://localhost:8000/api/pembeli/login`
2. In Body (raw JSON), add:
```json
{
  "email": "test@example.com",
  "password": "password123"
}
```
3. Copy the token from the response

### Step 3: Set Authorization for Protected Endpoints
1. In Postman, go to the Authorization tab
2. Select "Bearer Token" as the type
3. Paste the token you received

### Step 4: Test CRUD Operations

#### Create Product (Protected)
- POST `/api/produk`
- Body:
```json
{
  "nama": "Smartphone",
  "kode": "PHONE001",
  "deskripsi": "Latest smartphone model",
  "harga": 5000000,
  "stok": 25,
  "pembeli_id": 1
}
```

#### Get All Products (Public)
- GET `/api/produk`

#### Get Product by ID (Public)
- GET `/api/produk/1`

#### Update Product (Protected)
- PATCH `/api/produk/1`
- Body:
```json
{
  "nama": "Smartphone Updated",
  "stok": 20
}
```

#### Delete Product (Protected)
- DELETE `/api/produk/1`

## Eloquent Relationships

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

## Key Features

✅ Complete CRUD API for Pembeli and Produk
✅ One-to-Many relationship between Pembeli and Produk
✅ User authentication with registration and login
✅ API token-based authentication using Laravel Sanctum
✅ Eloquent ORM relationships (hasMany, belongsTo)
✅ Consistent JSON response format
✅ Input validation
✅ Error handling with appropriate HTTP status codes
✅ Protected endpoints requiring authentication
✅ SQLite database configuration

## Troubleshooting

### Database Not Found
```bash
touch database/database.sqlite
php artisan migrate
```

### Permission Errors
```bash
chmod -R 775 storage bootstrap/cache
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Project Structure

```
app/
├── Http/
│   └── Controllers/
│       ├── Api/
│       │   └── AuthController.php
│       ├── PembeliController.php
│       └── ProdukController.php
├── Models/
│   ├── Pembeli.php
│   ├── Produk.php
│   └── User.php
database/
├── migrations/
│   ├── 2025_10_02_045227_create_pembelis_table.php
│   └── 2025_10_02_045305_create_produks_table.php
routes/
└── api.php
```

## License
This project is licensed under the MIT License.
