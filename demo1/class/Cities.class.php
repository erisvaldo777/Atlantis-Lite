<?php          
class Cities extends Sql{            
    public $city_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'cities';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {
        
        $this->data = array_filter($array);
        $this->column('city_id','toInt');
        $this->column('city_name');
        
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