
<?php
$access_token = 'kbLZyUFX6yQoQAgLMCvnd3Aq/kK5Rdelck9Q062do9wqcBmnWGXB5kkosTKhJiNBYRamAF/iPzMk3aa6M+7yvnaC4Ln/rg5hT/DPxbIcFcr25/DuAILX3maUg4A8FwRYrMdzEOd10MYBfJf8VemfJwdB04t89/1O/w1cDnyilFU=';
// Get POST body content
$content = file_get_contents('php://input');
// Parse JSON
$events = json_decode($content, true);
// Validate parsed JSON data
$Light = file_get_contents('https://api.thingspeak.com/channels/262354/fields/1/last.txt');
$HUM = file_get_contents('https://api.thingspeak.com/channels/262354/fields/2/last.txt');
$TEM = file_get_contents('https://api.thingspeak.com/channels/262354/fields/3/last.txt');
//convert
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
				'text' => "ไม่มีคำสั่งที่คุณพิมพ์ กรุณาพิมพ์ help เพื่อดูเมนู" 
					// "text"
			];
			if (strtoupper($text) == "HELP"){		
				$messages = [
				'type' => 'text',
				'text' => "พิมพ์หมายเลข 1 เพื่อสถานะอากาศปัจจุบัน"."\n"."พิมพ์หมายเลข 2 ดูสถานที่ทั้งหมด"."\n"."พิมพ์หมายเลข 3 รายละเอียดสภาพอากาศ"
			];
				
			}	
				if(trim($text) == "1"){		
					$messages = [
					'type' => 'text',
					'text' => "สถานที่ : "."ตึก B8 "."\n"."ความสว่างของแสง : ".$Light ."\n"."อุณหภูมิ C :".$TEM."\n"."ความชื้น :".$HUM ."%"."\n"."[พิมพ์ help เพื่อดูเมนู]"
				];	
			}
			
			
			
			if(trim($text) == "2"){
				$messages = [ 
					'type' => 'text',
					'text' => "สถานที่ทั้งหมด :"."\n"."วัดพระธาตุ"."\n"."ม.วลัยลักษณ์"."\n"."[พิมพ์ help เพื่อดูเมนู]"
						];
					}
			
			if(trim($text) == "3"){		
					$messages = [
					'type' => 'text',
					'text' => "ค่าแสง : "."\n"."1500 - 800 = กลางคืน"."\n"."800-500 = เช้ามืด"."\n"
				];	
			}
			
			
			if(strtoupper($text) == "HI"){
                            
				$messages = [
				'type' => 'text',
				'text' => "hello"
			];
			}
			if($text == "รูป"){
                            
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg",
    				'previewImageUrl' => "https://sv6.postjung.com/picpost/data/184/184340-1-2995.jpg"
				
			];	
			}
			if($text == "ภาพ"){
                            
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://i.imgur.com//yuRTcoH.jpg",
    				'previewImageUrl' => "https://i.imgur.com//yuRTcoH.jpg"
					
			];	
			}
			if($text == "ภาพ1"){
                            
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://i.imgur.com/yuRTcoH.jpg",
    				'previewImageUrl' => "https://i.imgur.com/yuRTcoH.jpg"
			];	
			}
				if($text == "ภาพ 1"){
                            
				$messages = [
				'type' => 'image',
				'originalContentUrl' => "https://i.imgur.com/yuRTcoH.jpg",
    				'previewImageUrl' => "https://i.imgur.com/yuRTcoH.jpg"
			];	
			}
			
			/*if($text == "image"){
                            
				$messages = [
				$img_url = "http://sand.96.lt/images/q.jpg";
				$outputText = new LINE\LINEBot\MessageBuilder\ImageMessageBuilder($img_url, $img_url);
				$response = $bot->replyMessage($event->getReplyToken(), $outputText);
			
			];	
			}*/
						
			
			
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
