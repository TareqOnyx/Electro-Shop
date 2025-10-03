<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 py-6">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-[#D10024] leading-tight flex items-center gap-2">
                <svg class="w-6 h-6 text-[#D10024]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18" /></svg>
                {{ __('Orders Dashboard') }}
            </h2>
        </x-slot>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        <!-- Summary Row -->
        @php
            $totalOrders = $orders->count();
            $delivered = $orders->where('status', 'delivered')->count();
            $pending = $orders->where('status', 'pending')->count();
            $canceled = $orders->where('status', 'canceled')->count();
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center border-t-4 border-[#D10024]">
                <div class="text-2xl font-bold">{{ $totalOrders }}</div>
                <div class="text-gray-500">Total Orders</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center border-t-4 border-[#FFA500]">
                <div class="text-2xl font-bold">{{ $pending }}</div>
                <div class="text-gray-500">Pending</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center border-t-4 border-[#008000]">
                <div class="text-2xl font-bold">{{ $delivered }}</div>
                <div class="text-gray-500">Delivered</div>
            </div>
            <div class="bg-white rounded-xl shadow p-4 flex flex-col items-center border-t-4 border-[#FF0000]">
                <div class="text-2xl font-bold">{{ $canceled }}</div>
                <div class="text-gray-500">Canceled</div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 shadow rounded text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">User</th>
                        <th class="px-4 py-2 border-b">Total</th>
                        <th class="px-4 py-2 border-b">Status</th>
                        <th class="px-4 py-2 border-b">Area</th>
                        <th class="px-4 py-2 border-b">Address</th>
                        <th class="px-4 py-2 border-b">Placed At</th>
                        <th class="px-4 py-2 border-b">Items</th>
                        <th class="px-4 py-2 border-b">History</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $statusColors = [
                                'pending'   => ['bg' => '#FFA500', 'text' => '#000000', 'label' => 'Pending'],
                                'confirmed' => ['bg' => '#FFFF00', 'text' => '#000000', 'label' => 'Confirmed'],
                                'delivered' => ['bg' => '#008000', 'text' => '#FFFFFF', 'label' => 'Delivered'],
                                'canceled'  => ['bg' => '#FF0000', 'text' => '#FFFFFF', 'label' => 'Canceled'],
                            ];
                            $currentColor = $statusColors[$order->status] ?? ['bg' => '#E5E7EB', 'text' => '#000000', 'label' => ucfirst($order->status)];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b font-bold">#{{ $order->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->user->name }}</td>
                            <td class="px-4 py-2 border-b text-green-700 font-semibold">${{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-2 border-b">
                                <form action="{{ route('orders.updateStatus', $order) }}" method="POST" class="flex gap-2 justify-center items-center">
                                    @csrf
                                    @method('PUT')
                                    <span class="px-2 py-1 rounded text-xs font-bold" style="background-color: {{ $currentColor['bg'] }}; color: {{ $currentColor['text'] }};">
                                        {{ $currentColor['label'] }}
                                    </span>
                                    <select name="status" class="border-gray-300 rounded px-2 py-1 text-center ml-2">
                                        @foreach($statusColors as $status => $colors)
                                            <option value="{{ $status }}" @if($order->status === $status) selected @endif>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="ml-2 px-3 py-1 rounded bg-[#D10024] text-white hover:bg-[#b8001d]">Update</button>
                                </form>
                            </td>
                            <td class="px-4 py-2 border-b">{{ $order->area->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->address }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-2 border-b">
                                <button type="button" onclick="document.getElementById('items-{{ $order->id }}').classList.toggle('hidden')" class="text-[#D10024] underline font-semibold">View</button>
                                <div id="items-{{ $order->id }}" class="hidden mt-2 text-left bg-gray-50 rounded p-2 shadow">
                                    <ul class="space-y-1">
                                        @foreach($order->items as $item)
                                            <li class="flex items-center gap-2 border-b py-1">
                                                <span class="font-semibold">{{ $item->product->name ?? 'Product' }}</span>
                                                <span class="text-gray-500">x{{ $item->quantity }}</span>
                                                <span class="text-green-700">${{ number_format($item->price, 2) }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </td>
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
