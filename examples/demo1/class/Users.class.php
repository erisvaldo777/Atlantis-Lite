<?php          
class Users extends Sql{            
    public $user_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'users';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {
        
        $this->data = array_filter($array);        
        $this->column('src');
        $this->column('user_name');
        $this->column('email');
        $this->column('contact');
        $this->column('whatsapp',"toInt");
        $this->column('password');
        $this->column('user_status_id');
        $this->column('user_type_id','toInt');
        $this->column('city_id','toInt');
        
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