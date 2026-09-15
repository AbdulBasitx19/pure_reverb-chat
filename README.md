# 🎨 Velzon Admin Theme - Laravel Blade Template

A clean, production-ready Laravel Blade templating setup for the **Velzon Admin & Dashboard Theme**. This repository contains a decoupled, pure-UI layout structure (Master, Sidebar, Topbar, Footer, etc.) without any backend logic, authentication, or database dependencies. It serves as a perfect starter template for new Laravel projects.

## 📦 Step 1: Download Theme Assets (Required)

Due to GitHub file size limits, the theme's static assets (CSS, JS, Images, Fonts, and Libraries) are hosted externally. You must download them before running the project.

🔗 **Download Assets Here:** [Velzon Theme Assets (Google Drive)](https://drive.google.com/drive/folders/1m_QJfs4-TQ0vzx1bCQw_AeKkJceOPkSG?usp=sharing)

**Instructions:**
1. Download the `assets` folder (or `assets.zip`) from the Drive link above.
2. Extract it (if zipped).
3. Place the `assets` folder directly inside your Laravel project's `public/` directory.
   - **Correct Path:** `your-project/public/assets/` (It should contain `css`, `js`, `images`, `libs`, etc.)

## 🚀 Step 2: Installation & Setup

Once the assets are in the `public/` folder, follow these steps to get the template running:

```bash
# 1. Install PHP dependencies
composer install

# 2. Setup environment variables
cp .env.example .env

# 3. Generate application key
php artisan key:generate

# 4. Start the local development server
php artisan serve


## 👀 Step 3: View the Template
Open your browser and navigate to the dashboard route:
👉 http://127.0.0.1:8000/dashboard

##  📂 Project Structure

resources/views/
└── layouts/
    ├── master.blade.php              # Main skeleton (extends all partials)
    ├── head-css.blade.php            # Global CSS and CDN links
    ├── scripts.blade.php             # Global JS, jQuery, and CDN scripts
    ├── topbar.blade.php              # Header, search, notifications, user dropdown
    ├── sidebar.blade.php             # Navigation menu
    ├── footer.blade.php              # Page footer
    ├── body-tools.blade.php          # Preloader and back-to-top button
    ├── customizer.blade.php          # Theme settings offcanvas panel
    ├── notification-modal.blade.php  # Global confirmation modal
    └── pages/
        └── dashboard/
            └── index.blade.php       # Main dashboard view (extends master)

##  🛠️ Developer Notes
Pure UI: The template contains zero backend logic, no auth() checks, and no @can/@role directives. It is strictly HTML/Blade.
Routing: The sidebar currently uses href="#" for navigation links to prevent "Route not defined" errors in a fresh setup. Replace # with {{ route('your.route.name') }} as you build your controllers.
Dynamic Data: The user dropdown currently displays a static "Admin User". Replace this with {{ auth()->user()->name }} once you implement authentication.
Page-Specific Assets: Use @section('script') and @section('page-css') in your child views to inject page-specific JavaScript and CSS without bloating the master layout.
