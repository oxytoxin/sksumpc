<x-filament-panels::page>
    {{ $this->form }}

    <div class="mt-8">
        @if($this->loans_for_voucher->isNotEmpty())
            <div>
                <h3>Loans For Voucher:</h3>
                <table>
                    <thead>
                    <tr>
                        <th class="table-cell">Member</th>
                        <th class="table-cell">Reference Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($this->loans_for_voucher as $loan)
                        <tr>
                            <td class="table-cell">{{ $loan->member->full_name }}</td>
                            <td class="table-cell">{{ $loan->reference_number }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if($this->unposted_billings->isNotEmpty())
            <div>
                <h3>Unposted Billings:</h3>
                <table>
                    <thead>
                    <tr>
                        <th class="table-cell">Billable Date</th>
                        <th class="table-cell">Name</th>
                        <th class="table-cell">Reference Number</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($this->unposted_billings as $billing)
                        <tr>
                            <td class="table-cell">{{ $billing->billable_date }}</td>
                            <td class="table-cell">{{ $billing->name }}</td>
                            <td class="table-cell">{{ $billing->reference_number }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        @if(!$this->revolving_fund_cleared)
            <h4>Note: Revolving Fund is not cleared yet.</h4>
        @endif
        @if($this->active_users->isNotEmpty())
            <div>
                <h3>Other Logged In Users:</h3>
                <table>
                    <thead>
                    <tr>
                        <th class="table-cell">Name</th>
                        <th class="table-cell">Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($this->active_users as $user)
                        <tr>
                            <td class="table-cell">{{ $user->name }}</td>
                            <td class="table-cell">{{ $user->email }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div
        @endif
    </div>
</x-filament-panels::page>
