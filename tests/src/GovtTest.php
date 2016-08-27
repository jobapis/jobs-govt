<?php namespace JobApis\Jobs\Client\Providers\Test;

use JobApis\Jobs\Client\Providers\Govt;
use Mockery as m;

class GovtTest extends \PHPUnit_Framework_TestCase
{
    private $clientClass = 'JobApis\Jobs\Client\Providers\AbstractProvider';
    private $collectionClass = 'JobApis\Jobs\Client\Collection';
    private $jobClass = 'JobApis\Jobs\Client\Job';

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
        $from = rand(10, 100);
        $param = 'from='.$from;

        $url = $this->client->setFrom($from)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesFromWhenNotProvided()
    {
        $param = 'from=';

        $url = $this->client->setFrom(null)->getUrl();

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

    public function testItCanCreateJobObjectFromCleanedPayload()
    {
        $payload = $this->createJobArray(1);
        $payload['location'] = $payload['locations'][0];

        $results = $this->client->createJobObject($payload);

        $this->assertEquals($payload['id'], $results->sourceId);
        $this->assertEquals($payload['position_title'], $results->title);
        $this->assertEquals($payload['organization_name'], $results->company);
        $this->assertEquals($payload['url'], $results->url);
    }

    public function testItCanSetAllMethodsInReadme()
    {
        $attributes = [
            'keyword' => uniqid(),
            'organizationIds' => uniqid(),
            'hl' => rand(0,1),
            'count' => rand(1,5),
            'from' => rand(1,5),
            'tags' => uniqid(),
            'latLon' => uniqid(),
        ];
        $client = new Govt;
        // Set all values
        foreach ($attributes as $key => $val) {
            $client->{'set'.ucfirst($key)}($val);
        }
        // Get all values
        foreach ($attributes as $key => $val) {
            $this->assertEquals($val, $client->{'get'.ucfirst($key)}(), "$key was not set or retrieved properly.");
        }
    }

    public function testItCanRetreiveRealResults()
    {
        if (!getenv('REAL_CALL')) {
            $this->markTestSkipped('REAL_CALL not set. Real API call will not be made.');
        }

        $client = new Govt;

        $keyword = 'engineering';
        $client->setKeyword($keyword);
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
