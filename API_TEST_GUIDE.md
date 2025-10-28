# 🧪 API Test Script - Desa Sungai Meranti

## 🚀 Cara Menjalankan Test

### Menggunakan curl (Command Line)

#### 1. Test Authentication Flow
```bash
# Register User
curl -X POST http://localhost:8080/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "nik": "1234567890123456",
    "nama": "Test User API",
    "email": "apitest@example.com", 
    "password": "password123"
  }'

# Login User (simpan token)
TOKEN=$(curl -X POST http://localhost:8080/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "apitest@example.com",
    "password": "password123"
  }' | jq -r '.token')

echo "Token: $TOKEN"

# Get User Profile
curl -X GET http://localhost:8080/api/user \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"
```

#### 2. Test Letter Management
```bash
# Get Letter Types
curl -X GET http://localhost:8080/api/jenis-surat \
  -H "Accept: application/json"

# Submit Letter Request
curl -X POST http://localhost:8080/api/pengajuan \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "jenis_surat_id": 1,
    "data_pemohon": {
      "nama": "John Doe",
      "nik": "1234567890123456",
      "alamat": "Jl. Test No. 123"
    },
    "keterangan": "Permohonan surat keterangan domisili"
  }'
```

#### 3. Test Admin Functions
```bash
# Get All Requests
curl -X GET http://localhost:8080/api/admin/pengajuan \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json"

# Approve Request (ID: 1)
curl -X POST http://localhost:8080/api/admin/pengajuan/1/approve \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{"catatan": "Permohonan disetujui"}'
```

## 🐍 Python Test Script

```python
import requests
import json
import time

BASE_URL = "http://localhost:8080/api"

class APITester:
    def __init__(self):
        self.token = None
        self.user_id = None
    
    def register_user(self):
        """Test user registration"""
        data = {
            "nik": "9876543210987654",
            "nama": "Python Test User",
            "email": "python@test.com",
            "password": "password123"
        }
        
        response = requests.post(f"{BASE_URL}/register", json=data)
        print(f"Register Status: {response.status_code}")
        print(f"Register Response: {response.json()}")
        return response.status_code == 201
    
    def login_user(self):
        """Test user login"""
        data = {
            "email": "python@test.com",
            "password": "password123"
        }
        
        response = requests.post(f"{BASE_URL}/login", json=data)
        print(f"Login Status: {response.status_code}")
        
        if response.status_code == 200:
            result = response.json()
            self.token = result.get('token')
            print(f"Token: {self.token}")
            return True
        return False
    
    def get_user_profile(self):
        """Test get user profile"""
        headers = {"Authorization": f"Bearer {self.token}"}
        response = requests.get(f"{BASE_URL}/user", headers=headers)
        print(f"Get User Status: {response.status_code}")
        print(f"User Profile: {response.json()}")
        return response.status_code == 200
    
    def test_letter_types(self):
        """Test get letter types"""
        response = requests.get(f"{BASE_URL}/jenis-surat")
        print(f"Letter Types Status: {response.status_code}")
        print(f"Letter Types: {response.json()}")
        return response.status_code == 200
    
    def submit_letter_request(self):
        """Test submit letter request"""
        if not self.token:
            print("No token available")
            return False
            
        headers = {
            "Authorization": f"Bearer {self.token}",
            "Content-Type": "application/json"
        }
        data = {
            "jenis_surat_id": 1,
            "data_pemohon": {
                "nama": "Python Test User",
                "nik": "9876543210987654",
                "alamat": "Jl. Python Test 456"
            },
            "keterangan": "Test permohonan melalui Python script"
        }
        
        response = requests.post(f"{BASE_URL}/pengajuan", json=data, headers=headers)
        print(f"Submit Letter Status: {response.status_code}")
        print(f"Submit Letter Response: {response.json()}")
        return response.status_code == 201
    
    def run_all_tests(self):
        """Run all API tests"""
        print("🚀 Starting API Tests for Desa Sungai Meranti")
        print("=" * 50)
        
        tests = [
            ("User Registration", self.register_user),
            ("User Login", self.login_user),
            ("Get User Profile", self.get_user_profile),
            ("Get Letter Types", self.test_letter_types),
            ("Submit Letter Request", self.submit_letter_request)
        ]
        
        passed = 0
        failed = 0
        
        for test_name, test_func in tests:
            print(f"\n📋 Testing: {test_name}")
            print("-" * 30)
            try:
                if test_func():
                    print(f"✅ {test_name} - PASSED")
                    passed += 1
                else:
                    print(f"❌ {test_name} - FAILED")
                    failed += 1
            except Exception as e:
                print(f"💥 {test_name} - ERROR: {str(e)}")
                failed += 1
        
        print("\n" + "=" * 50)
        print(f"📊 Test Results: {passed} passed, {failed} failed")
        print(f"🎯 Success Rate: {(passed/(passed+failed))*100:.1f}%")

if __name__ == "__main__":
    tester = APITester()
    tester.run_all_tests()
```

## 📊 Expected Test Results

### Successful Response Examples:

#### 1. User Registration (201 Created)
```json
{
  "nik": "1234567890123456",
  "nama": "Test User",
  "email": "test@example.com",
  "role_id": 2,
  "updated_at": "2025-01-28T06:30:00.000Z"
}
```

#### 2. Login (200 OK)
```json
{
  "token": "1|aBcDeFgHiJkLmNoPqRsTuVwXyZ123456789",
  "user": {
    "nik": "1234567890123456",
    "nama": "Test User",
    "email": "test@example.com",
    "role_id": 2
  }
}
```

#### 3. Letter Request Submission (201 Created)
```json
{
  "id": 1,
  "jenis_surat_id": 1,
  "nik_pemohon": "1234567890123456",
  "status": "pending",
  "created_at": "2025-01-28T06:30:00.000Z"
}
```

## 🐛 Common Error Responses

#### 1. Validation Error (422 Unprocessable Entity)
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

#### 2. Authentication Error (401 Unauthorized)
```json
{
  "message": "Unauthenticated."
}
```

#### 3. Not Found Error (404 Not Found)
```json
{
  "message": "No query results for model [App\\Models\\PengajuanSurat]."
}
```

## 📝 Quick Test Checklist

- [ ] Laravel server running on port 8080
- [ ] Database migrated and seeded
- [ ] User can register successfully
- [ ] User can login and get token
- [ ] Authenticated routes work with token
- [ ] Letter types endpoint returns data
- [ ] Letter submission works
- [ ] Admin routes are accessible
- [ ] No errors in Laravel log

---
**Test Script Version**: 1.0  
**Compatible with**: Laravel 10.x API  
**Updated**: 2025-01-28