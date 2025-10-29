                                                                                                                                                                                                                                @extends('layout.app')

@section('title', 'Frontend Testing - Desa Sungai Meranti')

@section('content')
<div class="min-h-screen bg-gray-50 pt-20">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Frontend Testing Dashboard</h1>
                <p class="text-lg text-gray-600">Test all frontend components and functionality</p>
            </div>

            <!-- Test Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Authentication Tests -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔐 Authentication</h3>
                    <div class="space-y-3">
                        <a href="{{ route('login') }}" class="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                            Login Page
                        </a>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">
                            Register Page
                        </a>
                        <a href="{{ route('forgot-password') }}" class="block w-full text-center bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition">
                            Forgot Password
                        </a>
                    </div>
                </div>

                <!-- Dashboard Tests -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📊 Dashboards</h3>
                    <div class="space-y-3">
                        <a href="{{ route('warga.dashboard') }}" class="block w-full text-center bg-emerald-600 text-white py-2 px-4 rounded-lg hover:bg-emerald-700 transition">
                            Warga Dashboard
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="block w-full text-center bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition">
                            Admin Dashboard
                        </a>
                        <a href="{{ route('home') }}" class="block w-full text-center bg-green-700 text-white py-2 px-4 rounded-lg hover:bg-green-800 transition">
                            Homepage
                        </a>
                    </div>
                </div>

                <!-- Letter Management -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Letter Management</h3>
                    <div class="space-y-3">
                        <a href="{{ route('administrasi') }}" class="block w-full text-center bg-indigo-600 text-white py-2 px-4 rounded-lg hover:bg-indigo-700 transition">
                            Letter Types
                        </a>
                        <a href="{{ route('pengajuan.create') }}" class="block w-full text-center bg-orange-600 text-white py-2 px-4 rounded-lg hover:bg-orange-700 transition">
                            Submit Letter
                        </a>
                        <a href="{{ route('tracking') }}" class="block w-full text-center bg-cyan-600 text-white py-2 px-4 rounded-lg hover:bg-cyan-700 transition">
                            Tracking
                        </a>
                    </div>
                </div>

                <!-- Admin Management -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Admin Management</h3>
                    <div class="space-y-3">
                        <a href="{{ route('admin.jenis-surat.index') }}" class="block w-full text-center bg-gray-600 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition">
                            Letter Types Mgmt
                        </a>
                        <a href="{{ route('admin.pengajuan.index') }}" class="block w-full text-center bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition">
                            Letters Approval
                        </a>
                    </div>
                </div>

                <!-- UI Components -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🎨 UI Components</h3>
                    <div class="space-y-3">
                        <button onclick="showTestModal()" class="block w-full text-center bg-teal-600 text-white py-2 px-4 rounded-lg hover:bg-teal-700 transition">
                            Modal Test
                        </button>
                        <button onclick="testNotifications()" class="block w-full text-center bg-pink-600 text-white py-2 px-4 rounded-lg hover:bg-pink-700 transition">
                            Notifications
                        </button>
                        <button onclick="testAlerts()" class="block w-full text-center bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 transition">
                            Alerts
                        </button>
                    </div>
                </div>

                <!-- API Tests -->
                <div class="bg-white shadow-sm rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🔌 API Tests</h3>
                    <div class="space-y-3">
                        <button onclick="testAPI('/api/jenis-surat')" class="block w-full text-center bg-indigo-500 text-white py-2 px-4 rounded-lg hover:bg-indigo-600 transition">
                            Get Letter Types
                        </button>
                        <button onclick="testAPI('/api/user')" class="block w-full text-center bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition">
                            Get User Info
                        </button>
                        <button onclick="testAPI('/admin/dashboard')" class="block w-full text-center bg-purple-500 text-white py-2 px-4 rounded-lg hover:bg-purple-600 transition">
                            Admin Stats
                        </button>
                    </div>
                </div>
            </div>

            <!-- Test Results -->
            <div class="mt-8 bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Test Results</h3>
                </div>
                <div class="p-6">
                    <div id="testResults" class="space-y-2">
                        <p class="text-gray-500">Click any test button above to see results here...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Test Modal -->
<x-modal id="testModal" title="Test Modal" size="lg">
    <div class="space-y-4">
        <p>This is a test modal to verify the modal component is working correctly.</p>
        <div class="flex space-x-4">
            <button onclick="closeTestModal()" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                Cancel
            </button>
            <button onclick="closeTestModal()" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                OK
            </button>
        </div>
    </div>
</x-modal>

<!-- Test Notifications -->
<x-notification-container />

<script>
function showTestModal() {
    window.dispatchEvent(new CustomEvent('open-modal', { detail: 'testModal' }));
}

function closeTestModal() {
    window.dispatchEvent(new CustomEvent('close-modal', { detail: 'testModal' }));
}

function testNotifications() {
    // Test different notification types
    showSuccess('Success Test', 'This is a success notification test');
    
    setTimeout(() => {
        showError('Error Test', 'This is an error notification test');
    }, 1000);
    
    setTimeout(() => {
        showWarning('Warning Test', 'This is a warning notification test');
    }, 2000);
    
    setTimeout(() => {
        showInfo('Info Test', 'This is an info notification test');
    }, 3000);
}

function testAlerts() {
    const alerts = [
        { type: 'success', message: 'Success alert test' },
        { type: 'error', message: 'Error alert test' },
        { type: 'warning', message: 'Warning alert test' },
        { type: 'info', message: 'Info alert test' }
    ];
    
    alerts.forEach((alert, index) => {
        setTimeout(() => {
            showTestResult(`Alert Test ${index + 1}`, `Testing ${alert.type} alert`, 'success');
        }, index * 500);
    });
}

function testAPI(endpoint) {
    showTestResult('API Test', `Testing ${endpoint}`, 'info');
    
    fetch(endpoint)
        .then(response => response.json())
        .then(data => {
            showTestResult('API Success', `${endpoint} - ${JSON.stringify(data)}`, 'success');
        })
        .catch(error => {
            showTestResult('API Error', `${endpoint} - ${error.message}`, 'error');
        });
}

function showTestResult(title, message, type = 'info') {
    const resultsDiv = document.getElementById('testResults');
    const resultDiv = document.createElement('div');
    
    const typeClasses = {
        'success': 'bg-green-50 border-green-200 text-green-800',
        'error': 'bg-red-50 border-red-200 text-red-800',
        'warning': 'bg-yellow-50 border-yellow-200 text-yellow-800',
        'info': 'bg-blue-50 border-blue-200 text-blue-800'
    };
    
    const className = typeClasses[type] || typeClasses['info'];
    
    resultDiv.className = `border rounded-md p-3 ${className}`;
    resultDiv.innerHTML = `
        <div class="flex items-center">
            <div class="flex-1">
                <strong>${title}:</strong> ${message}
            </div>
            <div class="text-sm text-gray-500">
                ${new Date().toLocaleTimeString()}
            </div>
        </div>
    `;
    
    resultsDiv.appendChild(resultDiv);
    resultsDiv.scrollTop = resultsDiv.scrollHeight;
}
</script>
@endsection