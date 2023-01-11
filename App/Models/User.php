<?php
namespace App\Models;
use Core\Model;
use App\Token;
use PDO;
/**
 * Example user model
 *
 * PHP version 7.0
 */
class User extends \Core\Model {
	public $errors = [];
	public function __construct($data = []) {
		foreach ($data as $key => $value) {
			$this->$key = $value;
		}
	}
	public function save() {
		$this->validate();
		if (empty($this->errors)) {
			$password_hash = password_hash($this->password, PASSWORD_DEFAULT);
			$sql = 'INSERT INTO users (name, email, password_hash)
					VALUES (:name, :email, :password_hash)';
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
			$stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
			$stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
			return $stmt->execute();
		}
		return false;
	}
	public function validate() {
		if ($this->name == '') {
			$this->errors[] = 'Name is required';
		}
		if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
			$this->errors[] = 'Invalid email';
		}
		if (static::emailExists($this->email)) {
			$this->errors[] = 'Email already taken';
		}
		if (strlen($this->password) < 6) {
			$this->errors[] = 'Please enter at least 6 characters for the password';
		}
		if (strlen($this->password) > 20) {
			$this->errors[] = 'Please enter no more than 20 characters for the password';
		}
		if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
			$this->errors[] = 'Password needs at least one letter';
		}
		if (preg_match('/.*\d+.*/i', $this->password) == 0) {
			$this->errors[] = 'Password needs at least one number';
		}
	}
	public static function emailExists(string $email): bool {
		return static::findByEmail($email) !== false;
	}
	public static function findByEmail($email) {
		$sql = 'SELECT * FROM users WHERE email = :email';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetch();
	}
	public static function findByID($id) {
		$sql = 'SELECT * FROM users WHERE id = :id';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetch();
	}
	public static function authenticate(string $email, string $password) {
		$user = static::findByEmail($email);
		if ($user) {
			if (password_verify($password, $user->password_hash)) {
				return $user;
			}
		}
		return false;
	}
	public function rememberLogin() {
		$token = new Token();
		$hashed_token = $token->getHash();
		$this->remember_token = $token->getValue();
		$this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now
		$sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
		$stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
		$stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);
		return $stmt->execute();
	}
}
