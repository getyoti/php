<?php

declare(strict_types=1);

namespace Yoti\DocScan\Session\Retrieve;

use Yoti\DocScan\Constants;
use Yoti\Util\DateTime;

class GetSessionResult
{
    /**
     * @var string|null
     */
    private $state;

    /**
     * @var CheckResponse[]
     */
    private $checks = [];

    /**
     * @var ResourceContainer|null
     */
    private $resources;

    /**
     * @var string|null
     */
    private $sessionId;

    /**
     * @var string|null
     */
    private $userTrackingId;

    /**
     * @var string|null
     */
    private $clientSessionToken;

    /**
     * @var int|null
     */
    private $clientSessionTokenTtl;

    /**
     * @var \DateTime
     */
    private $biometricConsent;

    /**
     * DocScanSession constructor.
     * @param array<string, mixed> $sessionData
     */
    public function __construct(array $sessionData)
    {
        $this->state = $sessionData['state'] ?? null;
        $this->sessionId = $sessionData['session_id'] ?? null;
        $this->userTrackingId = $sessionData['user_tracking_id'] ?? null;
        $this->clientSessionToken = $sessionData['client_session_token'] ?? null;
        $this->clientSessionTokenTtl = $sessionData['client_session_token_ttl'] ?? null;

        if (isset($sessionData['biometric_consent'])) {
            $this->biometricConsent = DateTime::stringToDateTime($sessionData['biometric_consent']);
        }

        if (isset($sessionData['checks'])) {
            foreach ($sessionData['checks'] as $check) {
                $this->checks[] = $this->createCheckFromArray($check);
            }
        }

        if (isset($sessionData['resources'])) {
            $this->resources = new ResourceContainer($sessionData['resources']);
        }
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return CheckResponse[]
     */
    public function getChecks(): array
    {
        return $this->checks;
    }

    /**
     * @return ResourceContainer|null
     */
    public function getResources(): ?ResourceContainer
    {
        return $this->resources;
    }

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    /**
     * @return string|null
     */
    public function getUserTrackingId(): ?string
    {
        return $this->userTrackingId;
    }

    /**
     * @return string|null
     */
    public function getClientSessionToken(): ?string
    {
        return $this->clientSessionToken;
    }

    /**
     * @return int|null
     */
    public function getClientSessionTokenTtl(): ?int
    {
        return $this->clientSessionTokenTtl;
    }

    /**
     * @return \DateTime|null
     */
    public function getBiometricConsentTimestamp(): ?\DateTime
    {
        return $this->biometricConsent;
    }

    /**
     * @return AuthenticityCheckResponse[]
     */
    public function getAuthenticityChecks(): array
    {
        return $this->filterCheckByType(AuthenticityCheckResponse::class);
    }

    /**
     * @return IdDocumentComparisonCheckResponse[]
     */
    public function getIdDocumentComparisonChecks(): array
    {
        return $this->filterCheckByType(IdDocumentComparisonCheckResponse::class);
    }

    /**
     * @return ThirdPartyIdentityCheckResponse[]
     */
    public function getThirdPartyIdentityChecks(): array
    {
        return $this->filterCheckByType(ThirdPartyIdentityCheckResponse::class);
    }

    /**
     * @return WatchlistScreeningCheckResponse[]
     */
    public function getWatchlistScreeningChecks(): array
    {
        return $this->filterCheckByType(WatchlistScreeningCheckResponse::class);
    }

    /**
     * @return FaceMatchCheckResponse[]
     */
    public function getFaceMatchChecks(): array
    {
        return $this->filterCheckByType(FaceMatchCheckResponse::class);
    }

    /**
     * @deprecated replaced by ::getIdDocumentTextDataChecks()
     *
     * @return TextDataCheckResponse[]
     */
    public function getTextDataChecks(): array
    {
        return $this->getIdDocumentTextDataChecks();
    }

    /**
     * @return TextDataCheckResponse[]
     */
    public function getIdDocumentTextDataChecks(): array
    {
        return $this->filterCheckByType(TextDataCheckResponse::class);
    }

    /**
     * @return SupplementaryDocTextDataCheckResponse[]
     */
    public function getSupplementaryDocumentTextDataChecks(): array
    {
        return $this->filterCheckByType(SupplementaryDocTextDataCheckResponse::class);
    }

    /**
     * @return mixed[]
     */
    public function getWatchlistAdvancedCaChecks(): array
    {
        return $this->filterCheckByType(WatchlistAdvancedCaCheckResponse::class);
    }

    /**
     * @return LivenessCheckResponse[]
     */
    public function getLivenessChecks(): array
    {
        return $this->filterCheckByType(LivenessCheckResponse::class);
    }

    /**
     * @param array<string, mixed> $check
     * @return CheckResponse
     * @throws \Yoti\Exception\DateTimeException
     */
    private function createCheckFromArray(array $check): CheckResponse
    {
        switch ($check['type'] ?? null) {
            case Constants::ID_DOCUMENT_AUTHENTICITY:
                return new AuthenticityCheckResponse($check);
            case Constants::ID_DOCUMENT_COMPARISON:
                return new IdDocumentComparisonCheckResponse($check);
            case Constants::ID_DOCUMENT_FACE_MATCH:
                return new FaceMatchCheckResponse($check);
            case Constants::ID_DOCUMENT_TEXT_DATA_CHECK:
                return new TextDataCheckResponse($check);
            case Constants::SUPPLEMENTARY_DOCUMENT_TEXT_DATA_CHECK:
                return new SupplementaryDocTextDataCheckResponse($check);
            case Constants::LIVENESS:
                return new LivenessCheckResponse($check);
            case Constants::THIRD_PARTY_IDENTITY:
                return new ThirdPartyIdentityCheckResponse($check);
            case Constants::WATCHLIST_SCREENING:
                return new WatchlistScreeningCheckResponse($check);
            case Constants::WATCHLIST_ADVANCED_CA:
                return new WatchlistAdvancedCaCheckResponse($check);
            default:
                return new CheckResponse($check);
        }
    }

    /**
     * @param string $class
     * @return mixed[]
     */
    private function filterCheckByType(string $class): array
    {
        $filtered = array_filter(
            $this->getChecks(),
            function ($checkResponse) use ($class): bool {
                return $checkResponse instanceof $class;
            }
        );

        return array_values($filtered);
    }
}
