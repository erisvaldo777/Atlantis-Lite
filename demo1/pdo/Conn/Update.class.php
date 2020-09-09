<?php

/**
 * <b>Update [Classe]</b>
 * Classe responsável por atualizações genéricos no banco de dados
 * @copyright (c) 2019, Josivan Soares JSF TECNOLOGIA 
 */
class Update extends Conn { // A Conn será a Classe pai de todas as classes que irão manipular o banco

    private $Tabela;
    private $Dados;
    private $Termos;
    private $Places;
    private $Result;
    //preciso dos metodos da pdo, então vou criar um metodo responsável pelo pdo statements.
    // pela query preparada da PDO

    /** @var PDOStatement */
    private $Update;

    //atributo para pegar a conexão da PDO. A classe da PDO.

    /** @var PDO */
    private $Conn;

    public function ExeUpdate($Tabela, array $Dados, $Termos, $ParseString) {
        $this->Tabela = (string) $Tabela;
        $this->Dados = $Dados;
        $this->Termos = $Termos;

        parse_str($ParseString, $this->Places);
        $this->getSyntax();
        $this->Execute();
    }

    /** @return BOOLEAN retorna false caso não seja inserido na tabela.. */
    public function getResult() {
        return $this->Result;
    }

    //pega a quantidade de linhas da pesquisa
    public function getRowCount() {
        return $this->Update->rowCount();
    }

    public function setPlaces($ParseString) {
        //reseta caso eu já tenha chamado uma query em outro método
        parse_str($ParseString, $this->Places); // add no atributo a ParseString com o valor int passa pelo parâmetro
        $this->getSyntax();
        $this->Execute();
    }

    /**
     * ************************************
     * ********* PRIVATE METHODS **********
     * ************************************
     */
    //obtémo PDO e Prepara a Query
    private function Connect() {
        $this->Conn = parent::getConn($this->Update); // rescrevo o atributo com a query preparada
        $this->Update = $this->Conn->prepare($this->Update);
    }

    // aqui faço a query dinâmica, onde os nomes das colunas serão os links
    // Cria a Syntaxe da Query para Prepared Statements
    private function getSyntax() {
        foreach ($this->Dados as $Key => $Value):
            $Places[] = $Key . ' = :' . $Key;
        endforeach;

        $Places = implode(', ', $Places);
        $this->Update = "UPDATE {$this->Tabela} SET {$Places} {$this->Termos}";
    }

    //primeira coisa: executar a conexão e preparar a query::: o Connect ta fazendo isso.
    // Obtém a conexão e a Syntax, executa a query.
    private function Execute() {
        $this->Connect();
        try {
           $this->Update->execute(array_merge($this->Dados, $this->Places));
           $this->Result = true;
        } catch (PDOException $e) {
            $this->Result = false;
            //WSErro("<b>Erro ao Ler: </b>: {$e->getMessage()}", $e->getCode());
        }
                $this->Conn = null;

    }     
}
