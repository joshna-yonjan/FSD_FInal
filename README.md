🍽️ Restaurant Deluxe - Dashboard UI

A modern restaurant management system redesigned as a dashboard-style app with sidebar navigation and card-based layout.

🎯 Core Changes

Before: Website layout with top navigation
Now: Desktop-style dashboard with left sidebar + topbar

🎨 Key UI Features

Sidebar Navigation

Dark theme with user profile
Sectioned menu (Main, Management, Account)
Active state indicators
Cart badge counters
Dashboard Layout

Topbar with page title & quick actions
Card-based content grid
Menu cards with image areas
Professional color scheme
Responsive Design

Desktop: Fixed sidebar
Mobile: Hamburger menu
Cards stack on smaller screens
📁 Project Structure

text
restaurant_project/
├── includes/          # Sidebar, footer, functions
├── public/           # Main pages + assets
└── database/         # Schema & config
🚀 Quick Setup

Import database/schema.sql
Configure config/db.php
Access via http://localhost/restaurant_project/public/
🎨 Colors

Primary: Pink (#ff6b9d)
Sidebar: Dark Navy (#1a1a2e)
Background: Light Gray (#f8f9fa)
Cards: White (#ffffff)
📱 Responsive

Desktop (>768px): Sidebar visible
Mobile: Sidebar hidden, accessible via hamburger menu
🔒 Security Features

Bcrypt password hashing
Prepared statements
Session management
XSS protection
💡 Usage

Users: Browse menu, add to cart, view orders
Admins: Manage items & orders via sidebar Management section
New Look: Professional dashboard instead of traditional website layout.
