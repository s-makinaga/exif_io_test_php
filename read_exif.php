<?php
error_reporting(E_ALL);
require_once('vendor/autoload.php'); 

$filename = "read_exif.jpg";

//*/
$comment = '２バイト文字 test comment';
writeUserComment($filename, $comment);
//*/

//*/
$user_comment = getUserComment($filename);
echo($user_comment.PHP_EOL);
//*/



function getUserComment($filename){
	$jpeg_file = new lsolesen\pel\PelJpeg($filename); 
	$exif_obj = $jpeg_file->getExif(); 

	if(!$exif_obj){
		echo('error'.PHP_EOL);
		return;
	}

	$exif = $exif_obj->getTiff()->getIfd()->getSubIfd(lsolesen\pel\PelIfd::EXIF);

	$comment = $exif->getEntry(lsolesen\pel\PelTag::USER_COMMENT);
	if(!$comment)
		return "";
	else
		return mb_convert_encoding($comment->getValue(), "UTF-8", "SJIS");
}

function writeUserComment($filename, $comment){
	$jpeg_file = new lsolesen\pel\PelJpeg($filename); 
	$exif_obj = $jpeg_file->getExif(); 

	if(!$exif_obj){
		echo('error'.PHP_EOL);
		return;
	}

	$exif = $exif_obj->getTiff()->getIfd()->getSubIfd(lsolesen\pel\PelIfd::EXIF);

	$entry = $exif->getEntry(lsolesen\pel\PelTag::USER_COMMENT);
	if (!$entry) { 
    	$entry = new lsolesen\pel\PelEntryUserComment(mb_convert_encoding($comment, "SJIS", "auto"), "UTF-8"); 
    	$exif->addEntry($entry); 
    } else { 
    	$entry->setValue(mb_convert_encoding($comment, "SJIS", "UTF-8"), "JIS"); 
    } 
    file_put_contents($filename, $jpeg_file->getBytes()); 
}