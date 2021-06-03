<?php

namespace Inserve;

use Template;
use Debtor_Model;

class Inserve_Controller extends \Base_Controller
{
	public function __construct(Template $template)
	{
		// Call service controller construct
		parent::__construct($template);
	}

	public function index()
	{
        $apiKey = "xz8l9Yk4V2ZUOxZ8Rh3h6dVecAZJmbkSCNbUufy4c58qhf48qJCgXSjpMNEEgdTJ";
        $baseUrl = 'http://test.inserve-api.loc';
	    $api = new Inserve_Model($baseUrl, $apiKey);
        $debtor = new Debtor_Model();
        $debtor->show();
        $debtorCode = $debtor->DebtorCode;
        $companies = $api->get('/companies?builder[0][where][0]=debtor_code&builder[0][where][1]=' . $debtorCode);
        $companyId = $companies[0]['id'];
        $clients = $api->get('/clients?builder[0][where][0]=company_id&builder[0][where][1]=' . $companyId);
        $email = $clients[0]['email'];
        $token = $api->post('/auth/access-token', ['username' => $email]);
        $this->Template->accessToken = $token['access_token'];

        $this->Template->show('inserve');
	}

}
