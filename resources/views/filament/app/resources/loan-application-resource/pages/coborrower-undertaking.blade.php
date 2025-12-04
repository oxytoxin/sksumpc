<x-filament-panels::page>
    <div class="print:text-[8pt] font-serif" x-ref="print">
        <h1 class="text-center text-2xl font-semibold mb-8">
            Co-Borrower’s Undertaking
        </h1>
        <p class="leading-relaxed text-justify">
            I/We,
            @foreach($loan_application->comakers as $comaker)
                <span class="underline">{{ $comaker->member->full_name }}</span>,
            @endforeach
            of legal age, Filipino, and resident of
            @foreach($loan_application->comakers as $comaker)
                <span class="underline">{{ $comaker->member->address }}</span>,
            @endforeach

            do hereby declare under oath, that I/We the
            Co-borrower of
        </p>

        <div class="grid grid-cols-3 mt-6 mb-4 font-bold">
            <div>
                <span class="underline">{{ $loan_application->member->full_name }}</span><br>
                <span class="font-normal">(Name of borrower)</span>
            </div>

            <div class="text-center">
                <span class="underline">P{{ number_format($loan_application->desired_amount, 2) }}</span><br>
                <span class="font-normal">(Amount of Loan)</span>
            </div>

            <div class="text-right">
                <span class="underline">{{ $loan_application->member->address }}</span><br>
                <span class="font-normal">(Address)</span>
            </div>
        </div>

        <p class="leading-relaxed text-justify">
            That I/We bind myself to undertake the payment of the loan until the last centavo
            of the above mentioned principal borrower. In the event that the above mentioned
            principal borrower does not/do not pay the loan including interest, charges and penalty
            thereon I/We obligate to pay the same. The term “does not/do not” include the following
            actions/s:
        </p>

        <ol class="list-decimal ml-8 mt-3 space-y-1">
            <li>Partially paid but did not fully pay,</li>
            <li>Did not pay even a little amount/amortization, and/or</li>
            <li>Refuse/s to pay</li>
        </ol>

        <p class="mt-4 font-semibold text-justify">
            This co-borrower agrees to be sued in court or in other forum alone even
            without including the principal borrower/s if the Creditor wishes to sue him/her alone.
        </p>

        <p class="leading-relaxed text-left mt-6">
            As proof of this understanding, we have executed this Co-Borrower’s Undertaking
            in the presence of two or more witnesses on ______________________ at
            ____________________________________.
        </p>

        <p class="mt-10 font-semibold">Noted.</p>

        <div class="grid grid-cols-2 mt-6">
            @foreach($loan_application->comakers as $comaker)
                <div class="text-center">
                    <p class="font-bold underline">{{ $comaker->member->full_name }}</p>
                    <p>Co-Borrower</p>
                </div>
            @endforeach
        </div>

        <p class="mt-10 font-semibold">Conforme.</p>

        <div class="text-center mt-6">
            <p class="font-bold underline">{{ $loan_application->member->full_name }}</p>
            <p>Principal Borrower</p>
        </div>

        <p class="text-center mt-6 mb-2 font-semibold">Signed in the presence of</p>

        <div class="grid grid-cols-2 mt-6">
            <div class="text-center">
                <p class="font-bold underline">DESIREE G. LEGASPI</p>
                <p>Treasurer</p>
            </div>

            <div class="text-center">
                <p class="font-bold underline">ROLANDO F. HECHANOVA, RPAE, Ph.D.</p>
                <p>BOD-Chairperson</p>
            </div>
        </div>

        <div class="mt-12">
            <p>Republic of the Philippines )<br>
                ___________________________ ) S. S.<br>
                ___________________________ )</p>
        </div>

        <p class="leading-relaxed mt-4">
            SUBSCRIBED AND SWORN to before me this ____ day of ________________ 20______ at
            ______________________________________. Parties who executed the foregoing co-borrower’s
            undertaking acknowledged to me that the same is their free act and voluntary deed.
        </p>

        <div class="text-center mt-6">
            <p class="mt-4">________________________</p>
            <p>Notary</p>
        </div>

        <div class="mt-6">
            <p>Doc. No. _______________</p>
            <p>Page No. _______________</p>
            <p>Book No. _______________</p>
            <p>Series of _______________</p>
        </div>
    </div>
    <div class="flex justify-end space-x-2 p-4">
        <x-filament::button icon="heroicon-o-printer" @click="printOut($refs.print.outerHTML, `Coborrower's Undertaking`)">Print</x-filament::button>
    </div>
</x-filament-panels::page>
