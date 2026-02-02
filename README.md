# 🍽️ Restaurant Deluxe - Dashboard UI

A modern restaurant management system with a **completely redesigned dashboard-style interface** featuring sidebar navigation, topbar, and card-based layouts.

## 🎨 NEW UI DESIGN FEATURES

### Complete Layout Redesign
This is NOT just a color change - the entire UI structure has been redesigned from the ground up:

#### 1. **Sidebar Navigation** (Desktop App Style)
- Fixed sidebar on the left with dark theme
- Collapsible on mobile devices
- User profile card with avatar
- Sectioned navigation (Main, Management, Account)
- Active state indicators with animated accent bar
- Smooth hover effects
- Cart badge on sidebar items

#### 2. **Topbar Header** (Dashboard Style)
- Sticky top bar with page title
- Quick action buttons (cart icon with badge)
- Clean white background
- Separate from main navigation

#### 3. **Main Content Area**
- Centered content with proper padding
- Offset by sidebar width
- Responsive layout that adapts to screen size
- Card-based design throughout

#### 4. **Menu Cards with Image Areas**
- Each card has a gradient image placeholder section
- Separated content area for details
- Vertical card layout (not horizontal)
- Hover lift animations
- Better visual hierarchy

#### 5. **Dashboard-Style Notifications**
- Animated slide-down entrance
- Dismissible with close button
- Icon indicators for message types
- Gradient backgrounds
- Positioned at top of content area

#### 6. **Modern Color Scheme**
- Dark sidebar (#1a1a2e)
- Clean white content areas
- Pink primary color for accents
- Purple secondary color
- Professional gray text tones

## ✨ Key Design Differences from Original

### Before (Original Design):
- ❌ Top horizontal navigation bar
- ❌ Standard header with logo
- ❌ Simple card layout
- ❌ Traditional website structure
- ❌ No sidebar

### After (New Dashboard Design):
- ✅ Side navigation panel (app-style)
- ✅ Separate topbar with page title
- ✅ Menu cards with image sections
- ✅ Dashboard-style layout
- ✅ Mobile hamburger menu
- ✅ User profile in sidebar
- ✅ Quick actions in topbar
- ✅ Card-based UI throughout

## 🚀 Features

### Customer Features
- Browse menu items in modern card grid
- Add items to cart via sidebar or quick actions
- View orders in dashboard format
- User profile displayed in sidebar
- Search and filter with modern forms

### Admin Features
- Manage menu items from sidebar
- View all orders in dashboard
- Manage categories
- Update order status
- Add/Edit/Delete items

## 📁 Project Structure

```
restaurant_project/
├── config/
│   └── db.php              # Database configuration
├── database/
│   └── schema.sql          # Database schema
├── includes/
│   ├── header.php          # NEW: Sidebar + Topbar layout
│   ├── footer.php          # Updated for dashboard
│   └── functions.php       # Utility functions
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css   # COMPLETELY NEW dashboard CSS
│   │   └── js/
│   │       └── main.js     # JavaScript for interactivity
│   ├── index.php           # Updated with new card structure
│   ├── login.php           # Authentication pages
│   ├── register.php        
│   ├── view_cart.php       
│   ├── checkout.php        
│   ├── my_orders.php       
│   ├── admin_orders.php    
│   ├── add_item.php        
│   ├── edit_item.php       
│   ├── delete_item.php     
│   └── manage_categories.php
└── README.md
```

## 🎯 Installation

### Prerequisites
- PHP 7.4+
- MySQL 5.7+
- Web server (Apache/Nginx)

### Setup Steps

1. **Extract and Setup Database**
   ```bash
   unzip restaurant_project_redesigned.zip
   mysql -u username -p database_name < database/schema.sql
   ```

2. **Configure Database**
   Edit `config/db.php`:
   ```php
   $host = 'localhost';
   $dbname = 'your_database';
   $username = 'your_username';
   $password = 'your_password';
   ```

3. **Access the Application**
   Place in web server directory and navigate to:
   ```
   http://localhost/restaurant_project/public/
   ```

## 🎨 UI Components

### Sidebar Navigation
```
- Logo and branding
- User profile card with avatar
- Navigation sections:
  * Main (Browse Menu, Cart, Orders)
  * Management (Admin only)
  * Account (Login/Logout)
- Active state indicators
- Cart badge counter
```

### Menu Cards
```
- Image placeholder area (gradient)
- Content section with:
  * Item name
  * Category badge
  * Description
  * Price (gradient text)
  * Availability badge
  * Action buttons
```

### Topbar
```
- Page title
- Quick action buttons
- Cart icon with badge
- Responsive design
```

## 📱 Responsive Design

### Desktop (>768px)
- Sidebar visible and fixed
- Main content offset by sidebar width
- Full menu grid (3-4 columns)

### Tablet/Mobile (≤768px)
- Sidebar hidden by default
- Hamburger menu button appears
- Sidebar slides in from left when activated
- Main content takes full width
- Menu grid adapts to single column

## 🎨 Color Variables

```css
--primary: #ff6b9d         /* Pink */
--secondary: #6c5ce7       /* Purple */
--success: #00b894         /* Green */
--warning: #fdcb6e         /* Yellow */
--danger: #d63031          /* Red */

--sidebar-bg: #1a1a2e      /* Dark Navy */
--bg-main: #f8f9fa         /* Light Gray */
--bg-card: #ffffff         /* White */
```

## 🔒 Security

- Bcrypt password hashing
- Prepared SQL statements
- XSS protection
- Session management
- CSRF protection ready

## 💡 Usage Tips

### For Users
1. Login to see sidebar profile
2. Navigate using sidebar menu
3. Cart count shows in two places (sidebar + topbar)
4. Click items to add to cart
5. Notifications appear at top of content

### For Admins
1. Admin badge shows in sidebar profile
2. Management section appears in sidebar
3. Edit/Delete buttons on menu cards
4. Manage orders from dedicated page

## 🆕 What's Different

This redesign changes the **entire structure** of the application:

1. **Layout**: Website → Dashboard App
2. **Navigation**: Top bar → Sidebar
3. **Cards**: Simple → Image + Content sections
4. **Header**: Single bar → Sidebar + Topbar
5. **Style**: Traditional → Modern App UI
6. **UX**: Website flow → Dashboard workflow

## 🛠️ Technologies

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3 (Pure CSS, no frameworks)
- **JavaScript**: Vanilla JS for mobile menu
- **Design**: Custom dashboard UI

## 📄 License

Open source for educational purposes.

## 🎉 Enjoy Your New Dashboard!

This is a complete UI transformation - not just new colors, but an entirely new way to interact with the restaurant management system!
