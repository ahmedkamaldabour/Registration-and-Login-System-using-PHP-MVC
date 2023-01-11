<?php
namespace App\Controllers;
use App\Flash;
use \Core\View;
use \App\Models\User;
use \App\Auth;
class Login extends \Core\Controller {
	public function newAction() {
		View::renderTemplate('Login/new.html');
	}
	public function createAction() {
		$user = User::authenticate($_POST['email'], $_POST['password']);
		$remember_me = isset($_POST['remember_me']);
		if ($user) {
			Auth::login($user, $remember_me);
			Flash::addMessage('you are log in successfully', Flash::SUCCESS);
			$this->redirect(Auth::getReturnToPage());
		} else {
			Flash::addMessage('you\'re login unsuccessfully, please try again', Flash::WARNING);
			View::renderTemplate('Login/new.html', [
				'email' => $_POST['email'],
				'remember_me' => $remember_me
			]);
		}
	}
	public function destroyAction() {
		Auth::logout();
		$this->redirect('/login/showLogoutMessage');
	}
	public function showLogoutMessageAction() {
		Flash::addMessage('you\'re logging out successfully',);
		$this->redirect('/');
	}
}