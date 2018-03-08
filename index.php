<?php require_once('./vendor/autoload.php'); // Namespace 
use \LINE\LINEBot\HTTPClient\CurlHTTPClient; 
use \LINE\LINEBot; 
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder; 
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;

$channel_token = 'rQLpz44d7AEZHpO4SToWXv1xqs9Di2K29fxheb/QjZtlpbjK8aAnXFFDLkpBwy6GIK29x4qE8zQ0WEwsJZ3F2ulHkSeMrlrPttEW5cX1/WOatQhcqNx3E3IrOQS73o4RSneskAOJK0UvK9O83lROowdB04t89/1O/w1cDnyilFU='; 
$channel_secret = '41a728cbbf76503bc4611b84574fcaec'; 
// Get message from Line API 
$content = file_get_contents('php://input'); 
$events = json_decode($content, true); 

if (!is_null($events['events'])) { 
    // Loop through each event 
    foreach ($events['events'] as $event) { 
        // Line API send a lot of event type, we interested in message only. 
        if ($event['type'] == 'message') { 
            
            // Get replyToken 
            $replyToken = $event['replyToken'];
            $userId = $event['source']['userId'];
            $textMsg = $event['message']['text'];
            // Sticker
            $packageId = 1;
            $stickerId = 1;
            
            $wording1 = array("ดีจะ"=>"ดีเจ้าค่ะแม่หญิง", "สวัสดี"=>"จ๊ะสวัสดีค่ะ","ตื่น"=>"ตื่นแล้วเจ้าค่ะ ^ ^");

            switch($event['message']['type']) { 
                case 'text': 
                    // Reply message 
                    foreach ($wording1 as $key => $value){
                        if ($key == $textMsg){
                            $respMessage = $value;
//                            $respMessage .= $userId; 
                        } 
                    }

                    $httpClient = new CurlHTTPClient($channel_token); 
                    $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret)); 
                    $textMessageBuilder = new TextMessageBuilder($respMessage); 
                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
//                    $textMessageBuilder = new StickerMessageBuilder($packageId, $stickerId); 
//                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
                    if($response->isSucceeded()){
                        echo 'Succeed';
                        return;
                    }

                    break;

/*                 case 'image':
                    $messageID = $event['message']['id'];
                    $respMessage = 'Image ID '.$messageID;
                    break; */

/*                 case 'sticker':
                    $messageID = $event['message']['packageId'];
                    $respMessage='Sticker ID '. $messageID;
                    break;
 */
                default:
                    $respMessage='เจ้าค่ะ ^ ^';
                    $httpClient = new CurlHTTPClient($channel_token);
                    $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
                    $textMessageBuilder = new TextMessageBuilder($respMessage);
                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
        
                    break; 
            }
            $respMessage='เจ้าค่ะ ^ ^';
            $httpClient = new CurlHTTPClient($channel_token);
            $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret));
            $textMessageBuilder = new TextMessageBuilder($respMessage);
            $response = $bot->replyMessage($replyToken, $textMessageBuilder);

         } 
    } 
} 
echo "OK"; 
?>