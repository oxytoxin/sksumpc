<div>
    <div x-ref="print" class="flex flex-col gap-16">
        <div class="w-full border-2 border-green-600 p-8">
            <div class="text-sm">
                <x-app.cashier.reports.report-heading />
            </div>
            <div class="my-8 text-center">
                <h1 class="text-4xl font-bold text-green-600">CERTIFICATE OF TIME DEPOSIT</h1>
                <p>NO. <span class="underline">{{ $time_deposit->time_deposit_account->number }}</span></p>
            </div>
            <div class="flex flex-col items-center text-center">
                <p>This certifies that:</p>
                <p class="my-4 border-b-2 border-amber-600 px-8 text-2xl font-bold uppercase text-amber-600">
                    {{ $time_deposit->member->full_name }}</p>
            </div>
            <div class="text-center">
                <p>
                    has deposited with this Cooperative the sum of <strong class="uppercase">{{ $time_deposit->amount_in_words }} pesos
                        (Php {{ renumber_format($time_deposit->amount, 2) }})</strong>
                    for the period of {{ $time_deposit->days_in_words }} ({{ $time_deposit->number_of_days }})days from
                    the date of issuance hereof, bearing an
                    interest at the rate of <strong class="uppercase">{{ $time_deposit->interest_rate_in_words }}
                        percent
                        ({{ renumber_format($time_deposit->interest_rate * 100, 2) }}%)</strong> per annum, subject to
                    the conditions on the
                    reverse side hereof.
                </p>
            </div>
            <div class="mt-8 grid grid-cols-2 gap-4">
                <div class="mt-8 flex flex-col items-center">
                    <p class="font-bold uppercase">ROLANDO F. HECHANOVA</p>
                    <p>BOD Chairperson</p>
                </div>
                <div class="mt-8 flex flex-col items-center">
                    <p class="font-bold uppercase">DESIREE G. LEGASPI</p>
                    <p>Treasurer</p>
                </div>
            </div>
        </div>
        <div class="flex flex-col break-before-page gap-12">
            <div class="px-32 mx-auto text-sm">
                <h2 class="border-b border-black text-center">CONDITIONS</h2>
                <p class="text-center">
                    The depositors must present this certificate, properly endorsed, when applying for payment and/ or
                    renewal
                    at maturity date. This must be surrendered upon payment of the amount due to member. Interest on
                    time
                    deposit must conform to the cooperative’s policies.
                    If withdrawal is made before the due date, the Cooperative interest rate will be reduced to the
                    prevailing
                    rate on ordinary savings accounts. If upon maturity the depositor does not present the certificate
                    for
                    payment or renewal, the cooperative is authorized to make automatic renewal of this certificate to a
                    maturity of 180 days, following Cooperative policy on interest rates. If the depositor seeks
                    withdrawal
                    before the “new” 180 days, maturity of the this certificate, the Cooperative shall pay the
                    prevailing
                    ordinary savings rate covering the period of deposit within the 180 days maturity.
                    In the event of loss or mutilation of this certificate, the Cooperative reserves the right to
                    determine
                    the
                    appropriate amount due to or decline payment to member saver.
                </p>
            </div>
            <div class="flex-1">
                <h2 class="text-center">EXTENSION AGREEMENT</h2>
                <table class="doc-table">
                    <thead>
                        <th class="doc-table-header-cell">Date</th>
                        <th class="doc-table-header-cell">Amount</th>
                        <th class="doc-table-header-cell">Rate per Annum</th>
                        <th class="doc-table-header-cell">Value upon maturity</th>
                        <th class="doc-table-header-cell">Authorized Signatory</th>
                        <th class="doc-table-header-cell">Endorsed by</th>
                        <th class="doc-table-header-cell">Maturity Date</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="doc-table-cell">{{ $time_deposit->transaction_date->format('m/d/Y') }}
                            </td>
                            <td class="doc-table-cell">{{ renumber_format($time_deposit->amount, 2) }}</td>
                            <td class="doc-table-cell">
                                {{ renumber_format($time_deposit->interest_rate * 100, 2) }}%
                            </td>
                            <td class="doc-table-cell">
                                {{ renumber_format($time_deposit->maturity_amount, 2) }}
                            </td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell"></td>
                            <td class="doc-table-cell">{{ $time_deposit->maturity_date->format('m/d/Y') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-8">
                    <h3 class="text-center font-bold">RECEIPT</h3>
                    <p>
                        Received the sum of {{ $time_deposit->amount_in_words }} pesos
                        (P {{ renumber_format($time_deposit->amount, 2) }}) in payment of herein mentioned time deposit
                        upon
                    </p>
                    <ul class="ml-8">
                        <li class="flex gap-2">
                            <p>______</p>MATURITY
                        </li>
                        <li class="flex gap-2">
                            <p>______</p>WITHDRAWAL
                        </li>
                        <li class="flex gap-2">
                            <p>______</p>PRETERMINATION
                        </li>
                    </ul>
                    <p>With interest thereon as stated on the other side.</p>
                </div>
                <div class="mt-4 flex justify-evenly">
                    <div>
                        <p class="text-center">{{ $time_deposit->transaction_date->format('m/d/Y') }}</p>
                        <p class="border-t border-black px-16 text-sm">Date</p>
                    </div>
                    <div>
                        <p class="border-b border-black px-8 text-center uppercase">
                            {{ $time_deposit->member->full_name }}
                        </p>
                        <p class="text-sm">(Depositor's Signature Over Printed Name)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-end">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, '')">
            Print
        </x-filament::button>
    </div>
</div>
