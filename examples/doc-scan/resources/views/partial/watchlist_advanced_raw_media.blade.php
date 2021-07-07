<tr>
    <td>Raw Results Media</td>
    @if($check->getReport() != null)
        @if($check->getReport()->getWatchlistSummary() != null)
            <table class="table table-striped">
                <tbody>
                <tr>
                    <td>ID</td>
                    <td>
                        <a href="/media/{{$check->getReport()->getWatchlistSummary()->getRawResults()->getMedia()->getId()}}">
                            {{$check->getReport()->getWatchlistSummary()->getRawResults()->getMedia()->getId()}}
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Type</td>
                    <td>{{$check->getReport()->getWatchlistSummary()->getRawResults()->getMedia()->getType()}}</td>
                </tr>
                </tbody>
            </table>
        @endif
    @endif
</tr>