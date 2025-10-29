<div x-data="notificationManager()" class="fixed top-4 right-4 z-50 space-y-4 pointer-events-none">
    <template x-for="notification in notifications" :key="notification.id">
        <div 
            x-show="notification.show"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-100"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="removeNotification(notification.id)"
            class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden cursor-pointer transform hover:scale-105 transition-transform"
            :class="{
                'border-l-4 border-green-500': notification.type === 'success',
                'border-l-4 border-red-500': notification.type === 'error',
                'border-l-4 border-yellow-500': notification.type === 'warning',
                'border-l-4 border-blue-500': notification.type === 'info'
            }"
        >
            <div class="p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <template x-if="notification.type === 'success'">
                            <svg class="h-6 w-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                        <template x-if="notification.type === 'error'">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </template>
                        <template x-if="notification.type === 'warning'">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        </template>
                        <template x-if="notification.type === 'info'">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </template>
                    </div>
                    <div class="ml-3 w-0 flex-1 pt-0.5">
                        <p class="text-sm font-medium text-gray-900" x-text="notification.title"></p>
                        <p class="mt-1 text-sm text-gray-500" x-text="notification.message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button 
                            @click.stop="removeNotification(notification.id)"
                            class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                            <span class="sr-only">Close</span>
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function notificationManager() {
    return {
        notifications: [],
        nextId: 1,

        addNotification(type, title, message, duration = 5000) {
            const notification = {
                id: this.nextId++,
                type,
                title,
                message,
                show: true
            };

            this.notifications.push(notification);

            if (duration > 0) {
                setTimeout(() => {
                    this.removeNotification(notification.id);
                }, duration);
            }

            return notification.id;
        },

        removeNotification(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },

        success(title, message, duration) {
            return this.addNotification('success', title, message, duration);
        },

        error(title, message, duration) {
            return this.addNotification('error', title, message, duration);
        },

        warning(title, message, duration) {
            return this.addNotification('warning', title, message, duration);
        },

        info(title, message, duration) {
            return this.addNotification('info', title, message, duration);
        }
    }
}

// Global notification functions
window.showNotification = function(type, title, message, duration) {
    const container = document.querySelector('[x-data="notificationManager()"]');
    if (container && container._x_dataStack) {
        const manager = container._x_dataStack[0];
        return manager.addNotification(type, title, message, duration);
    }
};

window.showSuccess = function(title, message, duration) {
    return showNotification('success', title, message, duration);
};

window.showError = function(title, message, duration) {
    return showNotification('error', title, message, duration);
};

window.showWarning = function(title, message, duration) {
    return showNotification('warning', title, message, duration);
};

window.showInfo = function(title, message, duration) {
    return showNotification('info', title, message, duration);
};
</script>