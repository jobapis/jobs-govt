<?php namespace JobApis\Jobs\Client\Queries;

class GovtQuery extends AbstractQuery
{
    /**
     * Search query
     *
     * @var string
     */
    protected $query;

    /**
     * Organizations to search (comma-separated list of IDs)
     *
     * @var string
     */
    protected $organization_ids;

    /**
     * Highlight
     *
     * @var boolean
     */
    protected $hl;

    /**
     * Result set size
     *
     * @var integer
     */
    protected $size;

    /**
     * Starting record
     *
     * @var integer
     */
    protected $from;

    /**
     * Comma-separated list of agency tags.
     *
     * Available tags: federal, state, county, city
     *
     * @var string
     */
    protected $tags;

    /**
     * Comma-separated latitude and longitude
     *
     * @var string
     */
    protected $lat_lon;

    /**
     * Get baseUrl
     *
     * @return  string Value of the base url to this api
     */
    public function getBaseUrl()
    {
        return 'https://jobs.search.gov/jobs/search.json';
    }

    /**
     * Get keyword
     *
     * @return  string Attribute being used as the search keyword
     */
    public function getKeyword()
    {
        return $this->query;
    }
}
