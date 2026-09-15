# 🎨 Velzon Theme with Login UI - Laravel Blade Templating

A clean, production-ready Laravel Blade templating setup extending the base **Velzon Admin & Dashboard Theme** with a fully styled, responsive **Login Page**. 

This repository contains **pure UI only** (no backend authentication logic, database queries, or session handling). It is designed to be the perfect stepping stone for developers who want to integrate Velzon's beautiful auth screens into their Laravel projects before adding custom backend logic.

---

## 📦 IMPORTANT: Download Theme Assets (Required)

Due to GitHub's file size limits, the theme's static assets (CSS, JS, Images, Fonts, and Libraries) are **not** included in this repository. You must download them manually before running the project.

🔗 **Google Drive Link:** [Velzon Theme Assets](https://drive.google.com/drive/folders/1m_QJfs4-TQ0vzx1bCQw_AeKkJceOPkSG?usp=sharing)

### **Setup Instructions:**
1. Download the `assets` folder (or `assets.zip`) from the Drive link above.
2. Extract it (if zipped).
3. Place the `assets` folder directly inside your Laravel project's `public/` directory.
   - **Correct Path:** `your-project/public/assets/` (It must contain `css`, `js`, `images`, `libs`, etc.)

---

## 🚀 Installation & Setup

Follow these steps to get the templating running on your local machine:

### 1. Clone the Repository
```bash
git clone https://github.com/AbdulBasitx19/velzon-theme-with-login-ui.git
cd velzon-theme-with-login-ui
```
### 2.Install PHP Dependencies
composer install
### 3. Setup Environment
# Copy the example environment file
cp .env.example .env

# Generate a new application key
php artisan key:generate

### 4. Download Assets
Follow the "Download Theme Assets" instructions above and place the folder in public/.

### 5. Start the Development Server
php artisan serve

### 6. View the Templates
Open your browser and navigate to:
Dashboard UI: http://127.0.0.1:8000/dashboard
Login UI: http://127.0.0.1:8000/login

###  📂 Project Structure
resources/views/
├── auth/
│   ├── auth-master.blade.php       # Dedicated skeleton for auth pages (centered layout)
│   ├── head-css.blade.php          # Auth-specific CSS includes
│   ├── scripts.blade.php           # Auth-specific JS includes (e.g., password toggle)
│   ├── footer.blade.php            # Auth page footer
│   └── login.blade.php             # The main Login UI page (Form, Alerts, Carousel)
│
└── layouts/
    ├── master.blade.php            # Main dashboard skeleton
    ├── head-css.blade.php          # Global CSS and CDN links
    ├── scripts.blade.php           # Global JS, jQuery, and CDN scripts
    ├── topbar.blade.php            # Header, search, notifications, user dropdown
    ├── sidebar.blade.php           # Navigation menu
    ├── footer.blade.php            # Page footer
    ├── body-tools.blade.php        # Preloader and back-to-top button
    ├── customizer.blade.php        # Theme settings offcanvas panel
    ├── notification-modal.blade.php# Global confirmation modal
    └── pages/
        └── dashboard/
            └── index.blade.php     # Main dashboard view (extends master)

### 📄 License
This templating setup is provided for educational and starter-project purposes. The Velzon theme itself is subject to its original licensing terms by Themesbrand.
Built with ❤️ using Laravel 11+ and clean Blade templating practices.
