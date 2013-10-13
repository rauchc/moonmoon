<?php

/**
 * Planet person
 */

class PlanetFeed extends SimplePie
{
    public $name;
    public $feed;
    public $website;
	public $category;

    public function __construct($name, $feed, $website, $category)
    {
        $this->name    	= $name;
        $this->feed    	= $feed;
        $this->website 	= $website;
		$this->category = $category;
        parent::__construct();
        $this->set_item_class('PlanetItem');
        $this->set_cache_location(dirname(__FILE__).'/../../cache');
        $this->set_autodiscovery_level(SIMPLEPIE_LOCATOR_NONE);
        $this->set_feed_url($this->getFeed());
        $this->set_timeout(5);
        $this->set_stupidly_fast(true);
    }

    public function getFeed()
    {
        return $this->feed;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getWebsite()
    {
        return $this->website;
    }

	public function getCategory()
	{
		return $this->category;
	}
    public function compare($person1, $person2)
    {
        return strcasecmp($person1->name, $person2->name);
    }
}
