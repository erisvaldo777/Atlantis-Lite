<?php          
class Courses extends Sql{            
    public $course_id;
    public $cols;
    function __construct($sessao=0) {                   
        $this->table = 'courses';
        $this->setClientId($sessao);
    }           

    public function setData($array)
    {
        
        $this->data = array_filter($array);
        $this->column('course_name');
        $this->column('course_abbr');
        $this->column('course_status_id');
        
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