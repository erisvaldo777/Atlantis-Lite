<?php          
 class History extends Sql{            
    public $history_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'history';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {

        $this->data = array_filter($array);
        $this->column('user_id','toInt');
        $this->data['client_id'] = $this->getClientId();
        $this->column('created_at');
        $this->column('dt_contact');
        $this->column('dt_next_contact');
        $this->column('description');

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