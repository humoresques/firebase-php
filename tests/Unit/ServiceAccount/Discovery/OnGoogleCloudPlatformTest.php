<?php

declare(strict_types=1);

namespace Kreait\Firebase\Tests\Unit\ServiceAccount\Discovery;

use Kreait\Firebase\Exception\ServiceAccountDiscoveryFailed;
use Kreait\Firebase\ServiceAccount\Discovery\OnGoogleCloudPlatform;
use Kreait\GcpMetadata;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @internal
 */
class OnGoogleCloudPlatformTest extends TestCase
{
    /** @var GcpMetadata|ObjectProphecy */
    private $metadata;

    protected function setUp(): void
    {
        $this->metadata = $this->prophesize(GcpMetadata::class);
    }

    public function testItUsesGcpMetadata(): void
    {
        $this->metadata->project('project-id')->willReturn('project-id');
        $this->metadata->instance('service-accounts/default/email')->willReturn('email@example.org');

        $discoverer = new OnGoogleCloudPlatform($this->metadata->reveal());

        $serviceAccount = $discoverer();

        $this->assertSame('project-id', $serviceAccount->getProjectId());
        $this->assertSame('email@example.org', $serviceAccount->getClientEmail());
    }

    public function testIfFailsWhenNotOnGcp(): void
    {
        $discoverer = new OnGoogleCloudPlatform(new GcpMetadata());

        $this->expectException(ServiceAccountDiscoveryFailed::class);
        $discoverer();
    }
}
