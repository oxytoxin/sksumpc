<x-filament-panels::page>
    <x-app.cashier.reports.report-layout>
        <div class="flex gap-4">
            <img class="h-24 w-24" src="{{ $member->profile_photo ?? 'https://ui-avatars.com/api/?name=' . $member->full_name }}" alt="profile photo">
            <div class="flex-1">
                <table class="w-full border border-black print:text-[7pt]">
                    <tr>
                        <td class="border border-black px-4">NAME</td>
                        <td class="border border-black px-4">{{ $member->full_name }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">DATE OF BIRTH</td>
                        <td class="border border-black px-4">{{ $member->dob?->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">PLACE OF BIRTH</td>
                        <td class="border border-black px-4">{{ $member->place_of_birth }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">GENDER</td>
                        <td class="border border-black px-4">{{ $member->gender?->name }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">CIVIL STATUS</td>
                        <td class="border border-black px-4">{{ $member->civil_status?->name }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">CONTACT NUMBER</td>
                        <td class="border border-black px-4">{{ $member->contact_number }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">RELIGION</td>
                        <td class="border border-black px-4">{{ $member->religion?->name }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">HIGHEST EDUCATIONAL ATTAINMENT</td>
                        <td class="border border-black px-4">{{ $member->highest_educational_attainment }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">TIN</td>
                        <td class="border border-black px-4">{{ $member->tin }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">MEMBER TYPE</td>
                        <td class="border border-black px-4">{{ $member->member_type?->name }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">DIVISION</td>
                        <td class="border border-black px-4">{{ $member->division?->name }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">OCCUPATION</td>
                        <td class="border border-black px-4">{{ $member->occupation?->name }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">PRESENT EMPLOYER</td>
                        <td class="border border-black px-4">{{ $member->present_employer }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">ANNUAL INCOME</td>
                        <td class="border border-black px-4">{{ $member->annual_income }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">OTHER INCOME SOURCES</td>
                        <td class="border border-black px-4">{{ $member->other_income_sources }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border border-black px-4">DEPENDENTS</td>
                    </tr>
                    @forelse ($member->dependents ?? [] as $dependent)
                        <tr>
                            <td class="border border-black px-4">NAME</td>
                            <td class="border border-black px-4">{{ $dependent->name }}</td>
                        </tr>
                        <tr>
                            <td class="border border-black px-4">AGE</td>
                            <td class="border border-black px-4">{{ date_create($dependent->dob)?->diff(now())->y }}
                            </td>
                        </tr>
                        <tr>
                            <td class="border border-black px-4">RELATIONSHIP</td>
                            <td class="border border-black px-4">{{ $dependent->relationship }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="border border-black px-4">NO DEPENDENTS FOUND</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td class="border border-black px-4">MEMBERSHIP BOD RESOLUTION</td>
                        <td class="border border-black px-4">{{ $member->membership_acceptance?->bod_resolution }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">EFFECTIVITY DATE</td>
                        <td class="border border-black px-4">{{ $member->membership_acceptance?->effectivity_date?->format('F d, Y') }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">INITIAL NUMBER OF SHARES</td>
                        <td class="border border-black px-4">{{ $member->initial_capital_subscription?->number_of_shares }}</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4">INITIAL AMOUNT SUBSCRIBED</td>
                        <td class="border border-black px-4">{{ $member->initial_capital_subscription?->amount_subscribed }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
