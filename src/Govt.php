<?php namespace JobBrander\Jobs\Client\Providers;

use JobBrander\Jobs\Client\Job;
use JobBrander\Jobs\Client\Collection;

class Govt extends AbstractProvider
{
    /**
     * Returns the standardized job object
     *
     * @param array $payload
     *
     * @return \JobBrander\Jobs\Client\Job
     */
    public function createJobObject($payload)
    {
        $defaults = [
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

        $payload = static::parseAttributeDefaults($payload, $defaults);

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
                $job = $this->createJobObject($item);
                $job->setQuery($this->keyword)
                    ->setSource($this->getSource());
                $collection->add($job);
            }
        }, $listings);
        return $collection;
    }

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
     * Get data format
     *
     * @return string
     */
    public function getFormat()
    {
        return 'json';
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
     * Get keyword(s)
     *
     * @return string
     */
    public function getKeyword()
    {
        $keyword = ($this->keyword ? $this->keyword : null).($this->getLocation() ? ' in '.$this->getLocation() : null);

        if ($keyword) {
            return $keyword;
        }

        return null;
    }

    /**
     * Get combined location
     *
     * @return string
     */
    public function getLocation()
    {
        $location = ($this->city ? $this->city.', ' : null).($this->state ?: null);

        if ($location) {
            return $location;
        }

        return null;
    }

    /**
     * Get page
     *
     * @return  string
     */
    public function getFrom()
    {
        if ($this->page) {
            $from = ($this->page - 1) * $this->count;

            if ($from) {
                return $from;
            }
        }

        return null;
    }

    /**
     * Get query string for client based on properties
     *
     * @return string
     */
    public function getQueryString()
    {
        $query_params = [
            'query' => 'getKeyword',
            'from' => 'getFrom',
            'size' => 'getCount',
        ];

        $query_string = [];

        array_walk($query_params, function ($value, $key) use (&$query_string) {
            $computed_value = $this->$value();
            if (!is_null($computed_value)) {
                $query_string[$key] = $computed_value;
            }
        });

        return http_build_query($query_string);
    }

    /**
     * Get url
     *
     * @return  string
     */
    public function getUrl()
    {
        $query_string = $this->getQueryString();
        return 'http://api.usa.gov/jobs/search.json?'.$query_string;
    }

    /**
     * Get http verb
     *
     * @return  string
     */
    public function getVerb()
    {
        return 'GET';
    }
}
