<?php
/**
 * GammaMatrix
 *
 */

namespace GammaMatrix\Playground\Test\Unit\Http\Requests;

use GammaMatrix\Playground\Test\OrchestraTestCase;
use Illuminate\Foundation\Http\FormRequest;

/**
 * \GammaMatrix\Playground\Test\Unit\Http\Requests\RequestCase
 *
 * NOTE Set the request: protected string $requestClass = FormRequest::class;
 */
abstract class RequestCase extends OrchestraTestCase
{
    protected string $requestClass = FormRequest::class;

    protected function getRequest(): FormRequest
    {
        $requestClass = $this->getRequestClass();

        return new $requestClass();
    }

    /**
     * Get the FQDN of the request class.
     */
    protected function getRequestClass(): string
    {
        return $this->requestClass;
    }

    ############################################################################
    #
    # Verify: instance
    #
    ############################################################################

    public function test_request_instance(): void
    {
        $instance = $this->getRequest();

        $requestClass = $this->getRequestClass();

        $this->assertInstanceOf($requestClass, $instance);
    }
}
