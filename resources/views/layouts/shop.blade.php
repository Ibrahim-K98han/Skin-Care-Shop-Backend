<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('description', 'অরিজিনাল স্কিন কেয়ার প্রোডাক্ট, সারা বাংলাদেশে হোম ডেলিভারি')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800">

    <header class="bg-white border-b">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-xl font-bold text-pink-600">
                {{ config('app.name') }}
            </a>
            <nav class="flex gap-6 text-sm">
                <a href="{{ route('home') }}" class="hover:text-pink-600">হোম</a>
                <a href="{{ route('products.index') }}" class="hover:text-pink-600">সব প্রোডাক্ট</a>
            </nav>
        </div>
    </header>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="border-t bg-white mt-12">
        <div class="max-w-6xl mx-auto px-4 py-6 text-sm text-gray-500">
            © {{ date('Y') }} {{ config('app.name') }}। সব প্রোডাক্ট অরিজিনাল।
        </div>
    </footer>

</body>
</html>
