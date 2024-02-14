<x-filament-panels::page>
    <div class="">
        <x-app.cashier.reports.report-layout>
            <table class="w-full print:text-[7pt] text-xs">
                <thead>
                    <tr>
                        <th class="text-left px-2 border border-black whitespace-nowrap">CODE</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">NAME</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">DOB</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">AGE</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">CIVIL STATUS</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">GENDER</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">TIN</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">MEMBER_TYPE</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">TERMINATED_AT</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">PATRONAGE STATUS</th>
                        <th class="text-left px-2 border border-black whitespace-nowrap">MEMBERSHIP DATE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->members as $member)
                        <tr>
                            <td class="border border-black whitespace-nowrap px-2">{{ $member->mpc_code }}</td>
                            <td class="border border-black whitespace-nowrap px-2">{{ $member->alt_full_name }}</td>
                            <td class="border border-black whitespace-nowrap px-2">
                                {{ $member->dob?->format('m/d/Y') }}
                            </td>
                            <td class="border border-black whitespace-nowrap px-2">{{ $member->age }}</td>
                            <td class="border border-black whitespace-nowrap px-2">{{ $member->civil_status_name }}
                            </td>
                            <td class="border border-black whitespace-nowrap px-2">{{ $member->gender_name }}</td>
                            <td class="border border-black whitespace-nowrap px-2">{{ $member->tin }}</td>
                            <td class="border border-black whitespace-nowrap px-2">{{ $member->member_type_name }}</td>
                            <td class="border border-black whitespace-nowrap px-2">
                                {{ $member->terminated_at?->format('m/d/Y') }}</td>
                            <td class="border border-black whitespace-nowrap px-2">
                                {{ $member->patronage_status_name }}
                            </td>
                            <td class="border border-black whitespace-nowrap px-2">
                                {{ $member->created_at?->format('m/d/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-app.cashier.reports.report-layout>
    </div>
</x-filament-panels::page>
