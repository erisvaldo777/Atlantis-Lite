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
class Certificate 
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
			<!--
#Cfac{color:#003399}
#Ccert {
			font-style: italic; color:#003399;
			font-size: 35px;
			font-family: "Times New Roman";
			font-weight: bold;

		}

		body {
			background-image:url(img/bgCert.jpg) ;
			background-repeat: no-repeat;
			background-attachment: fixed;
			background-position: center; 
			margin-top: 0px;
			margin-right: 0px;
			margin-bottom: 0px;
		}
#txt{padding:0 60px 0 60px; text-align:justify; font-size:18px;font-family: Times New Roman, Times, serif;}
		-->
		</style>
		</head>

		<body>';
		foreach ($array as $k => $args) {

			$html .='
		<center ><div><h1 id="Cfac"><br /><br />
		NOME DA ENTIDADE CERTIFICADORA</h1>
		<p><span> Autorizada pelo MEC através da portaria nº xxx de xxxxx</span>
		<br />
		</p>
		<p><br />
		<span id="Ccert">CERTIFICADO</span><br />
		</p>
		<p>&nbsp;</p>
		<div align="justify">
		<p><div id="txt"><i>

		<b>A [Nome da empresa]</b> certifica que<b> ['.$args['client_name'].']</b>  participou do curso: <b> ['.$args['course_name'].']</b> no na turma ['.$args['class_id'].'], em 20 de Maio de 2020, [perfazendo a carga horária total de<b> 10 horas = se for o caso]</b>.

		</i></div></p>

		<br>
		<div align="right" style="float:right"><b>['.$args['city_name'].'], 20 de Maio de 2020.</b></div>
		</div>
		<p class="MsoNormal" align="right" style="text-align:right"><b style="mso-bidi-font-weight:
		normal"><span style="font-size:9.0pt;line-height:115%;font-family:"Cambria","serif"">

		</span></b>        <br></p>
		<table border="0" align="center">
		<tr>
		<td width="325" height="35" align="center"></td>

		</tr>
		<tr>
		<td width="325" align="center" height="35" valign="top"><b>[Assinatura do instrutor]</b></td>

		</tr>
		</table>
		<div align="center"><i>Confira a validade deste certificado em: <span style="color:blue"> www.site.com.br/validaCertificado?hash='.$this->masc('###-###',strtoupper('hash'),0,6).'</span></i></div>
		<h1></h1></div>
		<div style="page-break-dbefore: always;"></div>
		</center>';

		}
		$html .='</body>
		</html>';
		return $html;
	}
}

	
	$C = new Certificate();
	
	$dompdf = new Dompdf();
	$dompdf->loadHtml($C->html($ROWS),'UTF-8');


	$dompdf->setPaper('A4', 'landscape');


	$dompdf->render();


	$dompdf->stream();
	?>