<?php          
class Subscriptions extends Sql{            
    public $subscription_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'subscriptions';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {

        $this->data = array_filter($array);        
        $this->column('client_id');
        $this->column('class_id','toInt');
        $this->column('created_user_id','toInt');
        $this->column('dt_created_at');
        $this->column('subscription_status_id');

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