<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Orders') }}
            </h2>
        </x-slot>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 shadow rounded text-center">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-4 py-2 border-b">ID</th>
            <th class="px-4 py-2 border-b">User</th>
            <th class="px-4 py-2 border-b">Total</th>
            <th class="px-4 py-2 border-b">Status / Actions</th>
            <th class="px-4 py-2 border-b">Area</th>
            <th class="px-4 py-2 border-b">Address</th>
            <th class="px-4 py-2 border-b">Placed At</th>
            <th class="px-4 py-2 border-b">History</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            @php
                $statusColors = [
                    'pending'   => ['bg' => '#FFA500', 'text' => '#000000'],
                    'confirmed' => ['bg' => '#FFFF00', 'text' => '#000000'],
                    'delivered' => ['bg' => '#008000', 'text' => '#FFFFFF'],
                    'canceled'  => ['bg' => '#FF0000', 'text' => '#FFFFFF'],
                ];
                $currentColor = $statusColors[$order->status] ?? ['bg' => '#E5E7EB', 'text' => '#000000'];
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border-b">{{ $order->id }}</td>
                <td class="px-4 py-2 border-b">{{ $order->user->name }}</td>
                <td class="px-4 py-2 border-b">${{ number_format($order->total, 2) }}</td>

                <td class="px-4 py-2 border-b">
                    <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="flex gap-2 justify-center">
    @csrf
    @method('PUT') <!-- important for PUT/PATCH requests -->

    <select name="status" class="border-gray-300 rounded px-2 py-1 text-center"
        style="background-color: {{ $currentColor['bg'] }}; color: {{ $currentColor['text'] }};">
        @foreach($statusColors as $status => $colors)
            <option value="{{ $status }}" @if($order->status === $status) selected @endif>
                {{ ucfirst($status) }}
            </option>
        @endforeach
    </select>

    <button type="submit" class="px-3 py-1 rounded"
        style="background-color: {{ $currentColor['bg'] }}; color: {{ $currentColor['text'] }};">
        Update
    </button>
</form>

                </td>

                <td class="px-4 py-2 border-b">{{ $order->area->name ?? 'N/A' }}</td>
                <td class="px-4 py-2 border-b">{{ $order->address }}</td>
                <td class="px-4 py-2 border-b">{{ $order->created_at->format('Y-m-d H:i') }}</td>

                <td class="px-4 py-2 border-b text-left text-sm">
                    @if($order->statusHistories->count())
                        <ul class="space-y-1">
                            @foreach($order->statusHistories as $history)
                                <li>
                                    <span class="font-semibold">{{ ucfirst($history->status) }}</span>
                                    by <span class="text-blue-600">{{ $history->admin->name ?? 'System' }}</span>
                                    <span class="text-gray-500">({{ $history->created_at->format('Y-m-d H:i') }})</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-gray-400">No updates</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

        </div>
    </div>
</x-app-layout>
