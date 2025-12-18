# Implementation Plan: AFPROTECH Notification System

- [ ] 1. Set up database and backend API
  - Create MySQL table for notifications
  - Implement backend endpoints for CRUD operations
  - _Requirements: 1.1, 7.1_

- [ ] 1.1 Create notifications database table
  - Write SQL migration to create `afprotech_notifications` table
  - Add indexes for performance (user_id, type, is_read, created_at)
  - Test table creation on development database
  - _Requirements: 7.1_

- [ ] 1.2 Implement get notifications API endpoint
  - Create `afprotechs_get_notifications.php`
  - Accept parameters: user_id, type (optional), limit (optional)
  - Return notifications with unread count
  - Filter by user_id and optionally by type
  - Order by created_at DESC
  - _Requirements: 1.5, 6.1, 9.2_

- [ ] 1.3 Implement mark as read API endpoint
  - Create `afprotechs_mark_notification_read.php`
  - Accept notification_id parameter
  - Update is_read to true in database
  - Return success response
  - _Requirements: 1.4, 7.3_

- [ ] 1.4 Implement delete notification API endpoint
  - Create `afprotechs_delete_notification.php`
  - Accept notification_id parameter
  - Delete notification from database
  - Return success response
  - _Requirements: 6.4, 7.4_

- [ ] 1.5 Create notification trigger functions
  - Implement function to create notification when event is created
  - Implement function to create notification when announcement is posted
  - Implement function to create notification when attendance is recorded
  - Implement function to create notification when order status changes
  - Implement function to create notification when student product is approved/rejected
  - _Requirements: 2.1, 3.1, 4.1, 5.1, 5.5_

- [ ] 2. Create Flutter notification model and controller
  - Define notification data model
  - Implement GetX controller for state management
  - Set up local storage for caching
  - _Requirements: 7.1, 7.2_

- [ ] 2.1 Create notification model class
  - Define `NotificationModel` with all required fields
  - Implement `fromJson` and `toJson` methods
  - Add helper methods for type checking
  - _Requirements: 1.1, 2.4, 3.2, 4.2, 5.2_

- [ ] 2.2 Set up local storage with Hive
  - Add Hive dependency to pubspec.yaml
  - Create `NotificationCache` Hive model
  - Initialize Hive box for notifications
  - Implement cache read/write methods
  - _Requirements: 7.1, 7.2_

- [ ] 2.3 Create notification controller
  - Create `NotificationController` extending GetX controller
  - Add observable variables (notifications list, unread count, selected filter)
  - Implement `fetchNotifications()` method
  - Implement `markAsRead()` method
  - Implement `deleteNotification()` method
  - Implement `getFilteredNotifications()` method
  - _Requirements: 1.3, 6.3, 6.4, 9.1, 9.2_

- [ ]* 2.4 Write property test for notification controller
  - **Property 1: Badge count accuracy**
  - **Validates: Requirements 1.2**

- [ ]* 2.5 Write property test for filtering
  - **Property 4: Filtering correctness**
  - **Validates: Requirements 9.2, 9.3**

- [ ] 3. Implement notification API service
  - Create API service methods for backend communication
  - Handle network errors and retries
  - _Requirements: 8.4_

- [ ] 3.1 Create notification API service class
  - Create `NotificationApiService` class
  - Implement `getNotifications()` method with HTTP GET
  - Implement `markNotificationAsRead()` method with HTTP POST
  - Implement `deleteNotification()` method with HTTP POST
  - Implement `getUnreadCount()` method
  - Add error handling for network failures
  - _Requirements: 1.5, 6.3, 6.4, 8.4_

- [ ]* 3.2 Write property test for API service
  - **Property 2: Notification creation consistency**
  - **Validates: Requirements 2.1, 3.1, 4.1, 5.1**

- [ ] 4. Update app bar with notification bell icon
  - Add bell icon to app bar
  - Display badge with unread count
  - Handle icon tap to show modal
  - _Requirements: 1.1, 1.2, 1.3_

- [ ] 4.1 Add notification bell icon to dashboard app bar
  - Modify `afprotech_dashboard.dart` app bar
  - Replace existing notification icon with interactive bell icon
  - Add badge widget to show unread count
  - Inject `NotificationController` using GetX
  - Bind icon tap to `showNotificationModal()` method
  - _Requirements: 1.1, 1.2, 1.3_

- [ ]* 4.2 Write unit test for badge display
  - Test badge shows correct count
  - Test badge hides when count is zero
  - Test badge updates when count changes
  - _Requirements: 1.2, 1.4_

- [ ] 5. Create notification bottom sheet modal
  - Design slide-up modal UI
  - Implement filter tabs
  - Display notification list
  - _Requirements: 1.3, 6.1, 6.2, 9.1_

- [ ] 5.1 Create notification bottom sheet widget
  - Create `NotificationBottomSheet` stateful widget
  - Implement slide-up animation using `showModalBottomSheet`
  - Add rounded top corners and drag handle
  - Set modal height to 70% of screen
  - _Requirements: 1.3_

- [ ] 5.2 Add filter tabs to bottom sheet
  - Create filter tab bar with options: All, Events, Announcements, Attendance, Orders
  - Implement tab selection logic
  - Update displayed notifications based on selected filter
  - Highlight active filter tab
  - _Requirements: 9.1, 9.2, 9.3, 9.4_

- [ ] 5.3 Implement notification list view
  - Create scrollable list of notifications
  - Group notifications by type if "All" filter is selected
  - Display notification icon, title, message, and timestamp
  - Show "No notifications" empty state
  - Implement pull-to-refresh gesture
  - _Requirements: 6.1, 6.2, 6.5_

- [ ] 5.4 Create notification list item widget
  - Design notification card with icon, title, message, timestamp
  - Add visual indicator for unread notifications (bold text or dot)
  - Implement tap handler to mark as read and navigate
  - Add swipe-to-delete gesture
  - Show different icons based on notification type
  - _Requirements: 6.3, 6.4_

- [ ]* 5.5 Write property test for notification ordering
  - **Property 8: Notification ordering**
  - **Validates: Requirements 6.1**

- [ ] 6. Implement notification navigation
  - Handle notification tap to navigate to relevant screen
  - Extract data from notification payload
  - _Requirements: 2.5, 3.3, 4.3, 5.3_

- [ ] 6.1 Implement notification tap handler
  - Create `handleNotificationTap()` method in controller
  - Parse notification data JSON
  - Implement navigation logic for each type:
    - Event: Navigate to event details with event_id
    - Announcement: Navigate to announcement details with announcement_id
    - Attendance: Navigate to attendance history
    - Order: Navigate to order details with order_id
    - Product Approval: Navigate to student products with product_id
  - Mark notification as read before navigating
  - Close bottom sheet modal
  - _Requirements: 2.5, 3.3, 4.3, 5.3_

- [ ]* 6.2 Write property test for navigation data integrity
  - **Property 6: Navigation data integrity**
  - **Validates: Requirements 2.5, 3.3, 4.3, 5.3**

- [ ] 7. Implement real-time notification updates
  - Set up periodic polling for new notifications
  - Update badge count automatically
  - _Requirements: 8.1, 8.2, 8.3, 8.5_

- [ ] 7.1 Implement periodic notification polling
  - Create timer in `NotificationController` that runs every 30 seconds
  - Call `fetchNotifications()` on timer tick
  - Update unread count and notification list
  - Cancel timer when controller is disposed
  - _Requirements: 8.1, 8.2_

- [ ] 7.2 Add app lifecycle listener
  - Implement `WidgetsBindingObserver` in dashboard
  - Fetch notifications when app resumes from background
  - Pause polling when app goes to background
  - _Requirements: 8.5_

- [ ]* 7.3 Write property test for real-time updates
  - **Property 9: Real-time update consistency**
  - **Validates: Requirements 8.1, 8.2**

- [ ] 8. Implement notification persistence and caching
  - Save notifications to local storage
  - Load cached notifications on app start
  - Sync with server when online
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

- [ ] 8.1 Implement local notification caching
  - Save fetched notifications to Hive box
  - Load notifications from cache on app start
  - Update cache when notifications are marked as read or deleted
  - Clear cache on user logout
  - _Requirements: 7.1, 7.2, 7.3, 7.4, 7.5_

- [ ] 8.2 Implement offline queue for actions
  - Create queue for mark-as-read and delete actions when offline
  - Store queued actions in local storage
  - Sync queued actions when connection is restored
  - _Requirements: 8.4_

- [ ]* 8.3 Write property test for persistence
  - **Property 3: Read status persistence**
  - **Validates: Requirements 1.4, 7.3**

- [ ]* 8.4 Write property test for deletion consistency
  - **Property 5: Notification deletion consistency**
  - **Validates: Requirements 6.4, 7.4**

- [ ] 9. Add notification settings screen
  - Create settings UI for notification preferences
  - Implement toggle switches for each type
  - Save preferences to local storage
  - _Requirements: 10.1, 10.2, 10.3, 10.4, 10.5_

- [ ] 9.1 Create notification settings screen
  - Create `NotificationSettingsScreen` widget
  - Add navigation from profile or settings menu
  - Display toggle switches for each notification type
  - Implement toggle handlers to save preferences
  - _Requirements: 10.1, 10.2_

- [ ] 9.2 Implement notification filtering based on settings
  - Load notification preferences on app start
  - Filter out disabled notification types from display
  - Hide badge if all types are disabled
  - _Requirements: 10.2, 10.5_

- [ ]* 9.3 Write property test for settings persistence
  - **Property 10: Settings persistence**
  - **Validates: Requirements 10.2, 10.4**

- [ ] 10. Integrate notification creation in existing features
  - Trigger notifications when events are created
  - Trigger notifications when announcements are posted
  - Trigger notifications when attendance is recorded
  - Trigger notifications when order status changes
  - Trigger notifications when student products are approved/rejected
  - _Requirements: 2.1, 3.1, 4.1, 5.1, 5.5_

- [ ] 10.1 Add notification creation to event backend
  - Modify `afprotechs_create_event.php` to create notifications
  - Call notification creation function after event is created
  - Create notification for all AFPROTECH members
  - _Requirements: 2.1_

- [ ] 10.2 Add notification creation to announcement backend
  - Modify announcement creation endpoint to create notifications
  - Create notification for all AFPROTECH members
  - Mark urgent announcements with high priority
  - _Requirements: 3.1, 3.4_

- [ ] 10.3 Add notification creation to attendance backend
  - Modify `afprotechs_record_attendance.php` to create notifications
  - Create notification only for the specific student
  - Include event name and timestamp
  - _Requirements: 4.1, 4.2, 4.4_

- [ ] 10.4 Add notification creation to order backend
  - Modify `afprotechs_update_order_status.php` to create notifications
  - Create notification when order status changes
  - Include product name, order ID, and new status
  - _Requirements: 5.1, 5.2, 5.4_

- [ ] 10.5 Add notification creation to product approval backend
  - Modify `afprotechs_approve_student_product.php` to create notifications
  - Create notification when product is approved or rejected
  - Include product name and approval status
  - _Requirements: 5.5_

- [ ]* 10.6 Write property test for user-specific notifications
  - **Property 7: User-specific notifications**
  - **Validates: Requirements 4.4, 7.5**

- [ ] 11. Final testing and polish
  - Test complete notification flow end-to-end
  - Verify all animations are smooth
  - Test on different screen sizes
  - _Requirements: All_

- [ ] 11.1 Perform end-to-end integration testing
  - Test: Create event → notification appears → tap → navigate to event
  - Test: Post announcement → notification appears → tap → navigate to announcement
  - Test: Record attendance → notification appears → tap → navigate to attendance
  - Test: Update order → notification appears → tap → navigate to order
  - Test: Approve product → notification appears → tap → navigate to product
  - _Requirements: 2.1, 2.5, 3.1, 3.3, 4.1, 4.3, 5.1, 5.3, 5.5_

- [ ] 11.2 Test offline/online behavior
  - Test: Go offline → mark as read → go online → verify sync
  - Test: Go offline → delete notification → go online → verify sync
  - Test: Go offline → create event → go online → verify notification appears
  - _Requirements: 8.4_

- [ ] 11.3 Test notification persistence across app restarts
  - Test: Receive notifications → close app → reopen → verify notifications persist
  - Test: Mark as read → close app → reopen → verify read status persists
  - Test: Logout → login → verify notifications are cleared
  - _Requirements: 7.2, 7.5_

- [ ]* 11.4 Write UI tests for bottom sheet modal
  - Test modal slides up smoothly
  - Test filter tabs switch correctly
  - Test swipe-to-delete gesture works
  - Test pull-to-refresh works
  - _Requirements: 1.3, 6.4, 9.1_

- [ ] 12. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

