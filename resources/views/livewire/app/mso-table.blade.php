<div>
    <select class="rounded font-semibold" wire:model.live="mso_type">
        <option value="1">Savings</option>
        <option value="2">Imprest</option>
        <option value="3">Love Gift</option>
        <option value="4">Time Deposit</option>
    </select>

    <div class="mt-4">
        @switch($mso_type)
            @case(1)
                <div>
                    @livewire('app.savings-table', ['member_id' => $member->id])
                </div>
            @break

            @case(2)
                <div>
                    @livewire('app.imprests-table', ['member_id' => $member->id])
                </div>
            @break

            @case(3)
                <div>
                    @livewire('app.love-gifts-table', ['member_id' => $member->id])
                </div>
            @break

            @case(4)
                <div>
                    @livewire('app.time-deposits-table', ['member_id' => $member->id])
                </div>
            @break

            @default
        @endswitch
    </div>
</div>
