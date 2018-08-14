<?php namespace JobApis\Jobs\Client\Test;

use JobApis\Jobs\Client\Queries\GovtQuery;
use Mockery as m;

class GovtQueryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->query = new GovtQuery();
    }

    public function testItCanGetBaseUrl()
    {
        $this->assertEquals(
            'https://jobs.search.gov/jobs/search.json',
            $this->query->getBaseUrl()
        );
    }

    public function testItCanGetKeyword()
    {
        $keyword = uniqid();
        $this->query->set('query', $keyword);
        $this->assertEquals($keyword, $this->query->getKeyword());
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItThrowsExceptionWhenSettingInvalidAttribute()
    {
        $this->query->set(uniqid(), uniqid());
    }

    /**
     * @expectedException OutOfRangeException
     */
    public function testItThrowsExceptionWhenGettingInvalidAttribute()
    {
        $this->query->get(uniqid());
    }

    public function testItSetsAndGetsValidAttributes()
    {
        $attributes = [
            'query' => uniqid(),
            'hl' => uniqid(),
            'size' => uniqid(),
            'from' => uniqid(),
        ];

        foreach ($attributes as $key => $value) {
            $this->query->set($key, $value);
        }

        foreach ($attributes as $key => $value) {
            $this->assertEquals($value, $this->query->get($key));
        }

        $url = $this->query->getUrl();

        $this->assertContains('query=', $url);
        $this->assertContains('size=', $url);
        $this->assertContains('from=', $url);
        $this->assertContains('hl=', $url);
    }
}
