<table class="table table-striped">
    <tbody>
    <tr>
        <td>ID</td>
        <td>{{ $check->getId() }}</td>
    </tr>
    <tr>
        <td>State</td>
        <td>
                <span class="badge badge-{{ $check->getState() == 'DONE' ? 'success' : 'secondary' }}">
                    {{ $check->getState() }}
                </span>
        </td>
    </tr>
    <tr>
        <td>Created</td>
        <td>{{ $check->getCreated()->format('r') }}</td>
    </tr>
    <tr>
        <td>Last Updated</td>
        <td>{{ $check->getLastUpdated()->format('r') }}</td>
    </tr>
    <tr>
        <td>Resources Used</td>
        <td>{{ implode(', ', $check->getResourcesUsed()) }}</td>
    </tr>


    @if ($check->getReport())

        @if ($check->getReport()->getRecommendation())
            <tr>
                <td>Recommendation</td>
                <td>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <td>Value</td>
                            <td>{{ $check->getReport()->getRecommendation()->getValue() }}</td>
                        </tr>
                        <tr>
                            <td>Reason</td>
                            <td>{{ $check->getReport()->getRecommendation()->getReason() }}</td>
                        </tr>
                        <tr>
                            <td>Recovery Suggestion</td>
                            <td>{{ $check->getReport()->getRecommendation()->getRecoverySuggestion() }}</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif

        @if (count($check->getReport()->getBreakdown()) > 0)
            <tr>
                <td>Breakdown</td>
                <td>
                    @foreach ($check->getReport()->getBreakdown() as $breakdown)
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <td>Sub Check</td>
                                <td>{{ $breakdown->getSubCheck() }}</td>
                            </tr>
                            <tr>
                                <td>Result</td>
                                <td>{{ $breakdown->getResult() }}</td>
                            </tr>
                            @if (count($breakdown->getDetails()) > 0)
                                <tr>
                                    <td>Details</td>
                                    <td>
                                        <table class="table table-striped">
                                            <tbody>
                                            @foreach ($breakdown->getDetails() as $details)
                                                <tr>
                                                    <td>{{ $details->getName() }}</td>
                                                    <td>{{ $details->getValue() }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    @endforeach
                </td>
            </tr>
        @endif

    @endif

    @if (count($check->getGeneratedMedia()) > 0)
        <tr>
            <td>Generated Media</td>
            <td>
                @foreach ($check->getGeneratedMedia() as $media)
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <td>ID</td>
                            <td><a href="/media/{{ $media->getId() }}">{{ $media->getId() }}</a></td>
                        </tr>
                        <tr>
                            <td>Type</td>
                            <td>{{ $media->getType() }}</td>
                        </tr>
                        </tbody>
                    </table>
                @endforeach
            </td>
        </tr>
    @endif

    @if ($check->getType() == 'WATCHLIST_ADVANCED_CA' && $check->getReport() != null)
        <tr>
            @include('partial/watchlist_advanced_raw_media', ['check' => $check])
        </tr>
    @endif

    </tbody>
</table>