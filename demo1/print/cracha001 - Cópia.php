<?php 
ob_start();
require '../../vendor/autoload.php';
require_once '../pdo/Config.inc.php';

//if($_SESSION['LEVEL_ID'] >= 100){
//    header('location: /admin/login');
//    exit;
//}

require_once '../../cdn/php/singularis.php'; 
require_once '../../cdn/php/Sql.class.php'; 
require_once '../class/Clients.class.php'; 
require_once '../class/Users.class.php';
//require_once '../class/States.class.php';
require_once '../class/Status.class.php';
require_once '../class/Subscriptions.class.php'; 


/**/
$error    = '';
$return   = '';

$method   =  $_SERVER['REQUEST_METHOD'];
$in       =  ${'_'.$method};

$action = 'list';

$C         =  new Subscriptions($_SESSION['USER_ID']);


$ROWS_CLIENTS = $C->select()->from('clients')->where('client_status_id','=',1)->execute();
$ROWS_CLASSES = $C->select()->from('classes')->where('class_status_id','=',1)->execute();
$ROWS_USERS   = $C->select()->from('users')->where('user_status_id','=',1)->execute();
$ROWS_STATUS  = $C->select()->from('status')->where('class','=',1)->execute();

$C->table = 'subscriptions';

if($action == 'list'){    
	$ROWS = $C->select()
	->leftJoin('clients','B.client_id')
	->leftJoin('classes','C.class_id')
	->leftJoin('users','D.user_id','created_user_id')
	->leftJoin('status','E.status_id','subscription_status_id')
	->leftJoin('courses','F.course_id','C.course_id')
	->leftJoin('cities','G.city_id','C.city_id')
	//->where('subscription_status_id','!=',0)
	->where('class_id','=',$_GET['class_id'])
	->execute();    
}

/**/
if($C->rowCount() == 0){
echo 'NÃO EXISTE INSCRIÇÕES PARA ESSA TURMA!';
exit;
}

use Dompdf\Dompdf;

/**
 * 
 */
class Certificate extends Singularis
{
	


	public function masc($mascara,$string)
	{
		$string = str_replace(" ","",$string);
		for($i=0;$i<strlen($string);$i++)
		{
			$mascara[strpos($mascara,"#")] = $string[$i];
		}
		return $mascara;
	}


//if(!isset($_SESSION['sys_id_cadastro'])){
//echo "Vocę năo pode fazer isso!!";
//exit;
//}else{

//$hash= str_replace('-','',base64_decode($_GET['hash']));
//$sqlQ=$cnx->query('select * from certificados where hash="'.$hash.'" and idcadastro='.$_SESSION['sys_id_cadastro']);
//$sqlL=$sqlQ->fetch_assoc();
//};
//INFORMA SE O CERTIFICADO JA FOI BAIXADO
//$cnx->query('update certificados set status=1 where hash="'.$hash.'"');

	public function html($array)
	{
$html = '<html>
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
			<title>Untitled Document</title>
			<style type="text/css">
			

		
body,html{
	margin:10px;
	padding:0px;
	max-width:700px;
	
}
		
		</style>
		</head>

		<body><table width="100%" style="border-spacing: 15px;">';
$i = 0;
		foreach ($array as $k => $args) {
$fullName = strtoupper($args['client_name'].' '.' ');
$exp = explode(' ', $fullName);

$name = $exp[0];
$last_name = strlen($exp[1]) < 4 ? $exp[2] : $exp[1];

if($i == 0){
	$html .='<tr>';
	
	$i++;
}else{
	$html .='';
	$i = 0;
}
			$html .='<td style="">		
			<div style="border:#000 solid 2px; margin:1px;height:2.36in;  width: 100%;max-width:3.54in; max-height:2.36in">
			
		
		<h2 style="text-align:center;position:relative; font-size:39px">'.$name.'<br>'.$last_name.'</h2>

		<p class="MsoNormal" align="right" style="text-align:left;  position: relative; vertical-align:bottom; bottom:-0.1in; margin:10px">'.$args['course_name'].' - '.$this->sing_pad($args['class_number'],3,'0').'
		</p>
		<p class="MsoNormal" align="right" style="text-align:left;  position: relative; vertical-align:bottom; bottom:-0.1in; font-size:12px; margin:10px">'.$fullName.'
		</p>
		</div>
		
		<div>
		</div></td>'; 
	
		}
		$html .='</tr></table></body>
		</html>';
		return $html;
	}
}

	$C = new Certificate();
	echo $C->html($ROWS);
	

	/*$dompdf = new Dompdf();
	$dompdf->loadHtml($C->html($ROWS),'UTF-8');


	$dompdf->setPaper('A4', 'portrait');


	$dompdf->render();


	$dompdf->stream();*/