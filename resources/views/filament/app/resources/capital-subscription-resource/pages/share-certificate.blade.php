<x-filament-panels::page>
    <x-app.cashier.reports.report-layout :hasHeader="false">
        <div class="flex w-full gap-8 text-xs text-green-700">
            <div class="flex w-1/3 flex-col items-center border-r-2 border-dashed pr-2">
                <h4 class="mb-2 w-full border-4 border-green-300 bg-green-700 py-2 text-center font-extrabold text-yellow-200">SHARE CERTIFICATE</h4>
                <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-12 print:h-12">
                <p class="mt-4">NO. <span class="inline-block min-w-[6rem] border-b border-green-700">{{ $capital_subscription->code }}</span></p>
                <p class="mt-4 font-bold italic">For <span class="inline-block min-w-[2rem] border-b border-green-700 text-center">{{ round($capital_subscription->number_of_shares, 0) }}</span> Shares </p>
                <p class="font-bold italic">Issued to:</p>
                <p class="mt-6 w-full border-b border-green-700 text-center font-semibold"> {{ $this->member->full_name }}</p>
                <p class="mt-2 w-full border-b border-green-700 text-center">SKSU MPC</p>
                <p class="mt-2 font-semibold italic">Dated {{ $capital_subscription->transaction_date->format('F d, Y') }}</p>
                <p class="mt-4">FROM WHOM TRANSFERRED</p>
                <p class="w-full border-b border-green-700 text-center">&nbsp;</p>
                <p class="mt-2 font-semibold italic">Dated {{ $capital_subscription->transaction_date->format('F d, Y') }}</p>
                <table class="mt-4 text-xs text-green-700">
                    <thead>
                        <tr>
                            <td class="border border-black px-2 font-normal">NO. OF ORIGINAL CERTIFICATE</td>
                            <td class="border border-black px-2 font-normal">NO. OF ORIGINAL CERTIFICATE</td>
                            <td class="border border-black px-2 font-normal">NO. OF SHARES TRANSFERRED</td>
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
                <p class="mt-4 italic">
                    Received Share CERTIFICATE NO. {{ $capital_subscription->code }}
                </p>
                <p class="italic">
                    FOR {{ round($capital_subscription->number_of_shares, 0) }} Shares this {{ $capital_subscription->transaction_date->format('jS \d\a\y \o\f\ F, Y') }}.
                </p>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-evenly">
                    <p class="whitespace-nowrap text-lg font-semibold italic">No. {{ $capital_subscription->code }}</p>
                    <h4 class="mb-2 border-4 border-green-300 bg-green-700 px-8 py-2 text-center font-extrabold text-yellow-200">SHARE CERTIFICATE</h4>
                    <p class="whitespace-nowrap text-lg font-semibold italic">{{ round($capital_subscription->number_of_shares, 0) }} Shares</p>
                </div>
                <div class="flex flex-col items-center bg-green-700 p-2 text-yellow-300 print:text-[9pt] print:leading-6">
                    <strong class="print:text-[11pt]">SULTAN KUDARAT STATE UNIVERSITY - MULTI-PURPOSE COOPERATIVE</strong>
                    <strong class="print:text-[11pt]">(SKSU-MPC)</strong>
                    <div class="text-center print:leading-4">
                        <p>Bo. 2, EJC Montilla, Tacurong City</p>
                        <p>CDA Reg. No.: 9520-12000926 / CIN: 0103120093 / TIN: 005-811-330</p>
                        <p>Contact No: 0906-826-1905 or 0966-702-9200</p>
                        <p>Email Address: sksu.mpc@gmail.com</p>
                    </div>
                </div>
                <div class="mt-4 px-8">
                    <p>
                        <strong class="text-base font-semibold">This Certifies that</strong> <strong class="underline">{{ $capital_subscription->member->full_name }}</strong> is the owner of
                        <em class="font-semibold underline">{{ round($capital_subscription->number_of_shares, 0) }}</em> Shares at <strong>{{ $this->par_value }}</strong> <em>Par Value Per Share.</em>
                    </p>
                    <p class="mt-4 px-12">transferable only on the books of the Cooperative by the holder hereof in person or Attorney-In-Fact, upon surrender of this Share Certificate properly endorsed.</p>
                    <p class="mt-4 italic">
                        <strong class="text-base font-semibold not-italic"> In Witness Whereof, </strong> the said Cooperative has caused this Share Certificate to be signed by its duly authorized officers and to be sealed with the Seal of the Cooperative this <em class="underline">{{ $capital_subscription->transaction_date->format('jS \d\a\y \o\f\ F, Y') }}</em>
                    </p>
                </div>
                <div class="mt-8 flex items-center">
                    <img src="{{ asset('images/logo.jpg') }}" alt="logo" class="h-24 print:h-16">
                    <div class="flex w-full justify-around">
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
        <div class="mt-24 break-before-page gap-8 print:mt-0">
            <div class="flex flex-1 flex-col items-center gap-4 border-2 border-green-700 p-4">
                <h2 class="text-2xl font-bold">SHARE CERTIFICATE</h2>
                <p>FOR</p>
                <p><strong class="underline">{{ round($capital_subscription->number_of_shares, 0) }}</strong> SHARES</p>
                <div class="text-center italic">
                    <p>OF</p>
                    <p>Share Capital</p>
                </div>
                <p class="mt-8 font-semibold">Issued to</p>
                <p class="font-bold underline">{{ $capital_subscription->member->full_name }}</p>
                <div class="mt-8 text-center">
                    <p class="underline">{{ $capital_subscription->transaction_date->format('F d, Y') }}</p>
                    <p>Date</p>
                </div>
            </div>
            <div>
                <div class="mt-4 max-h-96 text-xs">
                    <p>For value received <strong class="underline">{{ number_format($capital_subscription->amount_subscribed, 2) }}</strong> hereby sell, assign and transfer unto <strong class="underline">{{ $capital_subscription->member->full_name }}</strong> Shares of the Share Capital represented by the herewith Certificate and do hereby irrevocably constitute and appoint ______________ to transfer the said shares on the books of the within named Cooperative with full power of substitution in the premises. </p>
                    <p class="mr-8">Dated {{ $capital_subscription->transaction_date->format('F d, Y') }}. </p>
                    <p> In the presence of</p>
                    <div class="mr-8 flex justify-center gap-32">
                        <div class="flex flex-col items-center">
                            <p class="inline-block min-w-[16rem] border-b border-black">&nbsp;</p>
                            <p>Witness</p>
                        </div>
                        <div class="flex flex-col items-center">
                            <p class="inline-block min-w-[16rem] border-b border-black text-center">{{ $capital_subscription->member->full_name }}</p>
                            <p>Share Capital Owner</p>
                        </div>
                    </div>
                </div>
                <div>
                    <p class="mt-4 text-justify text-xs">
                        NOTICE: THE SIGNATURE OF THE ASSIGNMENT MUST CORRESPOND WITH THE NAME AS WRITTEN UPON THE FACE OF THE CERTIFICATE IN EVERY PARTICULAR, WITHOUT ALTERATION OR ENLARGEMENT OR ANY CHANGES WHATSOEVER.
                    </p>
                </div>
            </div>
        </div>
    </x-app.cashier.reports.report-layout>
</x-filament-panels::page>
