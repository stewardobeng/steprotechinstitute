# Files to Update on Live Server

This document lists all files that need to be updated on your live server for the Message Template System.

## 📋 Database Migration

**Run this command on live server:**
```bash
php artisan migrate
```

**File to upload:**
- `database/migrations/2025_12_06_153519_create_message_templates_table.php`

---

## 🗄️ Database Seeder

**Run this command on live server to populate templates:**
```bash
php artisan db:seed --class=MessageTemplateSeeder
```

**File to upload:**
- `database/seeders/MessageTemplateSeeder.php`

---

## 📁 New Files to Create/Upload

### Models
- `app/Models/MessageTemplate.php`

### Controllers
- `app/Http/Controllers/Admin/MessageController.php` (UPDATED - already exists, needs update)
- `app/Http/Controllers/Admin/MessageTemplateController.php` (NEW)

### Mail Classes
- `app/Mail/CustomMessageMail.php` (UPDATED - already exists, needs update)

### Views
- `resources/views/admin/messages/index.blade.php` (UPDATED - already exists, needs update)
- `resources/views/admin/message-templates/index.blade.php` (NEW)
- `resources/views/admin/message-templates/create.blade.php` (NEW)
- `resources/views/admin/message-templates/edit.blade.php` (NEW)
- `resources/views/emails/custom-message.blade.php` (UPDATED - already exists, needs update)

### Routes
- `routes/web.php` (UPDATED - already exists, needs update)

### Layout
- `resources/views/layouts/app.blade.php` (UPDATED - already exists, needs update)

### Services
- `app/Services/NotificationService.php` (UPDATED - already exists, needs update)

---

## 📝 Complete File List

### New Files (Create these on live server):
1. `app/Models/MessageTemplate.php`
2. `app/Http/Controllers/Admin/MessageTemplateController.php`
3. `resources/views/admin/message-templates/index.blade.php`
4. `resources/views/admin/message-templates/create.blade.php`
5. `resources/views/admin/message-templates/edit.blade.php`
6. `database/migrations/2025_12_06_153519_create_message_templates_table.php`
7. `database/seeders/MessageTemplateSeeder.php`

### Updated Files (Replace these on live server):
1. `app/Http/Controllers/Admin/MessageController.php`
2. `app/Mail/CustomMessageMail.php`
3. `resources/views/admin/messages/index.blade.php`
4. `resources/views/emails/custom-message.blade.php`
5. `routes/web.php`
6. `resources/views/layouts/app.blade.php`
7. `app/Services/NotificationService.php`

---

## 🚀 Deployment Steps

1. **Upload all files** listed above to your live server

2. **Run migration:**
   ```bash
   php artisan migrate
   ```

3. **Seed the templates:**
   ```bash
   php artisan db:seed --class=MessageTemplateSeeder
   ```

4. **Clear cache (if needed):**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   ```

5. **Rebuild frontend (if needed):**
   ```bash
   npm run build
   ```

---

## ✅ Verification

After deployment, verify:
- [ ] Can access `/admin/message-templates` page
- [ ] Can create new templates
- [ ] Can edit existing templates
- [ ] Can delete templates
- [ ] Templates appear in message sending form dropdown
- [ ] Selecting a template auto-fills subject and message
- [ ] Sending messages with templates works correctly
- [ ] Variables are replaced correctly ({{name}}, {{date}}, etc.)

---

## 📌 Notes

- The seeder will populate 17 pre-defined message templates
- Templates are organized by categories
- Variables like `{{name}}`, `{{date}}`, `{{student_id}}` are automatically replaced
- Templates can be activated/deactivated
- All templates use the branded email template when sending emails
