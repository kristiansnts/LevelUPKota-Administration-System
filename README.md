# LevelUpKota Administration System

## About

LevelUpKota Administration System is a web application that allows administrators to manage the LevelUpKota management system. Built with Laravel and Filament, it provides a robust and user-friendly interface for system administration.

## Tech Stack

- PHP 8.2+
- Laravel 11.x
- Filament 3.x
- SQLite (Testing)
- MySQL/PostgreSQL (Development)

## Features

- User Management System
- Role-Based Access Control (via Filament Shield)
- Modern Admin Interface
- Database Management Tools
- Secure Authentication
- Customizable Admin Panels
- Google Drive Integration
- Google Socialite Authentication

## Prerequisites

- PHP >= 8.2
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

## Installation

1. Clone the repository:
```sh
git clone <repository-url>
cd LevelUPKota-Administration-System
```

2. Install PHP dependencies:
```sh
composer install
```

3. Copy environment file:
```sh
cp .env.example .env
```

4. Generate application key:
```sh
php artisan key:generate
```

5. Configure your database in `.env`:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=levelupkota
DB_USERNAME=root
DB_PASSWORD=password
```

6. Run migrations and seed the database:
```sh
php artisan migrate:fresh --seed
```


## Filament Shield

Filament Shield is a powerful package for managing role-based access control within Filament. It allows fine-grained permission management through an intuitive UI and provides Artisan commands to quickly configure and apply permissions.

More information can be found at: [Filament Shield Plugin](https://filamentphp.com/plugins/bezhansalleh-shield)


## Creating a Filament Resource

In the LevelUpKota Administration System, all Filament resources are placed under the `admin` panel directory.

To create a new Filament resource within the `admin` panel, run:
```sh
php artisan make:filament-resource ResourceName --panel=admin
```
This will generate the necessary files under `app/Filament/Admin/Resources/ResourceNameResource.php`.


## Default Admin Credentials

After seeding the database, you can log in with:
- **Email**: akundemo@gmail.com
- **Password**: demo

## Development

We use GoogleDrive Integration so you must add 
- **GOOGLE_DRIVE_CLIENT_ID**=your-drive-client-id
- **GOOGLE_DRIVE_CLIENT_SECRET**=your-drive-client-secret
- **GOOGLE_DRIVE_REFRESH_TOKEN**=your-drive-refresh-token
- **GOOGLE_DRIVE_SHARED_FOLDER_ID**=your-drive-shared-folder
- **GOOGLE_DRIVE_FOLDER**=your-drive-folder

you can get them by following :
[Flysystem Google Drive Ext from masbug](https://github.com/masbug/flysystem-google-drive-ext)

### Running the Application

Start the Laravel development server:
```sh
php artisan serve
```

Visit [http://localhost:8000/admin/login](http://localhost:8000/admin/login) to access the admin panel.

### Testing

Run tests using:
```sh
php artisan test
```

## Contributing

### Contribution Workflow

1. Create a new branch:
```sh
git checkout -b feature/your-feature-name
```

2. Make your changes and commit using Semantic Release rules:
```sh
git commit -m "feat: add new feature"
```

3. Push to your branch and create a pull request.

### Semantic Release Commit Messages

Follow the **Conventional Commits** specification to ensure consistent commit messages:

- `feat:` A new feature
- `fix:` Fixing something
- `chore:` Maintenance or tooling changes

## Security

If you discover any security vulnerabilities, please send an email to [epafroditus.kristian@gmail.com](mailto:epafroditus.kristian@gmail.com).

## Contact

If you want to join work or need to build project, please send an email to [epafroditus.kristian@gmail.com](mailto:epafroditus.kristian@gmail.com) or you can visit my personal website [KristianDev](kristiansnts.dev).

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Code of Conduct

To ensure a welcoming community, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an email to [Taylor Otwell](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## References

- [Filament Shield Plugin](https://filamentphp.com/plugins/bezhansalleh-shield)
- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Semantic Release](https://semantic-release.gitbook.io/semantic-release/)

