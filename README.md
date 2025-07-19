<span style="color:red">
🚨 Urgent Help Needed! 🚨  
Please help — I’m looking for contributors to join this project.  
Any help is appreciated, whether it's code, design, documentation, or testing.  
If you're interested, please reach out or open a pull request!
</span>

# Flex POS

Flex POS is a lightweight PHP-based point-of-sale and inventory management application. It includes an admin dashboard, customer and order management, and the ability to print receipts using predefined templates.

## Features
- Manage products with barcode search
- Quick product search box in the admin dashboard
- Track clients, orders and outstanding debts
- Print sales receipts through a configurable printer
- User accounts with role-based access and an admin panel
- Dashboard navigation links are shown only for roles the user has
- Notification bell shows count of recent orders
- Mark notifications as read from the admin panel
- Optional dark mode with persistent preference
- All pages include the dark mode toggle
- Dark mode updates Webpixels components for readability
- Upload a custom logo from the settings panel
- Export client or order lists as CSV from the management panels
- Fully localized interface with English and Arabic translations

## Prerequisites
- PHP 7.4 or later with the MySQLi extension
- MySQL database server
- A web server such as Apache or Nginx

## Installation
1. Clone this repository into a directory served by your web server.
2. Give the web server permission to write to the `print` directory.
3. Navigate to `/install/` in your browser and fill out the form with your database host, admin account credentials, company information and preferred language.
4. The installer will create the database, set the company settings and admin user, update `config.php`, and configure initial data.
5. When installation completes, the `install` folder will be removed automatically.
6. *(Optional)* Load sample data for quick testing by executing the SQL files under `scripts/` against your database.

## Configuration
All configuration values are stored in `config.php`. Update the timezone, app ID or database credentials here if needed. You can also adjust printer settings and other options from the admin panel once logged in. The active UI language is controlled by `DEFAULT_LANG` or `$_SESSION['lang']` and translation files under `lang/`.
You can change the interface language at any time from the Settings panel. The
language dropdown writes the selected code to `sittings.lang` so translations
load after refreshing.

All interface text is loaded from the translation files under `lang/`.

## Usage
- Log in using the administrator account created during installation. This user now includes roles for all panels by default.
- Access the admin panel to add products, clients, users and update settings.
- Use the sales screen to create new orders and print receipts.

### Default roles
The administrator account seeded during installation is granted the following roles:

- `admin_panel`
- `salse_panel`
- `orders_panel`
- `clients_panel`
- `users_panel`
- `products_panel`
- `unit_panel`
- `class_panel`
- `settings_panel`
- `client`
- `product`
- `class`
- `unit`
- `user`

These role names correspond to the dashboard pages available via the `p` query parameter.

### Adding roles for new pages
If you introduce a new panel or management page, insert a row into the `role` table for each user who should see it. The `role_name` must match the page identifier in the `p` parameter and the navigation link you add in `assets/theme/admin_panel.php`. See [docs/DEVELOPMENT.md](docs/DEVELOPMENT.md) for more details.

## Customizing the print template
The HTML used for printing receipts lives in `print/template/printtemplate.txt`.
Open the Settings panel and edit it using the built‑in editor, then press
**Save template** to persist your changes. To revert, overwrite the file with a
fresh copy from the repository.
Placeholders such as `@company_name`, `@company_address`, `@company_email` and `@company_phone` will be replaced with the values configured in Settings.

## Running the application
Host the project on your PHP-enabled server and visit `index.php` (e.g., `http://localhost/flexpostemp`). If the application isn't installed yet, you will be redirected to the installer.
## Security
All POST requests require a valid CSRF token. Every form includes a hidden `csrf_token` field generated from the current session. JavaScript that sends data via `$.post()` should either include this token manually or load `assets/js/csrf.js` which appends it automatically.

## Development
For details on setting up a local development environment, see [docs/DEVELOPMENT.md](docs/DEVELOPMENT.md).
Sample SQL scripts are provided in the `scripts/` directory to quickly seed products, clients and orders for testing.
Run `php scripts/dbcheck.php` to verify that your database credentials are valid.

## Database Backups
Create a dump of your database by running:

```bash
php scripts/backup.php
```

The script writes a timestamped SQL file under the `backups/` directory. To
schedule daily backups via cron at 2:00 AM, add a line like the following:

```
0 2 * * * /usr/bin/php /path/to/scripts/backup.php >/dev/null 2>&1
```

Replace `/path/to` with the absolute path to this project.

## License
This project is licensed under the [MIT License](LICENSE).
