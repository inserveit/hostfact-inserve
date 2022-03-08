<?php

namespace Inserve;

use Template;
use Debtor_Model;

class Inserve_Controller extends \Base_Controller
{
    public $config;

	public function __construct(Template $template)
	{
	    include_once(CUSTOMPATH . '/plugins/inserve/config.php');
	    if(empty($config))
	        throw new \Exception("Configuratiebestand niet gevonden.",500);

	    $this->config = $config;

		// Call service controller construct
		parent::__construct($template);
	}

	public function index()
	{
        $baseUrl = 'https://' . $this->config['subdomain'] . '.inserve-api'.($this->config['staging'] ? 'beta' : '').'.nl';
	    $api = new Inserve_Model($baseUrl, $this->config['apiKey']);
        $debtor = new Debtor_Model();
        $debtor->show();
        $debtorCode = $debtor->DebtorCode;
        try {
            $companies = $api->get('/companies?builder[0][where][0]=debtor_code&builder[0][where][1]=' . $debtorCode);
            $clients = $api->get('/clients?builder[0][whereHas][0]=companies&builder[0][whereHas][1]=id&builder[0][whereHas][2]=' . $companies[0]['id']);
            $token = $api->post('/auth/access-token', ['username' => $clients[0]['email']]);
            $this->Template->portalUrl = 'https://' . $this->config['subdomain'] . '.inportal' . ($this->config['staging'] ? 'beta' : '') . '.nl?access_token=' . $token['access_token'];
        } catch (\Exception $e) {
            throw new \Exception("Er ging iets mis in de verbinding met de API.",500);
        }

        $this->Template->show('inserve');
	}

}
