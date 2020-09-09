<?php

/**
 * <b>Create [Classe]</b>
 * Classe responsável por cadastros genéricos no banco de dados
 * @copyright (c) 2019, Josivan Soares JSF TECNOLOGIA 
 */
class Create extends Conn{ // A Conn será a Classe pai de todas as classes que irão manipular o banco

    private $Tabela ;
    private $Dados;
    private $Result;
    
    //preciso dos metodos da pdo, então vou criar um metodo responsável pelo pdo statements.
    // pela query preparada da PDO
    
    /** @var PDOStatement*/
    private $Create;
    
    
    //atributo para pegar a conexão da PDO. A classe da PDO.
    
    /** @var PDO */
    private $Conn;
    
    /** <b>ExeCreat: </b>Executa um cadastro simplificado no banco de dados utilizando prepared statements.
     * Basta informar o nome da tabela e um array atribuitivo com o nome da coluna e valor.
     * 
     * @param STRING $Tabela = Informa o nome da tabela no banco.
     * @param ARRAY $Dados = Informe um array atribuitivo. ( Nome da coluna => valor).
     */
    public function ExeCreate($Tabela, array $Dados) {
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;
        $this->getSyntax();
        $this->Execute();
    }


    /**@return BOOLEAN retorna false caso não seja inserido na tabela..*/
    public function getResult() {
        return $this->Result;
    }
    

    /*INSERE VARIOS VALORES DE UMA VEZ NO BD*/
    public function pdoMultiInsert($tableName, $data){

       $this->Tabela = (string) $tableName;
       $this->Dados = $data;
        //Will contain SQL snippets.
       $rowsSQL = array();

        //Will contain the values that we need to bind.
       $toBind = array();

        //Get a list of column names to use in the SQL statement.
       $columnNames = array_keys($data[0]);

        //Loop through our $data array.
       foreach($data as $arrayIndex => $row){
        $toBind=[];
        $params = array();
        foreach($row as $columnName => $columnValue){
            $param = ":" . $columnName . $arrayIndex;
            $params[] = $param;
            $toBind[$param] = $columnValue; 
        }
        
        

        $rowsSQL = "(" . implode(", ", $params) . ")";

        $this->Dados=$toBind;


        $this->Create = "INSERT INTO $tableName (" . implode(", ", $columnNames) . ") VALUES " . $rowsSQL;

        $this->execute();
     $return[] = $this->getResult();

    }

        $this->Result = $return;
}

    /**
     * ************************************
     * ********* PRIVATE METHODS **********
     * ************************************
     */
    
    private function Connect() {
        $this->Conn = parent::getConn(); //pegando a pdo
        $this->Conn->beginTransaction();
        $this->Create = $this->Conn->prepare($this->Create); // tenho que criar a query dentro do atributo crate
    }
    
    // aqui faço a query dinâmica, onde os nomes das colunas serão os links
    private function getSyntax() {
        $Fileds = implode(', ', array_keys($this->Dados)); // pego apenas os índices do array
        $Places = ':'. implode(', :', array_keys($this->Dados)); //faço a substituição colocando os : pontos
        $this->Create = "INSERT INTO {$this->Tabela} ({$Fileds}) VALUES ({$Places})";

    }
    
    //primeira coisa: executar a conexão e preparar a query::: o Connect ta fazendo isso.
    
    private function Execute() {
        $this->Connect();        
        
        try {

            $this->Create->execute($this->Dados);
            $this->Result = $this->Conn->lastInsertId();
            $this->Conn->commit();
        } catch (PDOException $e) {
            $this->Result = null;
            WSErro("Class Create::{$e->getMessage()}", $e->getCode());
        }
        $this->Conn = null;
    }
    
}
