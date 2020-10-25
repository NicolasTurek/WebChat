// odeslání dat - js

var req = new XMLHttpRequest(), data = "";
req.open("POST", "/", true);
req.setRequestHeader("Content-Type", "application/json; charset=utf-8");
req.onreadystatechange = function(){
	if (req.readyState == 4 && req.status == 200) data = req.responseText; // neúspěšné přijetí nechá proměnnou data nezměněnou
}
req.send(JSON.stringify({ "name" : "data" }));

// přijetí dat - php

$_POST = json_decode(file_get_contents('php://input'), true); // v případě špatného kódování vrací null
