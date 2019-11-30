<?php

/**
* Pagination
**/
class Paginate {

  /**
   * @var Integer
   */
  public $neighbour;

  /**
   * @var Integer
   */
  public $jump;

  /**
   * @var String
   */
  public $url_prefix;

  /**
   * @var String
   */
  public $url_suffix;

  /**
   * @var Array Default configuration
   */
  protected $_config = array(
    'url_prefix' => NULL,
    'url_suffix' => NULL,
    'neighbour' => 3,
    'jump' => 10,
  );

  /**
	* @var Array	List of links
	**/
	protected $paginate;

  // Paginate::config()->create();
  // (new Paginate())->create()

  /**
   * Configuration
   *
   * Act has singleton
   *
   * ~~~
   * $paginate = new Paginate();
   * $pagination = $paginate->create();
   * ~~~
   *
   * OR
   *
   * ~~~
   * $pagination = (new Paginate())->create();
   * ~~~
   *
   * @return Paginate
   */

  public function __construct($config = array())
  {
    $config = array_merge($this->_config, $config);
    $this->url_prefix = $config['url_prefix'];
    $this->url_suffix = $config['url_suffix'];
    $this->neighbour = $config['neighbour'];
    $this->jump = $config['jump'];
  }

	public function create($folio, $limit, $total_count)
	{

		$paginate = array();

		$paginate = array(
			'offset'		=> $limit * ($folio - 1),
			'limit'			=> $limit,
			'total_count'	=> $total_count,
			'folio'			=> (int) $folio,
      'total_page'	=> ceil($total_count / $limit),
      'pagination'  => array('links' => array()),
		);

		// Adding links
		$paginate['pagination']['first'] = array('link' => array(
				'href'			=> $this->url_prefix . 1 . $this->url_suffix,
				'current'		=> $folio == 1,
				// 'name'			=> __('First page'),
				'name'			=> '1',
				'title'			=> __('Got to first page') ,
      ));

    $paginate['pagination']['last'] = array('link' => array(
      'href'			=> $this->url_prefix . $paginate['total_page'] . $this->url_suffix,
      'current'		=> $folio == $paginate['total_page'],
      // 'name'			=> __('Last page'),
      'name'			=> $paginate['total_page'],
      'title'			=> __('Go to last page'),
    ));

		// Previous link
		// if ($folio > 1 )
		// {
			$paginate['pagination']['previous'] = array('link' => array(
				'href'		=> $this->url_prefix . max($folio - 1, 1) . $this->url_suffix,
				'name'		=> __('Previous'),
        'title'		=> __('Go to previous page'),
        'disabled' => $folio > 1
			));
		// }

		// Next link
		// if ($folio < $paginate['total_page'])
		// {
			$paginate['pagination']['next'] = array('link' => array(
				'href'		=> $this->url_prefix . min($folio + 1, $paginate['total_page']) . $this->url_suffix,
				'name'		=> __('Next'),
        'title'		=> __('Got to next page'),
        'disabled' => $folio < $paginate['total_page'],
			));
		// }

		// Links
    $i = 1;
		$link = array();

		while ($i++ < $paginate['total_page'] - 1)
		{
			$link = array('link' => array(
				'href'			=> $this->url_prefix . $i . $this->url_suffix,
				'name'			=> $i,
				'title'			=> __('Got to page') . ' ' . $i,
				'current'		=> $i == $folio,
			));

			// Before current link
			if ($i < $folio AND $i >  $folio - $this->neighbour)
				$paginate['pagination']['links']['before'][] = $link;

			// After current link
			if ($i > $folio AND $i - $this->neighbour <= $folio)
				$paginate['pagination']['links']['after'][] = $link;

			// Current link
			if ($i == $folio)
				$paginate['pagination']['links']['current'] = $link;
		}

		// Jump previous
		if ($folio > $this->neighbour + $this->jump)
		{
      $paginate['pagination']['links']['backward'] = array('link' =>  array(
        'href'			=> $this->url_prefix . ($folio - $this->jump) . $this->url_suffix,
        'name'			=> '< -' . $this->jump,
        'title'			=> __('Got to page') . ' ' . ($folio - $this->jump),
      ));
		}

		// Jump next
		if ($folio + $this->neighbour + $this->jump <= $paginate['total_page'])
		{
      $paginate['pagination']['links']['forward'] = array('link' => array(
        'href'		=> $this->url_prefix . ($folio + + $this->jump) . $this->url_suffix,
        'name'		=> '+' . $this->jump . ' >',
        'title'		=> __('Go to page') . ' ' . ($folio  + $this->jump),
      ));
		}

		$paginate['show'] = $paginate['total_page'] > 1;

		return $paginate;

	}

}
