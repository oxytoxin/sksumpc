<x-filament-panels::page>
    <x-app.cashier.reports.report-layout>
        <div class="flex flex-col items-center">
            <h4 class="mt-4 text-center text-xl font-bold uppercase print:text-[12pt]">MEMBERS REPORT AS OF {{ today()->format('F d, Y') }}</h4>
            <table class="mt-4 doc-table text-xs print:text-[7pt]">
                <thead>
                    <tr>
                        <th class="doc-table-header-cell">NO.</th>
                        <th class="doc-table-header-cell">CODE</th>
                        <th class="doc-table-header-cell">NAME</th>
                        <th class="doc-table-header-cell">DIVISION</th>
                        <th class="doc-table-header-cell">DOB</th>
                        <th class="doc-table-header-cell">AGE</th>
                        <th class="doc-table-header-cell">CIVIL STATUS</th>
                        <th class="doc-table-header-cell">GENDER</th>
                        <th class="doc-table-header-cell">TIN</th>
                        <th class="doc-table-header-cell">MEMBER TYPE</th>
                        <th class="doc-table-header-cell">TERMINATED AT</th>
                        <th class="doc-table-header-cell">PATRONAGE STATUS</th>
                        <th class="doc-table-header-cell">MEMBERSHIP DATE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->members as $member)
                        <tr>
                            <td class="doc-table-cell">{{ $loop->iteration }}</td>
                            <td class="doc-table-cell">{{ $member->mpc_code }}</td>
                            <td class="doc-table-cell">{{ $member->alt_full_name }}</td>
                            <td class="doc-table-cell">{{ $member->division?->name }}</td>
                            <td class="doc-table-cell">
                                {{ $member->dob?->format('m/d/Y') }}
                            </td>
                            <td class="doc-table-cell">{{ $member->age }}</td>
                            <td class="doc-table-cell">{{ $member->civil_status?->name }}
                            </td>
                            <td class="doc-table-cell">{{ $member->gender?->name }}</td>
                            <td class="doc-table-cell">{{ $member->tin }}</td>
                            <td class="doc-table-cell">{{ $member->member_type?->name }}</td>
                            <td class="doc-table-cell">{{ $member->terminated_at?->format('m/d/Y') }}</td>
                            <td class="doc-table-cell">{{ $member->patronage_status?->name }}</td>
                            <td class="doc-table-cell">{{ $member->membership_date?->format('m/d/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
