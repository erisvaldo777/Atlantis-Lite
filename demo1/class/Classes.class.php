<?php          
class Classes extends Sql{            
    public $class_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'classes';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {
        
        $this->data = array_filter($array);
        $this->column('class_name');
        $this->column('course_id','toInt');
        $this->column('dt_start',"toDate");
        $this->column('dt_end',"toDate");
        $this->column('city_id','toInt');
        $this->column('class_status_id');
        
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