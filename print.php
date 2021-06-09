<?php
header('Content-type: text/html; charset=utf-8');
header('Content-type: image/jpeg');

//echo "".$_POST["param"];
if($_POST["param"]=="printreceipt"){
	$receiptno = $_POST["receiptno"];
	$name = $_POST["name"];
	$donate_date = $_POST["donate_date"];
	$amount = $_POST["amount"];
	echo "receiptNo : ". $receiptno;
	echo "\t   Name : ". $name;
	echo "\t   Date : ".$donate_date;
	echo "\t    Amt : ".$amount;
	echo "\n";

	$check ="";
	printImage($receiptno,$name,$donate_date,$amount,$check);
}
else if($_POST["param"]=="allprintreceipt"){
	
	$data = explode(",", $_POST['AllData']);
	$i =0;
	for($i=0;$i<sizeof($data);$i++){
		$receiptno = str_replace(array("[","]","\""), '', $data[$i]);
		$name = str_replace(array("[","]","\""), '', $data[$i+1]);
		$d_date = str_replace(array("[","]","\""), '', $data[$i+2]);
		$amount = str_replace(array("[","]","\""), '', $data[$i+3]);

		echo "receiptNo : ". $receiptno;
		echo "\t   Name : ". $name;
		echo "\t   Date : ".$d_date;
		echo "\t    Amt : ".$amount;
		echo "\n";
		$i+=3;
		$check="all";
	   	printImage($receiptno,$name,$d_date,$amount,$check);
	};
}
echo "Donation Receipt Downloaded";

function printImage($receiptno,$name,$donate_date,$amount,$check){
	$receiptno= str_replace("\n", '', $receiptno);
	$name= str_replace("\n", '', $name);
	$donate_date= str_replace("\n", '', $donate_date);
	$amount= str_replace("\n", '', $amount);
	echo "Initial : ".str_replace("\n", "", trim($amount));

	if( $receiptno !="" && $name !="" && $donate_date != "" && $amount != ""){
	    $amount_1 = preg_split('/ +/', $amount);
		$first_str = str_replace(array("\n", "\r"), '', $amount_1[0]);
		$second_str = str_replace(array("\n", "\r"), '', $amount_1[1]);
		echo "first : ".$first_str;
		echo "second: ".$second_str;
		if($first_str == "JPY"){

			$price_text = (string)$amount_1[1]; // convert into a string
			$price_text = strrev($price_text); // reverse string
			$arr = str_split($price_text, "3"); // break string in 3 character sets

			$price_new_text = implode(",", $arr);  // implode array with comma
			$price_new_text = strrev($price_new_text); // reverse string back
			$disp_amount = $first_str." ".$price_new_text;
		}
		else if($second_str == "MMK"){
			$price_text = (string)$first_str; // convert into a string
			$price_text = strrev($price_text); // reverse string
			$arr = str_split($price_text, "3"); // break string in 3 character sets

			$price_new_text = implode(",", $arr);  // implode array with comma
			$price_new_text = strrev($price_new_text); // reverse string back
			$disp_amount = $price_new_text." ".str_replace(array("\n", "\r"), '', $amount_1[1]);
		}
		// Load And Create Image From Source
		$our_image = imagecreatefromjpeg('uploads\certificates\user\donate_receipt.jpg');

		// Allocate A Color For The Text Enter RGB Value
		$black_color = imagecolorallocate($our_image, 0, 0, 0);

		
		
		// Set Path to Font File
		//$font_path = getcwd()."\fonts\arial.ttf";
		//$font_path = realpath("..\fonts\arial.ttf");
		$font_path = 'C:\xampp\htdocs\1000dc\src\fonts\arial.ttf';
		$font = mb_convert_encoding($font_path, 'big5', 'utf-8');

		//receipt no
		//imagettftext($our_image, 18,0,870,40, $black_color, $font, $receiptno);


		$size=27;
		$angle=0;
		$left=350;

		//$top=630;
		if($check == "all"){
			$top = 692;
		}else{
			$top = 650;
		}


		//$name1 = mb_convert_encoding($name, "SJIS");
		//$name1 = mb_convert_encoding("日本語", 'UTF-8', array('EUC-JP', 'SHIFT-JIS', 'AUTO'));
		// Print Text On Image
		imagettftext($our_image, $size,$angle,$left,$top, $black_color, $font, strtoupper($name));

		//$top=730;
		//$top=745;
		if($check == "all"){
			$top = 790;
		}else{
			$top = 746;
		}
		imagettftext($our_image, $size,$angle,$left,$top, $black_color, $font, $donate_date);

		echo "Before print : ".$disp_amount;
		//$top=885; 
		$top=892;
		imagettftext($our_image, $size,$angle,$left,$top, $black_color, $font, $disp_amount);


		// Send Image to Browser
		//imagejpeg($our_image);
		$display_name = strtolower(str_replace(" ","",$name));
		$display_date = str_replace("/","",$donate_date);


		header( "Content-type: image/png" );
		imagepng($our_image);
		$save = str_replace(array("\n", "\r"), '', 'C:\xampp\htdocs\1000dc\save\dc_'.$receiptno."_".$display_name."_".$display_date.".png");
		//chmod($save,0755);
		imagepng($our_image, $save, 0, NULL);

		// Clear Memory
		imagedestroy($our_image);
	}
}
?>