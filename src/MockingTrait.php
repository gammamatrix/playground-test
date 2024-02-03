<?php
/**
 * Playground
 */
namespace Playground\Test;

use ReflectionClass;
use ReflectionProperty;

/**
 * \Playground\Test\MockingTrait
 */
trait MockingTrait
{
    /**
     * Creates a Request based on a given URI and configuration.
     *
     * The information contained in the URI always take precedence
     * over the other information (server and parameters).
     *
     * @param  string  $uri  The URI
     * @param  string  $method  The HTTP method
     * @param  array  $parameters  The query (GET) or request (POST) parameters
     * @param  array  $cookies  The request cookies ($_COOKIE)
     * @param  array  $files  The request files ($_FILES)
     * @param  array  $server  The server parameters ($_SERVER)
     * @param  string|resource|null  $content  The raw body data
     * @return static
     *
     * @see vendor/illuminate/http/Request.php
     * @see vendor/laravel/lumen-framework/src/Http/Request.php
     * @see vendor/symfony/http-foundation/Request.php
     */
    public static function mockRequest(
        string $uri = '',
        string $method = 'GET',
        array $parameters = [],
        array $cookies = [],
        array $files = [],
        array $server = [],
        $content = null
    ) {
        return \Illuminate\Http\Request::createFromBase(
            \Symfony\Component\HttpFoundation\Request::create(
                $uri,
                $method,
                $parameters,
                $cookies,
                $files,
                $server,
                $content
            )
        );
    }

    /**
     * Invoke a protected method for testing.
     *
     * @param object $out    The Object Under Test.
     * @param string $method The protected method.
     * @param array  $params The protected method parameters.
     */
    public function invokeProtected(
        &$out,
        $method,
        array $params = []
    ): mixed {
        $rc = new ReflectionClass(get_class($out));
        $gm = $rc->getMethod($method);

        return $gm->invokeArgs($out, $params);
    }

    /**
     * Makes any properties (private/protected etc) accessible on a given object via reflection.
     *
     * @param object $out      The Object Under Test.
     * @param string $property The protected property.
     * @param mixed  $value    The protected property value.
     *
     * @throws \ReflectionException
     */
    public function setProtected(&$out, string $property, mixed $value): void
    {
        $rp = new ReflectionProperty(get_class($out), $property);
        $rp->setValue($out, $value);
    }
}
