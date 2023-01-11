<?php
namespace App\Controllers;
abstract class Authenticated extends \Core\Controller {
	/**
	 * Before filter - called before an action method.
	 *
	 * @return void
	 */
	protected function before() {
		$this->requireLogin();
	}
}