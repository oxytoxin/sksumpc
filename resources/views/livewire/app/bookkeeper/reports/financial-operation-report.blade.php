<div>
    <h1 class="text-center font-bold text-xl my-2">STATEMENT OF FINANCIAL OPERATION</h1>
    <div class="overflow-x-auto">
        <table class="w-full my-2">
            <tbody>
                @foreach ($this->items as $item)
                    <tr>
                        <th class="border border-black" colspan="3">{{ $item['title'] }}</th>
                    </tr>
                    @foreach ($item['children'] as $child)
                        <tr>
                            <th class="border border-black px-2">{{ $child['title'] }}</th>
                            <th class="border border-black px-2">CURRENT</th>
                            <th class="border border-black px-2">PREVIOUS</th>
                        </tr>
                        @foreach ($child['entries'] as $entry)
                            <tr>
                                <td class="border border-black px-2 text-xs">{{ $entry['name'] }}</td>
                                <td class="border border-black px-2 text-xs"></td>
                                <td class="border border-black px-2 text-xs"></td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="border border-black px-2 font-bold text-xs">TOTAL {{ $child['title'] }}</td>
                            <td class="border border-black px-2 text-xs"></td>
                            <td class="border border-black px-2 text-xs"></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="border border-black px-2 font-bold">TOTAL {{ $item['title'] }}</td>
                        <td class="border border-black px-2 text-xs"></td>
                        <td class="border border-black px-2 text-xs"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
