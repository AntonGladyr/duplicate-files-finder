<?php

namespace DuplicatesSearch\Client;

use DuplicatesSearch\BusinessLogic\Comparator;
use DuplicatesSearch\Client\Processing\Processing;
use DuplicatesSearch\Client\Validator\Validator;
use SplObserver;

class Client implements SplObserver
{
	private $output;

	private $input;

	private $validator;

	private $argv = array();

	private $path;


	function __construct($argv)
	{
		$this->output = new Output();
		$this->validator = new Validator();
		$this->input = new Input();
		$this->argv = $argv;
		$this->path = isset($argv[1])?$argv[1]:'./';
		$this->input->setPath($this->path);
	}

	function  getPath()
	{
		if ($this->validator->isValidate($this->input->getPath()))
			return $this->input->getPath();
		else
		{
			$this->output->showError();
			return null;
		}
	}

	function displayFilteredResult($results)
	{
		$this->output->showDuplicates($results);
	}

	function  displayLinks($links)
	{
		$this->output->showLinks($links);
	}

	function displayAmountOfGroups($amounts)
	{
		$this->output->showAmountOfGroups($amounts);
	}

	function displayTime($time)
	{
		$this->output->showTime($time);
	}

	public function update(\SplSubject $subject) {
		$processing = new Processing();
		/** @var Comparator $subject */
		$status = $processing->calculateStatusBar($subject->getDoneBytes(), $subject->getTotalBytes());  //check is method exit?
		$this->output->showStatusBar($status);
		if ($subject->getDoneBytes() >= $subject->getTotalBytes())
			$this->output->showNewLine();
	}
}

?>