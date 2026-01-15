# E-Commerce API - Complete Test Guide

This document provides step-by-step instructions to test all API endpoints after deployment.

## Prerequisites

- Laravel development server running: `php artisan serve`
- API accessible at: `http://localhost:8000`
- Postman or cURL installed

---

## 📝 Test Scenarios

### Scenario 1: Complete CRUD Flow

#### Step 1: Register a User
```bash
curl -X POST http://localhost:8000/api/pembeli/register \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Ali Baba",
    "email": "ali.baba@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "telepon": "08123456789",
    "alamat": "Jakarta, Indonesia"
  }'
```

**Expected Response (201):**
```json
{
  "success": true,
  "message": "Pembeli berhasil disimpan",
  "token": "1|YOUR_TOKEN_HERE",
  "data": "Ali Baba"
}
```

**Note:** Copy the token for next requests.

#### Step 2: Login
```bash
curl -X POST http://localhost:8000/api/pembeli/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "ali.baba@example.com",
    "password": "password123"
  }'
```

**Expected Response (202):**
```json
{
  "success": true,
  "message": "Login berhasil",
  "token": "2|YOUR_NEW_TOKEN",
  "data": {
    "id": 1,
    "nama": "Ali Baba",
    "email": "ali.baba@example.com",
    ...
  }
}
```

#### Step 3: Get All Pembeli (Public - No Auth)
```bash
curl http://localhost:8000/api/pembeli
```

**Expected Response (200):**
```json
[
  {
    "id": 1,
    "nama": "Ali Baba",
    "email": "ali.baba@example.com",
    ...
  }
]
```

#### Step 4: Get Pembeli by ID (Public)
```bash
curl http://localhost:8000/api/pembeli/1
```

**Expected Response (200):**
```json
{
  "id": 1,
  "nama": "Ali Baba",
  "email": "ali.baba@example.com",
  "produk": []
}
```

#### Step 5: Create Product (Protected)
```bash
curl -X POST http://localhost:8000/api/produk \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Laptop Gaming",
    "kode": "LAP-GAMING-001",
    "deskripsi": "High-performance gaming laptop",
    "harga": 25000000,
    "stok": 5,
    "pembeli_id": 1
  }'
```

**Expected Response (201):**
```json
{
  "success": true,
  "message": "Data Produk Berhasil Disimpan",
  "data": {
    "id": 1,
    "nama": "Laptop Gaming",
    "kode": "LAP-GAMING-001",
    "harga": 25000000,
    "stok": 5,
    "pembeli_id": 1
  }
}
```

#### Step 6: Get All Products (Public)
```bash
curl http://localhost:8000/api/produk
```

**Expected Response (200):**
```json
[
  {
    "id": 1,
    "nama": "Laptop Gaming",
    "harga": 25000000,
    "pembeli": {
      "id": 1,
      "nama": "Ali Baba",
      ...
    }
  }
]
```

#### Step 7: Get Product by ID (Public)
```bash
curl http://localhost:8000/api/produk/1
```

**Expected Response (200):**
```json
{
  "id": 1,
  "nama": "Laptop Gaming",
  "kode": "LAP-GAMING-001",
  "deskripsi": "High-performance gaming laptop",
  "harga": 25000000,
  "stok": 5,
  "pembeli_id": 1,
  "pembeli": {
    "id": 1,
    "nama": "Ali Baba",
    "email": "ali.baba@example.com",
    ...
  }
}
```

#### Step 8: Update Product (Protected)
```bash
curl -X PATCH http://localhost:8000/api/produk/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Laptop Gaming Pro",
    "stok": 3
  }'
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Data Produk Berhasil Diperbarui",
  "data": {
    "id": 1,
    "nama": "Laptop Gaming Pro",
    "stok": 3,
    ...
  }
}
```

#### Step 9: Delete Product (Protected)
```bash
curl -X DELETE http://localhost:8000/api/produk/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Data Produk Berhasil Dihapus"
}
```

#### Step 10: Update Pembeli (Protected)
```bash
curl -X PATCH http://localhost:8000/api/pembeli/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Ali Baba Updated",
    "telepon": "08987654321"
  }'
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Data Pembeli Berhasil Diperbarui",
  "data": {
    "id": 1,
    "nama": "Ali Baba Updated",
    ...
  }
}
```

#### Step 11: Delete Pembeli (Protected)
```bash
curl -X DELETE http://localhost:8000/api/pembeli/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

**Expected Response (200):**
```json
{
  "success": true,
  "message": "Data Pembeli Berhasil Dihapus"
}
```

---

### Scenario 2: Error Handling Tests

#### Test 404 Not Found
```bash
curl http://localhost:8000/api/pembeli/999
```

**Expected Response (404):**
```json
{
  "success": false,
  "message": "Data tidak ditemukan"
}
```

#### Test 401 Unauthorized (Missing Token)
```bash
curl -X POST http://localhost:8000/api/produk \
  -H "Content-Type: application/json" \
  -d '{"nama": "Test"}'
```

**Expected Response (401):**
```json
{
  "message": "Unauthenticated."
}
```

#### Test 401 Invalid Token
```bash
curl -X POST http://localhost:8000/api/produk \
  -H "Authorization: Bearer INVALID_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"nama": "Test"}'
```

**Expected Response (401):**
```json
{
  "message": "Unauthenticated."
}
```

#### Test 422 Validation Error
```bash
curl -X POST http://localhost:8000/api/pembeli/register \
  -H "Content-Type: application/json" \
  -d '{
    "nama": "Test",
    "email": "invalid-email"
  }'
```

**Expected Response (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field must be a valid email."],
    "password": ["The password field is required."],
    ...
  }
}
```

---

## 🧪 Using Postman

1. **Import Collection:**
   - Open Postman
   - Click "Import"
   - Select `E-Commerce_API.postman_collection.json`

2. **Setup Environment:**
   - Create/select environment
   - Set `base_url = http://localhost:8000`

3. **Run Tests:**
   - Expand "Authentication" folder
   - Run Register request (token auto-captured)
   - Run remaining folders in order

---

## ✅ Expected Results Summary

| Endpoint | Method | Auth | Status | Result |
|----------|--------|------|--------|--------|
| /api/pembeli/register | POST | No | 201 | User created + token |
| /api/pembeli/login | POST | No | 202 | Login successful + token |
| /api/pembeli | GET | No | 200 | Array of buyers |
| /api/pembeli/{id} | GET | No | 200 | Single buyer with products |
| /api/pembeli | POST | Yes | 201 | New buyer created |
| /api/pembeli/{id} | PATCH | Yes | 200 | Buyer updated |
| /api/pembeli/{id} | DELETE | Yes | 200 | Buyer deleted |
| /api/produk | GET | No | 200 | Array of products |
| /api/produk/{id} | GET | No | 200 | Single product with buyer |
| /api/produk | POST | Yes | 201 | New product created |
| /api/produk/{id} | PATCH | Yes | 200 | Product updated |
| /api/produk/{id} | DELETE | Yes | 200 | Product deleted |

---

## 🐛 Common Issues & Solutions

### Issue: 500 Internal Server Error
**Solution:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan migrate:refresh
php artisan serve
```

### Issue: "Unauthenticated" on public endpoints
**Solution:** Some endpoints are incorrectly protected. Check `/routes/api.php`

### Issue: CORS errors
**Solution:** Add CORS headers or use proper client (Postman, same-origin request)

### Issue: Token not working
**Solution:** Token might be expired or invalid. Login again to get new token.

---

## 📊 Performance Tips

- **Batch Tests:** Use Postman collection runner for automated testing
- **Load Testing:** Use `ab` or `wrk` for load testing
- **Profiling:** Enable Laravel Debugbar for performance analysis
- **Database:** Check index optimization for large datasets

---

## 🔐 Security Verification

- [x] Passwords are hashed (bcrypt)
- [x] Tokens are generated securely
- [x] Protected endpoints require authentication
- [x] Input validation prevents SQL injection
- [x] CSRF tokens present (if form requests used)
- [x] Sensitive data not exposed in logs

---

## ✨ Additional Testing

### Database Relationships
```bash
# Test eager loading in Tinker
php artisan tinker

# Check buyer with products
>>> App\Models\Pembeli::with('produk')->first()

# Check product with buyer
>>> App\Models\Produk::with('pembeli')->first()
```

### Token Verification
```bash
# In Tinker
>>> $user = App\Models\Pembeli::first()
>>> $user->createToken('test')->plainTextToken
>>> $user->tokens // View all tokens
```

---

**Test Document:** Complete January 15, 2026
**Version:** 1.0
**Status:** Ready for Production ✅
