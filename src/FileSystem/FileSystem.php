<?php

namespace App\FileSystem;

/*

struktura hlavního souboru:
{
	"cl": [int], // počet chatů
	"ul": [int], // počet uživatelů
	"chats": array(
		[{
			"id": [int], // identifikátor, slouží k výběru souboru, a spojení s uživateli
			"name": [string], // název chatu
			"ul": [int],
			"users": array([int]) // id uživatelů - ověření
		}]
	)
	"users": array(
		[{
			"id": [int], // identifikátor
			"cid": [string], // kontrolní identifikátor, slouží k ověření uživatele, nutný k jakémukoliv přístupu
			"name": [string], // jméno
			"cl": [int],
			"chats": array([int]) // id chatů, do kterých má přístup - slouží k ověření
		}]
	)
}

struktura vedlejších souborů, jejich název je id + .json
{
	"ml": [int], // počet zpráv
	"messages": array(
		"id": identifikátor zprávy, především k určení pořadí, později může být přidána úprava zpráv
		"uid": [int], // id uživatele, který zprávu vytvořil
		"date": [string], // čas a datum vytvoření jako text
		// lze přidat další hodnoty - napadlo mě přidat type, který by umožňoval přidat i jiné typy, než text (obrázky, odkazy)
		"value": [string] // obsah zprávy
	)
}

*** vysvětlivky
	[n] => n je typ hodnoty, v případě, že obsahuje objekty, je ukázána struktura, u polí je datový typ pro všechny prvky

*/

class FileSystem {
	private $data, $folder, $uid;
	public function __construct(string $folder, string $controlId, int $userId){
		$this->folder = $folder;
		$data = $this->loadData();
		$uid = $this->checkUser($controlId, $userId);
	}
	
	// funkce třídy
	
	// poslání zprávy
	public function sendMessage(int $chatId, string $message) : bool {
		if ($this->data->cl < $chatId) {
			foreach ($this->data->chats[$chatId]->users as $userId) {
				if ($userId == $this->uid) {
					$chat = $this->loadChat($chatId);
					$chat->messages[$chat->ml++] = [
						"id" => $chat->ml - 1,
						"uid" => $userId,
						"date" => date("H:i:m;d.m.Y"),
						
						"value" => $message
					];
					$this->saveChat($chat);
					return true;
				}
			}
		}
		return false;
	}
	// výpis chatu
	public function showChat(int $chatId, int $last = 0, int $range = 10) : string {
		if ($this->data->cl < $chatId) {
			foreach ($this->data->chats[$chatId]->users as $userId) {
				if ($userId == $this->uid) {
					$chat = $this->loadChat($chatId);
					$string = "<div class=\"chat\">";
					for ($i = ($chat->ml - $last - $range < 0 ? 0 : $chat->ml - $last - $range); $i < $chat->ml - $last; $i++){
						$string .= "<p id=\"" . $chat->messages[$i]->id . "\">" .
							"<span class=\"messageUserName\">" . $this->data->users[$chat->messages[$i]->uid]->name . "</span>" .
							"<span class=\"messageValue\">" . $chat->messages[$i]->value . "</span>" .
						"</p>";
					}
					string .= "</div>";
					return string;
				}
			}
		}
		return "<div class=\"chat\">Přístup odepřen!</div>";
	}
	// výpis uživatelů
	public function showUsers() : string {
		$string = "<div class\"users\">"
		foreach ($this->data->users as $user) {
			$string .= "<p id=\"" . $user->id . "\">" .
				"<span class=\"userName\">" . $user->name . "</span>" .
			"</p>";
		}
		$string .= "</div>";
		return $string;
	}
	
	// práce se soubory
	
	// načtení hlavního souboru
	protected function loadData() : array {
		$file = fopen($folder . "main.json", "r");
		if (!isset($file) || filesize($folder . "main.json") == 0) return ["cl" => 0, "ul" => 0, "chats" => [], "users" => []];
		$string = fread($file, filesize($folder . "main.json"));
		fclose($file);
		return json_decode($string, true);
	}
	// uložení hlavního souboru
	protected function saveData() : bool {
		$file = fopen($folder . "main.json", "w");
		if (isset($file)) {
			fwrite($file, json_encode($chat));
			fclose($file);
			return true;
		}
		return false;
	}
	
	// načtení chatu ze souboru
	protected function loadChat(int $chatId) : array {
		$file = fopen($folder . $chatId . ".json", "r");
		if (!isset($file) || filesize($folder . $chatId . ".json") == 0) return ["length" => 0, "messages" => []];
		$string = fread($file, filesize($folder . $chatId . ".json"));
		fclose($file);
		return json_decode($string, true);
	}
	// uložení chatu do souboru
	protected function saveChat(int $chatId, array $chat) : bool {
		$file = fopen($folder . $chatId . ".json", "w");
		if (isset($file)) {
			fwrite($file, json_encode($chat));
			fclose($file);
			return true;
		}
		return false;
	}
	
	// zde přibudou bezpečnostní funkce (ověření oprávnění k přístupu do chatu)
	private function checkUser(string $cuid, int $id) : int {
		if ($data->ul > $id) if ($data->users[$id]->cid == $cuid) return $id;
		return -1;
	}
	
	// tady asi bude ještě pár funkcí... zapomněl jsem na vytvoření uživatelů a chatů
}
