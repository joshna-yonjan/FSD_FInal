# 🍽️ Restaurant Deluxe – Dashboard UI

A modern **restaurant management system** redesigned as a **desktop-style dashboard application** with sidebar navigation and a card-based layout.

This project replaces a traditional website layout with a **professional admin/user dashboard experience**.

---

## 🎯 Core Changes

**Before**
- Website-style layout
- Top navigation bar

**Now**
- Desktop-style dashboard
- Left sidebar navigation
- Topbar with quick actions
- Card-based content layout

---

## 🎨 Key UI Features

### 📌 Sidebar Navigation
- Dark theme with user profile
- Sectioned menu:
  - **Main**
  - **Management**
  - **Account**
- Active menu indicators
- Cart badge counters

### 📊 Dashboard Layout
- Topbar with page title & quick actions
- Card-based content grid
- Menu cards with image placeholders
- Clean and professional color scheme

### 📱 Responsive Design
- **Desktop (>768px)**: Fixed sidebar
- **Mobile (<768px)**:
  - Sidebar hidden
  - Accessible via hamburger menu
  - Cards stack vertically

---

## 📁 Project Structure

```
restaurant_project/
├── includes/        # Sidebar, footer, reusable functions
├── public/          # Main pages, CSS, JS, assets
└── database/        # Database schema & configuration
```

---

## 🚀 Quick Setup

1. Import the database:
   ```sql
   database/schema.sql
   ```

2. Configure database connection:
   ```php
   config/db.php
   ```

3. Run the project:
   ```
   http://localhost/restaurant_project/public/
   ```

---

## 🔐 Default Admin Login Credentials

```
Username: admin
Password: admin123
```

⚠️ **Change the admin password after first login for security reasons.**

---

## 🎨 Color Palette

| Element     | Color |
|------------|-------|
| Primary    | #ff6b9d |
| Sidebar    | #1a1a2e |
| Background | #f8f9fa |
| Cards      | #ffffff |

---

## 🔒 Security Features

- Bcrypt password hashing
- Prepared statements (PDO)
- Secure session handling
- Basic XSS protection

---

## 💡 Usage

### Users
- Browse menu items
- Add items to cart
- View orders

### Admins
- Manage menu items
- Manage orders
- Access management tools via sidebar

---

## ✨ New Look

A clean, professional dashboard UI that feels like a desktop application rather than a traditional website.

---

**Built with ❤️ using PHP, MySQL, HTML, CSS, and JavaScript**
