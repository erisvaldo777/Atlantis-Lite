<?php          
class Users_types extends Sql{            
    public $user_type_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'users_types';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {
        
        $this->data = array_filter($array);
        $this->column('user_type_id','toInt');
        $this->column('user_type');
        
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