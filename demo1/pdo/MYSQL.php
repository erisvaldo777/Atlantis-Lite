<?php 
class Sql extends singularis{
	public  $table 		= '';
	private $sql  		= [];
	private $where 		= ''; 
	private $insert 	= 'insert into '; 
	private $type 		= '';
	private $order_by   = '';
	public  $data 		= [];

	/*PDO*/
	public function pdo_create($args)
	{

		$create = new Create();

		$create->ExeCreate($args['table'],$this->data);
		$return = $create->getResult();
		if($return==null){
			$return = ["ERROR"=>true,'MESSAGE'=>ERROR];
       // $create->Conn->rollBack();
		}

		return $return;
	}
	public function pdo_read($args)
	{

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
		
		$read    = new Read();
		$read->ExeRead($this->table, $where.$order_by.$limit,$param,$args['columns']);
		$dado    = $read->getResult();

		if($read->getRowCount()>0)
			$return = $dado;
		else
			$return=[];		
		$this->where = [];
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
			$this->where = [];
		}else{
			$return = 'no-updated';
		}
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
			$this->where = [];
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
			foreach ($args['where'] as $key => $v) {
				
				$where[] = $v[0].$v[1].':'.$v[0].$key;
				$param[] = $v[0].$key.'='.$v[2];
				
			}

			$where = 'where '.implode(' and ',$where);
			$param = implode('&',$param);

			$delete = new Delete;
			$delete->ExeDelete($this->table,$where,$param);
			$return = $delete->getRowCount();

			if($return>=1){
				$return = 'deleted';
			}else{
				$return = 'no-deleted';            
			}
			$this->where = '';
		}else{
			$return = 'no-deleted';
		}
		return $return;
	}

	public function getData()
	{
		return $this->data;
	}
	public function column($col,$func='')
	{
		
		if(!isset($this->data[$col])){
			return null;
		}
		if($func!=''){
			$this->data[$col] = call_user_func_array([$this, $func], [$this->data[$col]]);
			//echo $this->toInt($this->data[$col]);
		}
	}

	/*CONSTRUÇÃO*/
	/*PARA READ*/

	public function select($cols='*') {
		$this->sql['columns']= $cols==''?'*':$cols;
		$this->type = 'read';
		return $this;
	}
	public function from($table ='') {
		$this->sql['table'] = $table==''?$this->table:$table;		
		return $this;
	}


	public function order_by($cols = '') {
		$this->sql['order_by']= $cols==''?'':$cols;		
		return $this;
	}
	public function limit($limit = '') {
		$this->sql['limit'] = $limit;
		$this->type = 'read';
		return $this;
	}


	/*PARA INSERT*/
	public function insert($table = '') {
		$this->sql['table'] = $table==''?$this->table:$table;
		$this->type = 'create';
		return $this;
	}

	/*USO GERAL*/
	public function values($values = '') {		
		$this->data = $values==''?$this->data:$values;		
		return $this;
	}
	public function where($column ='',$operator='',$condition='') {
		$this->sql['where'][] = [$column,$operator,$condition];					
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
	

	public function execute() {
		if($this->type == 'create'){
			return $this->pdo_create($this->sql);
		}

		if($this->type == 'read'){
			return $this->pdo_read($this->sql);
		}

		if($this->type == 'update'){			
			return $this->pdo_update($this->sql);
		}

		if($this->type == 'delete'){
			return $this->pdo_delete_fake($this->sql);
		}
		
	}
}