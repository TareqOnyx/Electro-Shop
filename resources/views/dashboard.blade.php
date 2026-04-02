<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#D10024] leading-tight flex items-center gap-2">
            <svg class="w-6 h-6 text-[#D10024]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6" /></svg>
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900 space-y-12 max-w-7xl mx-auto">
        <!-- =======================
             Quick Stats
             ======================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4 border-t-4 border-[#D10024]">
                <div class="bg-[#D10024] text-white rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" /></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $cats->count() }}</div>
                    <div class="text-gray-500">Categories</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4 border-t-4 border-[#15161D]">
                <div class="bg-[#15161D] text-white rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75" /></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $areas->count() }}</div>
                    <div class="text-gray-500">Areas</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 flex items-center gap-4 border-t-4 border-[#E4E7ED]">
                <div class="bg-[#E4E7ED] text-[#D10024] rounded-full p-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" /></svg>
                </div>
                <div>
                    <div class="text-2xl font-bold">{{ $products->count() }}</div>
                    <div class="text-gray-500">Products</div>
                </div>
            </div>
        </div>

        <!-- =======================
             Categories + Areas
             ======================= -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Categories -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2 text-[#D10024]">
                    <svg class="w-5 h-5 text-[#D10024]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" /></svg>
                    Categories
                </h2>
                <!-- Add Category Form -->
                <form action="{{ route('categories.store') }}" method="POST" class="mb-6 flex gap-2 items-end">
                    @csrf
                    <input type="text" name="catname" placeholder="New Category" class="border px-4 py-2 w-1/2 rounded-md focus:ring-2 focus:ring-[#D10024]" required>
                    <button type="submit" class="bg-[#D10024] text-white px-3 py-1 rounded-md hover:bg-[#b8001d] shadow">
                        <i class="fa fa-plus mr-1"></i> Add
                    </button>
                </form>
                <!-- Categories Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border border-gray-200 rounded-md bg-gray-50 text-sm">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">Name</th>
                                <th class="border px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cats as $cat)
                                <tr class="even:bg-gray-100 odd:bg-gray-50 hover:bg-[#FFF5F6]">
                                    <td class="border px-4 py-2 text-center">{{ $cat->id }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $cat->name }}</td>
                                    <td class="border px-4 py-2 flex justify-center gap-2">
                                        <!-- Update Category -->
                                        <form action="{{ route('categories.update', $cat->id) }}" method="POST" class="flex gap-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="catname" value="{{ $cat->name }}" class="border px-2 py-1 rounded-md focus:ring-2 focus:ring-[#D10024]">
                                            <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                                                <i class="fa fa-save"></i>
                                            </button>
                                        </form>
                                        <!-- Delete Category -->
                                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Areas -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 flex items-center gap-2 text-[#15161D]">
                    <svg class="w-5 h-5 text-[#15161D]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 010 7.75M8 3.13a4 4 0 000 7.75" /></svg>
                    Areas
                </h2>
                <!-- Add Area Form -->
                <form action="{{ route('areas.store') }}" method="POST" class="mb-6 flex gap-2 items-end">
                    @csrf
                    <input type="text" name="name" placeholder="New Area" class="border px-4 py-2 w-1/2 rounded-md focus:ring-2 focus:ring-[#15161D]" required>
                    <button type="submit" class="bg-[#15161D] text-white px-3 py-1 rounded-md hover:bg-[#23242e] shadow">
                        <i class="fa fa-plus mr-1"></i> Add
                    </button>
                </form>
                <!-- Areas Table -->
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border border-gray-200 rounded-md bg-gray-50 text-sm">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="border px-4 py-2">ID</th>
                                <th class="border px-4 py-2">Name</th>
                                <th class="border px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($areas as $area)
                                <tr class="even:bg-gray-100 odd:bg-gray-50 hover:bg-[#F5F7FA]">
                                    <td class="border px-4 py-2 text-center">{{ $area->id }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $area->name }}</td>
                                    <td class="border px-4 py-2 flex justify-center gap-2">
                                        <!-- Update Area -->
                                        <form action="{{ route('areas.update', $area->id) }}" method="POST" class="flex gap-1">
                                            @csrf
                                            @method('PUT')
                                            <input type="text" name="name" value="{{ $area->name }}" class="border px-2 py-1 rounded-md focus:ring-2 focus:ring-[#15161D]">
                                            <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                                                <i class="fa fa-save"></i>
                                            </button>
                                        </form>
                                        <!-- Delete Area -->
                                        <form action="{{ route('areas.destroy', $area->id) }}" method="POST" onsubmit="return confirm('Delete this area?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- =======================
             Products Table
             ======================= -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
            <h2 class="text-lg font-semibold mb-4 flex items-center gap-2 text-[#D10024]">
                <svg class="w-5 h-5 text-[#D10024]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4" /></svg>
                Products
            </h2>
            <!-- Add Product Form -->
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-2 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Name</label>
                    <input type="text" name="name" class="border px-2 py-1 w-full rounded-md focus:ring-2 focus:ring-[#D10024]" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Description</label>
                    <input type="text" name="desc" class="border px-2 py-1 w-full rounded-md">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Price</label>
                    <input type="number" step="0.01" name="price" class="border px-2 py-1 w-full rounded-md focus:ring-2 focus:ring-[#D10024]" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Category</label>
                    <select name="category_id" class="border px-2 py-1 w-full rounded-md focus:ring-2 focus:ring-[#D10024]" required>
                        @foreach($cats as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Image</label>
                    <input type="file" name="imgpro" class="border px-2 py-1 w-full rounded-md">
                </div>
                <div class="col-span-1 md:col-span-5">
                    <button type="submit" class="bg-[#D10024] text-white px-3 py-1 rounded-md hover:bg-[#b8001d] shadow">
                        <i class="fa fa-plus mr-1"></i> Add Product
                    </button>
                </div>
            </form>
            <!-- Products Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full border border-gray-200 rounded-md bg-gray-50 text-sm">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Description</th>
                            <th class="border px-4 py-2">Price</th>
                            <th class="border px-4 py-2">Category</th>
                            <th class="border px-4 py-2">Image</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr class="even:bg-gray-100 odd:bg-gray-50 hover:bg-[#FFF5F6]">
                                <td class="border px-4 py-2 text-center">{{ $product->id }}</td>
                                <td class="border px-4 py-2">
                                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-1 items-center">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $product->name }}" class="border px-2 py-1 w-full rounded-md focus:ring-2 focus:ring-[#D10024]">
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="text" name="desc" value="{{ $product->desc }}" class="border px-2 py-1 w-full rounded-md">
                                </td>
                                <td class="border px-4 py-2">
                                    <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="border px-2 py-1 w-full rounded-md focus:ring-2 focus:ring-[#D10024]">
                                </td>
                                <td class="border px-4 py-2">
                                    <select name="category_id" class="border px-2 py-1 w-full rounded-md focus:ring-2 focus:ring-[#D10024]">
                                        @foreach($cats as $cat)
                                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="border px-4 py-2">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="img" class="w-16 h-16 object-cover rounded-md border border-gray-300">
                                    @endif
                                    <input type="file" name="imgpro" class="border px-2 py-1 mt-1 w-full rounded-md">
                                </td>
                                <td class="border px-4 py-2 flex gap-1">
                                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                                            <i class="fa fa-save"></i>
                                        </button>
                                    </form>
                                    <!-- Delete Form -->
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
