<?PHP 
/*SINGULARIS

VERSAO: 11-10-2020
acrescentando o paramentro sing_pad(...)

VERSAO: 15-09-2020
Alterado método value_select($col,$value,$valueRef = NULL)
acrescentando o paramentro $valueRef

VERSAO: 01-09-2020
Adicionado o método toChar(...)

VERSAO: 29-08-2020
Adicionado o método trim(...)

VERSAO: 17-08-2020
Adicionado o método toUpper(...)

VERSAO: 15-08-2020
Adicionado o método md5(...)

VERSAO: 09-07-2020
VERSAO: 17-07-2020
VERSAO: 19-07-2020
VERSAO: 25-07-2020

*/
class singularis{
	public $fileName;
	/*configs de path upload de imagens*/

	public function toInt($str) {
		if($str=="null"||$str==null||$str==""){
			return null;
		}else{
			return preg_replace("/[^0-9]/", "", $str);
		}
	}
	public function toChar($str) {
		if($str=="null"||$str==null||$str==""){
			return null;
		}else{
			return preg_replace("/[^a-zA-Z]/", "", $str);
		}
	}
	public function toFloat($str){
		$str = str_replace(',', '.',$str);
		return floatval($str);
	}
	
	public function toUpper($str){		
		return mb_strtoupper($str);
	}

	public function trim($str){		
		return trim($str);
	}
	public function sing_value($value,$mask='')
	{
		return $this->inputmask($mask);
	}
	public function inputmask($v='')
	{
		$arr['dmy'] 		= "99/99/9999";
		$arr['dmyhi'] 		= "99/99/9999 99:99";
		$arr['dmyhis'] 		= "99/99/9999 99:99:00";
		$arr['cel'] 		= "(99) 9 9999-9999";
		$arr['whatsapp'] 	= "(99) 9 9999-9999";
		$arr['contact'] 	= "(99) 9 9999-9999";
		$arr['cep'] 		= "99999-999";
		$arr['zip_code'] 	= "99999-999";
		$arr['H:m'] 	    = "99:99";
		$arr['decimal'] 	= "[9][9][9][9].99";
		$arr['decimal3'] 	= "[9].99";
		$arr['decimal4'] 	= "[9][9].99";
		$arr['decimal5'] 	= "[9][9][9].99";
		$arr['decimal6'] 	= "[9][9][9][9].99";
		$arr['int'] 		= "9', 'repeat' : 6, 'greedy' : 'false";


		return 'data-mask data-inputmask="\'mask\':\''.$arr[$v].'\'"';
	}
	public function value_select($col,$value,$valueRef = NULL)
	{
		
		if($this->getCol($col) == $value || ($this->getCol($col) == '' && $valueRef != null && $value == $valueRef))
			$res = 'selected value="'.$value.'"';
		else
			$res = 'value="'.$value.'"';
		return $res;
	}
	public function md5($str)
	{
		return md5($str);
	}

	public function sing_pad($s,$i,$caract,$lr=null)
	{
		return str_pad($s, $i , $caract,$lr); 
	}
	/*UPLOAD upload('$_FILE',[''])*/
	public function upload($file,$path,$extension,$optionalName='')
	{	
		
		$ext            = !empty($extension)?$extension:['pdf','doc','docx','xls','xlsx','txt','jpg','png'];
		
		$nomeOriginal   = $file["name"]; 
		$nomeExp        = explode('.', $nomeOriginal);                
		$tamanho        = $file["size"];
		
		$filePermitido	= '';

		for($i=0;$i<=count($ext)-1;$i++){ 

			if($ext[$i] == $nomeExp[1])
			{
				$filePermitido=true;
			}
		}
		if($filePermitido==false)
		{
			echo "Extensão de arquivo não permitido!!".$ext;
			exit;
		}
		/*AQUI PEGA O NOME ORIGINAL E RENOMEIA COLOCANDO A DATA ATUAL PARA EVITAR DUPLICIDADE DE CÓDIGO*/
		$this->fileName=$this->url($optionalName==''?md5($nomeOriginal):$optionalName).'.'.$nomeExp[1];

		if(move_uploaded_file($file['tmp_name'], $path.$this->fileName)){	            
			$res = ['filename'=>$this->fileName,'result'=>true];
		}else{
			$res=['filename'=>$this->fileName,'result'=>false];
		}   
		return $res; 
	}

	public function permissao_acesso($array)
	{

		foreach ($_SESSION['SYS_CARGOS'] as $key => $value) {       


			if($value['id_funcao']==8){
				$r=true;
			}else if($array['id_curso']==$value['id_curso']){
				$r=true;
			}else if(count($array)==2&&$array['id_curso']==$value['id_curso']&&$array['id_unidade']==$value['id_unidade']){
				$r=true;
			}else{
				$r=false;
			}

		}
		return $r; 
	}
	public function mask($val, $mask)
	{
/*
echo mask($cnpj,'##.###.###/####-##');
echo mask($cpf,'###.###.###-##');
echo mask($cep,'#####-###');
echo mask($data,'##/##/####');
*/
$mask = $mask=='cel'?'(##) # ####-####':$mask;

$maskared = '';
$k = 0;
for($i = 0; $i<=strlen($mask)-1; $i++)
{
	if($mask[$i] == '#')
	{
		if(isset($val[$k]))
			$maskared .= $val[$k++];
	}else{
		if(isset($mask[$i]))
			$maskared .= $mask[$i];
	}
}

return $maskared;
}
/*USAR toDate NO LUGAR DESTA, SE TORNOU OBSOLETA EM 09/09/2020*/
public function data($d,$new_format=null){/*0000-00-00 - AAAA-MM-DD*/
	/*Se tem horas*/
	$temHoras = strpos($d,':') > 0 ? true : false;
	/*Verifica se o formato está no padrão BR*/
	$dataBr   = strpos($d,'/') > 0 ? true : false;
		//12/12/1212 12:12:12

	if($d=='0000-00-00'){
		$newD=''; 
	}else if($d==''||$d==null){
		$newD='0000-00-00';
	}else if($d!=''&&$dataBr==true&&$temHoras==false){
		$exp=explode(' ',$d);
		$expD=explode('/',$exp[0]);

		$newD=$expD[2].'-'.$expD[1].'-'.$expD[0];
		
	}else if($d!=''&&$dataBr==true&&$temHoras==true){
		$exp=explode(' ',$d);
		$expD=explode('/',$exp[0]);

		$newD=$expD[2].'-'.$expD[1].'-'.$expD[0].' '.$exp[1];
	}else if($d != '' && $dataBr == false && $temHoras == true){
		$exp=explode(' ',$d);
		$expD=explode('-',$exp[0]);

		$newD=$expD[2].'/'.$expD[1].'/'.$expD[0].' '.$exp[1];
		
	}else{
		$newD=strpos($d,'-')>2?date('d/m/Y',strtotime($d)):date('Y-m-d',strtotime(str_replace('/', '-', $d)));
	}

	return $newD;
}
public function toDate($d,$new_format=null){/*0000-00-00 - AAAA-MM-DD*/
	/*Se tem horas*/
	$temHoras = strpos($d,':') > 0 ? true : false;
	/*Verifica se o formato está no padrão BR*/
	$dataBr   = strpos($d,'/') > 0 ? true : false;
		//12/12/1212 12:12:12

	if($d=='0000-00-00'){
		$newD=''; 
	}else if($d==''||$d==null){
		$newD='0000-00-00';
	}else if($d!=''&&$dataBr==true&&$temHoras==false){
		$exp=explode(' ',$d);
		$expD=explode('/',$exp[0]);

		$newD=$expD[2].'-'.$expD[1].'-'.$expD[0];

	}else if($d!=''&&$dataBr==true&&$temHoras==true){
		$exp=explode(' ',$d);
		$expD=explode('/',$exp[0]);

		$newD=$expD[2].'-'.$expD[1].'-'.$expD[0].' '.$exp[1];
	}else if($d != '' && $dataBr == false && $temHoras == true){
		$exp=explode(' ',$d);
		$expD=explode('-',$exp[0]);

		$newD=$expD[2].'/'.$expD[1].'/'.$expD[0].' '.$exp[1];

	}else{
		$newD=strpos($d,'-')>2?date('d/m/Y',strtotime($d)):date('Y-m-d',strtotime(str_replace('/', '-', $d)));
	}

	return $newD;
}

public function remove_acento($str)
{
	return addslashes(strtolower(strtr(utf8_decode($str), "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ", "aaaaeeiooouucAAAAEEIOOOUUC"))); 	

}

public function url($string){
	return strtolower(preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/","/(ç)/","/(Ç)/","/( )/","/(,)/","/(_)/"),explode("|","a|A|e|E|i|I|o|O|u|U|n|N|c|c|-||-"),str_replace('/','',$string)));
}

public function now($format='')
{
	if($format=='BR'){
		$date=date('d/m/Y');

	}else if ($format=='BR+') {
		$date=date('d/m/Y H:i:s');
	}else if ($format=='BR+') {
		$date=date('Y-m-d H:i:s');
	}else if ($format=='USA') {
		$date=date('Y-m-d');
	}else{
		$date=date('Y-m-d H:i:s');
	}
	return $date;
}

public function moeda($value,$sinal=false)
	{//1.000,00

		$v=substr($value,-3,1)==','?str_replace(',','.', str_replace('.','', $value)):number_format($value,2,',','.');

		

		return $sinal==true?'R$'.$v:$v;
	}

	public function permissoes($tela,$parametro_permissoes){
		$exp=explode(' ',$_COOKIE['permissoes']);


		foreach ($exp as $key => $value) {

			$page=explode('-', $value);

			if($page[0]==$tela){
				
				if(strpos($page[1], $parametro_permissoes)!==false||$page[1]=='cud'||$page[1]=='crud'){
					return true;
					break;
				}
			}
		}

		
		echo '<div style="height:100%;width:100%;background:#f3f3f3" align="center"><img src="../../imagens/site/error-404.jpg"><h2>Você Acessou uma página protegida, e não tem permissão para isso!</h2></div>';
		
	}
	public function redirecionarUrl($parametro){
		return '<script>window.location.href="ALEM.PHP"</script>';
	}


	/* OBSOLETA DESDE 20-05-2020*/
	public function soNumeros($str) {
		return preg_replace("/[^0-9]/", "", $str);
	}
}