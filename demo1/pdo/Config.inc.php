<?php
session_start();

$ERROR = NULL;

$subdominio = explode('.', $_SERVER['HTTP_HOST'])[0];

if($subdominio != 'Sdev'){
/*define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '034479');
define('DBSA', 'webdelivery');*/
define('HOST', 'mysql.mazullo.com.br');
define('USER', 'mazullo');
define('PASS', 'Metal777');
define('DBSA', 'mazullo');   
}else{
/*define('HOST', 'mysql.webdelivery.online');
define('USER', 'webdelivery');
define('PASS', 'Metal777');
define('DBSA', 'webdelivery');    
*/
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '034479');
define('DBSA', 'mazullo_prospectos');
}

// CONFIGURAÇÕES DO SITE EM PRODUÇÃO ##################


// AUTO LOAD DE CLASSES  ##################
//function __autoload($Class) {
function autoload_reg($Class) {

    $cDir = ['Conn']; //cDir = CONFIGURAÇÃO DE DIRETÓRIO QUE VAI RECEBER O NOME DAS SUBPASTAS
    $iDir = null;       // iDir = INCLUDE DIRETÓRIO. PARA VERIFICAS SE A INCLUSÃO OCORREU
    
    // NAVEGA/PERCORRE O ARRAY $cDir[] QUE SÃO AS PASTAS DENTRO DA
    // APP E INCLUI O ARQUIVO SE EXISTIR.
    //OBS: "__DIR__" PEGA O DIRETÓRIO ATUAL; "is_dir()" verifica se é um diretório
    foreach ($cDir as $dirName):
        
        //VERIFICO SE O ARQUIVO EXISTE E SE NÃO É UM DIRETÓRIO
        if (!$iDir && file_exists(__DIR__ . "/{$dirName}/{$Class}.class.php") && !is_dir(__DIR__ . "/{$dirName}/{$Class}.class.php")):
            include_once (__DIR__ . "/{$dirName}/{$Class}.class.php");
            $iDir = true;
        endif;
    endforeach;

    if (!$iDir):
        trigger_error("Não foi possível incluir {$Class}.class.php", E_USER_ERROR);
        die;
    endif;
}
spl_autoload_register("autoload_reg");
// TRATAMENTO DE ERROS  ##################
//  CSS constantes :: Mensagens de Erro
define('WS_ACCEPT', 'accept');
define('WS_INFOR', 'infor');
define('WS_ALERT', 'alert');
define('WS_ERROR', 'error');

//WSErro :: Exibe erros lançados :: Front
function WSErro($ErrMsg, $ErrNo, $ErrDie = NULL) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));

    $ERROR = $ErrMsg;

    if ($ErrDie):
        die;
    endif;
}

//PHPErro :: Persolaliza o gatilho do PHP
function PHPErro($ErrNo, $ErrMsg, $ErrFile, $ErrLine) {
    $CssClass = ($ErrNo == E_USER_NOTICE ? WS_INFOR : ($ErrNo == E_USER_WARNING ? WS_ALERT : ($ErrNo == E_USER_ERROR ? WS_ERROR : $ErrNo)));
    
    echo "<b>Erro na linha: {$ErrLine} ::</b> {$ErrMsg} - {$ErrFile}<br>";
    

    if ($ErrNo == E_USER_ERROR):
        die;
    endif;
}

set_error_handler('PHPErro');
