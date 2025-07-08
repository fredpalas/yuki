<?php

namespace App\Tests\Feature\Controller;

use App\Tests\Shared\Infrastructure\PhpUnit\FeatureTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HealthCheckControllerTest extends FeatureTestCase
{
    public const ENDPOINT = '/health';
    private static KernelBrowser $baseClient;

    protected function setUp(): void
    {
        parent::setUp();

        self::$baseClient = static::createClient();
    }

    public function testShouldReturnOk()
    {
        self::$baseClient->request('GET', self::ENDPOINT);

        $response = self::$baseClient->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('OK', $response->getContent());
    }
}
