<?php
namespace TYPO3\Flow\Mvc;

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

/**
 * A dispatch component
 */
class DispatchComponent implements \TYPO3\Flow\Http\Component\HttpComponentInterface {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\Dispatcher
	 */
	protected $dispatcher;

	/**
	 * @param \TYPO3\Flow\Http\Request $request
	 * @param \TYPO3\Flow\Http\Response $response
	 * @return TRUE If the chain should be stopped
	 */
	public function handle(\TYPO3\Flow\Http\Request $request, \TYPO3\Flow\Http\Response $response) {
		$this->dispatcher->dispatch($request->getInternalArgument('actionRequest'), $response);
	}

}

?>