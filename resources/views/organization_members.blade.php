<div>
    <h1>Organization Members</h1>
    <table>
        @forelse($members as $member)
            <tr>
                <td>{{$member->full_name}}</td>
            </tr>
        @empty
            <tr>
                <td>No members found.</td>
            </tr>
        @endforelse
    </table>
</div>