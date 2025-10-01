<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900 space-y-12">

        <!-- =======================
             Categories + Areas
             ======================= -->
        <div class="grid grid-cols-2 gap-8">
            <!-- Categories -->
            <div>
                <h2 class="text-lg font-semibold mb-4">Categories</h2>

                <!-- Add Category Form -->
                <form action="{{ route('categories.store') }}" method="POST" class="mb-6 flex gap-2 items-end">
                    @csrf
                    <input type="text" name="catname" placeholder="New Category" class="border px-4 py-2 w-1/2 rounded-md" required>
                    <button type="submit" class="bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                        Add Category
                    </button>
                </form>

                <!-- Categories Table -->
                <table class="table-auto w-full border border-gray-300 rounded-md bg-gray-50">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cats as $cat)
                            <tr class="even:bg-gray-100 odd:bg-gray-50">
                                <td class="border px-4 py-2 text-center">{{ $cat->id }}</td>
                                <td class="border px-4 py-2 text-center">{{ $cat->name }}</td>
                                <td class="border px-4 py-2 flex justify-center gap-2">
                                    <!-- Update Category -->
                                    <form action="{{ route('categories.update', $cat->id) }}" method="POST" class="flex gap-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="catname" value="{{ $cat->name }}" class="border px-2 py-1 rounded-md">
                                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                                            Update
                                        </button>
                                    </form>

                                    <!-- Delete Category -->
                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this category?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Areas -->
            <div>
                <h2 class="text-lg font-semibold mb-4">Areas</h2>

                <!-- Add Area Form -->
                <form action="{{ route('areas.store') }}" method="POST" class="mb-6 flex gap-2 items-end">
                    @csrf
                    <input type="text" name="name" placeholder="New Area" class="border px-4 py-2 w-1/2 rounded-md" required>
                    <button type="submit" class="bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                        Add Area
                    </button>
                </form>

                <!-- Areas Table -->
                <table class="table-auto w-full border border-gray-300 rounded-md bg-gray-50">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="border px-4 py-2">ID</th>
                            <th class="border px-4 py-2">Name</th>
                            <th class="border px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($areas as $area)
                            <tr class="even:bg-gray-100 odd:bg-gray-50">
                                <td class="border px-4 py-2 text-center">{{ $area->id }}</td>
                                <td class="border px-4 py-2 text-center">{{ $area->name }}</td>
                                <td class="border px-4 py-2 flex justify-center gap-2">
                                    <!-- Update Area -->
                                    <form action="{{ route('areas.update', $area->id) }}" method="POST" class="flex gap-1">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $area->name }}" class="border px-2 py-1 rounded-md">
                                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                                            Update
                                        </button>
                                    </form>

                                    <!-- Delete Area -->
                                    <form action="{{ route('areas.destroy', $area->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this area?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- =======================
             Products Table
             ======================= -->
        <div>
            <h2 class="text-lg font-semibold mb-4">Products</h2>

            <!-- Add Product Form -->
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="mb-6 grid grid-cols-5 gap-2 items-end">
                @csrf
                <div>
                    <label>Name</label>
                    <input type="text" name="name" class="border px-2 py-1 w-full rounded-md" required>
                </div>
                <div>
                    <label>Description</label>
                    <input type="text" name="desc" class="border px-2 py-1 w-full rounded-md">
                </div>
                <div>
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" class="border px-2 py-1 w-full rounded-md" required>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category_id" class="border px-2 py-1 w-full rounded-md" required>
                        @foreach($cats as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Image</label>
                    <input type="file" name="imgpro" class="border px-2 py-1 w-full rounded-md">
                </div>
                <div class="col-span-5">
                    <button type="submit" class="bg-gray-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                        Add Product
                    </button>
                </div>
            </form>

            <!-- Products Table -->
            <table class="table-auto w-full border border-gray-300 rounded-md bg-gray-50">
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
                        <tr class="even:bg-gray-100 odd:bg-gray-50">
                            <td class="border px-4 py-2 text-center">{{ $product->id }}</td>
                            <td class="border px-4 py-2">
                                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="flex gap-1 items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $product->name }}" class="border px-2 py-1 w-full rounded-md">
                            </td>
                            <td class="border px-4 py-2">
                                <input type="text" name="desc" value="{{ $product->desc }}" class="border px-2 py-1 w-full rounded-md">
                            </td>
                            <td class="border px-4 py-2">
                                <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="border px-2 py-1 w-full rounded-md">
                            </td>
                            <td class="border px-4 py-2">
                                <select name="category_id" class="border px-2 py-1 w-full rounded-md">
                                    @foreach($cats as $cat)
                                        <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="border px-4 py-2">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="img" class="w-16 h-16 object-cover rounded-md">
                                @endif
                                <input type="file" name="imgpro" class="border px-2 py-1 mt-1 w-full rounded-md">
                            </td>
                            <td class="border px-4 py-2 flex gap-1">
                                    <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">
                                        Update
                                    </button>
                                </form>

                                <!-- Delete Form -->
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded-md hover:bg-red-700">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-app-layout>
