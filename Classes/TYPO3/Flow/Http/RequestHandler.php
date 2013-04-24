<?php
namespace TYPO3\Flow\Http;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Core\Bootstrap;
use TYPO3\Flow\Configuration\ConfigurationManager;

/**
 * A request handler which can handle HTTP requests.
 *
 * @Flow\Scope("singleton")
 * @Flow\Proxy(false)
 */
class RequestHandler implements HttpRequestHandlerInterface {

	/**
	 * @var \TYPO3\Flow\Core\Bootstrap
	 */
	protected $bootstrap;

	/**
	 * @var \TYPO3\Flow\Mvc\Dispatcher
	 */
	protected $dispatcher;

	/**
	 * @var array
	 */
	protected $routesConfiguration;

	/**
	 * @var \TYPO3\Flow\Mvc\Routing\Router
	 */
	protected $router;

	/**
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * @var \TYPO3\Flow\Http\Request
	 */
	protected $request;

	/**
	 * @var \TYPO3\Flow\Http\Response
	 */
	protected $response;

	/**
	 * The "http" settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * @var \TYPO3\Flow\Http\Component\BaseComponentChainFactory
	 */
	protected $baseComponentChainFactory;

	/**
	 * Make exit() a closure so it can be manipulated during tests
	 *
	 * @var \Closure
	 */
	public $exit;

	/**
	 * Constructor
	 *
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 */
	public function __construct(Bootstrap $bootstrap) {
		$this->bootstrap = $bootstrap;
		$this->exit = function() { exit(); };
	}

	/**
	 * This request handler can handle any web request.
	 *
	 * @return boolean If the request is a web request, TRUE otherwise FALSE
	 * @api
	 */
	public function canHandleRequest() {
		return (PHP_SAPI !== 'cli');
	}

	/**
	 * Returns the priority - how eager the handler is to actually handle the
	 * request.
	 *
	 * @return integer The priority of the request handler.
	 * @api
	 */
	public function getPriority() {
		return 100;
	}

	/**
	 * Handles a HTTP request
	 *
	 * @return void
	 */
	public function handleRequest() {
			// Create the request very early so the Resource Management has a chance to grab it:
		$this->request = Request::createFromEnvironment();
		$this->response = new Response();

		$this->boot();
		$this->resolveDependencies();
		if (isset($this->settings['http']['baseUri'])) {
			$this->request->setBaseUri(new Uri($this->settings['http']['baseUri']));
		}

		// $this->router->setRoutesConfiguration($this->routesConfiguration);
		// $actionRequest = $this->router->route($this->request);
		// $this->securityContext->setRequest($actionRequest);

		// $this->dispatcher->dispatch($actionRequest, $this->response);

		// $this->response->makeStandardsCompliant($this->request);

		$component = $this->buildHttpComponent();
		if ($component === NULL) {
			throw new \TYPO3\Flow\Exception('Base component could not be created', 1366813775);
		}
		$component->handle($this->request, $this->response);

		$this->response->send();

		$this->bootstrap->shutdown('Runtime');
		$this->exit->__invoke();
	}

	/**
	 * @return \TYPO3\Flow\Http\Component\HttpComponentInterface
	 */
	protected function buildHttpComponent() {
		return $this->baseComponentChainFactory->create($this->settings);
	}

	/**
	 * Returns the currently handled HTTP request
	 *
	 * @return \TYPO3\Flow\Http\Request
	 * @api
	 */
	public function getHttpRequest() {
		return $this->request;
	}

	/**
	 * Returns the HTTP response corresponding to the currently handled request
	 *
	 * @return \TYPO3\Flow\Http\Response
	 * @api
	 */
	public function getHttpResponse() {
		return $this->response;
	}

	/**
	 * Boots up Flow to runtime
	 *
	 * @return void
	 */
	protected function boot() {
		$sequence = $this->bootstrap->buildRuntimeSequence();
		$sequence->invoke($this->bootstrap);
	}

	/**
	 * Resolves a few dependencies of this request handler which can't be resolved
	 * automatically due to the early stage of the boot process this request handler
	 * is invoked at.
	 *
	 * @return void
	 */
	protected function resolveDependencies() {
		$objectManager = $this->bootstrap->getObjectManager();
		$this->baseComponentChainFactory = $objectManager->get('TYPO3\Flow\Http\Component\BaseComponentChainFactory');
		// $this->dispatcher = $objectManager->get('TYPO3\Flow\Mvc\Dispatcher');

		$configurationManager = $objectManager->get('TYPO3\Flow\Configuration\ConfigurationManager');
		$this->settings = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.Flow');

		// $this->routesConfiguration = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_ROUTES);
		// $this->router = $objectManager->get('TYPO3\Flow\Mvc\Routing\Router');

		// $this->securityContext = $objectManager->get('TYPO3\Flow\Security\Context');
	}
}
?>