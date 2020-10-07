<?php 

/*
09/08/2020
08/08/2020

*/
class Sql extends singularis{
	public  $table 		= '';
	private $sql  		= [];
	private $where 		= ''; 
	private $insert 	= 'insert into '; 
	private $type 		= '';	
	private $order_by   = '';
	public  $data 		= [];
	public  $dataType 	= '';
	public  $rowCount 	= 0;

	/*PDO*/
	public function pdo_create($args)
	{

		$create = new Create();	
		
		$create->ExeCreate($this->table,$this->data);
		$return = $create->getResult();
		if($return==null){
			/*$return = ["ERROR"=>true,'MESSAGE'=>ERROR];*/
			$return = 'no-created';
       // $create->Conn->rollBack();
		}

		return $return;
	}
	public function rowCount()
	{
		return $this->rowCount;
	}

	public function pdo_read($args)
	{

		$where    = [];
		$param    = [];
		$limit    = isset($args['limit'])?' limit '.$args['limit']:'';
		$order_by = isset($args['order_by'])?' ORDER BY '.$args['order_by'] : '';

		if(!empty($args['where'])){
			foreach ($args['where'] as $key => $v) {				
				$where[] = $v[0].$v[1].':'.$v[0].$key;
				$param[] = $v[0].$key.'='.$v[2];
			}

			$where = 'where '.implode(' and ',$where);
			$param = implode('&',$param);
		}else{
			$where = '';
			$param = '';
		}
		
		$read    = new Read();
		$read->ExeRead($this->table, $where.$order_by.$limit,$param,$args['columns']);
		$dado    = $read->getResult();
		$this->rowCount = $read->getRowCount();
		if($read->getRowCount()>0)
			$return = $dado;
		else
			$return  = [];		
		if($this->dataType!='')
			$dado = json_encode($dado);
		$this->sql = [];
		return $dado;
	}

	public function pdo_full_read($args)
	{
		//print_r($args);
		$l =['A','B','C','D','E','F','G'];
		$leftJoin = '';
		$select   = '*';
		$alias    = 'A';

		/*FAZ O TRATAMENTO DAS SELECTS*/
		if(is_array($args['columns'])){
			$select   = '';
			foreach ($args['columns'] as $k1 => $v1) {				
				foreach ($v1 as $k2 => $v2) {

					if($k2>0)
						$select .= ',';
					
					$select .=$l[$k1].'.'.$v2;					
				}
				
			}
		}

		if($args['columns'] != '*'){
			$select   = $args['columns'];
		}
		
		
		foreach($args['leftJoin'] as $k => $v) {

			$tb_alias = substr($v['tb_column'],0,1);
			
			$col = strpos($v['tb_ref'],'.') >= 0 ? $v['tb_ref'] : $alias.'.'.$v['tb_ref'];

			$tb_ref = $v['tb_ref'] == '' ? $alias.'.'.substr($v['tb_column'],2) : $col;
			
			/*SE EXIRTIR AS OUTRAS CONDIÇÕES DO LAFT JOIN*/
			$and = '';
			if(isset($v['andOr']))
				$and =" {$v['andOr']} {$v['col1']} = {$v['value']}"; 
			
			$leftJoin.= "left JOIN {$v['tb']} AS $tb_alias ON ({$v['tb_column']}={$tb_ref})$and"; 
			
		}

		$where     = [];
		$param     = [];
		$limit     = isset($args['limit'])?' limit '.$args['limit']:'';
		$order_by  = isset($args['order_by'])?' ORDER BY '.$args['order_by']:'';

		if(!empty($args['where'])){
			foreach ($args['where'] as $key => $v) {
				$p = str_replace('.', '', $v[0].$key); 
				$w = strpos($v[0], '.') > 0 ? $v[0].$v[1].':'.$p :  $alias.'.'.$v[0].$v[1].':'.$p;
				$where[] = $w;
				$param[] = $p.'='.$v[2];
			}

			$where = 'WHERE '.implode(' and ',$where);
			$param = implode('&',$param);
		}else{
			$where = '';
			$param = '';
		}

		//echo "SELECT {$select} FROM $this->table AS $alias $leftJoin $where $limit $order_by".$param;

		$read    = new Read();
		$read->FullRead("SELECT {$select} FROM $this->table AS $alias $leftJoin $where $limit $order_by",$param);
		$dado    = $read->getResult();
		$this->rowCount = $read->getRowCount();
		if($read->getRowCount()>0)
			$return = $dado;
		else
			$return  = [];		
		if($this->dataType!='')
			$dado = json_encode($dado);
		$this->sql = [];
		
		return $dado;
	}
	public function pdo_update($args)
	{
		
		$where = [];
		$param = [];
		
		if(!empty($args['where'])){
			foreach ($args['where'] as $key => $v) {
				
				$where[] = $v[0].$v[1].':'.$v[0].$key;
				$param[] = $v[0].$key.'='.$v[2];
				
			}

			$where = 'where '.implode(' and ',$where);			
			$param = implode('&',$param);

			$update = new Update;
			$update->ExeUpdate($this->table,$this->data,$where,$param);
			$return = $update->getRowCount();

			if($return>=1){
				$return = 'updated';
			}else{
				$return = 'no-updated';            
			}
			
		}else{
			$return = 'no-updated';
		}
		$this->sql['where'] = [];
		return $return;
	}
	public function pdo_delete_fake($args)
	{
		
		$where = [];
		$param = [];
		
		if(!empty($args['where'])){
			foreach ($args['where'] as $key => $v) {
				
				$where[] = $v[0].$v[1].':'.$v[0].$key;
				$param[] = $v[0].$key.'='.$v[2];
				
			}

			$where = 'where '.implode(' and ',$where);			
			$param = implode('&',$param);


			$update = new Update;
			$update->ExeUpdate($this->table,$this->data,$where,$param);
			$return = $update->getRowCount();

			if($return>=1){
				$return = 'deleted';
			}else{
				$return = 'no-deleted';            
			}
			$this->sql['where'] = [];
		}else{
			$return = 'no-deleted';
		}
		return $return;
	}
	public function pdo_delete($args)
	{
		
		$where = [];
		$param = [];
		
		if(!empty($args['where'])){
			$where    = [];
			$param    = [];
			$limit    = isset($args['limit'])?' limit '.$args['limit']:'';
			$order_by = isset($args['order_by'])?' ORDER BY '.$args['order_by']:'';

			if(!empty($args['where'])){
				foreach ($args['where'] as $key => $v) {				
					$where[] = $v[0].$v[1].':'.$v[0].$key;
					$param[] = $v[0].$key.'='.$v[2];
				}

				$where = 'where '.implode(' and ',$where);
				$param = implode('&',$param);
			}else{
				$where = '';
				$param = '';
			}

			$delete = new Delete;
			$delete->ExeDelete($this->table,$where,$param);
			$return = $delete->getRowCount();

			if($return>=1){
				$return = 'deleted';
			}else{
				$return = 'no-deleted1';            
			}
			$this->where = '';
		}else{
			$return = 'no-deleted2';
		}
		return $return;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getCol($col)
	{
		return isset($this->data[$col]) ? $this->data[$col] : '';
	}

	/*OBSOLETA A PARTIR DE 09/08/2020*/
	public function value($col,$mask = null)
	{ 
		if(isset($this->data[$col])){
			if(isset($mask)){
				$res = $this->sing_value($this->data[$col],$mask);
			}else{
				$res = 'value="'.$this->data[$col].'"';
			}			
		}
		if(!isset($this->data[$col]) && !isset($mask)){
			$res = 'value=""';
		}

		if(!isset($this->data[$col]) && isset($mask)){
			$res = $this->sing_value('',$mask);
		}


		return  $res;
		//return $this->data[$col];
	}
	public function valueN($col,$mask = null)
	{ 
		if(isset($this->data[$col])){
			if(isset($mask)){
				$res = $this->sing_value($this->data[$col],$mask).' name="'.$col.'" value="'.$this->data[$col].'"';
			}else{
				$res = 'value="'.$this->data[$col].'" name="'.$col.'"';
			}			
		}
		if(!isset($this->data[$col]) && !isset($mask)){
			$res = 'value="" name="'.$col.'"';
		}

		if(!isset($this->data[$col]) && isset($mask)){
			$res = $this->sing_value('',$mask).' name="'.$col.'"';
		}
		return  $res;		
	}
	
	public function column($col,$func='',$empty = null)
	{
		if(!isset($this->data[$col]) && $empty != null)
			$this->data[$col] = '';

		if(!isset($this->data[$col]))
			return null;
		
		if($func!='')
			$this->data[$col] = call_user_func_array([$this, $func], [$this->data[$col]]);		
	}


	/*CONSTRUÇÃO*/
	public function onlyLetter($str)
	{
		return preg_replace("/[^A-Za-z]/", "", $str);
	}
	/*PARA READ*/

	public function select($cols='*') {
		$this->sql['columns']= $cols==''?'*':$cols;
		$this->type = 'read';
		return $this;
	}
	public function from($table ='') {
		/*VERSÃO FUTURA VAI ACABAR ESTE METODO E COLOCAR, CASO PRECISE, NO METODO DE CHAMADA: INSERT, DELETE*/
		$this->table = $table;
		return $this;
	}

	public function leftJoin($tb,$tb_column,$tb_ref='',$andOr=null,$col1=null,$value=null) {		
		$this->type = 'full_read';
		$this->sql['leftJoin'][] = ['tb'=>$tb,'tb_column'=>$tb_column,'tb_ref'=>$tb_ref,'andOr'=>$andOr,'col1'=>$col1,'value'=>$value];
		return $this;
	}


	public function order_by($cols = '') {
		$this->sql['order_by']= $cols==''?'':$cols;		
		return $this;
	}
	public function limit($limit = '') {
		$this->sql['limit'] = $limit;		
		return $this;
	}


	/*PARA INSERT - SE TORNARÁ OBSOLETO*/
	public function insert($table = '') {		
		$this->type = 'create';
		return $this;
	}

	/*PARA INSERT*/
	/*public function create($table = '') {
		$this->sql['table'] = $table==''?$this->table:$table;
		$this->type = 'create';
		return $this;
	}
*/
	/*USO GERAL*/
	public function values($values = '') {		
		$this->data = $values==''?$this->data:$values;	

		return $this;
	}
	public function where($column ='',$operator='',$condition='') {
		if(is_array($column)){
			foreach ($column as $k => $v) {
				$this->sql['where'][] = [$v[0],$v[1],$v[2]];					
			}
		}else{
			$this->sql['where'][] = [$column,$operator,$condition];
		}
		return $this;
	}


	/*PARA UPDATE*/
	/*update json set */
	public function update($table = '') {
		$this->sql['table'] = $table==''?$this->table:$table;
		$this->type = 'update';
		return $this;
	}
	/*PARA DELETE*/
	/*update json set */
	public function delete($table = '') {
		$this->sql['table'] = $table==''?$this->table:$table;
		$this->type = 'delete';
		return $this;
	}
	

	public function execute($dataType='') {
		
		if($dataType!='')
			$this->dataType = $dataType;

		if($this->type == 'create'){
			return $this->pdo_create($this->sql);
		}

		if($this->type == 'read'){
			return $this->pdo_read($this->sql);
		}

		if($this->type == 'full_read'){
			return $this->pdo_full_read($this->sql);
		}

		if($this->type == 'update'){			
			return $this->pdo_update($this->sql);
		}

		if($this->type == 'delete'){
			return $this->pdo_delete($this->sql);
		}
		
	}
}