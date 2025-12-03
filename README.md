# SteProTECH Institute - Student Registration & Affiliate Management System

A comprehensive Laravel 11 application for managing student registrations through affiliate agents with payment processing, commission tracking, and withdrawal management. Built with modern web technologies and featuring a beautiful, responsive UI with dark/light theme support.

## 🚀 Features

### Core Functionality
- **Three User Roles**: Admin, Affiliate Agent, and Student with role-based access control
- **Affiliate System**: Agents can generate referral links and track commissions
- **Payment Integration**: PayStack payment gateway for secure student registration fee processing
- **Commission Tracking**: Automatic commission calculation (40 GHS per successful registration)
- **Withdrawal Management**: Agents can request withdrawals (minimum 200 GHS)
- **Invite Code System**: 
  - Admin-generated codes for agent registration with usage limits
  - Agent-generated codes for student tracking
- **Two-Factor Authentication**: PassKit integration for enhanced security
- **Classroom Management**: Integration with Teachmint for live sessions

### User Interface
- **Modern UI/UX**: Clean, professional design with Material Symbols icons
- **Dark/Light Theme**: Full theme support with user preference persistence
- **Responsive Design**: Works seamlessly on desktop, tablet, and mobile devices
- **Real-time Analytics**: Interactive charts and statistics dashboards
- **WhatsApp Integration**: Direct contact links for registrar communication

### Admin Features
- Comprehensive admin dashboard with analytics
- Manage affiliate agents (approve/reject registrations)
- Manage students and registrations
- Generate and manage invite codes
- Process withdrawal requests
- Configure system settings (PayStack, WhatsApp, App Name, etc.)
- Classroom ID management for Teachmint integration
- Student classroom access approval

### Affiliate Agent Features
- Personal dashboard with earnings and statistics
- Generate referral links
- Create invite codes for student tracking
- View referred students and commission history
- Request withdrawals
- Track earnings over time with visual charts

### Student Features
- Easy registration via affiliate links or invite codes
- Secure payment processing
- Student ID generation after payment
- Access to classroom information and Teachmint setup
- Resources section (recordings and downloads)
- Profile management

## 📋 Requirements

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Database**: MySQL 5.7+ / MariaDB 10.3+ / PostgreSQL 10+ / SQLite 3.8.8+
- **Node.js**: 18.x or higher
- **NPM**: 9.x or higher
- **Web Server**: Apache/Nginx (or PHP built-in server for development)

## 🔧 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/stewardobeng/steprotechinstitute.git
cd steprotechinstitute
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

### 5. Configure Environment Variables

Edit the `.env` file and configure the following:

#### Database Configuration
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=steproclass
DB_USERNAME=root
DB_PASSWORD=your_password
```

#### PayStack Configuration
```env
PAYSTACK_PUBLIC_KEY=your_paystack_public_key
PAYSTACK_SECRET_KEY=your_paystack_secret_key
PAYSTACK_MERCHANT_EMAIL=your_email@example.com
```

#### WhatsApp Number (for registrar contact)
```env
WHATSAPP_NUMBER=233244775129
```
Format: Country code + number (no + sign, no spaces)

#### PassKit Configuration (Optional - for 2FA)
```env
PASSKIT_API_KEY=your_passkit_api_key
PASSKIT_API_SECRET=your_passkit_api_secret
PASSKIT_BASE_URL=https://api.passkit.io
```

### 6. Database Setup

Create the database:

```sql
CREATE DATABASE steproclass CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Run migrations:

```bash
php artisan migrate
```

Seed the database (creates admin user):

```bash
php artisan db:seed
```

### 7. Storage Link

Create symbolic link for storage:

```bash
php artisan storage:link
```

### 8. Build Frontend Assets

For production:

```bash
npm run build
```

For development:

```bash
npm run dev
```

### 9. Start Development Server

```bash
php artisan serve
```

Visit `http://localhost:8000` in your browser.

## 🔐 Default Admin Credentials

After running the seeder, you can login with:

- **Email**: `admin@example.com`
- **Password**: `password`

**⚠️ IMPORTANT**: Change the admin password immediately after first login!

## 📖 Usage Guide

### Admin Dashboard

1. **Generate Invite Codes**
   - Navigate to "Invite Codes" in the admin panel
   - Set usage limit and expiration (optional)
   - Generate codes for affiliate agent registration

2. **Approve Affiliate Agents**
   - View pending affiliate registrations
   - Review agent details
   - Approve or reject registrations

3. **Manage Students**
   - View all student registrations
   - Filter by payment status
   - Verify student information

4. **Process Withdrawals**
   - Review withdrawal requests
   - Approve or reject requests
   - Track withdrawal history

5. **Configure Settings**
   - Set PayStack keys (can be updated via admin panel)
   - Configure WhatsApp number
   - Set application name
   - Manage classroom ID for Teachmint

### Affiliate Agent Workflow

1. **Registration**
   - Use admin-generated invite code
   - Complete registration form
   - Wait for admin approval

2. **After Approval**
   - Access dashboard
   - Copy referral link
   - Share link with potential students
   - Generate invite codes for tracking

3. **Track Performance**
   - View earnings and statistics
   - Monitor referred students
   - Check commission history

4. **Request Withdrawal**
   - Ensure minimum balance (200 GHS)
   - Submit withdrawal request
   - Wait for admin approval

### Student Registration Flow

1. **Access Registration**
   - Click affiliate referral link OR
   - Use invite code during registration

2. **Complete Registration**
   - Fill in personal information
   - Submit registration form

3. **Make Payment**
   - Click "Complete Payment" button
   - Pay via PayStack (150 GHS)
   - Receive Student ID after successful payment

4. **Access Resources**
   - View Student ID
   - Access classroom information
   - Download resources (if available)

## 💼 Business Logic

- **Registration Fee**: 150 GHS per student
- **Commission Rate**: 40 GHS per successful registration payment
- **Minimum Withdrawal**: 200 GHS
- **Agent Invite Codes**: 6 capital letters, unlimited uses
- **Admin Invite Codes**: Configurable usage limits and expiration dates

## 🔒 Security Features

- **Role-Based Access Control (RBAC)**: Middleware protection for routes
- **CSRF Protection**: All forms protected with CSRF tokens
- **SQL Injection Prevention**: Eloquent ORM with parameter binding
- **XSS Prevention**: Blade template auto-escaping
- **Password Hashing**: Bcrypt with configurable rounds
- **Two-Factor Authentication**: Optional PassKit integration
- **Secure File Uploads**: Validated file types and sizes
- **Security Headers**: CSP, X-Frame-Options, HSTS, etc.
- **Rate Limiting**: Protection against brute-force attacks
- **Input Validation**: Server-side validation on all inputs

## 🌐 Webhook Configuration

### PayStack Webhook

Configure the webhook URL in your PayStack dashboard:

```
https://yourdomain.com/payment/webhook
```

This webhook handles:
- Payment verification
- Automatic commission processing
- Registration status updates

**Important**: Ensure your webhook URL is accessible and uses HTTPS in production.

## 📁 Project Structure

```
steprotechinstitute/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin controllers
│   │   │   ├── Affiliate/      # Affiliate agent controllers
│   │   │   ├── Student/        # Student controllers
│   │   │   ├── PaymentController.php
│   │   │   └── ProfileController.php
│   │   ├── Middleware/
│   │   │   ├── RoleMiddleware.php
│   │   │   ├── EnsureAffiliateApproved.php
│   │   │   └── SecurityHeaders.php
│   │   └── Requests/           # Form request validation
│   ├── Models/                 # Eloquent models
│   │   ├── User.php
│   │   ├── StudentRegistration.php
│   │   ├── AffiliateAgent.php
│   │   ├── InviteCode.php
│   │   ├── Withdrawal.php
│   │   └── Setting.php
│   └── Services/               # Business logic services
│       ├── PaymentService.php
│       ├── CommissionService.php
│       ├── PassKitService.php
│       └── TwoFactorService.php
├── config/                     # Configuration files
│   ├── paystack.php
│   ├── passkit.php
│   └── ...
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── views/                 # Blade templates
│   │   ├── admin/
│   │   ├── affiliate/
│   │   ├── student/
│   │   ├── layouts/
│   │   └── profile/
│   ├── js/                    # JavaScript files
│   └── css/                   # CSS files
├── routes/
│   └── web.php                # Web routes
├── public/                    # Public assets
└── storage/                   # Storage (logs, uploads)
```

## 🛠️ Development

### Running Tests

```bash
php artisan test
```

### Code Style

The project uses Laravel Pint for code formatting:

```bash
./vendor/bin/pint
```

### Frontend Development

Watch for changes during development:

```bash
npm run dev
```

Build for production:

```bash
npm run build
```

### Database Migrations

Create a new migration:

```bash
php artisan make:migration create_example_table
```

Run migrations:

```bash
php artisan migrate
```

Rollback last migration:

```bash
php artisan migrate:rollback
```

## 🎨 Theme Customization

The application supports both dark and light themes. Users can toggle themes from their profile dropdown. Theme preference is stored in localStorage.

### Customizing Colors

Edit `tailwind.config.js` to customize the color scheme:

```javascript
theme: {
    extend: {
        colors: {
            "primary": "#137fec",
            // Add your custom colors here
        }
    }
}
```

## 📱 API Integration

### PayStack

The application integrates with PayStack for payment processing:
- Inline payment modal
- Webhook support for payment verification
- Automatic commission processing

### Teachmint

Classroom management integration:
- Classroom ID configuration
- Student access approval
- Registration instructions

## 🐛 Troubleshooting

### Common Issues

**Migration errors?**
- Ensure database exists and credentials are correct
- Check PHP version (8.2+)
- Verify database user has proper permissions

**Payment not working?**
- Verify PayStack keys in `.env` or admin settings
- Check webhook URL is accessible
- Ensure callback URL is correctly configured

**Charts not displaying?**
- Check browser console for errors
- Verify Chart.js CDN is accessible
- Check Content Security Policy settings

**Theme not persisting?**
- Clear browser cache
- Check localStorage is enabled
- Verify JavaScript is enabled

## 📝 License

This project is proprietary software. All rights reserved.

## 👥 Support

For issues, questions, or contributions, please contact the development team.

## 🙏 Acknowledgments

- Laravel Framework
- PayStack Payment Gateway
- Chart.js for data visualization
- Tailwind CSS for styling
- Material Symbols for icons

---

**Built with ❤️ for SteProTECH Institute**
