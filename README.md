# CRM Warehouse Management System

Warehouse management system with online payment capabilities.

## Key Features

- User-friendly interface for managing products, warehouses, and clients.
- Structuring products and defining user roles.
- Purchase and sales operations with the use of websockets.
- Online payment in the cashier interface.
- Ability to recover deleted records.

## Screenshots

![Interface Screenshot 1](https://i.imgur.com/6ooS1JK.png)
![Interface Screenshot 2](https://i.imgur.com/O68kY9g.png)
![Interface Screenshot 3](https://i.imgur.com/mKfkazu.png)
![Interface Screenshot 4](https://i.imgur.com/CqQ8ZF6.png)

## Installation

1. Clone the repository: `git clone https://github.com/FilipenkoDanil/crm.git`
2. Navigate to the project directory: `cd crm`
3. Install dependencies: `composer install & npm install`
4. Run migrations: `php artisan migrate --seed`
5. Start the project: `php artisan serve & vite`

## Note
For payment services to work, `APP_URL` must be accessible from the internet, for example, using ngrok or similar tools.
