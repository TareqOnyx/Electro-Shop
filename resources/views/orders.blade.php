<x-app-layout>
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold mb-6">Orders</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 shadow rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left border-b">ID</th>
                        <th class="px-4 py-2 text-left border-b">User</th>
                        <th class="px-4 py-2 text-left border-b">Total</th>
                        <th class="px-4 py-2 text-left border-b">Status</th>
                        <th class="px-4 py-2 text-left border-b">Area</th>
                        <th class="px-4 py-2 text-left border-b">Address</th>
                        <th class="px-4 py-2 text-left border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $bgColor = match($order->status) {
                                'pending'   => '#FFA500', // orange
                                'confirmed' => '#FFFF00', // yellow
                                'delivered' => '#008000', // green
                                'canceled'  => '#FF0000', // red
                                default     => '#E5E7EB', // gray-200
                            };
                            $textColor = ($order->status === 'confirmed' || $order->status === 'pending') ? '#000000' : '#FFFFFF';
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2 border-b">{{ $order->id }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->user->name }}</td>
                            <td class="px-4 py-2 border-b">${{ number_format($order->total, 2) }}</td>
                            <td class="px-4 py-2 border-b capitalize" style="background-color: {{ $bgColor }}; color: {{ $textColor }};">
                                {{ $order->status }}
                            </td>
                            <td class="px-4 py-2 border-b">{{ $order->area->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2 border-b">{{ $order->address }}</td>
                            <td class="px-4 py-2 border-b">
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="flex flex-col items-start gap-2">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="border-gray-300 rounded px-2 py-1">
                                        <option value="pending" @if($order->status === 'pending') selected @endif>Pending</option>
                                        <option value="confirmed" @if($order->status === 'confirmed') selected @endif>Confirmed</option>
                                        <option value="delivered" @if($order->status === 'delivered') selected @endif>Delivered</option>
                                        <option value="canceled" @if($order->status === 'canceled') selected @endif>Canceled</option>
                                    </select>
                                    <button type="submit" class="bg-blue-500 text-black px-3 py-1 rounded hover:bg-blue-600">
                                        Update
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
