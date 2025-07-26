<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ config('app.name', 'LearnPro') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-gray-900 bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md">
            <div class="p-4 text-xl font-bold text-orange-600">
                StrandNotes
            </div>
            <nav class="mt-6 space-y-2">
                <!-- Subjects -->
                <a href="{{ route('subjects.index') }}" class="block p-3 text-gray-600 transition-colors rounded-lg sidebar-hover">
                    <i class='mr-2 bx bxs-book-content'></i> Subjects
                </a>
                <!-- More sidebar links can go here -->
            </nav>
        </aside>

        <!-- Main Content -->
        <div class="flex flex-col flex-1">
            <!-- Navbar -->
            <header class="flex items-center justify-between px-6 py-4 bg-white shadow-sm">
                <div class="text-lg font-semibold">
                    @yield('title', 'Dashboard')
                </div>

                <!-- Search -->
                <div class="relative">
                    <input
                        type="text"
                        id="liveSearchInput"
                        class="px-3 py-2 border border-gray-300 rounded form-control me-2"
                        placeholder="Search subjects, strands, notes..."
                        autocomplete="off"
                    />
                    <div id="liveSearchResults" class="absolute z-10 hidden w-full mt-2 bg-white border border-gray-200 rounded shadow-lg"></div>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Search Script -->
    <script>
        const searchInput = document.getElementById('liveSearchInput');
        const resultsBox = document.getElementById('liveSearchResults');

        searchInput.addEventListener('input', function () {
            const query = this.value.trim();
            if (query.length < 2) {
                resultsBox.classList.add('hidden');
                return;
            }

            fetch(`/live-search-subjects?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (data.length === 0) {
                        resultsBox.innerHTML = '<div class="p-2 text-gray-500">No results found.</div>';
                    } else {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.innerHTML = `<a href="${item.url}" class="block p-2 hover:bg-gray-100">${item.name}</a>`;
                            resultsBox.appendChild(div);
                        });
                    }
                    resultsBox.classList.remove('hidden');
                })
                .catch(err => {
                    console.error(err);
                    resultsBox.classList.add('hidden');
                });
        });

        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
