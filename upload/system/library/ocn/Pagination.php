<?php


namespace OCN;


class Pagination
{
	private $total = 0;
	private $per_page = 3;
	private $visible_pages = 2;
	private $current_page = 1;
	private $last_page = 1;
	private $pages = [];
	private $first_page_url = '';
	private $last_page_url = '';
	private $next_page_url = '';
	private $prev_page_url = '';
	private $path= '';
	private $from = 1;
	private $to = 10;
	
	public function __construct(array $options = [])
	{
		foreach ($options as $key => $value) {
			$this->{$key} = $value;
		}
	}
	
	public function get()
	{
		return [
			'total' => $this->total,
			'pages' => $this->pages,
			'per_page' => $this->per_page,
			'current_page' => $this->current_page,
			'last_page' => $this->last_page,
			'first_page_url' => $this->first_page_url,
			'last_page_url' => $this->last_page_url,
			'next_page_url' => $this->next_page_url,
			'prev_page_url' => $this->prev_page_url,
			'from' => $this->from,
			'to' => $this->to
		];
	}
	
	public function prepare($total, $path, $current_page = 1)
	{
		$this->total = $total;
		$this->path = $path;
		$this->current_page = $current_page < 1 ? 1 : $current_page;
		$this->last_page = ceil($this->total / $this->per_page);
		
		$next_page = $this->current_page + 1;
		$prev_page = $this->current_page - 1;
		
		$this->first_page_url = $this->current_page == 1 ? '' : ($this->path . '&page=1');
		$this->last_page_url = $this->current_page == $this->last_page ? '' : ($this->path . '&page=' . $this->last_page);
		$this->next_page_url = $next_page > $this->last_page ? '' : ($this->path . '&page=' . $next_page);
		$this->prev_page_url = $prev_page < 1 ? '' : ($this->path . '&page=' . $prev_page);
		
		$min = $this->current_page - $this->visible_pages;
		$max = $this->current_page + $this->visible_pages;

		for ($i = 1; $i <= $this->last_page; $i++) {
			if (($i != 1 && $i < $min) || ($i > $max && $i != $this->last_page)) {
				continue;
			}
			$status = (($i == $min && $i != 1) || ($i == $max && $i != $this->last_page));
			$this->pages[$i] = [
				'url' => $status ? '#' : $this->path . '&page=' . $i,
				'active' => $this->current_page == $i,
				'disabled' => $status,
				'page' => $status ? '...' : $i
			];
		}
		
		$this->from = ($this->current_page - 1) * $this->per_page;
		$this->to = $this->from + $this->per_page;
	}
}
