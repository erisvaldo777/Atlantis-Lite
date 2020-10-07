<?php          
class Prospection extends Sql{            
    public $prospection_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'prospection';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {
        
        $this->data = array_filter($array);
        $this->column('prospection');
        $this->column('client_id');
        $this->column('percentage','toInt');
        $this->column('prospection_status_id');
        
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