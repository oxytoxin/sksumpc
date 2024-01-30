<div>
    <h1 class="text-center font-bold text-xl my-2">STATEMENT OF FINANCIAL CONDITION</h1>
    <div class="overflow-x-auto">
        <table class="w-full my-2 text-xs">
            <tbody>
                @foreach ($this->items as $item)
                    @switch($item['type'] ?? '')
                        @case('title')
                            @foreach ($item['data'] as $column)
                                <tr>
                                    <th class="border border-black" colspan="4">{{ $column }}</th>
                                </tr>
                            @endforeach
                        @break

                        @case('header')
                            <tr>
                                <th class="border border-black px-2 text-base">{{ $item['data']['name'] }}</th>
                                <th class="border border-black px-2 text-center">{{ $item['data']['current'] }}</th>
                                <th class="border border-black px-2 text-center">{{ $item['data']['previous'] }}</th>
                                <th class="border border-black px-2 text-center">{{ $item['data']['incdec'] }}</th>
                            </tr>
                        @break

                        @default
                            <tr>
                                <td class="border border-black px-2 text-center">{{ $item['data']['name'] }}</td>
                                <td class="border border-black px-2 text-center">
                                    {{ renumber_format($item['data']['current'], 2) }}</td>
                                <td class="border border-black px-2 text-center">
                                    {{ renumber_format($item['data']['previous'], 2) }}</td>
                                <td class="border border-black px-2 text-center">{{ $item['data']['incdec'] }}</td>
                            </tr>
                    @endswitch
                @endforeach
            </tbody>
        </table>

    </div>
</div>
