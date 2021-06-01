<?php

namespace Inserve;

use Template;
use Plugin;
use Settings_Model;

class Inserve_Controller extends \Base_Controller
{
	public function __construct(Template $template)
	{
		// Call service controller construct
		parent::__construct($template);
	}

	public function index()
	{
        $this->Template->show('inserve');
	}

}
