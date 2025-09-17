<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="p-6 text-gray-900">
        <!-- Add Category Form -->
        <form action="{{ route('categories.store') }}" method="POST" class="mb-6 flex">
            @csrf
            <input type="text" name="catname" placeholder="New Category"
                   class="border rounded-l px-4 py-2 w-1/2" required>
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700">
                Add
            </button>
        </form>

        <!-- Categories Table -->
        <table class="table-auto w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Name</th>
                    <th class="border px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cats as $cat)
                    <tr>
                        <td class="border px-4 py-2">{{ $cat->id }}</td>
                        <td class="border px-4 py-2">{{ $cat->name }}</td>
                        <td class="border px-4 py-2 flex gap-2">
                            <!-- Edit -->
                            <a href="{{ route('categories.edit', $cat->id) }}"
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                Edit
                            </a>

                            <!-- Delete -->
                            <form action="{{ route('categories.destroy', $cat->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="border px-4 py-2 text-center">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
