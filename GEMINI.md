# GEMINI.md

## Project Overview

**Company Syafa Fiks** is an integrated management information system for **Syafa Group** (Contractor & Supplier). The application handles construction project operations, inventory management for the food division (Eco), financial reporting for subcontractors, and a public news portal.

- **Main Technologies:** Laravel 12, PHP 8.2+, Tailwind CSS 4.x (via Vite), Alpine.js, MySQL.
- **Key Features:**
    - **Multi-Role Authentication:** Role-based access control for Admin, Keuangan, Subkon Eks (Vendor), Eco (Food Division), Subkon PT (Internal), Kepala Kantor, and Manager Unit.
    - **Financial & Claim Module:** Maker-Checker workflow for subcontractor payment claims.
    - **Eco Module:** Real-time monitoring of warehouse, factory, and store stock (pangan/food).
    - **Dashboard:** Interactive analytics using ApexCharts.js.
    - **Reporting:** PDF and Excel exports for various operational reports.

## Building and Running

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL

### Setup Commands
The project includes a custom setup script in `composer.json`:
```bash
composer run setup
```
This command performs the following:
1. Installs Composer dependencies.
2. Copies `.env.example` to `.env` (if not exists).
3. Generates the application key.
4. Runs database migrations (`php artisan migrate --force`).
5. Installs NPM packages.
6. Builds frontend assets (`npm run build`).

### Development
To start the development environment (Vite, Artisan server, Queue listener):
```bash
npm run dev
```

### Testing
Run the test suite using PHPUnit:
```bash
php artisan test
```

## Architecture & Conventions

### Authentication & Authorization
- **Middleware:** Access is controlled via the `role` middleware alias (mapped to `App\Http\Middleware\EnsureUserHasRole`).
- **Roles:** `admin`, `eco`, `indie`, `subkon_pt`, `subkon_eks`, `keuangan_indie`, `keuangan_eco`, `kepala_kantor`, `manager_unit`, `manager_wilayah`, `manager_unit_indie`, `kepala_kantor_indie`.
- **Routing:** Routes are organized by role prefixes and namespacing in `routes/web.php`.

### Directory Structure
- `app/Http/Controllers/`: Scoped by role (e.g., `Admin/`, `Eco/`, `SubkonEks/`).
- `app/Models/`: Standard Eloquent models.
- `resources/views/`: Blade templates organized by role/module.
- `database/migrations/`: Database schema definitions.

### Coding Standards
- **Frontend:** Tailwind CSS for styling, Alpine.js for lightweight interactivity.
- **Icons:** FontAwesome 6.
- **Alerts:** SweetAlert2 for notifications.
- **Charts:** ApexCharts.js for dashboard visualizations.
- **Code Style:** Laravel Pint is used for PHP code styling (referenced in `composer.json`).
