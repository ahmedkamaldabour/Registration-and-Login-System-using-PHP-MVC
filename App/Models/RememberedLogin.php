<?php
namespace App\Models;
use PDO;
use App\Token;
class RememberedLogin extends \Core\Model {
	// add a method that finds the record using the token form the database
	public static function findByToken($token) {
		$token = new Token($token);
		$token_hash = $token->getHash();
		$sql = "SELECT * FROM remembered_logins WHERE token_hash = :token_hash";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindvalue(':token_hash', $token_hash, PDO::PARAM_STR);
		$stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
		$stmt->execute();
		return $stmt->fetch();
	}
	public function getUser() {
		return User::findByID($this->user_id);
	}
	public function hasExpired() {
		return strtotime($this->expires_at) < time();
	}
	public function delete() {
		$sql = "DELETE FROM remembered_logins WHERE token_hash= :token_hash";
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindvalue(':token_hash', $this->token_hash, PDO::PARAM_STR);
		$stmt->execute();
	}
}