<?php namespace JobApis\Jobs\Client\Providers;

use JobApis\Jobs\Client\Job;
use JobApis\Jobs\Client\Collection;

class Govt extends AbstractProvider
{
    protected $baseUrl = 'http://api.usa.gov/jobs/search.json';

    /**
     * Takes a job valid for multiple locations and turns it into multiple jobs
     *
     * @param array $item
     *
     * @return array
     */
    public function createJobArray($item)
    {
        $jobs = [];
        if (isset($item['locations']) && count($item['locations']) > 1) {
            foreach ($item['locations'] as $location) {
                $item['location'] = $location;
                $jobs[] = $item;
            }
        } else {
            $item['location'] = $item['locations'][0];
            $jobs[] = $item;
        }
        return $jobs;
    }

    /**
     * Returns the standardized job object
     *
     * @param array $payload
     *
     * @return \JobApis\Jobs\Client\Job
     */
    public function createJobObject($payload)
    {
        $job = new Job([
            'sourceId' => $payload['id'],
            'title' => $payload['position_title'],
            'name' => $payload['position_title'],
            'url' => $payload['url'],
            'location' => $payload['location'],
            'maximumSalary' => $payload['maximum'],
            'startDate' => $payload['start_date'],
            'endDate' => $payload['end_date'],
        ]);

        $location = static::parseLocation($payload['location']);

        $job->setCompany($payload['organization_name'])
            ->setDatePostedAsString($payload['start_date'])
            ->setMinimumSalary($payload['minimum']);

        if (isset($location[0])) {
            $job->setCity($location[0]);
        }
        if (isset($location[1])) {
            $job->setState($location[1]);
        }

        return $job;
    }

    /**
     * Get default parameters and values
     *
     * @return  string
     */
    public function defaultParameters()
    {
        return [
            'from' => null,
            'hl' => null,
            'lat_lon' => null,
            'organization_ids' => null,
            'query' => null,
            'size' => null,
            'tags' => null,
        ];
    }

    /**
     * Job object default keys that must be set.
     *
     * @return  string
     */
    public function defaultResponseFields()
    {
        return [
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
    }

    /**
     * Get count
     *
     * @return string
     */
    public function getCount()
    {
        return $this->size;
    }

    /**
     * Create and get collection of jobs from given listings
     *
     * @param  array $listings
     *
     * @return Collection
     */
    protected function getJobsCollectionFromListings(array $listings = array())
    {
        $collection = new Collection;
        array_map(function ($item) use ($collection) {
            $jobs = $this->createJobArray($item);
            foreach ($jobs as $item) {
                $item = static::parseAttributeDefaults($item, $this->defaultResponseFields());
                $job = $this->createJobObject($item);
                $job->setQuery($this->getKeyword())
                    ->setSource($this->getSource());
                $collection->add($job);
            }
        }, $listings);
        return $collection;
    }

    /**
     * Get keyword(s)
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->query;
    }

    /**
     * Get lat_lon
     *
     * @return string
     */
    public function getLatLon()
    {
        return $this->lat_lon;
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return null;
    }

    /**
     * Get organization_ids
     *
     * @return string
     */
    public function getOrganizationIds()
    {
        return $this->organization_ids;
    }

    /**
     * Get parameters that MUST be set in order to satisfy the APIs requirements
     *
     * @return  string
     */
    public function requiredParameters()
    {
        return [];
    }

    /**
     * Set count (aka size)
     *
     * @return string
     */
    public function setCount($value)
    {
        $this->size = $value;
        return $this;
    }

    /**
     * Set keyword
     *
     * @return string
     */
    public function setKeyword($value)
    {
        $this->query = $value;
        return $this;
    }

    /**
     * Set lat_lon
     *
     * @return string
     */
    public function setLatLon($value)
    {
        $this->lat_lon = $value;
        return $this;
    }

    /**
     * Set organization_ids
     *
     * @return string
     */
    public function setOrganizationIds($value)
    {
        $this->organization_ids = $value;
        return $this;
    }

    /**
     * Get parameters that CAN be set
     *
     * @return  string
     */
    public function validParameters()
    {
        return array_keys($this->defaultParameters());
    }
}
