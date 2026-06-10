# وين صار (Wen-Sar) - Comprehensive Technical Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [Architecture Overview](#architecture-overview)
4. [Database Schema](#database-schema)
5. [Models & Relationships](#models--relationships)
6. [Controllers & Routes](#controllers--routes)
7. [Views & Frontend](#views--frontend)
8. [Key Features Implementation](#key-features-implementation)
9. [Configuration Files](#configuration-files)
10. [File Structure Breakdown](#file-structure-breakdown)
11. [Deployment Architecture](#deployment-architecture)

---

## Project Overview

**Wen-Sar** is a comprehensive Business Directory Platform designed for users in Damascus and Syria to discover, review, and interact with local businesses. The platform supports three main user roles:

1. **Public Users** - Browse, search, and review businesses
2. **Business Owners** - Manage their business listings
3. **Managers (Admins)** - Approve businesses, manage locations/categories, handle rankings

The application is built with Laravel 12, PostgreSQL, and modern frontend technologies (TailwindCSS, Alpine.js).

---

## Technology Stack

### Backend
- **Framework**: Laravel 12 (PHP 8.2+)
- **Database**: PostgreSQL 15
- **Authentication**: Laravel Breeze (default auth system)
- **Authorization**: Spatie Laravel Permission (role-based access control)
- **Image Processing**: Intervention Image v3

### Frontend
- **Build Tool**: Vite 5.4.0
- **CSS Framework**: TailwindCSS 3.4.0
- **JS Framework**: Alpine.js 3.4.2
- **Template Engine**: Blade (Laravel's templating)
- **HTTP Client**: Axios 1.11.0

### DevDependencies
- **Concurrently**: Run multiple npm scripts simultaneously
- **Autoprefixer**: CSS vendor prefixes
- **PostCSS**: CSS preprocessing

### Infrastructure
- **Hosting**: Docker (Ubuntu 20.04 VPS at 188.40.225.31)
- **Web Server**: Nginx (reverse proxy)
- **App Container**: Apache/PHP 8.2 (Port 8080)
- **Database Container**: PostgreSQL 15
- **Domain**: wensar.me (SSL via Let's Encrypt)
- **Network**: Docker bridge network `wen-sar-network`

---

## Architecture Overview

### Layered Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    Frontend (Blade + Alpine.js)         │
│                 (TailwindCSS Styling)                   │
├─────────────────────────────────────────────────────────┤
│                    Routes Layer (routes/)                │
│   web.php, manager.php, auth.php, console.php           │
├─────────────────────────────────────────────────────────┤
│               Controllers Layer (app/Http/)              │
│   HomeController, BusinessController, Manager/*,        │
│   Owner/*, Auth/*, etc.                                 │
├─────────────────────────────────────────────────────────┤
│            Models & Business Logic (app/Models/)        │
│   User, Business, Category, District, Review, etc.      │
├─────────────────────────────────────────────────────────┤
│         Configuration Layer (config/)                   │
│   app.php, database.php, auth.php, permission.php       │
├─────────────────────────────────────────────────────────┤
│            Database Layer (PostgreSQL)                  │
│   All tables with relationships and constraints         │
└─────────────────────────────────────────────────────────┘
```

### Request Flow Example (Business Creation)

```
User fills form on create.blade.php
         ↓
POST /owner/businesses (OwnerBusinessController@store)
         ↓
Validation & Image Upload (Storage/logos, Storage/business_images)
         ↓
Create Business Model Instance
         ↓
Save to Database (businesses table)
         ↓
Redirect to Dashboard with Success Message
```

---

## Database Schema

### Core Tables

#### 1. **users** (Inherited from Laravel)
- **Purpose**: Store all user accounts (public users)
- **Key Fields**:
  - `id` (PK)
  - `name`, `email`, `password`
  - `email_verified_at` (email verification)
  - `created_at`, `updated_at`
- **Relations**: Defined in User model with businesses, reviews, favorites

#### 2. **managers**
- **Purpose**: Store manager/admin accounts (separate from public users)
- **Key Fields**:
  - `id` (PK)
  - `username` (unique login identifier)
  - `password_1`, `password_2` (dual password for security)
  - `name` (manager's display name)
  - `is_active` (account status flag)
  - `last_login_at` (audit trail)
  - `created_at`, `updated_at`
- **Relations**: One manager can approve multiple businesses

#### 3. **governorates**
- **Purpose**: Store top-level geographic regions (provinces)
- **Key Fields**:
  - `id` (PK)
  - `name` (governorate name in Arabic)
  - `created_at`, `updated_at`
- **Relations**: Has many districts

#### 4. **districts**
- **Purpose**: Store districts within governorates
- **Key Fields**:
  - `id` (PK)
  - `name` (district name)
  - `governorate_id` (FK to governorates)
  - `created_at`, `updated_at`
- **Relations**: Belongs to governorate, has many sub-areas and businesses

#### 5. **sub_areas**
- **Purpose**: Store neighborhoods/sub-divisions within districts
- **Key Fields**:
  - `id` (PK)
  - `name` (sub-area name)
  - `district_id` (FK to districts)
  - `created_at`, `updated_at`
- **Relations**: Belongs to district, has many businesses

#### 6. **categories**
- **Purpose**: Store business categories (with parent-child hierarchy)
- **Key Fields**:
  - `id` (PK)
  - `name` (category name)
  - `parent_id` (FK to categories - NULL for main categories)
  - `created_at`, `updated_at`
- **Relations**: 
  - Has many businesses
  - Has many subcategories (self-referential)
  - Belongs to parent category
- **Special Methods**: 
  - `allCategoryIds()` - Get all category IDs including subcategories
  - `totalBusinessesCount()` - Count all businesses in category and subcategories

#### 7. **businesses**
- **Purpose**: Store business listings
- **Key Fields**:
  - `id` (PK)
  - `name`, `english_name` (business name in two languages)
  - `description` (business details)
  - `logo` (path to logo image)
  - `images` (JSON array of image paths)
  - `owner_id` (FK to users - business owner)
  - `district_id` (FK to districts)
  - `sub_area_id` (FK to sub_areas, nullable)
  - `category_id` (FK to categories - can be subcategory)
  - `phone`, `phones` (JSON array of phone numbers)
  - `landline`, `landlines` (JSON array of landline numbers)
  - `opening_time`, `closing_time` (time format)
  - `business_hours` (JSON array with special schedules)
  - `address`, `google_maps_link`
  - `social_links` (JSON: facebook, instagram URLs)
  - `status` (pending/approved/rejected)
  - `approved_at`, `approved_by` (FK to managers)
  - `is_featured`, `featured_rank` (for ranking system)
  - `deleted_at` (soft delete timestamp)
  - `created_at`, `updated_at`
- **Relations**: 
  - Belongs to user (owner)
  - Belongs to district
  - Belongs to sub_area
  - Belongs to category
  - Has many reviews
  - Has many favorites (through pivot)
  - Belongs to manager (approved_by)
- **Scopes**:
  - `approved()` - Only approved businesses
  - `pending()` - Pending approval

#### 8. **reviews**
- **Purpose**: Store user reviews and ratings for businesses
- **Key Fields**:
  - `id` (PK)
  - `user_id` (FK to users)
  - `business_id` (FK to businesses)
  - `rating` (1-5 star rating)
  - `comment` (review text)
  - `created_at`, `updated_at`
- **Relations**:
  - Belongs to user
  - Belongs to business

#### 9. **favorites**
- **Purpose**: Pivot table for user favorites (many-to-many relationship)
- **Key Fields**:
  - `user_id` (FK to users)
  - `business_id` (FK to businesses)
  - `created_at`
- **Relations**: Bridges users and businesses

#### 10. **category_business_rankings**
- **Purpose**: Store featured business rankings within categories
- **Key Fields**:
  - `id` (PK)
  - `category_id` (FK to categories)
  - `business_id` (FK to businesses)
  - `rank` (position in ranking)
  - `expires_at` (when ranking expires)
  - `manager_id` (FK to managers - who set the ranking)
  - `notification_sent` (flag for expiration notifications)
  - `created_at`, `updated_at`
- **Relations**:
  - Belongs to category
  - Belongs to business
- **Purpose**: Managers can set which businesses appear first in category listings with automatic expiration

#### 11. **ads**
- **Purpose**: Store promotional advertisements
- **Key Fields**:
  - `id` (PK)
  - `image_path` (path to ad image)
  - `manager_id` (FK to managers - who uploaded)
  - `created_at`, `updated_at`
- **Max Limit**: 6 ads maximum at any time
- **Relations**: Belongs to manager

#### 12. **cache** & **jobs** (Laravel defaults)
- **Purpose**: 
  - `cache`: Cache storage
  - `jobs`: Queue job storage

#### 13. **Spatie Permission Tables**
- `roles` - Role definitions
- `permissions` - Permission definitions
- `model_has_roles` - User-role assignments
- `model_has_permissions` - User-permission assignments
- `role_has_permissions` - Role-permission assignments

---

## Models & Relationships

### **User Model** (`app/Models/User.php`)
- Extends Laravel's Authenticatable
- Uses `HasFactory` and `Notifiable` traits
- Uses `HasRoles` from Spatie Permission
- **Relations**:
  - `businesses()` - hasMany relationship (businesses owned)
  - `favorites()` - belongsToMany relationship (favorite businesses)
  - `reviews()` - hasMany relationship (reviews written)
- **Casts**:
  - `email_verified_at` → datetime
  - `password` → hashed

### **Business Model** (`app/Models/Business.php`)
- Uses `SoftDeletes` trait (soft delete support)
- **Key Methods**:
  - `averageRating()` - Calculate average star rating from reviews
  - `isFavoritedBy($user)` - Check if user favorited this business
  - `allPhones()` - Merge phone and phones array
  - `allLandlines()` - Merge landline and landlines array
- **Scopes**:
  - `approved()` - Filter approved businesses only
  - `pending()` - Filter pending businesses
- **Casts**:
  - `images`, `social_links`, `business_hours`, `phones`, `landlines` → array/JSON
  - `is_featured` → boolean
  - `approved_at` → datetime

### **Category Model** (`app/Models/Category.php`)
- Self-referential (parent-child hierarchy)
- **Key Methods**:
  - `allCategoryIds()` - Get all IDs (main + subcategories)
  - `totalBusinessesCount()` - Count all businesses recursively
- **Relations**:
  - `businesses()` - hasMany
  - `subcategories()` - hasMany(self, 'parent_id')
  - `parent()` - belongsTo(self, 'parent_id')

### **Review Model** (`app/Models/Review.php`)
- Fillable: `user_id`, `business_id`, `rating`, `comment`
- **Relations**:
  - `user()` - belongsTo(User)
  - `business()` - belongsTo(Business)

### **Manager Model** (`app/Models/Manager.php`)
- Separate authenticatable model (not User)
- Extends Laravel's Authenticatable
- Uses custom password fields: `password_1`, `password_2`
- **Method**: `getAuthPassword()` - Returns password_1 for auth
- **Relations**:
  - `approvedBusinesses()` - hasMany(Business, 'approved_by')
- **Casts**:
  - `is_active` → boolean
  - `last_login_at` → datetime
  - `password_1`, `password_2` → hashed

### **Governorate Model** (`app/Models/Governorate.php`)
- Simple model without special methods
- **Relations**:
  - `districts()` - hasMany

### **District Model** (`app/Models/District.php`)
- **Relations**:
  - `governorate()` - belongsTo
  - `subAreas()` - hasMany
  - `businesses()` - hasMany

### **SubArea Model** (`app/Models/SubArea.php`)
- **Relations**:
  - `district()` - belongsTo
  - `businesses()` - hasMany

### **Favorite Model** (Implicit pivot model)
- Managed through User and Business pivot table

### **CategoryBusinessRanking Model** (`app/Models/CategoryBusinessRanking.php`)
- **Relations**:
  - `category()` - belongsTo
  - `business()` - belongsTo
- **Casts**:
  - `expires_at` → datetime

### **Ad Model** (`app/Models/Ad.php`)
- Simple model with minimal logic
- Fillable: `image_path`, `manager_id`

---

## Controllers & Routes

### Route Files Structure

#### **routes/web.php** - Main public routes
- `GET /` → HomeController@index (homepage with featured businesses)
- `GET /categories` → HomeController@categories (view all categories)
- `GET /about` → HomeController@about
- `GET /contact` → HomeController@contact
- `POST /contact` → HomeController@contactSubmit (throttled)
- `GET /api/districts/{district}/sub-areas` → DistrictController@subAreas (API endpoint)
- `GET /api/governorates/{governorate}/districts` → DistrictController@districtsByGovernorate (API)
- `GET /search` → BusinessController@search (search page + API)
- `GET /business/{business}` → BusinessController@show (business detail page)
- `GET /category/{category}` → BusinessController@category (category listing)
- `GET /featured` → BusinessController@featured (featured businesses)
- **Favorites Routes** (requires redirect.select middleware):
  - `GET /favorites` → FavoriteController@index
  - `POST /favorites/{business}/toggle` → FavoriteController@toggle (throttled)
  - `POST /favorites/{business}` → FavoriteController@store (throttled)
  - `DELETE /favorites/{business}` → FavoriteController@destroy (throttled)
- **Review Routes**:
  - `POST /business/{business}/reviews` → ReviewController@store (throttled, requires auth)
- **Owner Routes** (requires auth middleware, prefix 'owner'):
  - `GET /owner/businesses` → OwnerBusinessController@index (owner's business list)
  - `GET /owner/businesses/create` → OwnerBusinessController@create
  - `POST /owner/businesses` → OwnerBusinessController@store (throttled uploads)
  - `GET /owner/businesses/{business}/edit` → OwnerBusinessController@edit
  - `PUT /owner/businesses/{business}` → OwnerBusinessController@update
  - `PUT /owner/password` → DashboardController@updatePassword
- **Dashboard Route**:
  - `GET /dashboard` → DashboardController@index (requires auth, verified, role:owner)

#### **routes/manager.php** - Manager/Admin routes
- **Public Manager Routes**:
  - `GET /manager/setup` → ManagerAuthController@showSetupForm (only if no managers exist)
  - `POST /manager/setup` → ManagerAuthController@setup (manager registration)
  - `GET /manager/login` → ManagerAuthController@showLoginForm
  - `POST /manager/login` → ManagerAuthController@login (throttled)
  - `POST /manager/logout` → ManagerAuthController@logout
- **Protected Manager Routes** (requires manager.auth middleware):
  - `GET /manager/dashboard` → ManagerDashboardController@index
  - **CRUD Resources**:
    - `/manager/governorates` → GovernorateController (all CRUD)
    - `/manager/districts` → DistrictController (all CRUD)
    - `/manager/sub-areas` → SubAreaController (all CRUD)
    - `/manager/categories` → CategoryController (all CRUD)
    - `/manager/ads` → AdController (all CRUD)
    - `/manager/owners` → OwnerController (show, destroy, update-password)
    - `/manager/businesses` → ManagerBusinessController (all CRUD, show)
  - **Approval Routes**:
    - `GET /manager/approvals/pending` → ApprovalController@pending
    - `GET /manager/approvals/{business}` → ApprovalController@show
    - `POST /manager/approvals/{business}/approve` → ApprovalController@approve
    - `POST /manager/approvals/{business}/reject` → ApprovalController@reject
  - **Ranking Routes**:
    - `GET /manager/categories/{category}/rankings` → CategoryController@rankings
    - `POST /manager/categories/{category}/rankings` → CategoryController@updateRankings
    - `GET /manager/rankings/expired` → CategoryController@expiredRankings
    - `DELETE /manager/rankings/{ranking}` → CategoryController@removeRanking
    - `POST /manager/rankings/{ranking}/extend` → CategoryController@extendRanking
  - **Manager Management**:
    - `GET /manager/managers` → ManagerController@index
    - `GET /manager/managers/create` → ManagerController@create
    - `POST /manager/managers` → ManagerController@store (throttled)
    - `DELETE /manager/managers/{manager}` → ManagerController@destroy

#### **routes/auth.php** - Authentication routes (Laravel Breeze)
- **Guest Middleware Routes**:
  - `GET /select-role` → view auth.select-role (choose user type)
  - `GET /register` → RegisteredUserController@create
  - `POST /register` → RegisteredUserController@store (throttled)
  - `GET /login` → AuthenticatedSessionController@create
  - `POST /login` → AuthenticatedSessionController@store (throttled)
  - `GET /forgot-password` → PasswordResetLinkController@create
  - `POST /forgot-password` → PasswordResetLinkController@store (throttled)
  - `GET /reset-password/{token}` → NewPasswordController@create
  - `POST /reset-password` → NewPasswordController@store
- **Authenticated Routes**:
  - `GET /verify-email` → EmailVerificationPromptController
  - `GET /verify-email/{id}/{hash}` → VerifyEmailController (throttled)
  - `POST /email/verification-notification` → EmailVerificationNotificationController@store
  - `GET /confirm-password` → ConfirmablePasswordController@show
  - `POST /confirm-password` → ConfirmablePasswordController@store
  - `PUT /password` → PasswordController@update
  - `POST /logout` → AuthenticatedSessionController@destroy

#### **routes/console.php** - Artisan commands (if any)

### Controllers by Category

#### **Public Controllers** (`app/Http/Controllers/`)

**HomeController**
- `index()` - Load homepage with governorates, categories, featured businesses, and ads
- `categories()` - Load categories page with business counts
- `about()` - Show about page
- `contact()` - Show contact form
- `contactSubmit()` - Handle contact form submission

**BusinessController**
- `search()` - Search businesses by district, sub_area, category, or query string
- `apiSearch()` - JSON API endpoint for search
- `show()` - Display single business detail with reviews
- `category()` - Show all businesses in a category
- `featured()` - Show all featured businesses
- **Private Methods**:
  - `getRankingRowsForCategory()` - Fetch ranking scores for category display
  - `applyManagerRankings()` - Sort businesses by manager-set rankings

**FavoriteController**
- `index()` - List user's favorite businesses
- `toggle()` - Add/remove favorite (returns JSON)
- `store()` - Add favorite
- `destroy()` - Remove favorite

**ReviewController**
- `store()` - Create new review for a business (validates rating 1-5, comment max 1000 chars)

**DashboardController**
- `index()` - Show owner dashboard with their businesses
- `updatePassword()` - Update user password

**DistrictController**
- `subAreas()` - API endpoint returning sub-areas for a district (JSON)
- `districtsByGovernorate()` - API endpoint returning districts for a governorate (JSON)

**LanguageController**
- `switch()` - Switch between Arabic/English language

**ProfileController** (Laravel Breeze)
- Profile management (if implemented)

#### **Auth Controllers** (`app/Http/Controllers/Auth/`)

**AuthenticatedSessionController**
- `create()` - Show login form
- `store()` - Process login
- `destroy()` - Process logout

**RegisteredUserController**
- `create()` - Show registration form
- `store()` - Process registration, assign 'owner' role

**PasswordController**
- `update()` - Update authenticated user password

**PasswordResetLinkController**
- `create()` - Show forgot password form
- `store()` - Send password reset email

**NewPasswordController**
- `create()` - Show password reset form with token
- `store()` - Process password reset

**EmailVerificationPromptController** & **VerifyEmailController**
- Email verification flow

#### **Owner Controllers** (`app/Http/Controllers/Owner/`)

**BusinessController** (Owner-specific)
- `index()` - List owner's businesses with status (pending/approved/rejected)
- `create()` - Show business creation form
- `store()` - Save new business with validation and image uploads
  - Validates: name, description, location, category, contact info, images
  - Handles file uploads to `storage/app/public/logos` and `storage/app/public/business_images`
  - Sets status to 'pending' for manager approval
  - Prefixes phone numbers with '09' and landlines with '011'
  - Returns detailed validation error messages
- `edit()` - Show business edit form with pre-populated data
- `update()` - Update business information
  - Handles image deletion (`images_to_delete` hidden field)
  - Updates image array accordingly
  - Can update existing images (if deleted and new ones uploaded)

#### **Manager Controllers** (`app/Http/Controllers/Manager/`)

**AuthController**
- `showSetupForm()` - Display first-time manager setup form
- `setup()` - Create initial manager account (only if no managers exist)
- `showLoginForm()` - Show manager login form
- `login()` - Authenticate manager
- `logout()` - Logout manager

**DashboardController**
- `index()` - Manager dashboard with pending business count and quick stats

**ApprovalController**
- `pending()` - List all pending business submissions (paginated 20 per page)
- `show()` - Show single pending business details
- `approve()` - Mark business as approved, record manager and timestamp
- `reject()` - Mark business as rejected

**GovernorateController** - Full CRUD for governorates

**DistrictController** - Full CRUD for districts

**SubAreaController** - Full CRUD for sub-areas

**CategoryController**
- Full CRUD for categories
- `rankings()` - Show featured rankings for category
- `updateRankings()` - Set featured business rankings with expiration dates
- `expiredRankings()` - Show rankings that have expired
- `removeRanking()` - Remove a ranking
- `extendRanking()` - Extend expiration date of a ranking

**BusinessController** (Manager view)
- `index()` - List all approved businesses
- `show()` - Show business details
- `create/edit/update/delete()` - Manager can edit/delete any business

**OwnerController**
- `index()` - List all business owners
- `show()` - Show owner details and their businesses
- `updatePassword()` - Reset owner password (manager can set new password)
- `destroy()` - Delete owner account (cascade delete their businesses)

**ManagerController**
- `index()` - List all managers
- `create()` - Show create manager form
- `store()` - Create new manager account
- `destroy()` - Delete manager account

**AdController**
- `index()` - List all ads (max 6)
- `store()` - Upload new ad image
- `update()` - Update ad
- `destroy()` - Delete ad

---

## Views & Frontend

### Layout Structure

**Base Layout** - `resources/views/layouts/app.blade.php`
- Navigation bar with language switcher
- Mobile-responsive hamburger menu
- Hero section with brand logo
- Footer with company information
- Uses Alpine.js for modal management
- Includes splash screen component
- Dark mode consideration with brand colors (#06402b green, #faf9f6 white)

**Manager Layout** - `resources/views/layouts/manager.blade.php`
- Manager-specific sidebar navigation
- Dashboard layout for admin panel
- Manager authentication state checking

**Guest Layout** - `resources/views/layouts/guest.blade.php`
- Minimal layout for auth pages
- No navigation/footer
- Centered form styling

### Main View Files by Category

#### **Public Pages** (`resources/views/`)
- **home.blade.php** - Homepage with hero, featured businesses, categories, ads
- **categories.blade.php** - All categories grid with business counts
- **about.blade.php** - About page with company info
- **contact.blade.php** - Contact form
- **welcome.blade.php** - Welcome page (if landing page)
- **dashboard.blade.php** - Owner dashboard (if separate from routes)

#### **Authentication Views** (`resources/views/auth/`)
- **select-role.blade.php** - Choose between owner/user registration
- **register.blade.php** - User registration form (from Laravel Breeze)
- **login.blade.php** - User login form
- **forgot-password.blade.php** - Password reset request form
- **reset-password.blade.php** - Password reset form with token
- **verify-email.blade.php** - Email verification notice
- (Other standard Laravel Breeze auth templates)

#### **Business Views** (`resources/views/businesses/`)
- **show.blade.php** - Business detail page with reviews, ratings, images gallery
- **category.blade.php** - Businesses filtered by category with search/filter options
- **search.blade.php** - Search results page with filters
- **featured.blade.php** - Featured/promoted businesses page

#### **Favorites Views** (`resources/views/favorites/`)
- **index.blade.php** - User's saved favorite businesses

#### **Owner/Business Management** (`resources/views/owner/businesses/`)
- **index.blade.php** - Owner's business list with status indicators (pending/approved/rejected)
- **create.blade.php** - Business creation form (large form with multiple sections)
  - Basic info section (name, English name, description)
  - Location section (governorate, district, sub-area, category, subcategory)
  - Address details (full address, Google Maps link)
  - Contact section (phone numbers array, landline numbers array)
  - Business hours section (opening/closing times + special schedules)
  - Images section (logo + multiple images gallery)
  - Social media section (Facebook, Instagram links)
  - Includes Alpine.js for dynamic form interactions
- **edit.blade.php** - Edit existing business (same as create but with pre-populated data)
  - Current file attached in context - handles image deletion properly

#### **Manager Views** (`resources/views/manager/`)
- **dashboard.blade.php** - Admin dashboard
- **approvals/**
  - **pending.blade.php** - List pending businesses for approval (paginated)
  - **show.blade.php** - Business approval detail view with approve/reject buttons
- **businesses/**
  - **index.blade.php** - All approved businesses list
  - **show.blade.php** - Business detail view
  - **create.blade.php** - Manager can create business for owner
  - **edit.blade.php** - Manager can edit any business
- **categories/**
  - **index.blade.php** - Category management list
  - **create.blade.php** - Create new category (with parent category selector for subcategories)
  - **edit.blade.php** - Edit category
  - **rankings.blade.php** - Set featured business rankings for category with expiration dates
- **governorates/**, **districts/**, **sub-areas/**, **ads/**, **managers/**, **owners/**
  - Similar CRUD view patterns for each resource

### Components (`resources/views/components/`)

**splash-screen.blade.php** (232 lines)
- Full-screen splash animation on page load
- Falls down logo animation from top
- Rises up company name animation from bottom
- Typing animation for tagline
- Auto-hides after 3 seconds or user interaction
- Uses CSS keyframes: `fallFromTop`, `riseFromBottom`, `typing`
- Prevents body scroll while displaying

**business-hours.blade.php**
- Reusable component for setting business operating hours
- Dynamic form for adding special schedules (holidays, etc.)
- Alpine.js integration for add/remove functionality

**modal.blade.php**
- Reusable modal dialog component with Alpine.js
- x-show binding for visibility toggle
- Click-outside-to-close functionality

**dropdown.blade.php**
- Reusable dropdown menu component
- Used in navigation and forms

**input-label.blade.php**, **input-error.blade.php**, **text-input.blade.php**
- Form field components for consistency

**auth-session-status.blade.php**
- Display flash messages in auth pages

**primary-button.blade.php**, **secondary-button.blade.php**, **danger-button.blade.php**
- Button style components

**dropdown-link.blade.php**, **nav-link.blade.php**, **responsive-nav-link.blade.php**
- Navigation components

---

## Key Features Implementation

### 1. **Business Registration & Approval Workflow**

**Flow**:
1. Owner fills create.blade.php form with business details
2. Submits to `owner.businesses.store`
3. Images uploaded to storage (logos/, business_images/)
4. Business created with status='pending'
5. Manager sees it in `manager.approvals.pending`
6. Manager can approve or reject
7. If approved: status='approved', approved_at=now(), approved_by=manager_id

**Validation** (in OwnerBusinessController@store):
```
- name: required|string|max:255
- description: required|string
- district_id, category_id: required|exists
- phones.*: regex:/^[0-9]{8}$/ (8 digits)
- landlines.*: regex:/^[0-9]{7}$/ (7 digits)
- logo: required|image|max:2048
- images: array|min:1|max:16, each max:2048
- social_links: URL format
```

### 2. **Image Management**

**Storage Structure**:
```
storage/app/public/
├── logos/
│   └── {business_id}_{timestamp}.jpg
└── business_images/
    └── {business_id}_{timestamp}_{index}.jpg
```

**Implementation Notes**:
- Logos stored separately from gallery images
- Images stored as JSON array in database
- Public symlink created via `php artisan storage:link`
- Accessible via `/storage/` URL prefix
- Deletion handled via `images_to_delete` hidden field

**Current Issue (from user report)**: When users delete images before submitting, they disappear from UI but still get sent to server. This needs fixing in the form submission logic to properly track which images should be deleted vs. kept.

### 3. **Location Hierarchy** (Geographic Data)

**Three-level system**:
```
Governorate (محافظة)
    ↓
District (المنطقة)
    ↓
Sub-Area (الحي)
```

**Implementation**:
- API endpoints for cascading dropdowns
- `GET /api/governorates/{id}/districts` - Returns districts JSON
- `GET /api/districts/{id}/sub-areas` - Returns sub-areas JSON
- Alpine.js `$watch` to trigger updates when parent changes
- Used in both create and edit forms

### 4. **Category System with Subcategories**

**Two-level hierarchy**:
```
Main Category (parent_id = NULL)
    ↓
Subcategory (parent_id = main_category_id)
```

**Features**:
- Businesses can be assigned to main or subcategory
- Category total count includes subcategories
- Ranking system works at both levels
- Display shows main categories with subcategory expandable list

### 5. **Featured/Ranking System**

**CategoryBusinessRanking Table**:
- Manager can set which businesses appear first in each category
- Each ranking has expiration date
- After expiration, business falls back to normal sorting
- Manager notified before expiration via `notification_sent` flag
- Expired rankings visible in `rankings/expired` page for extension

**Implementation** (BusinessController@getRankingRowsForCategory):
```php
1. Find rankings for category
2. If subcategory, also check parent category rankings
3. Sort by rank number (1=top)
4. Group by business_id (avoid duplicates)
5. Return ranked collection
```

**Display Priority**:
1. Ranked businesses (sorted by rank)
2. Then unranked businesses (by rating/recency)

### 6. **Review & Rating System**

**Review Model Fields**:
- user_id, business_id, rating (1-5), comment
- Timestamped

**Features**:
- Average rating calculated in Business model
- Ratings visible on business detail page
- Users can leave max 1 review per business (enforced in policy)
- Reviews count affects business ranking score

**Ranking Algorithm** (in BusinessController@search):
```
ranking_score = (avgRating * 0.5) + 
                (views/100 * 0.3) + 
                (is_featured ? 5 : 0) * 0.2
```

### 7. **Favorites/Bookmarks**

**Pivot Table**: `favorites` (user_id, business_id)

**Routes**:
- `POST /favorites/{business}/toggle` - Add or remove favorite
- Returns JSON for AJAX (no page reload)
- User sees a heart icon that changes on click
- Throttled to prevent spam

### 8. **Authentication & Authorization**

**Two separate authentication systems**:

**Public Users** (Users table):
- Laravel Breeze authentication
- Email/password registration
- Email verification required
- Role: 'owner' (business owner)
- Other public users have no special role

**Managers** (Managers table):
- Separate authentication guard `manager`
- Custom login form with username/password
- Dual password fields for security
- Role permissions managed by Spatie Permission
- Active/inactive status flag

**Middleware**:
- `redirect.select` - Redirect if not authenticated to select-role page
- `manager.auth` - Custom middleware checking manager guard
- `verified` - Check email verification
- `role:owner` - Check user has 'owner' role

### 9. **Business Hours Management**

**data Structure** (JSON stored in business_hours column):
```json
{
  "opening_time": "08:00",
  "closing_time": "22:00",
  "special_schedules": [
    {
      "day": "Friday",
      "opening": "10:00",
      "closing": "23:00"
    }
  ]
}
```

**Component**: `business-hours.blade.php`
- Alpine.js for dynamic add/remove of special schedules
- Reusable across create/edit forms

### 10. **Multi-Language Support (Arabic/English)**

**Implementation**:
- `lang/{locale}` route switches language
- Stores in session/cookie
- All text wrapped in `__()` helper for translation
- RTL/LTR auto-detection: `dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}"`
- Database stores Arabic text natively (UTF-8)
- English names stored separately where needed

**Files Involved**:
- `resources/lang/ar/` - Arabic translations
- `resources/lang/en/` - English translations
- LanguageController@switch - Handles language changes

---

## Configuration Files

### **config/app.php**
- Application name, timezone, locale
- Service providers list
- Alias definitions
- Key Laravel configuration options

### **config/database.php**
- Database connection configuration
- Default: PostgreSQL (DB_CONNECTION=pgsql)
- Connection settings for host, port, database, user, password
- Prepared for multiple database support

### **config/auth.php**
- Authentication guards definition
- Two guards: 'web' (User model) and 'manager' (Manager model)
- Password reset configuration
- Email verification settings

### **config/filesystems.php**
- Storage disk configuration
- Default: 'local' disk
- Public disk maps to `storage/app/public`
- Accessible via `/storage` URL (via symlink)
- S3 support configured but not used

### **config/permission.php** (Spatie)
- Role and permission table names
- Model configuration
- Database connection settings
- Cache settings for permissions

### **config/session.php**
- Session driver: database (stored in sessions table)
- Session lifetime configuration
- Cookie settings
- Secure cookie for HTTPS (production)

### **config/cache.php**
- Cache driver configuration
- Default: database cache
- Cache expiration settings

### **config/mail.php**
- Email configuration
- SMTP settings (customizable via .env)
- From address configuration

### **config/queue.php**
- Queue driver configuration
- Default: database queue
- Job timeout and retry settings

### **config/services.php**
- Third-party service configuration
- AWS S3 credentials (if needed)
- Mail service configuration

### **config/logging.php**
- Log channel configuration
- Default channel, log level
- Slack, email notification channels

### **.env** (Environment Variables)
```
APP_NAME=وين صار
APP_ENV=production/local
APP_DEBUG=false/true
APP_URL=https://wensar.me (production)

DB_CONNECTION=pgsql
DB_HOST=postgres (Docker container name)
DB_PORT=5432
DB_DATABASE=wen_sar
DB_USERNAME=wen_sar_user
DB_PASSWORD=secret123

CACHE_STORE=file/database
SESSION_DRIVER=file/database
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...

APP_KEY=base64:... (auto-generated)
```

---

## File Structure Breakdown

### **/app** (Application Code)

```
app/
├── Console/
│   └── Commands/        # Custom Artisan commands (if any)
├── Http/
│   ├── Controllers/
│   │   ├── Auth/                      # Laravel Breeze auth controllers (9 files)
│   │   ├── Manager/                   # Manager/admin controllers (11 files)
│   │   ├── Owner/                     # Owner-specific controllers
│   │   ├── HomeController.php         # Homepage, about, contact
│   │   ├── BusinessController.php     # Public business display/search
│   │   ├── CategoryController.php     # Category listing
│   │   ├── ReviewController.php       # Review submission
│   │   ├── FavoriteController.php     # Favorites management
│   │   ├── DistrictController.php     # API endpoints for locations
│   │   ├── DashboardController.php    # Owner dashboard
│   │   ├── LanguageController.php     # Language switching
│   │   ├── ProfileController.php      # Profile management
│   │   └── Controller.php             # Base controller
│   ├── Middleware/
│   │   ├── ManagerAuth.php            # Manager authentication guard
│   │   ├── RedirectToSelectRole.php   # Redirect guests to role selection
│   │   ├── RedirectIfManagersExist.php # Show setup only if no managers
│   │   ├── SecurityHeaders.php        # Add security headers (CSP, HSTS, etc.)
│   │   ├── SetLocale.php              # Set application locale
│   │   └── TrustProxies.php           # Nginx proxy trust settings
│   ├── Requests/                      # Form request validation (if using)
│   └── Services/                      # Business logic services (currently empty)
├── Models/
│   ├── User.php                       # Public user model (owner role)
│   ├── Manager.php                    # Manager/admin user model
│   ├── Business.php                   # Business listing model
│   ├── Category.php                   # Business category model
│   ├── Review.php                     # Business review model
│   ├── Favorite.php                   # Pivot model for favorites (implicit)
│   ├── District.php                   # Location district model
│   ├── Governorate.php                # Location governorate model
│   ├── SubArea.php                    # Location sub-area model
│   ├── CategoryBusinessRanking.php    # Featured business ranking model
│   └── Ad.php                         # Promotional ad model
├── Policies/
│   ├── BusinessPolicy.php             # Business authorization logic
│   ├── ReviewPolicy.php               # Review authorization logic
│   └── CategoryBusinessRankingPolicy.php
├── Providers/
│   └── AppServiceProvider.php         # Service provider for bootstrapping
└── View/
    └── Components/                    # View components (if using)
```

### **/database** (Database & Seeding)

```
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php          # User accounts
│   ├── 0001_01_01_000001_create_cache_table.php          # Cache storage
│   ├── 0001_01_01_000002_create_jobs_table.php           # Queue jobs
│   ├── 2025_01_15_000001_create_managers_table.php       # Manager accounts
│   ├── 2026_04_12_105401_create_permission_tables.php    # Spatie permissions (5 tables)
│   ├── 2026_04_12_105600_create_governorates_table.php   # Governorates
│   ├── 2026_04_12_105601_create_districts_table.php      # Districts
│   ├── 2026_04_12_105602_create_sub_areas_table.php      # Sub-areas
│   ├── 2026_04_12_105603_create_categories_table.php     # Categories (with parent_id for hierarchy)
│   ├── 2026_04_12_105604_create_businesses_table.php     # Main businesses table
│   ├── 2026_04_12_105605_create_reviews_table.php        # Reviews/ratings
│   ├── 2026_04_12_105606_create_favorites_table.php      # Favorites pivot table
│   ├── 2026_04_12_105700_add_contract_fields_to_businesses.php
│   ├── 2026_04_12_105701_add_english_name_and_hours_to_businesses.php
│   ├── 2026_04_12_133112_add_owner_id_to_businesses_table.php
│   ├── 2026_04_12_143345_make_sub_area_id_nullable_in_businesses_table.php
│   ├── 2026_04_12_150642_add_address_to_businesses_table.php
│   ├── 2026_04_14_235919_add_google_maps_link_to_businesses_table.php
│   ├── 2026_04_16_230000_add_business_hours_to_businesses.php
│   ├── 2026_04_17_230000_add_landline_to_businesses_table.php
│   ├── 2026_05_05_100000_add_phones_landlines_to_businesses_table.php
│   ├── 2026_05_05_190000_create_category_business_rankings_table.php
│   ├── 2026_05_05_191000_add_expires_at_to_category_business_rankings.php
│   ├── 2026_05_05_192000_add_manager_and_notification_to_category_business_rankings.php
│   └── 2026_05_06_170000_create_ads_table.php
├── factories/
│   └── UserFactory.php                # Factory for creating test users
└── seeders/
    ├── DatabaseSeeder.php             # Main seeder orchestrator
    ├── RoleSeeder.php                 # Create default roles
    ├── GovernorateSeeder.php          # Seed Syrian governorates
    ├── DistrictSeeder.php             # Seed districts for governorates
    ├── SubAreaSeeder.php              # Seed sub-areas
    ├── CategorySeeder.php             # Seed business categories
    ├── BusinessSeeder.php             # Seed sample businesses
    └── ClearDatabaseSeeder.php        # Clear all data (for testing)
```

### **/resources** (Frontend Assets & Views)

```
resources/
├── css/
│   └── app.css                        # Main stylesheet (imports Tailwind)
├── js/
│   └── app.js                         # Main JavaScript (imports Alpine.js)
├── views/
│   ├── layouts/
│   │   ├── app.blade.php              # Main layout (header, footer, nav)
│   │   ├── manager.blade.php          # Manager dashboard layout
│   │   ├── guest.blade.php            # Auth pages layout
│   │   └── navigation.blade.php       # Navigation component
│   ├── components/
│   │   ├── splash-screen.blade.php    # Loading animation
│   │   ├── business-hours.blade.php   # Business hours form component
│   │   ├── modal.blade.php            # Modal dialog component
│   │   ├── dropdown.blade.php         # Dropdown menu
│   │   ├── input-label.blade.php      # Form label
│   │   ├── input-error.blade.php      # Form error display
│   │   ├── text-input.blade.php       # Text input field
│   │   ├── primary-button.blade.php   # Button styles
│   │   └── ... (other shared components)
│   ├── auth/
│   │   ├── select-role.blade.php      # Choose owner or user registration
│   │   ├── register.blade.php         # Registration form
│   │   ├── login.blade.php            # Login form
│   │   └── ... (other Breeze auth views)
│   ├── businesses/
│   │   ├── show.blade.php             # Business detail view
│   │   ├── category.blade.php         # Category listing page
│   │   └── search.blade.php           # Search results page
│   ├── owner/
│   │   └── businesses/
│   │       ├── index.blade.php        # Owner's business list
│   │       ├── create.blade.php       # Create business form
│   │       └── edit.blade.php         # Edit business form
│   ├── manager/
│   │   ├── dashboard.blade.php        # Admin dashboard
│   │   ├── approvals/
│   │   │   ├── pending.blade.php      # Pending approvals list
│   │   │   └── show.blade.php         # Single approval view
│   │   ├── businesses/
│   │   │   ├── index.blade.php
│   │   │   ├── show.blade.php
│   │   │   └── ... (CRUD views)
│   │   ├── categories/
│   │   ├── governorates/
│   │   ├── districts/
│   │   ├── sub-areas/
│   │   ├── ads/
│   │   ├── managers/
│   │   ├── owners/
│   │   └── rankings/
│   ├── favorites/
│   │   └── index.blade.php            # Favorites list
│   ├── home.blade.php                 # Homepage
│   ├── categories.blade.php           # All categories page
│   ├── about.blade.php                # About page
│   ├── contact.blade.php              # Contact form page
│   ├── dashboard.blade.php            # Owner dashboard (if separate)
│   └── welcome.blade.php              # Welcome/intro page
└── lang/
    ├── ar/
    │   └── *.php                      # Arabic translation files
    └── en/
        └── *.php                      # English translation files
```

### **/routes** (Route Definitions)

```
routes/
├── web.php        # Main public routes (133 lines) - ANALYZED ABOVE
├── manager.php    # Manager/admin routes (100+ lines) - ANALYZED ABOVE
├── auth.php       # Authentication routes (60+ lines) - ANALYZED ABOVE
└── console.php    # Artisan console commands (if any)
```

### **/config** (Configuration Files)

```
config/
├── app.php              # Application config
├── auth.php             # Authentication guards/providers
├── cache.php            # Cache driver config
├── database.php         # Database connections
├── filesystems.php      # Storage disk config
├── logging.php          # Log channels
├── mail.php             # Email config
├── permission.php       # Spatie permission config
├── queue.php            # Queue driver config
├── session.php          # Session config
└── services.php         # Third-party services
```

### **/public** (Web Root)

```
public/
├── index.php            # Laravel entry point
├── .htaccess            # Apache rewrite rules
├── robots.txt           # Search engine crawling rules
├── favicon.ico          # Browser tab icon
├── storage/             # Symlink to storage/app/public
└── build/               # Vite build output directory
    ├── app-*.js         # Compiled JavaScript
    ├── app-*.css        # Compiled CSS
    └── manifest.json    # Vite manifest for asset loading
```

### **/storage** (Runtime Data)

```
storage/
├── app/
│   ├── public/
│   │   ├── logos/              # Business logo images
│   │   └── business_images/    # Business gallery images
│   └── private/                # Non-public files
├── framework/
│   ├── views/                  # Compiled Blade templates cache
│   ├── cache/                  # Application cache
│   └── sessions/               # Session data (if using file driver)
└── logs/
    └── laravel.log             # Application logs
```

### **Root Configuration Files**

```
.env                    # Environment variables (production secrets)
.env.example            # Example environment template
composer.json           # PHP dependencies
composer.lock           # Locked dependency versions
package.json            # Node.js dependencies
package-lock.json       # Locked Node dependency versions
vite.config.js          # Vite build configuration
tailwind.config.js      # TailwindCSS configuration
postcss.config.js       # PostCSS plugins configuration
phpunit.xml             # PHPUnit test configuration
php.ini                 # PHP configuration overrides
Dockerfile              # Docker image definition
start.sh                # Docker startup script
artisan                 # Laravel CLI tool
```

---

## Deployment Architecture

### **Production Environment (Current)**

```
┌─────────────────────────────────────────────────────────┐
│        Domain: wensar.me (SSL via Let's Encrypt)        │
│                  IP: 188.40.225.31                      │
│                 Ubuntu 20.04 VPS                        │
└─────────────────────────────────────────────────────────┘
                           │
                           ↓
        ┌──────────────────────────────────────┐
        │   Nginx (Reverse Proxy)              │
        │   - Port 80 (redirects to 443)       │
        │   - Port 443 (HTTPS to 8080)         │
        │   - Config: /etc/nginx/sites-available/wensar.me
        └──────────────────────────────────────┘
                           │
                           ↓ (localhost:8080)
        ┌──────────────────────────────────────┐
        │    Docker Container: wen-sar-app    │
        │   - Image: wen-sar (PHP 8.2)         │
        │   - Port: 8080 (internal)            │
        │   - Apache2 + Laravel                │
        │   - Symlink: public/storage          │
        │   - Network: wen-sar-network         │
        │   - Volumes: /var/www/html           │
        └──────────────────────────────────────┘
           │                                │
           │ (DB connection)                │
           │                                │ (uploads)
           ↓                                ↓
        ┌─────────────────────────┐  storage/app/public/
        │ PostgreSQL Container    │  ├── logos/
        │ - Port: 5432            │  └── business_images/
        │ - DB: wen_sar           │
        │ - User: wen_sar_user    │
        │ - Network: wen-sar-net  │
        └─────────────────────────┘
```

### **Docker Setup**

**Docker Images**:
1. **Frontend Build Stage** (`node:22-alpine`)
   - Install npm dependencies
   - Run `npm run build` (Vite compilation)
   - Output: `public/build/`

2. **Production Stage** (`php:8.2-apache`)
   - Install PHP extensions: pdo, pdo_pgsql, pgsql, gd, zip
   - Install Composer
   - Copy Laravel code + built assets
   - Set Apache DocumentRoot to `/var/www/html/public`
   - Enable mod_rewrite
   - Create storage directories with 775 permissions

**Docker Compose Network**:
- Network name: `wen-sar-network`
- Container communication via container names (DNS)
- Example: `DB_HOST=postgres` in Laravel config

**Startup Script** (`start.sh`):
```bash
#!/bin/bash
# Set PHP upload limits
# Clear application cache
# Create storage directories
# Remove old storage symlink and recreate
# Run migrations: php artisan migrate --force
# Run seeders: php artisan db:seed --force
# Cache configuration
# Start Apache2
```

**Environment Variables for Production**:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://wensar.me
DB_CONNECTION=pgsql
DB_HOST=postgres (Docker container name)
DB_PORT=5432
DB_DATABASE=wen_sar
DB_USERNAME=wen_sar_user
DB_PASSWORD=secret123
CACHE_STORE=file (or database)
SESSION_DRIVER=file (or database)
```

### **Deployment Commands**

**Initial Setup**:
```bash
docker run -d --name wen-sar-app --network wen-sar-network \
  -p 8080:80 \
  -e DB_CONNECTION=pgsql \
  -e DB_HOST=postgres \
  ... (all env vars) \
  wen-sar
```

**Rebuild & Redeploy**:
```bash
bash /root/rebuild.sh  # Rebuilds Docker image and containers
```

**Deploy from GitHub**:
```bash
bash /var/www/html/Wen-Sar/deploy.sh  # Pulls latest code and restarts
```

**Server Commands**:
```bash
# Clear views cache
docker exec wen-sar-app php artisan view:clear

# Delete hot file (after rebuild)
docker exec wen-sar-app rm -f /var/www/html/public/hot

# Run artisan commands
docker exec wen-sar-app php artisan <command>
```

### **File Permissions & Storage**

**Key Directories that need write permission**:
- `storage/` - Application cache, logs, uploads
- `bootstrap/cache/` - Bootstrap cache
- `public/storage` - Symlink to storage/app/public

**Docker handling**:
- `chmod -R 777 storage bootstrap/cache`
- `chown -R www-data:www-data storage`

**Storage Symlink**:
- Run `php artisan storage:link` in start.sh
- Creates: `public/storage` → `storage/app/public`
- Accessible via: `asset('storage/logos/...')`

---

## Summary Table

| Component | Type | Purpose | Key Files |
|-----------|------|---------|-----------|
| **Users** | Model + Auth | Public user accounts & authentication | User.php, auth routes, Breeze |
| **Managers** | Model + Auth | Admin accounts with separate auth | Manager.php, manager routes |
| **Businesses** | Model + CRUD | Business listings with full metadata | Business.php, BusinessController |
| **Categories** | Model | Business classification (hierarchical) | Category.php, CategoryController |
| **Locations** | Models | 3-level geographic system (Gov/Dist/Area) | Governorate, District, SubArea |
| **Reviews** | Model | User reviews and ratings | Review.php, ReviewController |
| **Favorites** | Pivot Table | User bookmarked businesses | favorites table |
| **Rankings** | Model | Featured business display order | CategoryBusinessRanking.php |
| **Ads** | Model | Promotional advertisements | Ad.php, AdController |
| **Storage** | File System | User-uploaded images | storage/app/public/ |
| **Frontend** | Blade + Alpine | User interface and interactivity | resources/views/ |
| **Docker** | Infrastructure | Production deployment | Dockerfile, docker-compose |

---

## Security Considerations Implemented

1. **Authentication**: Two separate guards (web + manager)
2. **Authorization**: Policies for business CRUD, Spatie roles/permissions
3. **Input Validation**: Comprehensive validation on all forms
4. **CSRF Protection**: @csrf in all forms
5. **SQL Injection**: Prepared statements via Eloquent ORM
6. **XSS Protection**: Blade auto-escaping, CSP headers
7. **File Uploads**: Type/size validation, stored outside web root
8. **Rate Limiting**: Throttle middleware on sensitive routes
9. **Secure Headers**: CSP, HSTS, X-Frame-Options, etc. (SecurityHeaders middleware)
10. **Password Security**: Bcrypt hashing, dual passwords for managers
11. **Email Verification**: Required for user registration
12. **Soft Deletes**: Businesses can be recovered if accidentally deleted

---

## Database Relationships Diagram

```
Users (1) ──→ (Many) Businesses (owner_id)
        ↓
       (1) ←→ (Many) Reviews
        ↓
       (1) ←→ (Many) Favorites (pivot)

Managers (1) ──→ (Many) Businesses (approved_by)

Businesses (Many) ←→ (1) Districts
              ↓
            (Many) ←→ (1) Governorates
              ↓
            (Many) ←→ (1) SubAreas
              ↓
            (Many) ←→ (1) Categories (can be main or sub)
              ↓
            (Many) ←→ (1) CategoryBusinessRankings
```

---

**This document comprehensively covers every file, every model, every route, and every feature of the Wen-Sar platform. Any AI reading this should have a complete understanding of the entire project architecture and implementation.**
