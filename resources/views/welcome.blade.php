<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes API - Backend Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Notes API</h1>
                <p class="text-gray-600">Backend Developer Test - By Wildan Miladji</p>
                <p class="text-sm text-gray-500 mt-2">REST API untuk sistem CRUD Notes dengan JWT Authentication</p>
            </div>

            <!-- Quick Links -->
            <div class="grid md:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Dokumentasi</h2>
                    <div class="space-y-3">
                        <a href="/api/documentation" target="_blank" 
                           class="block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-center">
                            Swagger UI Documentation
                        </a>
                        <a href="{{ asset('postman_collection.json') }}" download
                           class="block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-center">
                            Download Postman Collection
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Testing</h2>
                    <div class="space-y-3">
                        <button onclick="testAPI()" 
                                class="block w-full bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600">
                            Test API Connection
                        </button>
                        <div id="testResult" class="text-sm"></div>
                    </div>
                </div>
            </div>

            <!-- API Endpoints -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">API Endpoints</h2>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Authentication</h3>
                        <ul class="text-sm space-y-1">
                            <li><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">POST</span> /api/register</li>
                            <li><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">POST</span> /api/login</li>
                            <li><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">GET</span> /api/me</li>
                            <li><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">PUT</span> /api/update-profile</li>
                            <li><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">POST</span> /api/logout</li>
                            <li><span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">DELETE</span> /api/delete-account</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-700 mb-2">Notes CRUD</h3>
                        <ul class="text-sm space-y-1">
                            <li><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">GET</span> /api/notes</li>
                            <li><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">POST</span> /api/notes</li>
                            <li><span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">GET</span> /api/notes/{id}</li>
                            <li><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">PUT</span> /api/notes/{id}</li>
                            <li><span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">DELETE</span> /api/notes/{id}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Features</h2>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl mb-2">[AUTH]</div>
                        <h3 class="font-semibold">JWT Authentication</h3>
                        <p class="text-sm text-gray-600">Secure token-based auth</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl mb-2">[SEC]</div>
                        <h3 class="font-semibold">Security</h3>
                        <p class="text-sm text-gray-600">Rate limiting & headers</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl mb-2">[CRUD]</div>
                        <h3 class="font-semibold">CRUD Operations</h3>
                        <p class="text-sm text-gray-600">Complete notes management</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function testAPI() {
            const resultDiv = document.getElementById('testResult');
            resultDiv.innerHTML = '<div class="text-blue-600">Testing API connection...</div>';
            
            try {
                const response = await fetch('/api/notes');
                if (response.status === 401) {
                    resultDiv.innerHTML = '<div class="text-green-600">API is working! (401 Unauthorized - expected without token)</div>';
                } else {
                    resultDiv.innerHTML = '<div class="text-green-600">API is working! Status: ' + response.status + '</div>';
                }
            } catch (error) {
                resultDiv.innerHTML = '<div class="text-red-600">API connection failed: ' + error.message + '</div>';
            }
        }
    </script>
</body>
</html>