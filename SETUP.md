# Quick Setup Guide

## Step 1: Install Dependencies
```bash
composer install
npm install
```

## Step 2: Configure Environment
Copy `.env.example` to `.env` and update:
- Database credentials
- PayStack keys (PUBLIC_KEY and SECRET_KEY)
- WhatsApp number for registrar
- PassKit credentials (optional)

## Step 3: Generate Application Key
```bash
php artisan key:generate
```

## Step 4: Run Migrations
```bash
php artisan migrate
```

## Step 5: Seed Admin User
```bash
php artisan db:seed
```

Default admin credentials:
- Email: admin@example.com
- Password: password

**Change this immediately after first login!**

## Step 6: Build Frontend Assets
```bash
npm run build
```

## Step 7: Start Server
```bash
php artisan serve
```

## Step 8: Access the Application
- Visit: http://localhost:8000
- Login as admin
- Generate invite codes for affiliate agents
- Approve affiliate agent registrations

## Next Steps

1. **Configure PayStack Webhook**
   - Go to your PayStack dashboard
   - Add webhook URL: `https://yourdomain.com/payment/webhook`
   - This is critical for automatic payment processing

2. **Create First Affiliate Agent**
   - Login as admin
   - Generate an invite code
   - Share the code with the affiliate agent
   - Agent registers using the code
   - Approve the agent registration

3. **Test Student Registration**
   - Use affiliate agent's referral link
   - Register a test student
   - Complete payment via PayStack
   - Verify commission is credited to agent

## Important Notes

- Registration fee: 150 GHS
- Commission per registration: 40 GHS
- Minimum withdrawal: 200 GHS
- Agent invite codes: 6 capital letters
- All sensitive operations require proper authentication

## Troubleshooting

**Migration errors?**
- Ensure database exists and credentials are correct
- Check PHP version (8.2+)

**Payment not working?**
- Verify PayStack keys in `.env`
- Check webhook URL is accessible
- Review logs: `storage/logs/laravel.log`

**Can't login as admin?**
- Run seeder again: `php artisan db:seed --class=AdminUserSeeder`
- Or create manually via tinker: `php artisan tinker`

