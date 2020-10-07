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
        $this->data['user_id'] = $_SESSION['USER_ID'];
        $this->column('client_id');
        $this->column('created_at');
        $this->column('dt_contact','toDate');
        $this->column('history_status_id');
        $this->column('dt_next_contact','toDate');
        $this->column('b_notification');
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