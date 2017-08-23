<?php
namespace Tests\AppBundle\EndToEnd;

use aik099\PHPUnit\BrowserTestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * BaseWebTestCase
 *
 * @author    Robert Freigang <robert.freigang@projektmotor.de>
 * @copyright ProjektMOTOR GmbH 2016
 */
class BaseBrowserTestCase extends BrowserTestCase
{
    const WAIT = 5000;

    public function setUp()
    {
		$parameters = Yaml::parse(file_get_contents('app/config/parameters.yml'));

		$tunnelId = getenv($parameters['parameters']['mink_tunnel_id_env_name']);
		putenv('PHPUNIT_MINK_TUNNEL_ID='.$tunnelId);

		$browserConfiguration = [
			'driver' => 'selenium2',
			'host' => $parameters['parameters']['mink_host'],
			'port' => $parameters['parameters']['mink_port'],
			'browserName' => 'chrome',
			'baseUrl' => $parameters['parameters']['mink_base_url'],
			'type' => $parameters['parameters']['mink_type'],
		];

		if ($browserConfiguration['type'] !== 'default') {
			$browserConfiguration['api_username'] = getenv($parameters['parameters']['mink_api_username_env_name']);
			$browserConfiguration['api_key'] = getenv($parameters['parameters']['mink_api_key_env_name']);
		}

        $browser = $this->createBrowserConfiguration($browserConfiguration);

        $this->setBrowser($browser);

        parent::setUp();
    }

    protected function openHomepage()
    {
        $this->getSession()->visit($this->getBrowser()->getBaseUrl());
    }
}
