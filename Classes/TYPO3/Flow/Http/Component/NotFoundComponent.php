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
class NotFoundComponent implements HttpComponentInterface {

	/**
	 *
	 * @param \TYPO3\Flow\Http\Request $request
	 * @param \TYPO3\Flow\Http\Response $response
	 * @return FALSE If the chain should be stopped
	 */
	public function handle(Request $request, Response $response) {
		$response->setStatus(404, 'Not found');
		$response->setContent('<h1>Not found, could not handle this request</h1>');
	}

}

?>