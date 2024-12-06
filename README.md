# TaskMaster Pro

A modern task management system built with Laravel, featuring intelligent task organization, real-time filtering, and dark mode support.

<p align="center">
<a href="https://github.com/nikhilkumar1505/TaskMasterPro/actions"><img src="https://img.shields.io/github/workflow/status/nikhilkumar1505/TaskMasterPro/tests" alt="Build Status"></a>
<a href="https://github.com/nikhilkumar1505/TaskMasterPro"><img src="https://img.shields.io/github/license/nikhilkumar1505/TaskMasterPro" alt="License"></a>
</p>

## Features

- 🎯 Smart Task Management
- 🔍 Real-time Filtering
- 📊 Analytics & Insights
- 🌓 Dark/Light Mode
- 📱 Responsive Design
- 🔒 Secure Authentication
- ⚡ High Performance
- 🔄 Real-time Updates

## Requirements

- PHP >= 8.1
- Node.js >= 16
- MySQL >= 5.7
- Composer
- npm

## Installation

1.1 Clone the repository
1.2 after installation and everything is set up, read the "sql.txt" and "C implementation.txt" file in the root directory for more information

```bash
git clone https://github.com/nikhilkumar1505/TaskMasterPro.git
cd TaskMasterPro
```

2. Install PHP dependencies

```bash
composer install
```

3. Install Node dependencies

```bash
npm install
```

4. Create environment file

```bash
cp .env.example .env
```

5. Generate application key

```bash
php artisan key:generate
```

6. Configure your database in .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=taskmaster_pro
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

7. Run database migrations

```bash
php artisan migrate
```

8. Create storage link

```bash
php artisan storage:link
```

9. Build assets

```bash
npm run build
```

10. Start the development server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser

## Development

For development, you'll need to run:

```bash
# Terminal 1: Start Laravel development server
php artisan serve

# Terminal 2: Watch for asset changes
npm run dev
```

## Testing

```bash
# Run PHP tests
php artisan test

# Run JavaScript tests
npm run test
```

## Directory Structure

```
TaskMasterPro/
├── app/                # PHP application code
├── resources/         # Frontend assets and views
│   ├── js/           # JavaScript files
│   ├── css/          # CSS files
│   └── views/        # Blade templates
├── routes/            # Application routes
├── database/          # Database migrations and seeds
└── tests/             # Test files
```

## Key Features Explained

### Task Management
- Create, edit, and delete tasks
- Set priorities and deadlines
- Organize with categories
- Track task status

### Real-time Filtering
- Filter by status
- Sort by priority
- Search functionality
- Quick filters

### Analytics
- Track productivity
- View completion rates
- Monitor progress
- Generate reports

## Contributing

1. Fork the repository
2. Create your feature branch

```bash
git checkout -b feature/amazing-feature
```
3. Commit your changes

```bash
git commit -m 'Add some amazing feature'
```
4. Push to the branch

```bash
git push origin feature/amazing-feature
```
5. Open a Pull Request

## Security

If you discover any security-related issues, please email nikhilkumar150501@gmail.com instead of using the issue tracker.

## Credits

- [Nikhil Kumar](https://github.com/nikhilkumar1505)
- [All Contributors](../../contributors)

## License

The TaskMaster Pro is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, email nikhilkumar150501@gmail.com or visit [my portfolio](https://ashadeewanexports.com/portfolio).
