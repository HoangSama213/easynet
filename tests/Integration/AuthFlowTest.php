<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

class AuthFlowTest extends TestCase
{
    private static $serverProcess = null;
    private static string $baseUrl;

    public static function setUpBeforeClass(): void
    {
        $port = 8123;
        self::$baseUrl = 'http://127.0.0.1:' . $port;
        $router = dirname(__DIR__) . '/Fixtures/web/router.php';
        $command = escapeshellarg(PHP_BINARY) . ' -S 127.0.0.1:' . $port . ' ' . escapeshellarg($router);

        self::$serverProcess = proc_open(
            $command,
            [
                0 => ['pipe', 'r'],
                1 => ['file', PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null', 'a'],
                2 => ['file', PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null', 'a'],
            ],
            $pipes,
            dirname(__DIR__, 2)
        );

        if (!is_resource(self::$serverProcess)) {
            self::fail('Không thể khởi động PHP built-in server cho integration test.');
        }

        usleep(700000);
    }

    public static function tearDownAfterClass(): void
    {
        if (is_resource(self::$serverProcess)) {
            proc_terminate(self::$serverProcess);
            proc_close(self::$serverProcess);
        }
    }

    public function testRedirectHelperReturnsLoginLocationHeader(): void
    {
        $response = $this->request('GET', '/redirect-test');

        $this->assertSame(302, $response['status']);
        $this->assertSame(self::$baseUrl . '/login', $response['headers']['Location'] ?? null);
    }

    public function testGuestIsRedirectedToLoginWhenAccessingProtectedRoute(): void
    {
        $response = $this->request('GET', '/protected');

        $this->assertSame(302, $response['status']);
        $this->assertSame(self::$baseUrl . '/login', $response['headers']['Location'] ?? null);
    }

    public function testGetLoginRendersStoredLoginErrorMessage(): void
    {
        $seed = $this->request('GET', '/seed-login-error');
        $response = $this->request('GET', '/login', [], $seed['cookies']);

        $this->assertSame(200, $response['status']);
        $this->assertStringContainsString('Email hoặc mật khẩu không đúng', $response['body']);
        $this->assertStringContainsString('demo@easynet.vn', $response['body']);
    }

    public function testPostLoginRedirectsAuthenticatedUserToDashboard(): void
    {
        $loginPage = $this->request('GET', '/login');
        preg_match('/name="_token" value="([^"]+)"/', $loginPage['body'], $matches);
        $csrfToken = $matches[1] ?? '';

        $this->assertNotSame('', $csrfToken);

        $response = $this->request('POST', '/login', [
            '_token' => $csrfToken,
            'email' => 'admin@easynet.vn',
            'password' => 'Admin@123',
        ], $loginPage['cookies']);

        $this->assertSame(302, $response['status']);
        $this->assertSame(self::$baseUrl, $response['headers']['Location'] ?? null);
    }

    public function testPostLoginWithWrongPasswordRedirectsBackWithErrorMessage(): void
    {
        $loginPage = $this->request('GET', '/login');
        preg_match('/name="_token" value="([^"]+)"/', $loginPage['body'], $matches);
        $csrfToken = $matches[1] ?? '';

        $this->assertNotSame('', $csrfToken);

        $response = $this->request('POST', '/login', [
            '_token' => $csrfToken,
            'email' => 'admin@easynet.vn',
            'password' => 'SaiMatKhau',
        ], $loginPage['cookies']);

        $this->assertSame(302, $response['status']);
        $this->assertSame(self::$baseUrl . '/login', $response['headers']['Location'] ?? null);

        $loginAgain = $this->request('GET', '/login', [], $response['cookies']);

        $this->assertSame(200, $loginAgain['status']);
        $this->assertStringContainsString('Email hoặc mật khẩu không đúng', $loginAgain['body']);
        $this->assertStringContainsString('admin@easynet.vn', $loginAgain['body']);
    }

    public function testPostLoginWithLockedUserRedirectsBackWithLockedMessage(): void
    {
        $loginPage = $this->request('GET', '/login');
        preg_match('/name="_token" value="([^"]+)"/', $loginPage['body'], $matches);
        $csrfToken = $matches[1] ?? '';

        $this->assertNotSame('', $csrfToken);

        $response = $this->request('POST', '/login', [
            '_token' => $csrfToken,
            'email' => 'locked@easynet.vn',
            'password' => 'Locked@123',
        ], $loginPage['cookies']);

        $this->assertSame(302, $response['status']);
        $this->assertSame(self::$baseUrl . '/login', $response['headers']['Location'] ?? null);

        $loginAgain = $this->request('GET', '/login', [], $response['cookies']);

        $this->assertSame(200, $loginAgain['status']);
        $this->assertStringContainsString('Tài khoản đã bị khóa', $loginAgain['body']);
        $this->assertStringContainsString('locked@easynet.vn', $loginAgain['body']);
    }

    public function testLogoutClearsSessionAndProtectedRouteRedirectsAgain(): void
    {
        $seed = $this->request('GET', '/seed-auth');
        $logout = $this->request('GET', '/logout', [], $seed['cookies']);

        $this->assertSame(302, $logout['status']);
        $this->assertSame(self::$baseUrl . '/login', $logout['headers']['Location'] ?? null);

        $cookiesAfterLogout = $this->mergeCookies($seed['cookies'], $logout['cookies']);
        $protected = $this->request('GET', '/protected', [], $cookiesAfterLogout);

        $this->assertSame(302, $protected['status']);
        $this->assertSame(self::$baseUrl . '/login', $protected['headers']['Location'] ?? null);
    }

    private function request(string $method, string $path, array $data = [], array $cookies = []): array
    {
        $headers = [
            'ignore_errors' => true,
            'follow_location' => 0,
            'method' => $method,
            'header' => '',
        ];

        if ($cookies !== []) {
            $headers['header'] .= 'Cookie: ' . implode('; ', $cookies) . "\r\n";
        }

        if ($method === 'POST') {
            $headers['header'] .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $headers['content'] = http_build_query($data);
        }

        $context = stream_context_create(['http' => $headers]);
        $body = file_get_contents(self::$baseUrl . $path, false, $context);
        $responseHeaders = $http_response_header ?? [];

        $statusLine = $responseHeaders[0] ?? 'HTTP/1.1 500 Internal Server Error';
        preg_match('#HTTP/\d+\.\d+\s+(\d{3})#', $statusLine, $matches);
        $status = isset($matches[1]) ? (int) $matches[1] : 500;

        $headerMap = [];
        $cookieBag = [];
        foreach ($responseHeaders as $headerLine) {
            if (!str_contains($headerLine, ':')) {
                continue;
            }

            [$name, $value] = explode(':', $headerLine, 2);
            $name = trim($name);
            $value = trim($value);

            if (strcasecmp($name, 'Set-Cookie') === 0) {
                $cookieBag[] = trim(explode(';', $value, 2)[0]);
                continue;
            }

            $headerMap[$name] = $value;
        }

        return [
            'status' => $status,
            'headers' => $headerMap,
            'cookies' => $cookieBag,
            'body' => $body === false ? '' : $body,
        ];
    }

    private function mergeCookies(array ...$cookieSets): array
    {
        $map = [];

        foreach ($cookieSets as $cookieSet) {
            foreach ($cookieSet as $cookie) {
                [$name, $value] = array_pad(explode('=', $cookie, 2), 2, '');
                $map[$name] = $value;
            }
        }

        $merged = [];
        foreach ($map as $name => $value) {
            $merged[] = $name . '=' . $value;
        }

        return $merged;
    }
}
