# Design Document: AFPROTECH Notification System

## Overview

The AFPROTECH Notification System provides real-time updates to students about events, announcements, attendance records, and order status changes. The system features a notification bell icon in the app bar with a badge counter, and a slide-up modal (bottom sheet) that displays categorized notifications.

## Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                     Mobile App (Flutter)                     │
├─────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │   App Bar    │  │ Notification │  │  Bottom      │     │
│  │   (Bell      │→ │  Controller  │→ │  Sheet       │     │
│  │   Icon)      │  │              │  │  Modal       │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│         ↓                  ↓                  ↓             │
│  ┌──────────────────────────────────────────────────────┐  │
│  │         Local Database (SQLite/Hive)                 │  │
│  │  - Notifications Table                               │  │
│  │  - User Preferences Table                            │  │
│  └──────────────────────────────────────────────────────┘  │
│         ↓                                                   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │         API Service Layer                            │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────────┐
│                    Backend (PHP/MySQL)                       │
├─────────────────────────────────────────────────────────────┤
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐     │
│  │  Events API  │  │Announcements │  │ Attendance   │     │
│  │              │  │     API      │  │    API       │     │
│  └──────────────┘  └──────────────┘  └──────────────┘     │
│  ┌──────────────┐  ┌──────────────────────────────────┐   │
│  │  Orders API  │  │  Notifications API               │   │
│  │              │  │  - Get notifications             │   │
│  └──────────────┘  │  - Mark as read                  │   │
│                    │  - Delete notification           │   │
│                    └──────────────────────────────────┘   │
│  ┌──────────────────────────────────────────────────────┐  │
│  │         MySQL Database                               │  │
│  │  - afprotech_notifications                           │  │
│  │  - afprotech_events                                  │  │
│  │  - afprotech_announcements                           │  │
│  │  - afprotech_attendance                              │  │
│  │  - afprotech_orders                                  │  │
│  └──────────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

## Components and Interfaces

### 1. Notification Model (Dart)

```dart
class NotificationModel {
  final String id;
  final String userId;
  final String type; // 'event', 'announcement', 'attendance', 'order'
  final String title;
  final String message;
  final String? imageUrl;
  final Map<String, dynamic>? data; // Additional data for navigation
  final bool isRead;
  final DateTime createdAt;
  
  NotificationModel({
    required this.id,
    required this.userId,
    required this.type,
    required this.title,
    required this.message,
    this.imageUrl,
    this.data,
    required this.isRead,
    required this.createdAt,
  });
  
  factory NotificationModel.fromJson(Map<String, dynamic> json);
  Map<String, dynamic> toJson();
}
```

### 2. Notification Controller (GetX)

```dart
class NotificationController extends GetxController {
  final RxList<NotificationModel> notifications = <NotificationModel>[].obs;
  final RxInt unreadCount = 0.obs;
  final RxString selectedFilter = 'all'.obs;
  
  // Methods
  Future<void> fetchNotifications();
  Future<void> markAsRead(String notificationId);
  Future<void> markAllAsRead();
  Future<void> deleteNotification(String notificationId);
  List<NotificationModel> getFilteredNotifications();
  void showNotificationModal(BuildContext context);
  Future<void> handleNotificationTap(NotificationModel notification);
}
```

### 3. Notification API Service

```dart
class NotificationApiService {
  static Future<List<NotificationModel>> getNotifications(String userId);
  static Future<bool> markNotificationAsRead(String notificationId);
  static Future<bool> deleteNotification(String notificationId);
  static Future<int> getUnreadCount(String userId);
}
```

### 4. Backend API Endpoints

#### Get Notifications
- **Endpoint**: `GET /modules/afprotech/backend/afprotechs_get_notifications.php`
- **Parameters**: `user_id`, `type` (optional), `limit` (optional)
- **Response**:
```json
{
  "success": true,
  "data": [
    {
      "id": "1",
      "user_id": "2021-12345",
      "type": "event",
      "title": "New Event: Tech Workshop",
      "message": "Join us for an exciting tech workshop on December 20",
      "image_url": null,
      "data": "{\"event_id\": \"123\"}",
      "is_read": false,
      "created_at": "2025-12-18 10:30:00"
    }
  ],
  "unread_count": 5
}
```

#### Mark as Read
- **Endpoint**: `POST /modules/afprotech/backend/afprotechs_mark_notification_read.php`
- **Body**: `{ "notification_id": "1" }`
- **Response**: `{ "success": true, "message": "Notification marked as read" }`

#### Delete Notification
- **Endpoint**: `POST /modules/afprotech/backend/afprotechs_delete_notification.php`
- **Body**: `{ "notification_id": "1" }`
- **Response**: `{ "success": true, "message": "Notification deleted" }`

## Data Models

### Database Schema (MySQL)

```sql
CREATE TABLE IF NOT EXISTS afprotech_notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    type ENUM('event', 'announcement', 'attendance', 'order', 'product_approval') NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    image_url VARCHAR(500) NULL,
    data JSON NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_user_id (user_id),
    INDEX idx_type (type),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at),
    INDEX idx_user_unread (user_id, is_read)
);
```

### Local Storage (Hive/SQLite)

```dart
@HiveType(typeId: 1)
class NotificationCache {
  @HiveField(0)
  String id;
  
  @HiveField(1)
  String userId;
  
  @HiveField(2)
  String type;
  
  @HiveField(3)
  String title;
  
  @HiveField(4)
  String message;
  
  @HiveField(5)
  String? imageUrl;
  
  @HiveField(6)
  String? dataJson;
  
  @HiveField(7)
  bool isRead;
  
  @HiveField(8)
  DateTime createdAt;
}
```

## Correctness Properties

*A property is a characteristic or behavior that should hold true across all valid executions of a system-essentially, a formal statement about what the system should do. Properties serve as the bridge between human-readable specifications and machine-verifiable correctness guarantees.*

### Property 1: Notification Badge Count Accuracy
*For any* user session, the notification badge count should always equal the number of unread notifications in the database for that user.
**Validates: Requirements 1.2**

### Property 2: Notification Creation Consistency
*For any* new event, announcement, attendance record, or order status change, a corresponding notification should be created in the database with the correct type and user_id.
**Validates: Requirements 2.1, 3.1, 4.1, 5.1**

### Property 3: Read Status Persistence
*For any* notification marked as read, querying the database should return that notification with is_read = true, and the unread count should decrease by one.
**Validates: Requirements 1.4, 7.3**

### Property 4: Notification Filtering Correctness
*For any* selected filter type, the displayed notifications should only include notifications matching that type, and the "All" filter should display all notifications.
**Validates: Requirements 9.2, 9.3**

### Property 5: Notification Deletion Consistency
*For any* deleted notification, subsequent queries should not return that notification, and it should be removed from both local and remote storage.
**Validates: Requirements 6.4, 7.4**

### Property 6: Navigation Data Integrity
*For any* notification with associated data, tapping the notification should navigate to the correct screen with the correct parameters extracted from the data field.
**Validates: Requirements 2.5, 3.3, 4.3, 5.3**

### Property 7: User-Specific Notifications
*For any* notification query, only notifications belonging to the authenticated user should be returned.
**Validates: Requirements 4.4, 7.5**

### Property 8: Notification Ordering
*For any* list of notifications, they should be sorted by created_at timestamp in descending order (newest first).
**Validates: Requirements 6.1**

### Property 9: Real-time Update Consistency
*For any* new notification created while the app is open, the notification should appear in the list and the badge count should update within 30 seconds.
**Validates: Requirements 8.1, 8.2**

### Property 10: Settings Persistence
*For any* notification type disabled in settings, no notifications of that type should be displayed, and the setting should persist across app restarts.
**Validates: Requirements 10.2, 10.4**

## Error Handling

### Network Errors
- **Scenario**: API call fails due to network issues
- **Handling**: 
  - Display cached notifications from local storage
  - Show a subtle indicator that data may be stale
  - Retry automatically when connection is restored
  - Queue any mark-as-read or delete actions for later sync

### Database Errors
- **Scenario**: Local database operation fails
- **Handling**:
  - Log error details for debugging
  - Show user-friendly error message
  - Attempt to recover by re-initializing database
  - Fall back to in-memory storage if database is corrupted

### Invalid Notification Data
- **Scenario**: Notification data is malformed or missing required fields
- **Handling**:
  - Skip the invalid notification
  - Log the error with notification ID
  - Continue processing other notifications
  - Do not crash the app

### Navigation Errors
- **Scenario**: Notification references a deleted or invalid resource
- **Handling**:
  - Show error message: "This content is no longer available"
  - Mark notification as read
  - Offer option to delete the notification

## Testing Strategy

### Unit Tests
- Test notification model serialization/deserialization
- Test notification controller methods (fetch, mark as read, delete)
- Test API service methods with mocked responses
- Test filter logic for different notification types
- Test badge count calculation

### Property-Based Tests
- **Property 1 Test**: Generate random sets of notifications with varying read statuses, verify badge count equals unread count
- **Property 2 Test**: Generate random events/announcements/orders, verify notifications are created with correct types
- **Property 3 Test**: Generate random notifications, mark as read, verify database reflects changes
- **Property 4 Test**: Generate mixed notification types, apply filters, verify only matching types are returned
- **Property 5 Test**: Generate notifications, delete random ones, verify they don't appear in subsequent queries
- **Property 6 Test**: Generate notifications with various data payloads, verify navigation extracts correct parameters
- **Property 7 Test**: Generate notifications for multiple users, verify queries only return user-specific notifications
- **Property 8 Test**: Generate notifications with random timestamps, verify they're sorted newest-first
- **Property 9 Test**: Simulate real-time notification creation, verify UI updates within time limit
- **Property 10 Test**: Toggle notification settings, verify filtered notifications match enabled types

### Integration Tests
- Test end-to-end flow: create event → notification appears → tap notification → navigate to event
- Test notification persistence across app restarts
- Test sync behavior when going offline and coming back online
- Test notification modal slide-up animation and interaction

### UI Tests
- Test notification bell icon displays correct badge count
- Test bottom sheet modal slides up smoothly
- Test notification list scrolling and item rendering
- Test swipe-to-delete gesture
- Test filter tabs switching
- Test empty state display

## Implementation Notes

### Performance Considerations
- Implement pagination for notification list (load 20 at a time)
- Cache notifications locally to reduce API calls
- Use debouncing for real-time updates (check every 30 seconds, not continuously)
- Optimize database queries with proper indexes
- Use lazy loading for notification images

### UI/UX Guidelines
- Use Material Design bottom sheet for modal
- Animate badge count changes
- Show skeleton loaders while fetching
- Use pull-to-refresh gesture
- Implement haptic feedback on interactions
- Use distinct icons for each notification type
- Show relative timestamps ("2 hours ago")

### Security Considerations
- Validate user_id on backend to prevent unauthorized access
- Sanitize notification content to prevent XSS
- Use HTTPS for all API calls
- Implement rate limiting on notification creation
- Validate notification data structure before processing

