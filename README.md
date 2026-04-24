# Staff Management Module - Restro Order SaaS

A powerful staff management system for the **Restro Order SaaS** platform, built with **Laravel** (backend) and **Vue 3 + Inertia.js** (frontend).

## Features

-  Add, Edit, View & Delete Restaurant Staff
-  Bulk Import Staff via CSV file
-  Toggle Staff Active/Inactive Status
-  Search staff by name or email
-  Responsive Data Table with CoreUI Components
-  Queued Bulk Import for better performance
-  Role & Permission ready structure (extendable)
-  Avatar support

## Tech Stack

- **Backend**: Laravel 10/11
- **Frontend**: Vue 3 (Composition API) + Inertia.js
- **UI Library**: CoreUI for Vue
- **Import Library**: Spatie SimpleExcel + OpenSpout
- **Queue**: Laravel Queue (for bulk import)

## Project Structure (Relevant Files)
app/
├── Jobs/
│   └── BulkStaffCreateJob.php         
routes/
├── web.php
app/Http/Controllers/
├── StaffController.php
resources/js/Pages/
├── Staffs/
│   └── Index.vue

## Commands:

## PROJECT INITALIZATION:
composer install
npm install

composer install
npm install

php artisan migrate

npm run dev
# or for production
npm run build

php artisan queue:work


## CSV FILE FORMAT
name,email,phone,password,status
John Doe,john@example.com,password

