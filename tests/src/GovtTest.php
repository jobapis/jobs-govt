<?php namespace JobBrander\Jobs\Client\Providers\Test;

use JobBrander\Jobs\Client\Providers\Govt;
use Mockery as m;

class GovtTest extends \PHPUnit_Framework_TestCase
{
    private $clientClass = 'JobBrander\Jobs\Client\Providers\AbstractProvider';
    private $collectionClass = 'JobBrander\Jobs\Client\Collection';
    private $jobClass = 'JobBrander\Jobs\Client\Job';

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

    public function testItCreatesMultipleJobsWhenMultipleLocationsReturned()
    {
        $loc_count = rand(2,5);
        $jobArray = $this->createJobArray($loc_count);

        $array = $this->client->createJobArray($jobArray);

        foreach ($array as $key => $job) {
            $this->assertEquals($jobArray['position_title'], $array[0]['position_title']);
            $this->assertEquals($jobArray['locations'][$key], $array[$key]['location']);
        }
        $this->assertEquals($loc_count, count($array));
    }

    public function testItCreatesOneJobWhenOneLocationsReturned()
    {
        $loc_count = 1;
        $jobArray = $this->createJobArray($loc_count);

        $array = $this->client->createJobArray($jobArray);

        foreach ($array as $key => $job) {
            $this->assertEquals($jobArray['position_title'], $array[0]['position_title']);
            $this->assertEquals($jobArray['locations'][$key], $array[$key]['location']);
        }
        $this->assertEquals($loc_count, count($array));
    }

    public function testItCanCreateJobFromPayload()
    {
        $payload = $this->createJobArray(2);
        $results = $this->client->createJobObject($payload);

        $this->assertEquals($payload['id'], $results->sourceId);
        $this->assertEquals($payload['position_title'], $results->title);
        $this->assertEquals($payload['organization_name'], $results->company);
        $this->assertEquals($payload['url'], $results->url);
    }

    public function testItCanConnect()
    {
        $provider = $this->getProviderAttributes();

        for ($i = 0; $i < $provider['jobs_count']; $i++) {
            $payload[] = $this->createJobArray();
        }

        $responseBody = json_encode($payload);

        $job = m::mock($this->jobClass);
        $job->shouldReceive('setQuery')->with($provider['keyword'])
            ->times($provider['jobs_count'])->andReturnSelf();
        $job->shouldReceive('setSource')->with($provider['source'])
            ->times($provider['jobs_count'])->andReturnSelf();

        $response = m::mock('GuzzleHttp\Message\Response');
        $response->shouldReceive('getBody')->once()->andReturn($responseBody);

        $http = m::mock('GuzzleHttp\Client');
        $http->shouldReceive(strtolower($this->client->getVerb()))
            ->with($this->client->getUrl(), $this->client->getHttpClientOptions())
            ->once()
            ->andReturn($response);
        $this->client->setClient($http);

        $results = $this->client->getJobs();

        $this->assertInstanceOf($this->collectionClass, $results);
        $this->assertCount($provider['jobs_count'], $results);
    }

    private function createJobArray($loc_count = 1) {
        return [
            'id' => uniqid(),
            'position_title' => uniqid(),
            'organization_name' => uniqid(),
            'locations' => $this->createLocationsArray($loc_count),
            'start_date' => '2015-07-'.rand(1,31),
            'end_date' => '2016-07-'.rand(1,31),
            'url' => uniqid(),
            'minimum' => uniqid(),
            'maximum' => uniqid(),
        ];
    }

    private function createLocationsArray($loc_count = 3) {
        $locations = [];
        $i = 0;
        while ($i < $loc_count) {
            $locations[] = uniqid().', '.uniqid();
            $i++;
        }
        return $locations;
    }

    private function getProviderAttributes($attributes = [])
    {
        $defaults = [
            'path' => uniqid(),
            'format' => 'json',
            'keyword' => uniqid(),
            'source' => uniqid(),
            'params' => [uniqid()],
            'jobs_count' => rand(2,10),

        ];
        return array_replace($defaults, $attributes);
    }
}
