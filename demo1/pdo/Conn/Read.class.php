<?php

/**
 * <b>Read [Classe]</b>
 * Classe responsável por leituras genéricos no banco de dados
 * @copyright (c) 2019, Josivan Soares JSF TECNOLOGIA 
 */
class Read extends Conn { // A Conn será a Classe pai de todas as classes que irão manipular o banco

    private $Select; // Onde será armazenada a query
    private $Places; // Onde será armazenada a ParseString
    private $Result; // Onde será Aramazenado o resultado
    //preciso dos metodos da pdo, então vou criar um metodo responsável pelo pdo statements.
    // pela query preparada da PDO

    /** @var PDOStatement */
    private $Read;

    //atributo para pegar a conexão da PDO. A classe da PDO.

    /** @var PDO */
    private $Conn;


    public function ExeRead($Tabela, $Termos='', $ParseString = null,$Colunas='*') {
        if (!empty($ParseString)):
            //pega a string passada na $PaseString e tranforma em um array com chave e valor.
            parse_str($ParseString, $this->Places);
        endif;

        $this->Select = "SELECT {$Colunas} FROM {$Tabela} {$Termos}";
        $this->Execute();
    }

    /** @return BOOLEAN retorna false caso não seja inserido na tabela.. */
    public function getResult() {
        return $this->Result;
    }

    //pega a quantidade de linhas da pesquisa
    public function getRowCount() {
        return $this->Read->rowCount();
    }

    // passar toda a query manualmente, pois pode ter funções como o INNER JOIN
    public function FullRead($Query, $ParseString = NULL) {
        $this->Select = (string) $Query; // add no atributo a string da query passada no parâmetro
        if (!empty($ParseString)):
            //pega a string passada na $PaseString e tranforma em um array com chave e valor.
            parse_str($ParseString, $this->Places); // add no atributo a ParseString com o valor int passa pelo parâmetro
        endif;
        $this->Execute();
    }

    public function setPlaces($ParseString) {
        //reseta caso eu já tenha chamado uma query em outro método
        parse_str($ParseString, $this->Places); // add no atributo a ParseString com o valor int passa pelo parâmetro
        $this->Execute();
    }

    /**
     * ************************************
     * ********* PRIVATE METHODS **********
     * ************************************
     */
    //obtémo PDO e Prepara a Query
    private function Connect() {
        $this->Conn = parent::getConn(); // pego a PDO
        $this->Read = $this->Conn->prepare($this->Select); // preparo a query
        $this->Read->setFetchMode(PDO::FETCH_ASSOC); // pego no formato de Array da PDO.
    }

    // aqui faço a query dinâmica, onde os nomes das colunas serão os links
    // Cria a Syntaxe da Query para Prepared Statements
    private function getSyntax() {
        if($this->Places): // se existir places
        foreach ($this->Places as $Vinculo => $Valor):
            if ($Vinculo == 'limit' || $Vinculo == 'offset'): // limit e offset são palavras reservadas da parseString
                $Valor = (int) $Valor;
            endif;
            // faço os bindValue
            $this->Read->bindValue(":{$Vinculo}", $Valor, (is_int($Valor) ? PDO::PARAM_INT : PDO::PARAM_STR));
        endforeach;
        endif;
    }

    //primeira coisa: executar a conexão e preparar a query::: o Connect ta fazendo isso.
    // Obtém a conexão e a Syntax, executa a query.
    private function Execute() {
        $this->Connect();
        try {
            $this->getSyntax();
            $this->Read->execute();
            $this->Result = $this->Read->fetchAll();
        } catch (PDOException $e) {
            $this->Result = null;
            WSErro("<b>Erro ao Ler: </b>{$e->getMessage()}", $e->getCode());
        }
        $this->Conn = null;
    }

}
