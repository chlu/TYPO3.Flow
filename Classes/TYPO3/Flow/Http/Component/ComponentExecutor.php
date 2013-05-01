<?php
namespace TYPO3\Flow\Http\Component;

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
 *
 */
abstract class ComponentExecutor implements HttpComponentInterface {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * Handle a component from string, array or instance
	 *
	 * @param mixed $component
	 * @param \TYPO3\Flow\Http\Request $request
	 * @param \TYPO3\Flow\Http\Response $response
	 */
	protected function handleComponent($component, $request, $response) {
		if ($component instanceof HttpComponentInterface) {
			return $component->handle($request, $response);
		} elseif (is_string($component)) {
			$comp = $this->objectManager->get($component);
			if ($comp instanceof HttpComponentInterface) {
				return $comp->handle($request, $response);
			} else {
				// TODO Handle wrong object name / instance
			}
		} elseif (is_callable($component)) {
			return $component($request, $response);
		} else {
			throw new \InvalidArgumentException('Given component of type "" is not callable and does not implement HttpComponentInterface', 1366812942);
		}
	}

}

?>