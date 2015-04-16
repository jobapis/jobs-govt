<?php namespace JobBrander\Jobs\Client\Providers\Test;

use JobBrander\Jobs\Client\Providers\Govt;
use Mockery as m;

class GovtTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client = new Govt();
    }

    public function testItWillUseJsonFormat()
    {
        $format = $this->client->getFormat();

        $this->assertEquals('json', $format);
    }

    public function testItWillUseGetHttpVerb()
    {
        $verb = $this->client->getVerb();

        $this->assertEquals('GET', $verb);
    }

    public function testListingPath()
    {
        $path = $this->client->getListingsPath();

        $this->assertEmpty($path);
        $this->assertEquals(null, $path);
    }

    public function testItWillProvideEmptyParameters()
    {
        $parameters = $this->client->getParameters();

        $this->assertEmpty($parameters);
        $this->assertTrue(is_array($parameters));
    }

    public function testUrlIncludesKeywordWhenKeywordProvided()
    {
        $keyword = uniqid().' '.uniqid();
        $param = 'query='.urlencode($keyword);

        $url = $this->client->setKeyword($keyword)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesKeywordWhenCityAndStateProvided()
    {
        $city = uniqid();
        $state = uniqid();
        $param = 'query='.urlencode($city.', '.$state);

        $url = $this->client->setCity($city)->setState($state)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesKeywordWhenCityProvided()
    {
        $city = uniqid();
        $param = 'query='.urlencode($city);

        $url = $this->client->setCity($city)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesKeywordWhenStateProvided()
    {
        $state = uniqid();
        $param = 'query='.urlencode($state);

        $url = $this->client->setState($state)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesKeywordWhenNotProvided()
    {
        $param = 'query=';

        $url = $this->client->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesSizeWhenProvided()
    {
        $size = uniqid();
        $param = 'size='.$size;

        $url = $this->client->setCount($size)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesSizeWhenNotProvided()
    {
        $param = 'size=';

        $url = $this->client->setCount(null)->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesFromWhenProvided()
    {
        $page = rand(5, 15);
        $count = rand(10, 100);
        $param = 'from='.(($page - 1) * $count);

        $url = $this->client->setPage($page)->setCount($count)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesFromWhenNotProvided()
    {
        $param = 'from=';

        $url = $this->client->setPage(null)->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testItCanConnect()
    {
        $listings = [
            0 => [
                'position_title' => uniqid(),
                'id' => uniqid(),
            ],
        ];

        $this->client->setKeyword('project manager')
            ->setCity('Chicago')
            ->setState('IL');

        $response = m::mock('GuzzleHttp\Message\Response');
        $response->shouldReceive($this->client->getFormat())->once()->andReturn($listings);

        $http = m::mock('GuzzleHttp\Client');
        $http->shouldReceive(strtolower($this->client->getVerb()))
            ->with($this->client->getUrl(), $this->client->getHttpClientOptions())
            ->once()
            ->andReturn($response);
        $this->client->setClient($http);

        $results = $this->client->getJobs();
    }
}
