<x-app-layout>
    <x-slot name="title">Notifications</x-slot>

    <div class="p-4 sm:p-6 lg:p-10">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        You have <span class="font-semibold text-primary" x-text="unreadCount"></span> unread notifications
                    </p>
                </div>
                <button 
                    @click="markAllAsRead()"
                    x-show="unreadCount > 0"
                    class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition text-sm font-medium"
                    style="display: none;"
                >
                    Mark all as read
                </button>
            </div>

            <!-- Notifications List -->
            <div class="space-y-3" x-data="notificationData()" x-init="init()">
                <template x-if="loading">
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                        <span class="material-symbols-outlined animate-spin text-4xl">refresh</span>
                        <p class="mt-2">Loading notifications...</p>
                    </div>
                </template>

                <template x-if="!loading && notifications.length === 0">
                    <div class="text-center py-12">
                        <span class="material-symbols-outlined text-gray-400 dark:text-gray-600 text-6xl">notifications_off</span>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">No notifications yet</p>
                    </div>
                </template>

                <template x-for="notification in notifications" :key="notification.id">
                    <div 
                        @click="handleNotificationClick(notification)"
                        :class="notification.read ? 'bg-gray-50 dark:bg-gray-800/30 opacity-75' : 'bg-white dark:bg-[#111a22] border-l-4 border-primary'"
                        class="p-4 rounded-lg border border-gray-200 dark:border-[#324d67] cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                    >
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 mt-1">
                                <span class="material-symbols-outlined text-2xl" :class="notification.read ? 'text-gray-400 dark:text-gray-600' : 'text-primary'" x-text="getNotificationIcon(notification.type)" style="font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-base" :class="notification.read ? 'text-gray-500 dark:text-gray-500' : 'text-gray-900 dark:text-white'" x-text="notification.title"></h3>
                                        <p class="text-sm mt-1 whitespace-pre-line" :class="notification.read ? 'text-gray-400 dark:text-gray-600' : 'text-gray-600 dark:text-gray-400'" x-text="notification.message"></p>
                                        <p class="text-gray-500 dark:text-gray-500 text-xs mt-2" x-text="formatDate(notification.created_at)"></p>
                                    </div>
                                    <div x-show="!notification.read" class="flex-shrink-0">
                                        <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Pagination -->
                <div x-show="!loading && notifications.length > 0" class="mt-6 flex justify-center">
                    <div class="flex gap-2">
                        <button 
                            @click="fetchNotifications(currentPage - 1)"
                            :disabled="currentPage === 1"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-800"
                        >
                            Previous
                        </button>
                        <span class="px-4 py-2 text-gray-700 dark:text-gray-300" x-text="`Page ${currentPage} of ${lastPage}`"></span>
                        <button 
                            @click="fetchNotifications(currentPage + 1)"
                            :disabled="currentPage === lastPage"
                            class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-800"
                        >
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function notificationData() {
            return {
                notifications: @json($notifications->items()),
                loading: false,
                unreadCount: {{ $unreadCount }},
                currentPage: {{ $notifications->currentPage() }},
                lastPage: {{ $notifications->lastPage() }},
                
                init() {
                    // Notifications are already loaded from server
                    this.loading = false;
                },
                
                async fetchNotifications(page = 1) {
                    this.loading = true;
                    try {
                        const response = await fetch(`{{ route('notifications.index') }}?page=${page}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            }
                        });
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        const data = await response.json();
                        this.notifications = data.notifications?.data || [];
                        this.currentPage = data.notifications?.current_page || 1;
                        this.lastPage = data.notifications?.last_page || 1;
                        this.unreadCount = data.unread_count || 0;
                    } catch (error) {
                        console.error('Failed to fetch notifications:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async markAsRead(notificationId) {
                    // Find the notification in the array and mark it as read immediately (optimistic update)
                    const notification = this.notifications.find(n => n.id === notificationId);
                    if (notification && !notification.read) {
                        notification.read = true;
                        notification.read_at = new Date().toISOString();
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                    
                    try {
                        await fetch(`{{ url('/notifications') }}/${notificationId}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        });
                        // Refresh to ensure sync with server
                        this.fetchNotifications(this.currentPage);
                    } catch (error) {
                        console.error('Failed to mark notification as read:', error);
                        // Revert optimistic update on error
                        if (notification) {
                            notification.read = false;
                            this.unreadCount += 1;
                        }
                    }
                },
                
                async handleNotificationClick(notification) {
                    // Mark as read first
                    if (!notification.read) {
                        await this.markAsRead(notification.id);
                    }
                    
                    // Then redirect if action URL exists
                    if (notification.data && notification.data.action_url) {
                        // Small delay to ensure UI updates
                        setTimeout(() => {
                            window.location.href = notification.data.action_url;
                        }, 100);
                    }
                },
                
                async markAllAsRead() {
                    try {
                        await fetch('{{ route('notifications.mark-all-read') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        });
                        this.unreadCount = 0;
                        this.fetchNotifications(this.currentPage);
                    } catch (error) {
                        console.error('Failed to mark all as read:', error);
                    }
                },
                
                getNotificationIcon(type) {
                    const icons = {
                        'account_approved': 'check_circle',
                        'withdrawal_requested': 'payments',
                        'withdrawal_approved': 'payments',
                        'student_added': 'person_add',
                        'student_referred': 'person_add',
                        'payment_completed': 'payment',
                        'commission_earned': 'account_balance_wallet',
                    };
                    return icons[type] || 'notifications';
                },
                
                formatDate(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diff = now - date;
                    const minutes = Math.floor(diff / 60000);
                    const hours = Math.floor(diff / 3600000);
                    const days = Math.floor(diff / 86400000);
                    
                    if (minutes < 1) return 'Just now';
                    if (minutes < 60) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
                    if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`;
                    if (days < 7) return `${days} day${days > 1 ? 's' : ''} ago`;
                    
                    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
                }
            };
        }
    </script>
</x-app-layout>

