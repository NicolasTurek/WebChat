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
	private $data, $folder;
	public function __construct(string $folder){
		$this->folder = $folder;
	}
	
	// funkce třídy
	
	// poslání zprávy
	public function sendMessage(string $userId, string $chatId, string $message) : bool {
		return false;
	}
	// výpis chatu
	public function showChat(string $userId, string $chatId) : string {
		return "<div>Chat</div>";
	}
	// výpis uživatelů
	public function showUsers(string $userId) : string {
		return "<div>users</div>";
	}
	
	// práce se soubory
	
	// načtení hlavního souboru
	private function loadData() : array {
		$file = fopen($folder . "main.json", "r");
		if (!isset($file) || filesize($folder . "main.json") == 0) return ["cl" => 0, "ul" => 0, "chats" => [], "users" => []];
		$string = fread($file, filesize($folder . "main.json"));
		fclose($file);
		return json_decode($string, true);
	}
	// uložení hlavního souboru
	private function saveData() : bool {
		$file = fopen($folder . "main.json", "w");
		if (isset($file)) {
			fwrite($file, json_encode($chat));
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
		return json_decode($string, true);
	}
	// uložení chatu do souboru
	private function saveChat(string $chatId, array $chat) : bool {
		$file = fopen($folder . $chatId . ".json", "w");
		if (isset($file)) {
			fwrite($file, json_encode($chat));
			fclose($file);
			return true;
		}
		return false;
	}
	
	// zde přibudou bezpečnostní funkce (ověření oprávnění k přístupu do chatu)
}
