<div>
    <div x-ref="print" class="flex gap-4">
        <div class="max-w-md text-sm">
            <h2 class="border-b border-black text-center">CONDITIONS</h2>
            <p class="text-justify">
                The depositors must present this certificate, properly endorsed, when applying for payment and/ or
                renewal
                at maturity date. This must be surrendered upon payment of the amount due to member. Interest on time
                deposit must conform to the cooperative’s policies.
                If withdrawal is made before the due date, the Cooperative interest rate will be reduced to the
                prevailing
                rate on ordinary savings accounts. If upon maturity the depositor does not present the certificate for
                payment or renewal, the cooperative is authorized to make automatic renewal of this certificate to a
                maturity of 180 days, following Cooperative policy on interest rates. If the depositor seeks withdrawal
                before the “new” 180 days, maturity of the this certificate, the Cooperative shall pay the prevailing
                ordinary savings rate covering the period of deposit within the 180 days maturity.
                In the event of loss or mutilation of this certificate, the Cooperative reserves the right to determine
                the
                appropriate amount due to or decline payment to member saver.
            </p>
        </div>
        <div class="flex-1">
            <h2 class="text-center">EXTENSION AGREEMENT</h2>
            <table>
                <thead>
                    <th class="border border-black px-4">Date</th>
                    <th class="border border-black px-4">Amount</th>
                    <th class="border border-black px-4">Rate per Annum</th>
                    <th class="border border-black px-4">Value upon maturity</th>
                    <th class="border border-black px-4">Authorized Signatory</th>
                    <th class="border border-black px-4">Endorsed by</th>
                    <th class="border border-black px-4">Maturity Date</th>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black px-4">{{ $time_deposit->transaction_date->format('m/d/Y') }}</td>
                        <td class="border border-black px-4">{{ renumber_format($time_deposit->amount, 2) }}</td>
                        <td class="border border-black px-4">
                            {{ renumber_format($time_deposit->interest_rate * 100, 2) }}%
                        </td>
                        <td class="border border-black px-4">{{ renumber_format($time_deposit->maturity_amount, 2) }}
                        </td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4"></td>
                        <td class="border border-black px-4">{{ $time_deposit->maturity_date->format('m/d/Y') }}</td>
                    </tr>
                </tbody>
            </table>
            <div class="mt-8">
                <h3 class="text-center font-bold">RECEIPT</h3>
                <p>
                    Received the sum of {{ $time_deposit->amount_in_words }} pesos only
                    (P {{ renumber_format($time_deposit->amount, 2) }}) in payment of herein mentioned time deposit upon
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
                    <p class="border-b px-8 border-black uppercase">{{ $time_deposit->member->full_name }}</p>
                    <p class="text-sm">(Depositor's Signature Over Printed Name)</p>
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
