<?php 
/**
 * 
 */
class Permissions
{
	private $user_id; 
	private $c;
	function __construct($user_id)
	{
		$this->user_id = $user_id;
	}
	
	public function check($screen,$action)
	{
		global $CLASS;

		$RES = $CLASS->select()->from('rules')->where('user_id','=',$this->user_id)->where('screen_id','=',$screen)->limit('1')->execute();
		
		if($CLASS->rowCount() == 0){
			//header('location:../../admin/danied-access');
			//exit;
		}else{
			if($action == 'update' || $action == 'show' and $RES[0]['u'] == 0){
				echo 'não pode fazer atualização de cadastro';
				exit;
			}
			if($action == 'create' and $RES[0]['c'] == 0){
				echo 'Não pode criar cadastro';
				exit;
			}
			if($action == 'delete' and $RES[0]['d'] == 0){
				echo 'Permissão negada';
				exit;
			}
		}
		//exit;
	}
}
$RULES = new Permissions($_SESSION['USER_ID']);
