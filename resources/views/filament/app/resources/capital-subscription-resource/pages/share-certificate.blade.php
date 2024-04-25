<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :hasHeader="false">
        <div class="flex gap-8 w-full text-xs text-green-700">
            <div class="flex flex-col items-center w-1/3 border-r-2 border-dashed pr-2">
                <h4
                    class=" text-yellow-200 font-extrabold w-full py-2 text-center border-4 mb-2 border-green-300 bg-green-700">
                    SHARE CERTIFICATE</h4>
                <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-12 print:h-12">
                <p class="mt-4">NO. <span
                        class="min-w-[6rem] border-b border-green-700 inline-block">{{ $capital_subscription->code }}</span>
                </p>
                <p class="mt-4 italic font-bold">For <span
                        class="min-w-[2rem] text-center border-b border-green-700 inline-block">{{ round($capital_subscription->number_of_shares, 0) }}</span>
                    Shares </p>
                <p class="italic font-bold">Issued to:</p>
                <p class="mt-6 font-semibold w-full text-center border-b border-green-700">
                    {{ $this->member->full_name }}</p>
                <p class="w-full text-center border-b mt-2 border-green-700">SKSU MPC</p>
                <p class="mt-2 italic font-semibold">Dated
                    {{ $capital_subscription->transaction_date->format('F d, Y') }}</p>
                <p class="mt-4">FROM WHOM TRANSFERRED</p>
                <p class="w-full text-center border-b border-green-700">&nbsp;</p>
                <p class="italic font-semibold mt-2">Dated
                    {{ $capital_subscription->transaction_date->format('F d, Y') }}</p>
                <table class="mt-4 text-green-700 text-xs">
                    <thead>
                        <tr>
                            <td class="font-normal px-2 border border-black">NO. OF ORIGINAL CERTIFICATE</td>
                            <td class="font-normal px-2 border border-black">NO. OF ORIGINAL CERTIFICATE</td>
                            <td class="font-normal px-2 border border-black">NO. OF SHARES TRANSFERRED</td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-black">&nbsp;</td>
                            <td class="border border-black">&nbsp;</td>
                            <td class="border border-black">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="border border-black">&nbsp;</td>
                            <td class="border border-black">&nbsp;</td>
                            <td class="border border-black">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="border border-black">&nbsp;</td>
                            <td class="border border-black">&nbsp;</td>
                            <td class="border border-black">&nbsp;</td>
                        </tr>
                    </tbody>
                </table>
                <p class="italic mt-4">Received Share CERTIFICATE NO. {{ $capital_subscription->code }}
                </p>
                <p class="italic">FOR {{ round($capital_subscription->number_of_shares, 0) }} Shares this
                    {{ $capital_subscription->transaction_date->format('jS \d\a\y \o\f\ F, Y') }}.
                </p>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-evenly">
                    <p class="italic text-lg font-semibold whitespace-nowrap">No. {{ $capital_subscription->code }}</p>
                    <h4
                        class="text-yellow-200 font-extrabold px-8 py-2 text-center border-4 mb-2 border-green-300 bg-green-700">
                        SHARE CERTIFICATE</h4>
                    <p class="italic text-lg font-semibold whitespace-nowrap">
                        {{ round($capital_subscription->number_of_shares, 0) }} Shares</p>
                </div>
                <div
                    class="flex flex-col text-yellow-300 bg-green-700 p-2 items-center print:text-[9pt] print:leading-6">
                    <strong class="print:text-[11pt]">SULTAN KUDARAT STATE UNIVERSITY - MULTI-PURPOSE
                        COOPERATIVE</strong>
                    <strong class="print:text-[11pt]">(SKSU-MPC)</strong>
                    <div class="print:leading-4 text-center">
                        <p>Bo. 2, EJC Montilla, Tacurong City</p>
                        <p>CDA Reg. No.: 9520-12000926 / CIN: 0103120093 / TIN: 005-811-330</p>
                        <p>Contact No: 0906-826-1905 or 0966-702-9200</p>
                        <p>Email Address: sksu.mpc@gmail.com</p>
                    </div>
                </div>
                <div class="mt-4 px-8">
                    <p><strong class="text-base font-semibold">This Certifies that</strong> <strong
                            class="underline">{{ $capital_subscription->member->full_name }}</strong> is the owner of
                        <em
                            class="font-semibold underline">{{ round($capital_subscription->number_of_shares, 0) }}</em>
                        Shares at
                        <strong>{{ $this->par_value }}</strong> <em>Par
                            Value
                            Per Share.</em>
                    </p>
                    <p class="px-12 mt-4">transferable only on the books of the Cooperative by the holder hereof in
                        person
                        or
                        Attorney-In-Fact,
                        upon surrender of this Share Certificate properly endorsed.</p>
                    <p class="mt-4 italic"><strong class="text-base font-semibold not-italic">In Witness
                            Whereof,</strong> the said
                        Cooperative has
                        caused this Share
                        Certificate to be
                        signed by its
                        duly
                        authorized officers and to be sealed with the Seal of the Cooperative this
                        <em
                            class="underline">{{ $capital_subscription->transaction_date->format('jS \d\a\y \o\f\ F, Y') }}</em>
                    </p>
                </div>
                <div class="flex items-center mt-8">
                    <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-24 print:h-16">
                    <div class="flex justify-around w-full">
                        <div class="text-center">
                            <p class="font-bold">LARA JEAN M. LEGARIO</p>
                            <p>SECRETARY</p>
                        </div>
                        <div class="text-center">
                            <p class="font-bold">ROLANDO F. HECHANOVA</p>
                            <p>BOD CHAIRPERSON</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="break-before-page mt-24 print:mt-0 flex gap-8">
            <div class="w-60">
                <div style="writing-mode:vertical-rl; transform:rotate(180deg);" class="max-h-96 text-xs">
                    <p>For value received <strong
                            class="underline">{{ number_format($capital_subscription->amount_subscribed, 2) }}</strong>
                        hereby sell,
                        assign and transfer unto <strong
                            class="underline">{{ $capital_subscription->member->full_name }}</strong>
                        Shares of the Share Capital represented by the herewith Certificate and do hereby irrevocably
                        constitute and appoint ______________ to transfer the said shares on the books of
                        the within named Cooperative with full power of substitution in the premises.
                    </p>
                    <p class="mr-8">Dated {{ $capital_subscription->transaction_date->format('F d, Y') }}.
                    </p>
                    <p> In the presence of</p>
                    <div class="flex justify-between mr-8">
                        <div class="flex items-center flex-col">
                            <p>________________________</p>
                            <p>Witness</p>
                        </div>
                        <div class="flex items-center flex-col">
                            <p class="underline">{{ $capital_subscription->member->full_name }}</p>
                            <p>Share Capital Owner</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="text-justify text-xs mt-4">NOTICE: THE SIGNATURE OF THE ASSIGNMENT MUST CORRESPOND WITH
                        THE
                        NAME AS
                        WRITTEN UPON THE FACE OF
                        THE
                        CERTIFICATE IN EVERY PARTICULAR, WITHOUT ALTERATION OR ENLARGEMENT OR ANY CHANGES WHATSOEVER.
                    </p>
                </div>
            </div>
            <div class="border-2 border-green-700 flex-1 flex flex-col items-center p-4 gap-4">
                <h2 class="text-2xl font-bold">SHARE CERTIFICATE</h2>
                <p>FOR</p>
                <p><strong class="underline">{{ round($capital_subscription->number_of_shares, 0) }}</strong> SHARES
                </p>
                <div class="text-center italic">
                    <p>OF</p>
                    <p>Share Capital</p>
                </div>
                <p class="mt-8 font-semibold">Issued to</p>
                <p class="font-bold underline">{{ $capital_subscription->member->full_name }}</p>
                <div class="text-center mt-8">
                    <p class="underline">{{ $capital_subscription->transaction_date->format('F d, Y') }}</p>
                    <p>Date</p>
                </div>
            </div>
        </div>
    </x-app.cashier.reports.report-layout>

</x-filament-panels::page>
