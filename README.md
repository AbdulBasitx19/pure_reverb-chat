# 💬 Laravel Pure Reverb Chat (No NPM / No Pusher)

A lightweight, production-ready, real-time chat system built with **Laravel Reverb** and **Native Vanilla JavaScript WebSocket API**. 

This project demonstrates how to build a robust real-time application **without** relying on heavy frontend dependencies like `laravel-echo`, `pusher-js`, or `npm`. It uses the browser's native `WebSocket` object for maximum performance and minimal bundle size.

---

## ✨ Key Features

- ⚡ **Pure Native WebSockets**: Zero NPM dependencies for real-time logic. Uses browser's native `new WebSocket()`.
- 🔒 **Private Channel Authorization**: Secure `chat.{min_id}.{max_id}` channels with Laravel broadcasting authentication.
- 🛡️ **Bulletproof UI**: Integrated with Velzon Admin Theme. Features flexbox layout, zero horizontal scrolling, and responsive design.
- 🛡️ **XSS Protection**: Built-in `escapeHtml` helper to prevent Cross-Site Scripting attacks on message rendering.
- 🧠 **Smart Data Parsing**: Handles Reverb's stringified `data.data` payload gracefully, preventing "Unknown" sender or "Invalid Date" bugs.
- 🔍 **Live User Search**: Instant client-side filtering of the user list.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11, PHP 8.2+
- **Real-Time Engine**: Laravel Reverb (First-party WebSocket Server)
- **Frontend**: Vanilla JavaScript (Native WebSocket API), jQuery (DOM manipulation), Bootstrap 5
- **Database**: MySQL
- **UI Theme**: Velzon Admin Dashboard

---

## 🚀 Installation & Setup

### 1. Clone the Repository
```bash
git clone https://github.com/AbdulBasitx19/pure_reverb-chat.git
cd pure_reverb-chat
```
### 2. Install Dependencies and environment setup
```bash
composer install
cp .env.example .env
php artisan key:generate
```
### 3. Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Reverb Configuration
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your_app_id
REVERB_APP_KEY=your_app_key
REVERB_APP_SECRET=your_app_secret
REVERB_HOST="127.0.0.1"
REVERB_PORT=8082
REVERB_SCHEME=http

```bash
php artisan migrate
```

### 4. Running the Application 

# Terminal 1: HTTP Web Server
```bash
php artisan serve
```
# Terminal 2: WebSocket Server (Reverb)
```bash
php artisan reverb:start --port=8082
```

### 🧠 How the Native WebSocket Works (Architecture)
Unlike traditional setups, this project manually handles the Pusher/Reverb protocol:
Connection: new WebSocket('ws://127.0.0.1:8082/app/KEY?protocol=7')
Socket ID Extraction: Listens for pusher:connection_established and parses JSON.parse(data.data).socket_id.
Authentication: Sends a POST request to /broadcasting/auth with the socket_id and channel_name.
Subscription: Upon receiving the auth signature, it sends a pusher:subscribe event back through the open WebSocket.
Message Handling: Listens for message.sent, parses the stringified data.data payload, and safely appends it to the DOM with XSS protection.


### 📄 License
This project is open-sourced software built for educational and portfolio purposes. The Velzon UI theme is subject to its original licensing terms by Themesbrand.

