<?php

/**
 * Conn.class [ CONEXÃO ]
 * Classe abstrata de conexão. Padrão SingleTon.
 * Retorna um obejto pelo método estático getConn();
 * 
 * @copyright (c) 2017, Josivan Soares JSF Tecnologia Ltda.
 */
class Conn {

    private static $Host = HOST;
    private static $User = USER;
    private static $Pass = PASS;
    private static $Dbsa = DBSA;

    /** @var PDO */
    private static $Connect = null;

    /**
     * Conecta com o banco de dados com o pattern singleton.
     * Retorna um objeto PDO!
     */
    private static function beginTransaction()
    {
        $this->Connect->beginTransaction();
    }

    private static function Conectar() {
        try {
            if (self::$Connect == null):
                $dsn = 'mysql:host=' . self::$Host . ';dbname=' . self::$Dbsa;
                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];// TEM QUE SER PASSADAS COMO ARRAY
                self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);
            endif;
        } catch (PDOException $e) {
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            die;
        }
        // COMO IREMOS TRABALHAR COM LANÇAMENTO DE EXCESSÕES PRECISAMOS DO ERRMOD_EXCEPTION
        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }

    /** Retorna um objeto PDO Singleton Pattern */
    public function getConn() {
        return self::Conectar();
    }

    
    
    // O PADRÃO SINGLETON É SIMPLESMENTE TER APENAS UM ONJETO INSTANCIADO NA 
    // MEMÓRIA DO SERVIDOR, OU SEJA, ESSA CLASSE VAI SER UMA SINGLETON
    // POIS SÓ TERÁ UMA CONEXÃO COM O BANCO.
}
