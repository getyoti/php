@extends('layouts.app')

@section('content')
    <div class="container mb-3">

        <div class="row">
            <div class="col">
                <h1>Get Session Result</h1>

                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td>Session ID</td>
                        <td>{{ $sessionResult->getSessionId() }}</td>
                    </tr>
                    <tr>
                        <td>State</td>
                        <td>
                            <span class="badge badge-{{ $sessionResult->getState() == 'COMPLETED' ? 'success' : 'secondary' }}">
                                {{ $sessionResult->getState() }}
                            </span>
                        </td>
                    </tr>
                    @if ($sessionResult->getClientSessionToken())
                        <tr>
                            <td>Client Session Token</td>
                            <td>
                                {{ $sessionResult->getClientSessionToken() }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>Client Session Token TTL</td>
                        <td>{{ $sessionResult->getClientSessionTokenTtl() }}</td>
                    </tr>
                    <tr>
                        <td>User Tracking ID</td>
                        <td>{{ $sessionResult->getUserTrackingId() }}</td>
                    </tr>
                    @if ($sessionResult->getBiometricConsentTimestamp())
                        <tr>
                            <td>Biometric Consent Timestamp</td>
                            <td>
                                {{ $sessionResult->getBiometricConsentTimestamp()->format('r') }}
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>


        @if (count($sessionResult->getChecks()) > 0)
            <div class="row pt-4">
                <div class="col">
                    <h2>Checks</h2>

                    <div class="accordion mt-3" id="checks">

                        @if (count($sessionResult->getAuthenticityChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="authenticity-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-authenticity-checks" aria-expanded="true"
                                                aria-controls="collapse-authenticity-checks">
                                            Authenticity Checks
                                        </button>
                                    </h3>
                                </div>

                                <div id="collapse-authenticity-checks" class="collapse"
                                     aria-labelledby="authenticity-checks">
                                    <div class="card-body">
                                        @foreach($sessionResult->getAuthenticityChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($sessionResult->getIdDocumentTextDataChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="text-data-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-text-data-checks" aria-expanded="true"
                                                aria-controls="collapse-text-data-checks">
                                            ID Document Text Data Checks
                                        </button>
                                    </h3>

                                </div>

                                <div id="collapse-text-data-checks" class="collapse" aria-labelledby="text-data-checks">
                                    <div class="card-body">
                                        @foreach($sessionResult->getTextDataChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($sessionResult->getFaceMatchChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="face-match-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-face-match-checks" aria-expanded="true"
                                                aria-controls="collapse-face-match-checks">
                                            FaceMatch Checks
                                        </button>
                                    </h3>

                                </div>

                                <div id="collapse-face-match-checks" class="collapse"
                                     aria-labelledby="face-match-checks">
                                    <div class="card-body">
                                        @foreach ($sessionResult->getFaceMatchChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($sessionResult->getLivenessChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="liveness-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-liveness-checks" aria-expanded="true"
                                                aria-controls="collapse-liveness-checks">
                                            Liveness Checks
                                        </button>
                                    </h3>

                                </div>

                                <div id="collapse-liveness-checks" class="collapse" aria-labelledby="liveness-checks">
                                    <div class="card-body">
                                        @foreach ($sessionResult->getLivenessChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($sessionResult->getIdDocumentComparisonChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="comparison-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-comparison-checks" aria-expanded="true"
                                                aria-controls="collapse-comparison-checks">
                                            ID Document Comparison Checks
                                        </button>
                                    </h3>
                                </div>

                                <div id="collapse-comparison-checks" class="collapse"
                                     aria-labelledby="comparison-checks">
                                    <div class="card-body">
                                        @foreach($sessionResult->getIdDocumentComparisonChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($sessionResult->getSupplementaryDocumentTextDataChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="sup-doc-text-data-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-sup-doc-text-data-checks" aria-expanded="true"
                                                aria-controls="collapse-sup-doc-text-data-checks">
                                            Supplementary Document Text Data Checks
                                        </button>
                                    </h3>
                                </div>

                                <div id="collapse-sup-doc-text-data-checks" class="collapse"
                                     aria-labelledby="sup-doc-text-data-checks">
                                    <div class="card-body">
                                        @foreach($sessionResult->getSupplementaryDocumentTextDataChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($sessionResult->getThirdPartyIdentityChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="third-party-identity-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-third-party-identity-checks" aria-expanded="true"
                                                aria-controls="collapse-third-party-identity-checks">
                                            Third Party Identity Checks
                                        </button>
                                    </h3>
                                </div>

                                <div id="collapse-third-party-identity-checks" class="collapse"
                                     aria-labelledby="third-party-identity-checks">
                                    <div class="card-body">
                                        @foreach($sessionResult->getThirdPartyIdentityChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($sessionResult->getWatchlistScreeningChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="watchlist-screening-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-watchlist-screening-checks" aria-expanded="true"
                                                aria-controls="collapse-watchlist-screening-checks">
                                            Watchlist Screening Checks
                                        </button>
                                    </h3>
                                </div>

                                <div id="collapse-watchlist-screening-checks" class="collapse"
                                     aria-labelledby="watchlist-screening-checks">
                                    <div class="card-body">
                                        @foreach($sessionResult->getWatchlistScreeningChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (count($sessionResult->getWatchlistAdvancedCaChecks()) > 0)
                            <div class="card">
                                <div class="card-header" id="watchlist-advanced-ca-checks">
                                    <h3 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                                data-target="#collapse-watchlist-advanced-ca-checks"
                                                aria-expanded="true"
                                                aria-controls="collapse-watchlist-advanced-ca-checks">
                                            Watchlist Advanced Checks
                                        </button>
                                    </h3>
                                </div>

                                <div id="collapse-watchlist-advanced-ca-checks" class="collapse"
                                     aria-labelledby="watchlist-advanced-ca-checks">
                                    <div class="card-body">
                                        @foreach($sessionResult->getWatchlistAdvancedCaChecks() as $check)
                                            @include('partial/check', ['check' => $check])
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if (count($sessionResult->getResources()->getIdDocuments()) > 0)
            <div class="row pt-4">
                <div class="col">
                    <h2>ID Documents</h2>
                </div>
            </div>

            @foreach ($sessionResult->getResources()->getIdDocuments() as $docNum => $document)

                <div class="row pt-4">
                    <div class="col">

                        <h3>{{ $document->getDocumentType() }} <span
                                    class="badge badge-primary">{{ $document->getIssuingCountry() }}</span></h3>

                        <div class="accordion mt-3">

                            @if ($document->getDocumentFields())
                                <div class="card">
                                    <div class="card-header" id="document-fields-{{ $docNum }}">
                                        <h4 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapse-document-fields-{{ $docNum }}"
                                                    aria-expanded="true"
                                                    aria-controls="collapse-document-fields-{{ $docNum }}">
                                                Document Fields
                                            </button>
                                        </h4>
                                    </div>
                                    <div id="collapse-document-fields-{{ $docNum }}" class="collapse"
                                         aria-labelledby="document-fields-{{ $docNum }}">
                                        <div class="card-body">
                                            @if ($document->getDocumentFields()->getMedia())
                                                <h5>Media</h5>
                                                <table class="table table-striped table-light">
                                                    <tbody>
                                                    <tr>
                                                        <td>ID</td>
                                                        <td>
                                                            <a href="/media/{{ $document->getDocumentFields()->getMedia()->getId() }}">
                                                                {{ $document->getDocumentFields()->getMedia()->getId() }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($document->getDocumentIdPhoto())
                                <div class="card">
                                    <div class="card-header" id="document-id-photo-{{ $docNum }}">
                                        <h4 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapse-document-photo-{{ $docNum }}"
                                                    aria-expanded="true"
                                                    aria-controls="collapse-document-photo-{{ $docNum }}">
                                                Document ID Photo
                                            </button>
                                        </h4>
                                    </div>
                                    <div id="collapse-document-photo-{{ $docNum }}" class="collapse"
                                         aria-labelledby="document-photo-{{ $docNum }}">
                                        <div class="card-body">
                                            @if ($document->getDocumentIdPhoto()->getMedia())
                                                <img class="card-img-top"
                                                     src="/media/{{ $document->getDocumentIdPhoto()->getMedia()->getId() }}"/>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (count($document->getTextExtractionTasks()) > 0)
                                <div class="card">
                                    <div class="card-header" id="text-extraction-tasks-{{ $docNum }}">
                                        <h4 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapse-text-extraction-tasks-{{ $docNum }}"
                                                    aria-expanded="true"
                                                    aria-controls="collapse-text-extraction-tasks-{{ $docNum }}">
                                                Text Extraction Tasks
                                            </button>
                                        </h4>
                                    </div>
                                    <div id="collapse-text-extraction-tasks-{{ $docNum }}" class="collapse"
                                         aria-labelledby="text-extraction-tasks-{{ $docNum }}">
                                        <div class="card-body">
                                            @foreach ($document->getTextExtractionTasks() as $task)
                                                @include('partial/task', ['task' => $task])

                                                @if (count($task->getGeneratedTextDataChecks()) > 0)
                                                    <h5>Generated Text Data Checks</h5>
                                                    @foreach ($task->getGeneratedTextDataChecks() as $check)
                                                        <table class="table table-striped">
                                                            <tbody>
                                                            <tr>
                                                                <td>ID</td>
                                                                <td>{{ $check->getId() }}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    @endforeach
                                                @endif

                                                @if (count($task->getGeneratedMedia()) > 0)
                                                    <h5>Generated Media</h5>
                                                    @foreach ($task->getGeneratedMedia() as $media)
                                                        <table class="table table-striped">
                                                            <tbody>
                                                            <tr>
                                                                <td>ID</td>
                                                                <td>
                                                                    <a href="/media/{{ $media->getId() }}">{{ $media->getId() }}</a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Type</td>
                                                                <td>{{ $media->getType() }}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    @endforeach
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif


                            @if (count($document->getPages()) > 0)
                                @foreach ($document->getPages() as $pageNum => $page)
                                    <div class="card">
                                        <div class="card-header" id="document-pages-{{ $docNum }}-{{ $pageNum }}">
                                            <h4 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#collapse-document-pages-{{ $docNum }}-{{ $pageNum }}"
                                                        aria-expanded="true"
                                                        aria-controls="collapse-document-pages-{{ $docNum }}-{{ $pageNum }}">
                                                    Page {{ $pageNum + 1 }}
                                                </button>
                                            </h4>
                                        </div>
                                        <div id="collapse-document-pages-{{ $docNum }}-{{ $pageNum }}" class="collapse"
                                             aria-labelledby="document-pages-{{ $docNum }}-{{ $pageNum }}">

                                            @if ($page->getMedia())
                                                <div class="card-group">
                                                    <div class="card" style="width: 18rem;">
                                                        <img class="card-img-top"
                                                             src="/media/{{ $page->getMedia()->getId() }}"/>
                                                        <div class="card-body">
                                                            <p>Method: {{ $page->getCaptureMethod() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($page->getFrames())
                                                <div class="card-group">
                                                    @foreach ($page->getFrames() as $frame)
                                                        @if ($frame->getMedia())
                                                            <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top"
                                                                     src="/media/{{ $frame->getMedia()->getId() }}"/>
                                                                <div class="card-body">
                                                                    <div class="card-title">Frame</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                    </div>
                </div>

            @endforeach
        @endif


        @if (count($sessionResult->getResources()->getSupplementaryDocuments()) > 0)
            <div class="row pt-4">
                <div class="col">
                    <h2>Supplementary Documents</h2>
                </div>
            </div>

            @foreach ($sessionResult->getResources()->getSupplementaryDocuments() as $docNum => $document)

                <div class="row pt-4">
                    <div class="col">

                        <h3>{{ $document->getDocumentType() }} <span
                                    class="badge badge-primary">{{ $document->getIssuingCountry() }}</span></h3>

                        <div class="accordion mt-3">

                            @if ($document->getDocumentFields())
                                <div class="card">
                                    <div class="card-header" id="sub-doc-fields-{{ $docNum }}">
                                        <h4 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapse-sub-doc-fields-{{ $docNum }}"
                                                    aria-expanded="true"
                                                    aria-controls="collapse-sub-doc-fields-{{ $docNum }}">
                                                Document Fields
                                            </button>
                                        </h4>
                                    </div>
                                    <div id="collapse-sub-doc-fields-{{ $docNum }}" class="collapse"
                                         aria-labelledby="sub-doc-fields-{{ $docNum }}">
                                        <div class="card-body">
                                            @if ($document->getDocumentFields()->getMedia())
                                                <h5>Media</h5>
                                                <table class="table table-striped table-light">
                                                    <tbody>
                                                    <tr>
                                                        <td>ID</td>
                                                        <td>
                                                            <a href="/media/{{ $document->getDocumentFields()->getMedia()->getId() }}">
                                                                {{ $document->getDocumentFields()->getMedia()->getId() }}
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if (count($document->getTextExtractionTasks()) > 0)
                                <div class="card">
                                    <div class="card-header" id="sup-doc-text-extraction-tasks-{{ $docNum }}">
                                        <h4 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapse-sup-doc-text-extraction-tasks-{{ $docNum }}"
                                                    aria-expanded="true"
                                                    aria-controls="collapse-sup-doc-text-extraction-tasks-{{ $docNum }}">
                                                Text Extraction Tasks
                                            </button>
                                        </h4>
                                    </div>
                                    <div id="collapse-sup-doc-text-extraction-tasks-{{ $docNum }}" class="collapse"
                                         aria-labelledby="sup-doc-text-extraction-tasks-{{ $docNum }}">
                                        <div class="card-body">
                                            @foreach ($document->getTextExtractionTasks() as $task)
                                                @include('partial/task', ['task' => $task])

                                                @if (count($task->getGeneratedTextDataChecks()) > 0)
                                                    <h5>Generated Text Data Checks</h5>
                                                    @foreach ($task->getGeneratedTextDataChecks() as $check)
                                                        <table class="table table-striped">
                                                            <tbody>
                                                            <tr>
                                                                <td>ID</td>
                                                                <td>{{ $check->getId() }}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    @endforeach
                                                @endif

                                                @if (count($task->getGeneratedMedia()) > 0)
                                                    <h5>Generated Media</h5>
                                                    @foreach ($task->getGeneratedMedia() as $media)
                                                        <table class="table table-striped">
                                                            <tbody>
                                                            <tr>
                                                                <td>ID</td>
                                                                <td>
                                                                    <a href="/media/{{ $media->getId() }}">{{ $media->getId() }}</a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Type</td>
                                                                <td>{{ $media->getType() }}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    @endforeach
                                                @endif

                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($document->getDocumentFile())
                                <div class="card">
                                    <div class="card-header" id="sup-doc-file-{{ $docNum }}">
                                        <h4 class="mb-0">
                                            <a class="btn btn-link" type="button"
                                               href="/media/{{ $document->getDocumentFile()->getMedia()->getId() }}">
                                                Download File
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                            @endif

                            @if (count($document->getPages()) > 0)
                                @foreach ($document->getPages() as $pageNum => $page)
                                    <div class="card">
                                        <div class="card-header" id="sup-doc-pages-{{ $docNum }}-{{ $pageNum }}">
                                            <h4 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse"
                                                        data-target="#collapse-sup-doc-pages-{{ $docNum }}-{{ $pageNum }}"
                                                        aria-expanded="true"
                                                        aria-controls="collapse-sup-doc-pages-{{ $docNum }}-{{ $pageNum }}">
                                                    Page {{ $pageNum + 1 }}
                                                </button>
                                            </h4>
                                        </div>
                                        <div id="collapse-sup-doc-pages-{{ $docNum }}-{{ $pageNum }}" class="collapse"
                                             aria-labelledby="sup-doc-pages-{{ $docNum }}-{{ $pageNum }}">

                                            @if ($page->getMedia())
                                                <div class="card-group">
                                                    <div class="card" style="width: 18rem;">
                                                        <img class="card-img-top"
                                                             src="/media/{{ $page->getMedia()->getId() }}"/>
                                                        <div class="card-body">
                                                            <p>Method: {{ $page->getCaptureMethod() }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($page->getFrames())
                                                <div class="card-group">
                                                    @foreach ($page->getFrames() as $frame)
                                                        @if ($frame->getMedia())
                                                            <div class="card" style="width: 18rem;">
                                                                <img class="card-img-top"
                                                                     src="/media/{{ $frame->getMedia()->getId() }}"/>
                                                                <div class="card-body">
                                                                    <div class="card-title">Frame</div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif

                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                    </div>
                </div>

            @endforeach
        @endif

        @if (count($sessionResult->getResources()->getZoomLivenessResources()) > 0)
            <div class="row pt-4">
                <div class="col">
                    <h2>Zoom Liveness Resources</h2>
                </div>
            </div>

            @foreach ($sessionResult->getResources()->getZoomLivenessResources() as $livenessNum => $livenessResource)

                <div class="row pt-4">
                    <div class="col">
                        <table class="table table-striped table-light">
                            <tbody>
                            <tr>
                                <td>ID</td>
                                <td>{{ $livenessResource->getId() }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="accordion mt-3">

                            @if ($livenessResource->getFrames() != null && count($livenessResource->getFrames()) > 0)
                                <div class="card">
                                    <div class="card-header" id="liveness-{{ $livenessNum }}-frames">
                                        <h3 class="mb-0">
                                            <button class="btn btn-link" type="button" data-toggle="collapse"
                                                    data-target="#collapse-liveness-{{ $livenessNum }}-frames"
                                                    aria-expanded="true"
                                                    aria-controls="collapse-liveness-{{ $livenessNum }}-frames">
                                                Frames
                                            </button>
                                        </h3>
                                    </div>
                                    <div id="collapse-liveness-{{ $livenessNum }}-frames" class="collapse"
                                         aria-labelledby="liveness-{{ $livenessNum }}-frames">
                                        <div class="card-group">
                                            @foreach ($livenessResource->getFrames() as $frame)
                                                @if ($frame->getMedia())
                                                    <div class="card">
                                                        <img class="card-img-top"
                                                             src="/media/{{ $frame->getMedia()->getId() }}"/>
                                                        <div class="card-body">
                                                            <h5 class="card-title">Frame</h5>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

            @endforeach

        @endif
    </div>
@endsection