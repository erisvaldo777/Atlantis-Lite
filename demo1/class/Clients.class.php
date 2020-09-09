<?php          
class Clients extends Sql{            
    public $user_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'clients';
        $this->setUserId($sessao);
    }           

    public function setData($array)
    {

        $this->data = array_filter($array);
        $this->data['created_by_user_id'] = $this->getUserId(); 
        $this->column('client_name');
        $this->column('email');
        $this->column('cell_phone');
        $this->column('whatsapp_number','toInt');
        $this->column('job');
        $this->column('workspace');
        $this->column('address');
        $this->column('zip_code','toInt');
        $this->column('city_id','toInt');
        $this->column('city_name');
        $this->column('state_id','toInt');
        $this->column('state_abbr');
        $this->column('indicated_by');
        $this->column('client_status_id');

    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    public function setCol($cols)
    {
        $this->cols = $cols;
    }

}