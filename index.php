<?php
require_once 'vendor/autoload.php';
include 'LanguageList.php';

use Faker\Provider\UserAgent;
use Stichoza\GoogleTranslate\GoogleTranslate;
//use \Renziito\BingTranslate;


//echo UserAgent::userAgent().' <select>';
//foreach($languagelist as $kll => $vll){
//	echo'<option value="'.$vll.'">'.$kll.'</option>';
//};echo'</select><br><br>';

//$trans = new BingTranslate();
$tr = new GoogleTranslate('en', $lang, [
    'timeout' => 10,
    'headers' => [
        'User-Agent' => UserAgent::userAgent()
    ]
]);
foreach ($languagelist as $kll => $vll):
$lang = $vll;
//$src = 'en';
$tr->setSource('en');
//$tr->setSource();
$tr->setTarget($lang);
$jsn = json_decode(file_get_contents('input/en.json'), True);

//$jsonData = rtrim($jsn, "\0");
//var_dump($jsn);
//var_dump(json_last_error_msg());
array_walk_recursive($jsn,
	function (&$val,$key){
		//global $trans,$lang,$src;
		global $tr;
		sleep(1);
		if(is_array($val)){
			array_walk_recursive($key,
				function (&$val1,$key1){
					if(is_array($val1)){
						array_walk_recursive($key1,
							function (&$val2,$key2){
								if(is_array($val2)){
									array_walk_recursive($key2,
										function (&$val3,$key3){
											//$val3 = $trans->translate($val3,$lang,$src);
											$val3 = $tr->translate($val3);
										}
									);
								}else{
									//$val2 = $trans->translate($val2,$lang,$src);
									$val2 = $tr->translate($val2);
								}
							}
						);
					}else{
						//$val1 = $trans->translate($val1,$lang,$src);
						$val1 = $tr->translate($val1);
					}
				}
			);
		}else{
			//$val = $trans->translate($val,$lang,$src);
			$val = $tr->translate($val);
		}
	}
);

$create = fopen($lang.".json","w") or die("gatot");
$save = fwrite($create, json_encode($jsn));
fclose($create);
if($save){echo'Sukses '.$lang;}else{echo'Gatot';}
//print_r( $json );
endforeach;
?>