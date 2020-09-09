<?php          
class Status extends Sql{            
    public $status_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'status';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {
        
        $this->data = array_filter($array);
        $this->column('status_id');
        $this->column('status_name');
        $this->column('status_desc');
        $this->column('tabela');
        
    }

    public function getClientId()
    {
        return $this->client_id;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
    }

    public function setCol($cols)
    {
        $this->cols = $cols;
    }

}