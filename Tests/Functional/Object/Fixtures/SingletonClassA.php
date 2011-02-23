<?php
declare(ENCODING = 'utf-8');
namespace F3\FLOW3\Tests\Functional\Object\Fixtures;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * A class of scope singleton
 */
class SingletonClassA {

	/**
	 * @var \F3\FLOW3\Tests\Functional\Object\Fixtures\SingletonClassB
	 */
	protected $objectB;

	/**
	 * @param \F3\FLOW3\Tests\Functional\Object\Fixtures\SingletonClassB $objectB
	 */
	public function __construct(\F3\FLOW3\Tests\Functional\Object\Fixtures\SingletonClassB $objectB) {
		$this->objectB = $objectB;
	}

	/**
	 * @return \F3\FLOW3\Tests\Functional\Object\Fixtures\SingletonClassB
	 */
	public function getObjectB() {
		return $this->objectB;
	}

}
?>