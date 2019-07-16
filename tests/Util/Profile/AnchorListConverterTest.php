<?php

namespace YotiTest\Util\Profile;

use ArrayObject;
use Yoti\Entity\Anchor;
use YotiTest\TestCase;
use Yoti\Util\Profile\AnchorListConverter;

/**
 * @coversDefaultClass \Yoti\Util\Profile\AnchorListConverter
 */
class AnchorListConverterTest extends TestCase
{
    /**
     * @covers ::convert
     */
    public function testConvertingTwoSources()
    {
        $anchorsData = AnchorListConverter::convert(new ArrayObject([
            $this->parseFromBase64String(TestAnchors::SOURCE_PP_ANCHOR),
            $this->parseFromBase64String(TestAnchors::SOURCE_DL_ANCHOR),
        ]));

        $anchorSource1 = $anchorsData[Anchor::TYPE_SOURCE_OID][0];
        $anchorSource2 = $anchorsData[Anchor::TYPE_SOURCE_OID][1];

        $this->assertEquals('PASSPORT', $anchorSource1->getValue());
        $this->assertEquals('DRIVING_LICENCE', $anchorSource2->getValue());
    }

    /**
     * @covers ::convert
     */
    public function testConvertingAnyAnchor()
    {
        $anchorsData = AnchorListConverter::convert(new ArrayObject([
            $this->parseFromBase64String(TestAnchors::SOURCE_DL_ANCHOR),
            $this->parseFromBase64String(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR),
        ]));

        $anchorSource = $anchorsData[Anchor::TYPE_SOURCE_OID][0];
        $this->assertEquals('Source', $anchorSource->getType());
        $this->assertEquals('DRIVING_LICENCE', $anchorSource->getValue());

        $anchorVerifier = $anchorsData[Anchor::TYPE_VERIFIER_OID][0];
        $this->assertEquals('Verifier', $anchorVerifier->getType());
        $this->assertEquals('YOTI_ADMIN', $anchorVerifier->getValue());
    }

    /**
     * @param string $anchorString
     *
     * @return array $anchors
     */
    private function parseFromBase64String($anchorString)
    {
        $anchor = new \Attrpubapi\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));
        return $anchor;
    }
}
