# Files to Replace on Live Server

## View Files (Blade Templates)
These files contain the responsive design updates and should be replaced on the live server:

1. **resources/views/welcome.blade.php**
   - Home page with hamburger menu
   - Responsive navigation and all sections

2. **resources/views/layouts/app.blade.php**
   - Main app layout with responsive sidebar
   - Hamburger menu functionality

3. **resources/views/admin/dashboard.blade.php**
   - Admin dashboard with responsive stats and tables

4. **resources/views/admin/students/index.blade.php**
   - Admin students list with responsive table

5. **resources/views/affiliate/dashboard.blade.php**
   - Affiliate dashboard with responsive layout

6. **resources/views/affiliate/students/index.blade.php**
   - Affiliate students list with responsive table

7. **resources/views/student/dashboard.blade.php**
   - Student dashboard with responsive cards

8. **resources/views/student/register.blade.php**
   - Student registration form with responsive layout

9. **resources/views/auth/login.blade.php**
   - Login page with responsive design

## Frontend Build Files (Compiled Assets)
These are the compiled CSS and JavaScript files that need to be deployed:

1. **public/build/manifest.json**
   - Vite manifest file (maps source files to compiled assets)

2. **public/build/assets/app-CGvPCQN5.css**
   - Compiled CSS file (contains all Tailwind styles)

3. **public/build/assets/app-C-X0ZeQP.js**
   - Compiled JavaScript file (contains Alpine.js and app logic)

## Deployment Steps

### Option 1: Manual File Replacement
1. Upload all 9 view files to their respective locations in `resources/views/`
2. Upload all 3 build files to `public/build/` directory
3. Clear Laravel cache: `php artisan cache:clear`
4. Clear view cache: `php artisan view:clear`

### Option 2: Git Pull (Recommended)
If your live server has git access:
1. SSH into your live server
2. Navigate to project directory
3. Run: `git pull origin main`
4. Run: `npm run build` (if node_modules are available)
5. Clear caches: `php artisan cache:clear && php artisan view:clear`

### Option 3: Using Deployment Script
```bash
# On live server
cd /path/to/your/project
git pull origin main
npm install  # if needed
npm run build
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

## Important Notes

⚠️ **Before Deployment:**
- Backup your current files
- Test on staging environment if possible
- Ensure Node.js and npm are available on live server if rebuilding

⚠️ **After Deployment:**
- Clear all Laravel caches
- Test the responsive design on mobile devices
- Verify hamburger menus work correctly
- Check that all tables scroll properly on mobile

## File Count Summary
- **View Files:** 9 files
- **Build Files:** 3 files
- **Total Files to Deploy:** 12 files

## Quick Copy Commands (Linux/Mac)
```bash
# View files
scp resources/views/welcome.blade.php user@server:/path/to/project/resources/views/
scp resources/views/layouts/app.blade.php user@server:/path/to/project/resources/views/layouts/
scp resources/views/admin/dashboard.blade.php user@server:/path/to/project/resources/views/admin/
scp resources/views/admin/students/index.blade.php user@server:/path/to/project/resources/views/admin/students/
scp resources/views/affiliate/dashboard.blade.php user@server:/path/to/project/resources/views/affiliate/
scp resources/views/affiliate/students/index.blade.php user@server:/path/to/project/resources/views/affiliate/students/
scp resources/views/student/dashboard.blade.php user@server:/path/to/project/resources/views/student/
scp resources/views/student/register.blade.php user@server:/path/to/project/resources/views/student/
scp resources/views/auth/login.blade.php user@server:/path/to/project/resources/views/auth/

# Build files
scp -r public/build/* user@server:/path/to/project/public/build/
```

