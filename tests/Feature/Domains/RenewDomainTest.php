<?php

namespace Pleets\Tests\Feature\Domains;

use EasyHttp\MockBuilder\HttpMock;
use Pleets\NameCom\Domains\Requests\PurchaseRequest;
use Pleets\NameCom\NameComApi;
use Pleets\Tests\TestCaseWithMockAuthentication;

class RenewDomainTest extends TestCaseWithMockAuthentication
{
    /**
     * @test
     */
    public function itCanRenewADomainWithMinimumData()
    {
        $domainName = $this->faker->domainName;
        $jsonResponse = $this->responseWithDomain($domainName);

        $this->builder
            ->when()
                ->methodIs('POST')
                ->pathMatch('/v4\/domains\/' . self::DOMAIN_REGEX . ':renew/')
            ->then()
                ->statusCode(200)
                ->json($jsonResponse);

        $service = new NameComApi($this->baseUri);
        $service->setCredentials($this->username, $this->password);
        $service->withHandler(new HttpMock($this->builder));

        $response = $service->renewDomain(new PurchaseRequest($domainName));

        $this->assertTrue($response->isSuccessful());
        $this->assertSame(200, $response->getResponse()->getStatusCode());
        $this->assertSame($jsonResponse, $response->toArray());
        $this->assertSame($domainName, $jsonResponse['domain']['domainName']);
    }

    /**
     * @test
     */
    public function itCanRenewADomainWithAllRequestData()
    {
        $domainName = $this->faker->domainName;
        $jsonResponse = $this->responseWithDomain($domainName);

        $this->builder
            ->when()
                ->methodIs('POST')
                ->pathMatch('/v4\/domains\/' . self::DOMAIN_REGEX . ':renew/')
            ->then()
                ->statusCode(200)
                ->json($jsonResponse);

        $service = new NameComApi($this->baseUri);
        $service->setCredentials($this->username, $this->password);
        $service->withHandler(new HttpMock($this->builder));

        $request = new PurchaseRequest($domainName);
        $request->setPurchasePrice('14.99');
        $request->setPurchaseYears(2);
        $response = $service->renewDomain($request);

        $this->assertTrue($response->isSuccessful());
        $this->assertSame(200, $response->getResponse()->getStatusCode());
        $this->assertSame($jsonResponse, $response->toArray());
        $this->assertSame($domainName, $jsonResponse['domain']['domainName']);
    }
}
