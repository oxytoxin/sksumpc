<div>
    <h2 class="mb-4">Organization Members</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y border border-gray-200 divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                </th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    MPC Code
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Type
                </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($members as $member)
                <tr class="hover:bg-gray-50 transition-colors duration-150">
                    <td class="p-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $member->full_name }}</div>
                    </td>
                    <td class="p-4 whitespace-nowrap">
                        <div class="text-sm text-gray-500">{{ $member->mpc_code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $member->member_type?->name ?? 'Not specified' }}
                            </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No members found</h3>
                        <p class="mt-1 text-sm text-gray-500">This organization currently has no members.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

</div>