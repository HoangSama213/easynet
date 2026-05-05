<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Core\App;
use PHPUnit\Framework\TestCase;

require_once dirname(__DIR__, 3) . '/app/Core/App.php';
require_once dirname(__DIR__, 3) . '/app/Helpers/helpers.php';
require_once dirname(__DIR__, 3) . '/middleware/auth.php';

class ProjectUrlTest extends TestCase
{
    protected function setUp(): void
    {
        App::setConfig([
            'app' => [
                'base_url' => 'http://localhost/easynet',
            ],
        ]);
        $_SERVER['HTTP_HOST'] = '10.10.50.153:8000';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
    }

    public function testProjectUrlUsesBaseUrlWhenHelperIsAvailable(): void
    {
        putenv('APP_ENV=local');

        $this->assertSame('http://10.10.50.153:8000/login', project_url('login'));
        $this->assertSame('http://10.10.50.153:8000', project_url());
    }
}
