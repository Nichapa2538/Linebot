void Line_Notify(String message) ;
#define LINE_TOKEN "Vtcjq5NYhVv9AXCCfiJVsRxwp02dhbK4VRGBs7T4HWa"
#define SW D2
String message = "I'm ESP8266";
void setup() {
pinMode(SW, INPUT);
Serial.begin(9600);
WiFi.mode(WIFI_STA);
// connect to wifi.
WiFi.begin(WIFI_SSID, WIFI_PASSWORD);
Serial.print("connecting");
while (WiFi.status() != WL_CONNECTED) {
Serial.print(".");
delay(500);
}
Serial.println();
Serial.print("connected: ");
Serial.println(WiFi.localIP());
}
void loop() {
int sensor = analogRead(A0);
message = "Now ";
message += sensor;
Line_Notify(message);
delay(5000);
}
void Line_Notify(String message) {
WiFiClientSecure client;
if (!client.connect("notify-api.line.me", 443)) {
Serial.println("connection failed");
return; 
}
String req = "";
req += "POST /api/notify HTTP/1.1\r\n";
req += "Host: notify-api.line.me\r\n";
req += "Authorization: Bearer " + String(LINE_TOKEN) + "\r\n";
req += "Cache-Control: no-cache\r\n";
//req += "User-Agent: ESP8266\r\n";
req += "Content-Type: application/x-www-form-urlencoded\r\n";
req += "Content-Length: " + String(String("message=" + message).length()) + "\r\n";
req += "\r\n";
req += "message=" + message;
Serial.println(req);
client.print(req);
delay(20);
// Serial.println("-------------");
while(client.connected()) {
String line = client.readStringUntil('\n');
if (line == "\r") {
break;
}
//Serial.println(line);
}
// Serial.println("-------------");
}
<?php
$access_token = 'kbLZyUFX6yQoQAgLMCvnd3Aq/kK5Rdelck9Q062do9wqcBmnWGXB5kkosTKhJiNBYRamAF/iPzMk3aa6M+7yvnaC4Ln/rg5hT/DPxbIcFcr25/DuAILX3maUg4A8FwRYrMdzEOd10MYBfJf8VemfJwdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
$ph = file_get_contents('https://api.thingspeak.com/channels/321156/fields/1/last.txt');
//convert 
$Natural = 5 ;
//$Acidic = 6 ;
//$Alkaline = 10 ;	
	
if (!is_null($events['events'])) {
	// Loop through each event
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			
			
			
			// Build message to reply back
			$messages = [
				'type' => 'text',
				'text' => "กรุณากรอกข้อความให้ถูกต้องคะ พิมพ์ HELP เพื่อดูรายการ" 
					// "text"
			];
			if (strtoupper($text) == "HELP"){		
				$messages = [
				'type' => 'text',
				'text' => "ดูค่าPH พิมพ์ pH "."\n"		];
				
			}
			
			if (strtoupper($text) == "PH"){		
				$messages = [
				'type' => 'text',
				'text' => "Natural pH : ".$ph ."\n"."Acidic pH : ".$Acidic ."\n"."Alkaline pH : ".$Alkaline ];
				
			}
			
			
					
			
			
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
				'messages' => [$messages],
			];
			$post = json_encode($data);
			$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			$result = curl_exec($ch);
			curl_close($ch);
			echo $result . "\r\n";
		}
	}
	
}
echo "OK";
