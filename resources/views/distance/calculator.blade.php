<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distance Calculator</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
                <div class="max-w-md mx-auto">
                    <div class="divide-y divide-gray-200">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                            <h2 class="text-2xl font-bold mb-8 text-center text-gray-800">Distance Calculator</h2>
                            
                            <!-- Error Alert -->
                            <div id="error-alert" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span id="error-message" class="block sm:inline"></span>
                            </div>

                            <!-- Success Alert -->
                            <div id="success-alert" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span id="result-message" class="block sm:inline"></span>
                            </div>

                            <form id="distance-form" class="space-y-4">
                                @csrf
                                <!-- Location 1 -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="font-semibold mb-3">Location 1</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Latitude</label>
                                            <input type="text" name="lat1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Longitude</label>
                                            <input type="text" name="lng1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location 2 -->
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h3 class="font-semibold mb-3">Location 2</h3>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Latitude</label>
                                            <input type="text" name="lat2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Longitude</label>
                                            <input type="text" name="lng2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Unit Selection -->
                                <div class="flex items-center space-x-2">
                                    <input type="checkbox" name="in_miles" id="in_miles" class="rounded text-blue-600 focus:ring-blue-500">
                                    <label for="in_miles" class="text-sm text-gray-700">Show distance in miles</label>
                                </div>

                                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Calculate Distance
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validateCoordinate(value, type) {
            // Remove any spaces and convert to number
            value = value.trim();
            const num = parseFloat(value);
            
            // Check if it's a valid number
            if (isNaN(num)) return false;
            
            // Check ranges
            if (type === 'lat' && (num < -90 || num > 90)) return false;
            if (type === 'lng' && (num < -180 || num > 180)) return false;
            
            return true;
        }

        document.getElementById('distance-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Hide any existing alerts
            document.getElementById('error-alert').classList.add('hidden');
            document.getElementById('success-alert').classList.add('hidden');

            const formData = new FormData(e.target);
            
            // Validate coordinates before sending
            const lat1 = formData.get('lat1');
            const lng1 = formData.get('lng1');
            const lat2 = formData.get('lat2');
            const lng2 = formData.get('lng2');

            if (!validateCoordinate(lat1, 'lat') || !validateCoordinate(lat2, 'lat') ||
                !validateCoordinate(lng1, 'lng') || !validateCoordinate(lng2, 'lng')) {
                document.getElementById('error-message').textContent = 
                    'Please enter valid coordinates. Latitude must be between -90 and 90, and longitude between -180 and 180.';
                document.getElementById('error-alert').classList.remove('hidden');
                return;
            }

            const data = {
                lat1: parseFloat(lat1),
                lng1: parseFloat(lng1),
                lat2: parseFloat(lat2),
                lng2: parseFloat(lng2),
                in_miles: formData.get('in_miles') === 'on'
            };

            try {
                const response = await fetch('/calculate-distance', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (!response.ok) {
                    throw new Error(result.error || 'An error occurred while calculating the distance');
                }

                // Show success message
                const resultMessage = `The distance between the two points is ${result.distance} ${result.unit}.`;
                document.getElementById('result-message').textContent = resultMessage;
                document.getElementById('success-alert').classList.remove('hidden');
            } catch (error) {
                // Show error message
                document.getElementById('error-message').textContent = error.message;
                document.getElementById('error-alert').classList.remove('hidden');
            }
        });
    </script>
</body>
</html>