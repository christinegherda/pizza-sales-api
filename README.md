# 🍕 Pizza Sales Dashboard

A full-stack web application to visualize pizza sales data using **Laravel REST API** and a **Quasar (Vue 3)** frontend dashboard.

---

## 🛠 Tech Stack

- **Backend:** Laravel 10 (REST API)
- **Frontend:** Quasar Framework (Vue 3)
- **Database:** MySQL 
- **Authentication:** Laravel Sanctum (or simple token auth)

---

## ⚙️ Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/pizza-sales-dashboard.git
cd pizza-sales-dashboard
```

---

## 🔧 Backend Setup (Laravel API)

### Navigate to the API folder:
```bash
cd pizza-sales-api
```

### 1. Install Dependencies
```bash
composer install
```

### 2. Configure `.env`
Copy `.env.example` to `.env` and update:

```bash
cp .env.example .env
```

Update DB credentials:
```
DB_DATABASE=pizza_sales
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Generate App Key
```bash
php artisan key:generate
```

### 4. Run Migrations and Seeders
```bash
php artisan migrate --seed
```

Or run a specific seeder:
```bash
php artisan db:seed --class=HardcodedUserSeeder
```

### 5. Serve the Laravel API
```bash
php artisan serve
```

API will be available at:  
📍 http://127.0.0.1:8000

---

## 🎨 Frontend Setup (Quasar)

### Navigate to Quasar frontend folder:
```bash
cd pizza-challenge
```

### 1. Install Node Modules
```bash
npm install
```

### 2. Run the Quasar Dev Server
```bash
quasar dev
```

The app will be available at:  
🌐 http://localhost:9000

### 3. Quasar Notes
Make sure you have the Quasar CLI installed globally:

```bash
npm install -g @quasar/cli
```

---

## 🔐 Authentication

### Hardcoded Login (for demo)

```json
POST /api/login

{
  "email": "admin@admin.com",
  "password": "admin123"
}
```

Returns a token for authenticated requests.  
Pass the token in headers:

```http
Authorization: Bearer your_token_here
```

---

## 📊 Dashboard Features

- ✅ Login Page 
- 🥧 Pizza Performance by Type
- 📋 Orders Table
- 🔍 Search and Filtering

---

## 📁 CSV Import APIs

- `/api/import/pizzas`
- `/api/import/pizza-types`
- `/api/import/orders`
- `/api/import/order-details`

Upload via Postman or custom admin UI (WIP).

---
 

## 📬 Contact

For questions:  
👩‍💻 **Christine Herda**