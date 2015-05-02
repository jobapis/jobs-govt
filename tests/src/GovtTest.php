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
        $job_count = rand(2,10);
        $listings = $this->createJobArray($job_count);
        $source = $this->client->getSource();
        $keyword = 'project manager';

        $this->client->setKeyword($keyword)
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

        foreach ($listings as $i => $result) {
            $this->assertEquals($listings[$i]['id'], $results->get($i)->id);
            $this->assertEquals($listings[$i]['position_title'], $results->get($i)->title);
            $this->assertEquals($listings[$i]['url'], $results->get($i)->url);
            $this->assertEquals($listings[$i]['organization_name'], $results->get($i)->company);
            $this->assertEquals($listings[$i]['start_date'], $results->get($i)->startDate);
            $this->assertEquals($listings[$i]['end_date'], $results->get($i)->endDate);
            $this->assertEquals($listings[$i]['minimum'], $results->get($i)->minimumSalary);
            $this->assertEquals($listings[$i]['maximum'], $results->get($i)->maximumSalary);

            // Add assertion related to locations

            $this->assertEquals($keyword, $results->get($i)->query);
            $this->assertEquals($source, $results->get($i)->source);
        }

        $this->assertEquals(count($listings), $results->count());
    }

    private function createJobArray($num = 10) {
        $jobs = [];
        $i = 0;
        while ($i < $num) {
            $jobs[] = [
                'id' => uniqid(),
                'position_title' => uniqid(),
                'organization_name' => uniqid(),
                'locations' => $this->createLocationsArray(),
                'start_date' => uniqid(),
                'end_date' => uniqid(),
                'url' => uniqid(),
                'minimum' => uniqid(),
                'maximum' => uniqid(),
            ];
            $i++;
        }
        return $jobs;
    }

    private function createLocationsArray($num = 2) {
        $locations = [];
        $i = 0;
        while ($i < $num) {
            $locations[] = uniqid();
            $i++;
        }
        return $locations;
    }
}
