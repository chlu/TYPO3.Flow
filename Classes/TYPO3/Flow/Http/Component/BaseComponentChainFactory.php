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
class BaseComponentChainFactory {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * @param array $settings
	 * @return \TYPO3\Flow\Http\Component\HttpComponentInterface
	 * @TODO Use Objects.yaml with factory object instead of settings?
	 */
	public function create(array $settings) {
		// TODO This could be compiled statically!
		// TODO Use dynamically sortable list from TypoScript (move to base Algorithms)
		$chainComponents = array();
		$chainConfiguration = array();
		foreach ($settings['http']['chain'] as $chainItem) {
			$chainConfiguration[$chainItem['position']] = $chainItem;
		}
		ksort($chainComponents);

		foreach ($chainConfiguration as $configuration) {
			$chainComponents[] = $this->objectManager->get($configuration['className']);
		}

		$chain = new ComponentChain();
		$chain->setComponents($chainComponents);
		return $chain;
	}

}

?>