<?php

namespace YotiTest\Util\Profile;

use Yoti\Entity\Anchor;
use YotiTest\TestCase;
use Yoti\Util\Profile\AnchorConverter;

/**
 * @coversDefaultClass \Yoti\Util\Profile\AnchorListConverter
 */
class AnchorConverterTest extends TestCase
{
    /**
     * @covers ::convertAnchor
     */
    public function testConvertingSourceAnchor()
    {
        $anchor = $this->parseFromBase64String(TestAnchors::SOURCE_PP_ANCHOR);

        $this->assertEquals('Source', $anchor->getType());
        $this->assertEquals('OCR', $anchor->getSubtype());
        $this->assertEquals(
            '2018-04-12 13:14:32.835537',
            $anchor->getSignedTimestamp()->getTimestamp()->format('Y-m-d H:i:s.u')
        );
        $this->assertEquals('PASSPORT', $anchor->getValue());

        $this->assertSerialNumber($anchor, '277870515583559162487099305254898397834');
        $this->assertIssuer($anchor, 'id-at-commonName', 'passport-registration-server');
    }

    /**
     * @covers ::convertAnchor
     */
    public function testConvertingVerifierAnchor()
    {
        $anchor = $this->parseFromBase64String(TestAnchors::VERIFIER_YOTI_ADMIN_ANCHOR);

        $this->assertEquals('Verifier', $anchor->getType());
        $this->assertEquals('', $anchor->getSubtype());
        $this->assertEquals(
            '2018-04-11 12:13:04.095238',
            $anchor->getSignedTimestamp()->getTimestamp()->format('Y-m-d H:i:s.u')
        );
        $this->assertEquals('YOTI_ADMIN', $anchor->getValue());

        $this->assertSerialNumber($anchor, '256616937783084706710155170893983549581');
        $this->assertIssuer($anchor, 'id-at-commonName', 'driving-licence-registration-server');
    }

    /**
     * @covers ::convert
     */
    public function testConvert()
    {
        $anchor = new \Attrpubapi\Anchor();
        $anchor->mergeFromString(base64_decode(TestAnchors::SOURCE_PP_ANCHOR));
        $anchorMap = AnchorConverter::convert($anchor);
        $this->assertEquals(Anchor::TYPE_SOURCE_OID, $anchorMap['oid']);
        $this->assertEquals('PASSPORT', $anchorMap['yoti_anchor']->getValue());
    }

    /**
     * @param string $anchorString
     *
     * @return Anchor
     */
    private function parseFromBase64String($anchorString)
    {
        $anchor = new \Attrpubapi\Anchor();
        $anchor->mergeFromString(base64_decode($anchorString));
        return AnchorConverter::convert($anchor)['yoti_anchor'];
    }

    /**
     * @param Anchor $anchor
     * @param string $serial_number
     */
    private function assertSerialNumber($anchor, $serial_number)
    {
        $cert = $anchor->getOriginServerCerts()[0];
        $this->assertSame($serial_number, $cert->tbsCertificate->serialNumber->value);
    }

    /**
     * @param Anchor $anchor
     * @param string $type
     * @param string $value
     */
    private function assertIssuer($anchor, $type, $value)
    {
        $cert = $anchor->getOriginServerCerts()[0];
        $issuer = $cert->tbsCertificate->issuer;
        $this->assertEquals($type, $issuer->rdnSequence[0][0]->type);
        $this->assertEquals($value, $issuer->rdnSequence[0][0]->value->printableString);
    }
}