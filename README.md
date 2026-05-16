# Uptime Monitor

A Laravel API for monitoring website uptime.

## Requirements
- PHP 8.4+
- Laravel 13.x
- MySQL
- Composer

## Setup

1. Clone the repository
   git clone https://github.com/collinstb01/uptime-monitor.git
   cd uptime-monitor

2. Install dependencies
   composer install

3. Copy environment file
   cp .env.example .env

4. Generate app key
   php artisan key:generate

5. Configure .env
   Set DB_DATABASE , DB_USERNAME , DB_PASSWORD
   Set MAIL_* credentials (use Mailtrap for testing)

6. Run migrations
   php artisan migrate

7. Start the server
   php artisan serve

8. Run the scheduler (in a separate terminal)
   php artisan schedule:work

9. Run the queue worker (in a separate terminal)
   php artisan queue:work

## API Endpoints

### Register a monitor
POST /api/monitors
Content-Type: application/json
{
  "url": "https://example.com",
  "check_interval": 5,
  "threshold": 3
}

### List all monitors
GET /api/monitors

### Get monitor history
GET /api/monitors/{id}/history?page=1&per_page=15

## Testing
php artisan monitors:check