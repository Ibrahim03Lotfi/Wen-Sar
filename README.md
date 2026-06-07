# وين صار (Wen-Sar) - Business Directory Platform

A comprehensive business directory platform built with Laravel 12, designed to help users discover and review local businesses in their area.

## 🌟 Features

### For Public Users
- **Business Discovery**: Browse businesses by category, location (governorate/district/sub-area)
- **Advanced Search**: Search businesses by name, description, or location
- **Business Details**: View comprehensive business information including:
  - Contact information (phone, landline, multiple phone numbers)
  - Business hours with special schedules
  - Address with Google Maps integration
  - Social media links
  - Photo gallery (logo + multiple images)
- **Reviews & Ratings**: Submit and read business reviews with star ratings
- **Favorites**: Save favorite businesses for quick access
- **Featured Businesses**: View promoted/featured businesses
- **Multi-language Support**: Switch between Arabic and English
- **Contact Form**: Send inquiries to platform administrators

### For Business Owners
- **Dashboard**: Manage personal business listings
- **Business Management**: Create, edit, and delete business profiles
- **Image Upload**: Upload business logos and gallery images
- **Status Tracking**: Monitor approval status of submitted businesses
- **Profile Management**: Update personal information and password

### For Managers (Admins)
- **Setup Wizard**: Initial manager account creation
- **Location Management**: 
  - Manage governorates
  - Manage districts
  - Manage sub-areas
- **Category Management**: 
  - Create and manage business categories
  - Support for subcategories
- **Business Approval Workflow**: 
  - Review pending business submissions
  - Approve or reject businesses
  - Edit approved business information
- **Owner Management**: 
  - View and manage business owners
  - Reset owner passwords
  - Delete owner accounts
- **Ads Management**: 
  - Manage promotional ads (max 6 ads)
  - Upload and rotate ad images
- **Category Rankings**: 
  - Set featured businesses per category
  - Manage ranking expiration dates
  - Extend or remove rankings
- **Manager Management**: 
  - Create additional manager accounts
  - Manage manager permissions

## 🛠️ Tech Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: PostgreSQL
- **Frontend**: 
  - Vite for asset bundling
  - TailwindCSS for styling
  - Alpine.js for interactivity
  - Blade templates
- **Authentication**: Laravel Breeze
- **Authorization**: Spatie Laravel Permission
- **Image Processing**: Intervention Image v3
- **Session/Cache**: Database-driven
- **Queue**: Database queue

## 📋 Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18
- PostgreSQL >= 12
- NPM or Yarn

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone <repository-url>
cd Wen-Sar
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file with your database credentials:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=wen_sar
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Create Storage Link
```bash
php artisan storage:link
```

### 7. Build Assets
```bash
npm run build
```

### 8. Start Development Server
```bash
php artisan serve
npm run dev
```

Or use the composer script:
```bash
composer run dev
```

## 👥 User Roles & Permissions

### Manager (Admin)
- Full access to all management features
- Can create other managers
- Can approve/reject businesses
- Can manage all platform data

### Business Owner
- Can create and manage their own businesses
- Can update their profile
- Cannot approve businesses (requires manager approval)

### Regular User
- Can browse businesses
- Can submit reviews
- Can favorite businesses
- Can update their profile

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Manager/          # Manager-specific controllers
│   │   ├── Owner/            # Business owner controllers
│   │   └── Auth/             # Authentication controllers
│   └── Requests/             # Form request validation
├── Models/                    # Eloquent models
├── Services/                  # Business logic services
└── View/                      # View composers
database/
├── migrations/               # Database migrations
└── seeders/                  # Database seeders
resources/
├── views/                     # Blade templates
│   ├── layouts/              # Layout templates
│   ├── components/          # Reusable components
│   ├── manager/              # Manager views
│   ├── owner/                # Owner views
│   └── auth/                 # Authentication views
└── js/                       # JavaScript files
routes/
├── web.php                   # Public routes
├── manager.php               # Manager routes
└── auth.php                  # Authentication routes
```

## 🔧 Configuration

### Environment Variables
Key configuration options in `.env`:

- `APP_NAME`: Application name (default: "وين صار")
- `APP_ENV`: Environment (local/production)
- `APP_DEBUG`: Debug mode (true/false)
- `APP_URL`: Application URL
- `APP_LOCALE`: Default locale (ar/en)
- `DB_*`: Database connection settings
- `SESSION_DRIVER`: Session storage (database)
- `CACHE_STORE`: Cache storage (database)

### Rate Limiting
The application includes rate limiting for:
- Login attempts
- Contact form submissions
- Search requests
- Favorite toggles
- Review submissions
- Business creation/updates

## 🗄️ Database Schema

### Core Tables
- **users**: Regular users and business owners
- **managers**: Admin users with separate authentication
- **governorates**: Top-level geographical divisions
- **districts**: Second-level geographical divisions
- **sub_areas**: Third-level geographical divisions
- **categories**: Business categories with subcategory support
- **businesses**: Business listings with approval workflow
- **reviews**: Business reviews and ratings
- **favorites**: User favorite businesses
- **ads**: Promotional advertisements
- **category_business_rankings**: Featured business rankings per category

### Key Relationships
- Business → belongsTo → User (owner)
- Business → belongsTo → Category
- Business → belongsTo → District
- Business → belongsTo → SubArea
- Business → hasMany → Reviews
- Business → belongsToMany → Users (favorites)
- Category → hasMany → Subcategories
- District → belongsTo → Governorate
- SubArea → belongsTo → District

## 🚢 Deployment

### Using Docker
```bash
docker build -t wen-sar .
docker run -p 80:80 \
  -e DB_HOST=your_db_host \
  -e DB_DATABASE=your_db_name \
  -e DB_USERNAME=your_db_user \
  -e DB_PASSWORD=your_db_password \
  -e APP_URL=https://your-domain.com \
  -e APP_ENV=production \
  -e APP_DEBUG=false \
  wen-sar
```

### Manual Deployment
1. **Update Environment**:
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://your-domain.com
   ```

2. **Install Production Dependencies**:
   ```bash
   composer install --no-dev --optimize-autoloader
   npm install --production
   npm run build
   ```

3. **Run Migrations**:
   ```bash
   php artisan migrate --force
   ```

4. **Cache Configuration**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

5. **Set Permissions**:
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

6. **Create Storage Link**:
   ```bash
   php artisan storage:link
   ```

## 🔐 Security

- CSRF protection enabled
- SQL injection prevention via Eloquent ORM
- XSS protection via Blade templating
- Rate limiting on sensitive endpoints
- Encrypted sessions
- Password hashing (bcrypt)
- Secure cookie settings (HTTP-only, secure flag)

## 🌍 Localization

The application supports:
- **Arabic (ar)**: Primary language
- **English (en)**: Secondary language

Language can be switched via the language switcher in the UI or by appending `?locale=en` to URLs.

## 📞 API Endpoints

### Public API
- `GET /api/districts/{district}/sub-areas` - Get sub-areas for a district
- `GET /api/governorates/{governorate}/districts` - Get districts for a governorate
- `GET /api/search` - Search businesses (AJAX endpoint)

### Debug Endpoints (Local Only)
- `GET /debug/logs` - View recent log entries
- `GET /debug/storage` - Debug storage configuration

## 🧪 Testing

Run tests with:
```bash
php artisan test
```

## 📝 Development

### Available Composer Scripts
- `composer run setup` - Full project setup
- `composer run dev` - Start development server with hot reload
- `composer run test` - Run test suite

### Available NPM Scripts
- `npm run dev` - Start Vite dev server
- `npm run build` - Build for production

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 📄 License

This project is open-sourced software licensed under the MIT license.

## 🆘 Support

For issues and questions, please contact the development team or open an issue in the repository.

## 🔄 Version History

- **v1.0.0** - Initial release with core directory features
- Business listings and search
- Multi-user authentication system
- Manager approval workflow
- Reviews and favorites
- Category rankings with expiration
- Ads management
