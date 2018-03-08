<?php require_once('./vendor/autoload.php'); // Namespace 
use \LINE\LINEBot; 
use \LINE\LINEBot\HTTPClient;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient; 
use \LINE\LINEBot\MessageBuilder\TextMessageBuilder; 
use \LINE\LINEBot\MessageBuilder\StickerMessageBuilder;

$host = "localhost";
$username = "sangudom_pmt";
$password = "canon50d";
$db = "sangudom_pmt";
//header("HTTP/1.1 200 OK");

mysql_connect($host, $username, $password) or die('dead');
mysql_select_db($db) or die('dead');
mysql_query('set name utf8');


$channel_token = 'rQLpz44d7AEZHpO4SToWXv1xqs9Di2K29fxheb/QjZtlpbjK8aAnXFFDLkpBwy6GIK29x4qE8zQ0WEwsJZ3F2ulHkSeMrlrPttEW5cX1/WOatQhcqNx3E3IrOQS73o4RSneskAOJK0UvK9O83lROowdB04t89/1O/w1cDnyilFU='; 
$channel_secret = '41a728cbbf76503bc4611b84574fcaec'; 
$httpClient = new CurlHTTPClient($channel_token); 
$bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret)); 
 
// Get message from Line API 
$content = file_get_contents('php://input'); 
$events = json_decode($content, true); 

/* $strSQL = "INSERT INTO lineuser(lid,userid,displayname,function) VALUES('','y','y','y')";
echo $strSQL;
$objQuery = mysql_query($strSQL) or die ('insert error');
if(!objQuery){
    echo "Error";
}    */     





if (!is_null($events['events'])) { 

    // Loop through each event 
    foreach ($events['events'] as $event) { 
        // Line API send a lot of event type, we interested in message only. 
        if ($event['type'] == 'message') { 
            
            // Get replyToken 
            $replyToken = $event['replyToken'];
            $userId = $event['source']['userId'];
            $textMsg = $event['message']['text'];
            $userProfile = $bot->getProfile($userId);
            $userData = $userProfile->getJSONDecodedBody();
            $userDisplayName = $userData['displayName'];

            // $strSQLcheck = "SELECT * FROM lineuser WHERE userid ='".$userId."'";
            // $objDB = mysql_query($strSQLcheck) or die ('select error');
            // if(mysql_num_rows($objDB)<1){
            // $strSQL = "INSERT INTO lineuser(lid,userid,displayname,function) VALUES('','".$userId."','".$userDisplayName."','y')";
            // echo $strSQL;
            // $objQuery = mysql_query($strSQL) or die ('insert error');
            // if(!objQuery){
            //     echo "Error";
            // }        
//            }

            // Sticker
            $packageId = 1;
            $stickerId = 1;
            
            $wording1 = array("ดีจะ"=>"ดีเจ้าค่ะแม่หญิง", "สวัสดี"=>"จ๊ะสวัสดีค่ะ","ตื่น"=>"ตื่นแล้วเจ้าค่ะ ^ ^","ต้องการ"=>"ออเจ้า โพสได้ตามสบายเจ้าค่ะ ^ ^");

            switch($event['message']['type']) { 
                case 'text': 
                    // Reply message 
                    foreach ($wording1 as $key => $value){
                        if ($key == $textMsg){
                            $respMessage = $value;
//                            $respMessage .= $userId;
                            $respMessage .= $userData['displayName'];
                        } 
                    }

//                   $httpClient = new CurlHTTPClient($channel_token); 
//                    $bot = new LINEBot($httpClient, array('channelSecret' => $channel_secret)); 
                    $textMessageBuilder = new TextMessageBuilder($respMessage); 
                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
//                    $textMessageBuilder = new StickerMessageBuilder($packageId, $stickerId); 
//                    $response = $bot->replyMessage($replyToken, $textMessageBuilder);
                    if($response->isSucceeded()){
                        echo 'Succeed';
                        return;
                    }
                    // $response = $bot->pushMessage($userId, $textMessageBuilder);
                    // if($response->isSucceeded()){
                    //     echo 'Succeed';
                    //     return;
                    // }

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

        }
 
    }

}


echo "OK"; 
?>