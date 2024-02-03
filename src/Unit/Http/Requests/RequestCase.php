<?php
/**
 * Playground
 */
namespace Playground\Test\Unit\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Playground\Test\OrchestraTestCase;

/**
 * \Playground\Test\Unit\Http\Requests\RequestCase
 *
 * NOTE Set the request: protected string $requestClass = FormRequest::class;
 */
abstract class RequestCase extends OrchestraTestCase
{
    /**
     * @var class-string<FormRequest>
     */
    protected string $requestClass = FormRequest::class;

    protected function getRequest(): FormRequest
    {
        $requestClass = $this->getRequestClass();

        return new $requestClass();
    }

    /**
     * Get the FQDN of the request class.
     *
     * @return class-string<FormRequest>
     */
    protected function getRequestClass(): string
    {
        return $this->requestClass;
    }

    public function test_request_instance(): void
    {
        $instance = $this->getRequest();

        $requestClass = $this->getRequestClass();

        $this->assertInstanceOf($requestClass, $instance);
    }
}
