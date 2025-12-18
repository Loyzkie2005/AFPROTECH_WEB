# Deployment Guide - Remote Server Configuration

## Problem Fixed
When transferring files to WinSCP (remote server) and turning off local XAMPP, the mobile app was stuck loading because it was trying to connect to local servers with long timeouts before falling back to the online server.

## Solution Implemented

### 1. **Aggressive Timeout System**
- **Online servers** (103.125.219.236): 10 seconds timeout
- **Local servers** (192.168.x.x): 1 second timeout
- This ensures very quick failover when XAMPP is off

### 2. **Production Mode Flag**
Located in: `centralized_societree/lib/modules/afprotechs/config/api_config.dart`

```dart
static const bool isProduction = true; // Set to false for development
```

**When `isProduction = true`:**
- ONLY online servers are used (NO local servers at all)
- Local servers are completely excluded in production
- Instant connection to online server when Apache is off

**When `isProduction = false`:**
- All servers are tried (useful for development)
- Local XAMPP servers are included in rotation

### 3. **Server Priority Order (Production Mode)**
1. `http://103.125.219.236/societrees_web` (Primary)
2. `http://103.125.219.236` (Alternative path)
3. `http://103.125.219.236/public_html` (Fallback)
4. `http://103.125.219.236/htdocs` (Fallback)
5. Local servers (last resort)

### 4. **Test Connection Endpoint**
Created: `modules/afprotech/backend/test_connection.php`

Test if server is responding:
```
http://103.125.219.236/societrees_web/modules/afprotech/backend/test_connection.php
```

## How to Deploy

### Step 1: Transfer Files via WinSCP
1. Connect to your remote server (103.125.219.236)
2. Upload all files from `modules/afprotech/backend/` to the server
3. Ensure file permissions are correct (644 for PHP files)

### Step 2: Configure Production Mode
In `centralized_societree/lib/modules/afprotechs/config/api_config.dart`:
```dart
static const bool isProduction = true; // Enable production mode
```

### Step 3: Rebuild the App
```bash
cd centralized_societree
flutter clean
flutter pub get
flutter build apk --release
```

### Step 4: Test Connection
1. Turn off local XAMPP
2. Open the app on mobile
3. App should connect to online server within 3-5 seconds
4. Check console logs for connection attempts

## Troubleshooting

### App Still Loading Forever
1. Check if remote server is accessible:
   ```
   http://103.125.219.236/societrees_web/modules/afprotech/backend/test_connection.php
   ```
2. Verify `isProduction = true` in api_config.dart
3. Rebuild the app after changes

### Server Not Responding
1. Check WinSCP connection
2. Verify PHP files are uploaded correctly
3. Check server logs for errors
4. Ensure database connection is configured

### Mixed Content Errors
If using HTTPS, update URLs in api_config.dart:
```dart
static const String onlineBaseUrl = 'https://103.125.219.236';
```

## Development vs Production

### Development Mode (`isProduction = false`)
- Use when testing with local XAMPP
- Tries local servers first
- Longer timeouts for debugging

### Production Mode (`isProduction = true`)
- Use when deploying to users
- Prioritizes online servers
- Quick failover (3 seconds)
- Better user experience

## Files Modified
1. `centralized_societree/lib/modules/afprotechs/config/api_config.dart`
   - Added production mode flag
   - Reorganized server priority
   
2. `centralized_societree/lib/modules/afprotechs/service/afprotech_api_service.dart`
   - Added smart timeout system
   - Improved error handling
   
3. `modules/afprotech/backend/test_connection.php`
   - New test endpoint for connectivity checks

## Notes
- Always set `isProduction = true` before releasing to users
- Keep local XAMPP for development and testing
- Remote server should always be accessible for production
- Monitor server logs for any connection issues
