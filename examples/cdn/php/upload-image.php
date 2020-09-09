<?php
require_once '../../admin/pdo/Config.inc.php';
require_once '../../cdn/php/singularis.php'; 
require_once '../../admin/pdo/Sql.class.php'; 
require_once '../../admin/class/Clients.class.php'; 
/*https://foliotek.github.io/Croppie/*/

$image = $_POST['image'];

list($type, $image) = explode(';',$image);
list(, $image) = explode(',',$image);

$exp = explode('/', $type);
$ext = end($exp);

$image = base64_decode($image);
$image_name = 'BG-'.time().'.'.$ext;

try{
	file_put_contents('../../clients/cliente/'.$_SESSION['USER_DIRECTORY'].'/'.$image_name, $image);
	$upload = 'SUCCESS';
}catch (Exception $exc) {
	$upload = 'FAIL';
}

$Clients     =  new Clients($_SESSION['CLIENT_ID']);
//$Clients->setClientId($_SESSION['CLIENT_ID']);
$in = ['background'=>$image_name];
$Clients->setData($in);


if($upload == 'SUCCESS')
	$return = $Clients->update()->where('client_id','=',$_SESSION['CLIENT_ID'])->execute();  


if($return == 'updated' && $upload == 'SUCCESS')
	echo 'SUCCESS';
else
	echo 'FAIL';