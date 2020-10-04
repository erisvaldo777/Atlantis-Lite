<?php          
class Rules extends Sql{            
    public $rule_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'rules';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {
        
        $this->data = array_filter($array);
        $this->column('screen_id','toInt');
        $this->column('user_id','toInt');
        $this->column('c','toInt');
        $this->column('u','toInt');
        $this->column('d','toInt');
        
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