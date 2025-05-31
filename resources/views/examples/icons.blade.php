<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zyna Icon Examples</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Zyna Icon Examples</h1>
        
        <!-- Basic Usage -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Basic Usage</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center space-x-4">
                    <x-zyna:icon name="home" />
                    <x-zyna:icon name="user" />
                    <x-zyna:icon name="cog" />
                    <x-zyna:icon name="bell" />
                </div>
                <pre class="mt-4 bg-gray-100 p-3 rounded text-sm overflow-x-auto">
&lt;x-zyna:icon name="home" /&gt;
&lt;x-zyna:icon name="user" /&gt;
&lt;x-zyna:icon name="cog" /&gt;
&lt;x-zyna:icon name="bell" /&gt;</pre>
            </div>
        </section>

        <!-- Sizes -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Sizes</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center space-x-4">
                    <x-zyna:icon name="star" size="xs" />
                    <x-zyna:icon name="star" size="sm" />
                    <x-zyna:icon name="star" size="md" />
                    <x-zyna:icon name="star" size="lg" />
                    <x-zyna:icon name="star" size="xl" />
                    <x-zyna:icon name="star" size="2xl" />
                </div>
                <pre class="mt-4 bg-gray-100 p-3 rounded text-sm overflow-x-auto">
&lt;x-zyna:icon name="star" size="xs" /&gt;
&lt;x-zyna:icon name="star" size="sm" /&gt;
&lt;x-zyna:icon name="star" size="md" /&gt;
&lt;x-zyna:icon name="star" size="lg" /&gt;
&lt;x-zyna:icon name="star" size="xl" /&gt;
&lt;x-zyna:icon name="star" size="2xl" /&gt;</pre>
            </div>
        </section>

        <!-- Colors -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Colors</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center space-x-4">
                    <x-zyna:icon name="heart" size="lg" color="primary" />
                    <x-zyna:icon name="heart" size="lg" color="secondary" />
                    <x-zyna:icon name="heart" size="lg" color="success" />
                    <x-zyna:icon name="heart" size="lg" color="danger" />
                    <x-zyna:icon name="heart" size="lg" color="warning" />
                    <x-zyna:icon name="heart" size="lg" color="info" />
                </div>
                <pre class="mt-4 bg-gray-100 p-3 rounded text-sm overflow-x-auto">
&lt;x-zyna:icon name="heart" size="lg" color="primary" /&gt;
&lt;x-zyna:icon name="heart" size="lg" color="secondary" /&gt;
&lt;x-zyna:icon name="heart" size="lg" color="success" /&gt;
&lt;x-zyna:icon name="heart" size="lg" color="danger" /&gt;
&lt;x-zyna:icon name="heart" size="lg" color="warning" /&gt;
&lt;x-zyna:icon name="heart" size="lg" color="info" /&gt;</pre>
            </div>
        </section>

        <!-- Styles -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Styles (Outline vs Solid)</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <x-zyna:icon name="bookmark" size="xl" style="outline" />
                        <p class="text-sm text-gray-600 mt-2">Outline</p>
                    </div>
                    <div class="text-center">
                        <x-zyna:icon name="bookmark" size="xl" style="solid" />
                        <p class="text-sm text-gray-600 mt-2">Solid</p>
                    </div>
                </div>
                <pre class="mt-4 bg-gray-100 p-3 rounded text-sm overflow-x-auto">
&lt;x-zyna:icon name="bookmark" size="xl" style="outline" /&gt;
&lt;x-zyna:icon name="bookmark" size="xl" style="solid" /&gt;</pre>
            </div>
        </section>

        <!-- Custom Classes -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Custom Classes</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center space-x-4">
                    <x-zyna:icon name="fire" size="lg" class="animate-pulse text-orange-500" />
                    <x-zyna:icon name="refresh" size="lg" class="animate-spin text-blue-500" />
                    <x-zyna:icon name="bell" size="lg" class="animate-bounce text-purple-500" />
                </div>
                <pre class="mt-4 bg-gray-100 p-3 rounded text-sm overflow-x-auto">
&lt;x-zyna:icon name="fire" size="lg" class="animate-pulse text-orange-500" /&gt;
&lt;x-zyna:icon name="refresh" size="lg" class="animate-spin text-blue-500" /&gt;
&lt;x-zyna:icon name="bell" size="lg" class="animate-bounce text-purple-500" /&gt;</pre>
            </div>
        </section>

        <!-- Usage in Components -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Usage in Components</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="space-y-4">
                    <!-- Button with icon -->
                    <button class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <x-zyna:icon name="download" size="sm" class="mr-2" />
                        Download
                    </button>

                    <!-- Alert with icon -->
                    <div class="flex items-start p-4 bg-green-50 border border-green-200 rounded-lg">
                        <x-zyna:icon name="check-circle" size="md" color="success" class="mr-3 flex-shrink-0" />
                        <p class="text-green-800">Your changes have been saved successfully!</p>
                    </div>

                    <!-- Navigation item -->
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                        <x-zyna:icon name="home" size="md" class="mr-3" />
                        Dashboard
                    </a>
                </div>
            </div>
        </section>

        <!-- Icon Search/Browser -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Available Icons</h2>
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <p class="text-gray-600 mb-4">
                    To see all available icons, run the download command first:
                </p>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto">
<code>php artisan zyna:download-icons</code></pre>
                <p class="text-gray-600 mt-4">
                    This will download all Flowbite icons from their GitHub repository. You can also download specific categories:
                </p>
                <pre class="bg-gray-900 text-gray-100 p-4 rounded-lg overflow-x-auto">
<code>php artisan zyna:download-icons --categories=general --categories=arrows</code></pre>
            </div>
        </section>
    </div>
</body>
</html>