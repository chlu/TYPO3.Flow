<?php
namespace TYPO3\Flow\Mvc\Routing;

/*                                                                        *
 * This script belongs to the TYPO3 Flow framework.                       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow,
	\TYPO3\Flow\Http\Request,
	\TYPO3\Flow\Http\Response;

/**
 * A routing HTTP component
 */
class RoutingComponent extends \TYPO3\Flow\Http\Component\ComponentExecutor implements \TYPO3\Flow\Http\Component\HttpComponentInterface {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\Routing\Router
	 */
	protected $router;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * Component to handle the request if no component was found in the route matching result
	 * @var mixed
	 */
	protected $notFoundComponent = 'TYPO3\Flow\Http\Component\NotFoundComponent';

	/**
	 * Resolve a route for the request
	 *
	 * Stores the resolved arguments in the internal request arguments to pass them
	 * to other components.
	 *
	 * @param \TYPO3\Flow\Http\Request $request
	 * @param \TYPO3\Flow\Http\Response $response
	 * @return TRUE If the chain should be stopped
	 */
	public function handle(Request $request, Response $response) {
		$routesConfiguration = $this->configurationManager->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_ROUTES);
		$this->router->setRoutesConfiguration($routesConfiguration);

		$matchResults = $this->router->route($request);

		$request->setInternalArgument('routeResults', $matchResults);

		if (isset($matchResults['@component'])) {
			if ($this->handleComponent($matchResults['@component'], $request, $response) === FALSE) {
				return FALSE;
			}
		} else {
			if ($this->handleComponent($this->notFoundComponent, $request, $response) === FALSE) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * @param mixed $notFoundComponent
	 */
	public function setNotFoundComponent($notFoundComponent) {
		$this->notFoundComponent = $notFoundComponent;
	}

}

?>