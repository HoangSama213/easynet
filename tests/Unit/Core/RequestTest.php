<?php

declare(strict_types=1);

namespace Tests\Unit\Core;

use App\Core\App;
use App\Core\Request;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 3) . '/app/Core/App.php';
require_once dirname(__DIR__, 3) . '/app/Helpers/helpers.php';
require_once dirname(__DIR__, 3) . '/app/Core/Request.php';

class RequestTest extends TestCase
{
    protected function setUp(): void
    {
        App::setConfig([
            'app' => [
                'base_url' => 'http://localhost/easynet',
            ],
        ]);
    }

    public function testUriStripsConfiguredBasePath(): void
    {
        $_SERVER['REQUEST_URI'] = '/easynet/login?foo=bar';

        $request = new Request();

        $this->assertSame('/login', $request->uri());
    }

    public function testUriKeepsRootPathWhenApplicationRunsAtDomainRoot(): void
    {
        App::setConfig([
            'app' => [
                'base_url' => 'http://10.10.50.153:8000',
            ],
        ]);
        $_SERVER['REQUEST_URI'] = '/supplier-products?page=2';

        $request = new Request();

        $this->assertSame('/supplier-products', $request->uri());
    }
}
