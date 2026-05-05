<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Core\App;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 3) . '/app/Core/App.php';
require_once dirname(__DIR__, 3) . '/app/Helpers/helpers.php';

class BaseUrlTest extends TestCase
{
    protected function setUp(): void
    {
        $_SERVER['HTTPS'] = 'off';
        $_SERVER['HTTP_HOST'] = '10.10.50.153:8000';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        App::setConfig([
            'app' => [
                'base_url' => 'http://localhost/easynet',
            ],
        ]);
    }

    public function testBaseUrlUsesDetectedHostInLocalEnvironment(): void
    {
        putenv('APP_ENV=local');

        $this->assertSame('http://10.10.50.153:8000', base_url());
        $this->assertSame('http://10.10.50.153:8000/login', base_url('login'));
    }

    public function testBaseUrlUsesConfiguredBaseUrlOutsideLocalEnvironment(): void
    {
        putenv('APP_ENV=testing');

        $this->assertSame('http://localhost/easynet', base_url());
        $this->assertSame('http://localhost/easynet/supplier-products', base_url('/supplier-products'));
    }
}
