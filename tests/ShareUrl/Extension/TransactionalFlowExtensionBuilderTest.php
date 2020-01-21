<?php

declare(strict_types=1);

namespace YotiTest\ShareUrl\Extension;

use Yoti\ShareUrl\Extension\TransactionalFlowExtensionBuilder;
use YotiTest\TestCase;

/**
 * @coversDefaultClass \Yoti\ShareUrl\Extension\TransactionalFlowExtensionBuilder
 */
class TransactionalFlowExtensionBuilderTest extends TestCase
{
    const TYPE_TRANSACTIONAL_FLOW = 'TRANSACTIONAL_FLOW';

    /**
     * @covers ::build
     * @covers ::withContent
     */
    public function testBuild()
    {
        $someContent = ['some' => 'content'];

        $constraints = (new TransactionalFlowExtensionBuilder())
            ->withContent($someContent)
            ->build();

        $expectedJson = json_encode([
            'type' => self::TYPE_TRANSACTIONAL_FLOW,
            'content' => $someContent,
        ]);

        $this->assertEquals($expectedJson, json_encode($constraints));
        $this->assertEquals($expectedJson, $constraints);
    }

    /**
     * @covers ::build
     * @covers ::withContent
     */
    public function testNullContent()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('content cannot be null');

        (new TransactionalFlowExtensionBuilder())
            ->withContent(null)
            ->build();
    }
}
