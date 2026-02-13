# Google Service Account Setup Guide

## 📌 Overview

This guide helps admins/teachers find the service account email needed to share Google Sheets with the system.

## 🔑 Service Account Email

The service account email is the "robot" account that the system uses to access Google Sheets.

### Where to Find It

**Option 1: Via Web UI (Admin Dashboard)**

```
1. Go to Admin Panel → Settings/Configuration
2. Look for "Service Account Email"
3. Copy the email address
```

**Option 2: Via File System**

```bash
# SSH/Terminal into server
cat storage/app/google-sheets.json | grep client_email
```

Output will show:
```json
"client_email": "google-sheets-api@valued-geode-487120-b5.iam.gserviceaccount.com"
```

**Option 3: Via Laravel Tinker**

```bash
php artisan tinker
>>> dd(config('services.google'));
```

Should output:
```php
[
  'service_account_json' => '/path/to/storage/app/google-sheets.json'
]
```

## 🔐 How to Share Google Sheet with Service Account

### Step-by-Step

1. **Get service account email** (from above)
   - Example: `google-sheets-api@valued-geode-487120-b5.iam.gserviceaccount.com`

2. **Open your Google Sheet**
   - Go to: https://docs.google.com/spreadsheets/d/YOUR_SHEET_ID/edit

3. **Click Share** (top right corner)

4. **Paste service account email**
   - In the "Share with people and groups" field
   - Paste: `google-sheets-api@valued-geode-487120-b5.iam.gserviceaccount.com`

5. **Select Permission: Editor**
   - ⚠️ IMPORTANT: Must be "Editor" (not Viewer)

6. **Click Share**
   - Don't send notification (it's a robot account)

7. **Confirm** - Sheet is now shared

### What Teachers See

After sharing, teachers:
1. Register sheet in dashboard
2. Dashboard will fetch data successfully
3. Scores import to database

### What Happens if NOT Shared

❌ **Error when syncing:**
```
Permission denied: The service account does not have access to this sheet
```

**Solution:** Share sheet with service account (Editor permission)

## ✅ Verification

To verify sharing works:

### Via Dashboard

1. Register a test sheet
2. Click "Sync"
3. If it works → Sharing is correct ✅
4. If error → Sheet not shared or wrong email

### Via Command Line

```bash
php artisan tinker

$client = app(\App\Services\GoogleSheetsClient::class);
$data = $client->readTab('YOUR_SHEET_ID', 'TabName');
dd($data);
```

Should return sheet data without errors.

## 📋 Service Account Credentials Structure

The `storage/app/google-sheets.json` file contains:

```json
{
  "type": "service_account",
  "project_id": "valued-geode-487120-b5",
  "private_key_id": "...",
  "private_key": "-----BEGIN PRIVATE KEY-----\n...\n-----END PRIVATE KEY-----\n",
  "client_email": "google-sheets-api@valued-geode-487120-b5.iam.gserviceaccount.com",
  "client_id": "123456789",
  "auth_uri": "https://accounts.google.com/o/oauth2/auth",
  "token_uri": "https://oauth2.googleapis.com/token",
  "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
  "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/google-sheets-api%40..."
}
```

**Important fields:**
- `client_email` - The email to share sheets with
- `private_key` - Used to authenticate with Google (keep secret!)

## 🚀 Create New Service Account (If Needed)

If you need to create a NEW service account:

### Setup via Google Cloud Console

1. Go to: https://console.cloud.google.com/
2. Create new project or select existing
3. Enable Google Sheets API:
   - APIs & Services → Library
   - Search: "Google Sheets API"
   - Click Enable
4. Create service account:
   - APIs & Services → Credentials
   - Create Credentials → Service Account
   - Fill in details
   - Create and Continue
   - Grant Editor role (optional step)
   - Create key (JSON format)
5. Download JSON file
6. Save to: `storage/app/google-sheets.json`

### Add to Project

```bash
# Replace the existing file
mv downloaded-service-account.json storage/app/google-sheets.json

# Verify permissions
chmod 600 storage/app/google-sheets.json

# Clear cache
php artisan config:clear
```

## 🔍 Common Issues

### "Permission Denied"

**Problem:** Sheet not shared with service account

**Solution:**
1. Get service account email
2. Open sheet
3. Click Share
4. Add email with Editor permission

### "Invalid Credentials"

**Problem:** `google-sheets.json` file is missing or corrupted

**Solution:**
1. Check file exists: `ls storage/app/google-sheets.json`
2. Verify it's valid JSON: `cat storage/app/google-sheets.json | jq .`
3. Re-download from Google Cloud Console if corrupted

### "Service Account Has No Access"

**Problem:** Wrong email in sharing settings

**Solution:**
1. Get correct email from `google-sheets.json` → `client_email`
2. Go to sheet
3. Click Share
4. Remove wrong email
5. Add correct email from step 1

### "Quota Exceeded"

**Problem:** Too many requests to Google Sheets API

**Solution:**
1. Check if multiple syncs running simultaneously
2. Wait a few minutes
3. Retry
4. Contact Google Cloud support for quota increase

## 📊 Admin Dashboard Section

Add to admin dashboard a section showing:

```
Service Account Email: google-sheets-api@valued-geode-487120-b5.iam.gserviceaccount.com

[Copy Email] [Share Instructions]

Last API Call: 2 minutes ago
API Quota Used: 45%
```

## 🔐 Security Best Practices

⚠️ **IMPORTANT:**

1. **Never share the full `google-sheets.json` file**
   - Only share the `client_email` part
   - Keep the `private_key` secret!

2. **Don't commit to Git**
   - Add to `.gitignore`: `storage/app/google-sheets.json`
   - Never push credentials to GitHub/GitLab

3. **File permissions**
   ```bash
   chmod 600 storage/app/google-sheets.json
   chown www-data:www-data storage/app/google-sheets.json
   ```

4. **Rotate credentials**
   - Create new service account every 6-12 months
   - Delete old account in Google Cloud Console

5. **Limit access**
   - Only share sheets with service account (not full project access)
   - Use "Editor" permission, not "Owner"

## 📚 References

- [Google Sheets API Documentation](https://developers.google.com/sheets/api)
- [Service Account Setup](https://cloud.google.com/docs/authentication/getting-started)
- [Laravel Configuration](config/services.php)

## ✅ Checklist

- [ ] Found service account email in `google-sheets.json`
- [ ] Shared test Google Sheet with service account
- [ ] Given Editor permission
- [ ] Verified sheet sync works from dashboard
- [ ] Documented email for teachers
- [ ] Added to admin panel/documentation
- [ ] Secured `google-sheets.json` file
- [ ] Added to `.gitignore`

---

**When stuck:** Check `storage/logs/laravel.log` for detailed error messages
