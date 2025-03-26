# Real-Time Stock Tracking Application

This project is a **real-time stock tracking application** built with Laravel and Highcharts. It allows multiple users to view live stock updates and visualize them using charts.

## Features
- Real-time stock updates using Laravel Reverb
- CRUD operations for stock management
- Interactive charts with Highcharts
- WebSockets integration for instant updates

---

## Installation Guide
### 1. Clone the Repository
```sh
git clone https://github.com/yassinekamouss/Atelier5.git
cd Atelier5
```

### 2. Install Dependencies
```sh
composer install
npm install
npm run build
```

### 3. Copy The Environment File
```sh
cp .env.example .env
```

### 4. Run Migrations
```sh
php artisan migrate
```

### 5. Run Laravel Reverb Server
```sh
php artisan reverb:start
```

### 6. Serve the Application
```sh
php artisan serve
```
---

## Usage
1. Open the application in your browser.
2. Add, update, or delete stocks.
3. See real-time updates reflected in the Highcharts graph.

---

## WebSockets Configuration
Ensure you have set up Laravel Reverb properly:
```sh
php artisan reverb:start
```
Your frontend should listen to updates using:
```js
window.Echo.channel('stock').listen('StockUpdated', (e) => {
    console.log('Stock updated:', e);
});
```

---

## Technologies Used
- Laravel
- Laravel Reverb (WebSockets)
- Pusher
- Highcharts
- Tailwind CSS (Optional for UI)

---