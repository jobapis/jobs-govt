<?php namespace JobApis\Jobs\Client\Providers\Test;

use JobApis\Jobs\Client\Collection;
use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Providers\GovtProvider;
use JobApis\Jobs\Client\Queries\GovtQuery;
use Mockery as m;

class GovtProviderTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = m::mock('JobApis\Jobs\Client\Queries\GovtQuery');

        $this->client = new GovtProvider($this->query);
    }

    public function testItCanGetDefaultResponseFields()
    {
        $fields = [
            'id',
            'position_title',
            'organization_name',
            'location',
            'start_date',
            'end_date',
            'url',
            'minimum',
            'maximum'
        ];
        $this->assertEquals($fields, $this->client->getDefaultResponseFields());
    }

    public function testItCanGetListingsPath()
    {
        $this->assertEmpty($this->client->getListingsPath());
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

    public function testItCanCreateJobObjectFromPayload()
    {
        $payload = $this->createJobArray();
        $payload['location'] = $payload['locations'][0];

        $results = $this->client->createJobObject($payload);

        $this->assertInstanceOf(Job::class, $results);
        $this->assertEquals($payload['id'], $results->sourceId);
        $this->assertEquals($payload['position_title'], $results->title);
        $this->assertEquals($payload['organization_name'], $results->company);
        $this->assertEquals($payload['url'], $results->url);
    }

    /**
     * Integration test for the client's getJobs() method.
     */
    public function testItCanGetJobs()
    {
        $url = 'https://api.usa.gov/jobs/search.json';

        $options = [
            'query' => uniqid(),
            'hl' => uniqid(),
            'size' => uniqid(),
        ];

        $guzzle = m::mock('GuzzleHttp\Client');

        $query = new GovtQuery($options);

        $client = new GovtProvider($query);

        $client->setClient($guzzle);

        $response = m::mock('GuzzleHttp\Message\Response');

        $jobObjects = [
            (object) $this->createJobArray(),
            (object) $this->createJobArray(),
            (object) $this->createJobArray(),
        ];

        $jobs = json_encode($jobObjects);

        $guzzle->shouldReceive('get')
            ->with($query->getUrl(), [])
            ->once()
            ->andReturn($response);
        $response->shouldReceive('getBody')
            ->once()
            ->andReturn($jobs);

        $results = $client->getJobs();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertCount(count($jobObjects), $results);
    }

    /**
     * Integration test with actual API call to the provider.
     */
    public function testItCanGetJobsFromApi()
    {
        if (!getenv('REAL_CALL')) {
            $this->markTestSkipped('REAL_CALL not set. Real API call will not be made.');
        }

        $keyword = 'engineering';

        $query = new GovtQuery([
            'query' => $keyword,
        ]);

        $client = new GovtProvider($query);

        $results = $client->getJobs();

        $this->assertInstanceOf('JobApis\Jobs\Client\Collection', $results);

        foreach($results as $job) {
            $this->assertEquals($keyword, $job->query);
        }
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
}
