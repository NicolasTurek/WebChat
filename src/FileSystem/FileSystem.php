<?php

namespace App\FileSystem;

class FileSystem {
	private $data, $folder;
	public function __construct(string $folder){
		$this->folder = $file;
	}
	
	// funkce třídy
	
	// poslání zprávy
	public function sendMessage(string $userId, string, string $message) : bool {
		return false;
	}
	// výpis chatu
	public function loadChat(string $userId, string $chatId) : string {
		return "<div>Chat</div>";
	}
	// výpis uživatelů
	public function loadUsers(string $userId) : string {
		return "<div>users</div>"
	}
	
	// práce se soubory
	
	// načtení hlavního souboru
	private function loadData() : array {
		$file = fopen($folder . "main.json", "r");
		if (!isset($file) || filesize($folder . "main.json") == 0) return ["cl" => 0, "ul" => 0, "chats" => [], "users" => []];
		$string = fread($file, filesize($folder . "main.json"));
		fclose($file);
		return json_decode($string);
	}
	// uložení hlavního souboru
	private function saveData() : bool {
		$file = fopen($folder . "main.json", "w");
		if (isset($file)) {
			fwrite($file, json_encode($chat);
			fclose($file);
			return true;
		}
		return false;
	}
	
	// načtení chatu ze souboru
	private function loadChat(string $chatId) : array {
		$file = fopen($folder . $chatId . ".json", "r");
		if (!isset($file) || filesize($folder . $chatId . ".json") == 0) return ["length" => 0, "messages" => []];
		$string = fread($file, filesize($folder . $chatId . ".json"));
		fclose($file);
		return json_decode($string);
	}
	// uložení chatu do souboru
	private function saveChat(string $chatId, array $chat) : bool {
		$file = fopen($folder . $chatId . ".json", "w");
		if (isset($file)) {
			fwrite($file, json_encode($chat);
			fclose($file);
			return true;
		}
		return false;
	}
	
	// zde přibudou bezpečnostní funkce (ověření oprávnění k přístupu do chatu)
}
