<x-filament-panels::page>
    <x-app.cashier.reports.report-layout>
        <div class="flex flex-col items-center">
            <h4 class="mt-4 text-center text-xl font-bold uppercase print:text-[12pt]">MEMBERS REPORT AS OF {{ today()->format('F d, Y') }}</h4>
            <table class="mt-4 w-full text-xs print:text-[7pt]">
                <thead>
                    <tr>
                        <th class="whitespace-nowrap border border-black px-2 text-left">NO.</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">CODE</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">NAME</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">DIVISION</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">DOB</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">AGE</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">CIVIL STATUS</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">GENDER</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">TIN</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">MEMBER TYPE</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">TERMINATED AT</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">PATRONAGE STATUS</th>
                        <th class="whitespace-nowrap border border-black px-2 text-left">MEMBERSHIP DATE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->members as $member)
                        <tr>
                            <td class="whitespace-nowrap border border-black px-2">{{ $loop->iteration }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->mpc_code }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->alt_full_name }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->division?->name }}</td>
                            <td class="whitespace-nowrap border border-black px-2">
                                {{ $member->dob?->format('m/d/Y') }}
                            </td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->age }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->civil_status?->name }}
                            </td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->gender?->name }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->tin }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->member_type?->name }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->terminated_at?->format('m/d/Y') }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->patronage_status?->name }}</td>
                            <td class="whitespace-nowrap border border-black px-2">{{ $member->membership_date?->format('m/d/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
