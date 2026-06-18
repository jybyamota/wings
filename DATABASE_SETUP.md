# Wings User Database Setup

## Overview
The Wings application now includes a robust user database system using JSON-based storage with file locking for concurrent access.

## Files Added

### `/includes/database.php`
- **Database** class that manages user data persistence
- Automatically creates necessary files on first run
- File-based system with atomic writes and locking
- Supports migration from existing `users.json` file

### `/includes/user-manager.php`
- **UserManager** class for all user operations
- Methods for CRUD operations, authentication, and search
- Session and audit logging support

### `/migrate-users.php`
- One-time migration script to import existing users from JSON

## Database Structure

### User Record
```json
{
    "id": 1,
    "email": "user@example.com",
    "name": "User Name",
    "phone": "09123456789",
    "password_hash": "$2y$12$...",
    "created_at": "2026-06-18 16:35:12",
    "updated_at": "2026-06-18 16:35:12",
    "last_login": null,
    "is_active": true
}
```

### Audit Log Entry
```json
{
    "id": 1,
    "user_id": 1,
    "action": "LOGIN",
    "description": "User logged in successfully",
    "ip_address": "192.168.1.1",
    "created_at": "2026-06-18 16:35:12"
}
```

## Setup Instructions

### Step 1: Run Migration
```bash
php migrate-users.php
```
This will:
- Create database file at `data/users-db.json`
- Create audit log at `data/audit-log.json`
- Import existing users from `users.json`
- Display migration status

### Step 2: Update Authentication Code
Replace JSON file reads with UserManager:

**Before (JSON):**
```php
$users = json_decode(file_get_contents('data/users.json'), true);
```

**After (Database):**
```php
require_once __DIR__ . '/includes/user-manager.php';
$userManager = new UserManager();
$user = $userManager->getUserByEmail($email);
```

## Usage Examples

### Create User
```php
$userManager = new UserManager();
$userManager->createUser('user@example.com', 'John Doe', 'password123', '09123456789');
```

### Verify Password
```php
if ($userManager->verifyPassword('user@example.com', 'password123')) {
    // Login successful
    $userManager->updateLastLogin($user['id']);
    $userManager->logAudit($user['id'], 'LOGIN', 'User logged in');
}
```

### Update User
```php
$userManager->updateUser($userId, [
    'name' => 'New Name',
    'phone' => '09987654321'
]);
```

### Get All Users
```php
$users = $userManager->getAllUsers($limit = 50, $offset = 0);
foreach ($users as $user) {
    echo $user['email'] . ': ' . $user['name'] . PHP_EOL;
}
```

### Search Users
```php
$results = $userManager->searchUsers('john', $limit = 20);
```

### User Count
```php
$count = $userManager->getUserCount();
echo "Total active users: " . $count;
```

### Deactivate User
```php
$userManager->deactivateUser($userId);
```

### Change Password
```php
if ($userManager->changePassword($userId, $oldPassword, $newPassword)) {
    $userManager->logAudit($userId, 'PASSWORD_CHANGED', 'User changed password');
}
```

### Audit Logging
```php
$userManager->logAudit($userId, 'ACTION_NAME', 'Description', $_SERVER['REMOTE_ADDR']);

// View audit log
$logs = $userManager->getAuditLog($limit = 100);
foreach ($logs as $log) {
    echo "[{$log['created_at']}] {$log['action']}: {$log['description']}\n";
}
```

## Database File Locations
- **Users:** `data/users-db.json`
- **Audit Log:** `data/audit-log.json`
- **Type:** JSON with file locking
- **Backup:** Recommended to backup regularly

## Security Features
- Passwords hashed with bcrypt (cost: 12)
- File locking prevents race conditions
- No password hashes returned by default
- Audit trail for all activities
- Email validation and normalization
- User activation/deactivation support
- Last login tracking

## Performance Characteristics

### File-Based JSON Approach
- **Advantages:**
  - No database server required
  - Zero setup complexity
  - Easy to backup and version control
  - Human-readable for debugging
  
- **Limitations:**
  - Best for < 10,000 users
  - Not ideal for high-concurrency scenarios
  - Slower than database for large datasets

### For Production Scale-Up
When your user base grows beyond 10,000 users, consider migrating to:
- **MySQL/MariaDB** - Full-featured relational database
- **PostgreSQL** - Advanced open-source database
- **MongoDB** - Document-based NoSQL option

## Migration from JSON
The existing `users.json` file is preserved. To fully transition:
1. Run `migrate-users.php`
2. Verify all users imported correctly with `$userManager->getUserCount()`
3. Update authentication code to use UserManager
4. Archive/delete `users.json` when confident in migration

## Troubleshooting

### Database File Not Created
- Ensure `data/` directory exists and is writable
- Check file system permissions

### Migration Shows No Users
- Verify `users.json` exists and is valid JSON
- Check file is readable by PHP process
- Run migration with `php migrate-users.php` again

### File Lock Errors
- Usually temporary; operations automatically retry
- Check disk space and file permissions
- Ensure no other processes are corrupting files

### Password Verification Fails
- Verify password hashes are bcrypt format (`$2y$` prefix)
- Ensure PHP `password_*` functions are available

## File Format

### users-db.json
JSON array of user objects, updated atomically with file locking.

### audit-log.json
JSON array of audit entries. Automatically maintains last 10,000 entries to prevent unbounded growth.

## API Reference

### UserManager Methods

```php
// Get/Search
getUserByEmail(string $email): ?array
getUserById(int $id): ?array
getAllUsers(int $limit = 50, int $offset = 0): array
getUserCount(): int
searchUsers(string $query, int $limit = 20): array

// Create/Update
createUser(string $email, string $name, string $password, string $phone = ''): bool
updateUser(int $id, array $data): bool
updateLastLogin(int $id): bool

// Authentication
verifyPassword(string $email, string $password): bool
changePassword(int $id, string $oldPassword, string $newPassword): bool

// Management
deactivateUser(int $id): bool
deleteUser(int $id): bool

// Audit
logAudit(?int $userId, string $action, string $description = '', string $ipAddress = ''): bool
getAuditLog(int $limit = 100): array
```
