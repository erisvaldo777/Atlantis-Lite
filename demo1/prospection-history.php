<?php
require_once 'pdo/Config.inc.php';
require_once '../cdn/php/singularis.php'; 
require_once '../cdn/php/Sql.class.php'; 
require_once 'class/History.class.php'; 


$error    = '';
$return   = '';

$method   =  $_SERVER['REQUEST_METHOD'];
$in       =  ${'_'.$method};

$action = isset($_POST['action']) ? $in['action'] : $_GET['action'];

$C         =  new History($_SESSION['USER_ID']);



$ROWS_PROSPECTION = $C->select()->from('prospection')->where('prospection_status_id','=',1)->execute();
$ROWS_STATUS = $C->select()->from('status')->where('class','=',3)->execute();

$C->table = 'history';
if($method=='GET' && $action == 'update' ){         
    $ROWS = $C->select()->where('history_id','=',$_GET["id"])->limit('1')->execute()[0];    
    $C->setData($ROWS);

}else if($action == 'list'){    
    $ROWS = $C->select()
    ->leftJoin('prospection','B.prospection_id')
    ->leftJoin('clients','C.client_id')
    ->where('b_notification','=',1)->order_by('dt_next_contact asc')->execute();    
}else if($action == 'create' && $method == 'GET'){
    $ROWS = [];        
}else{

    if(isset($in)){

        $C->setData($in);
        
        
        
        if($action == 'create')
            $return = $C->insert()->execute();
        if($action == 'update')
            $return = $C->update()->where('history_id','=',$_GET['id'])->execute();        
        if($action == 'delete')
            $return = $C->delete($_GET['history_id']);  

        if($return > 0 || $return == 'updated' || $return == 'deleted'){
            header('location:///history/list');
            exit;
        };

        if($return == 'no-created'||$return == 'no-updated'||$return == 'no-deleted'){
            $error = 'Não foi possivel fazer alteração!';            
        }     
    }
}

if($method == 'POST' && ($action == 'show' || $action == 'update')){
    $ROWS = $_POST;
    $C->setData($ROWS);    
}

require_once("head.php"); ?>
<body>
    <div class="wrapper">
        <!-- HEADER -->
        <?php require_once("header.php"); ?>
        <!-- END HEADER -->

        <!-- Sidebar -->
        <?php require_once("side-bar.php"); ?>
        <!-- END Sidebar -->
        
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    <!-- PAGE HEADER -->
                    <div class="page-header">
                        <h4 class="page-title">Atividade</h4>
                        <ul class="breadcrumbs">
                            <li class="nav-home">
                                <a href="#">
                                    <i class="flaticon-home"></i>
                                </a>
                            </li>
                            <?PHP 
                            foreach (explode("/",$_SERVER["REQUEST_URI"]) as $key => $value) {
                                if($key > 1){
                                    echo '
                                    <li class="separator">
                                    <i class="flaticon-right-arrow"></i>
                                    </li>
                                    <li class="nav-item">
                                    <a href="#">'.ucfirst($value).'</a>
                                    </li>
                                    ';
                                }
                            }

                            ?>
                        </ul>
                    </div>
                    <!-- END - PAGE HEADER -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- <div class="card-header"></div> -->
                                <div class="card-body">

                              
                                        <!--=================================| /EDIT |=================================-->

                                        <?php  if($_GET['action'] == 'list'){?>

                                            <!-- DIV SEPARADO DE OPTIONAL -->                
                                            
                                            <div class="col-md-12 d-flex">
                                                <table class="table table-hover my-0 mt-2">
                                                    <thead>
                                                        <tr>
                                                            <th>Cliente</th>
                                                            <th>Data do contato</th>
                                                            <th>Próximo contato</th>
                                                            <th>Descrição</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if($C->rowCount() == 0){ ?>
                                                            <tr>
                                                                <td align="center" colspan="8">Nenhum resultado!</td>
                                                            </tr>
                                                        <?php }else{ foreach($ROWS as $k => $v){?>
                                                            <tr>
                                                                <td><?= $v['client_name'].'<br><small>'.$v['prospection'].'</small>';?></td>
                                                                <td><?= $C->toDate($v['dt_contact']);?></td>
                                                                <td><?= $C->toDate($v['dt_next_contact']);?></td>
                                                                <td><?= $v['description'];?></td>
                                                                <td class="text-right">
                                                                    <a href="/admin/cadastros/history/<?= $v['client_id'];?>/<?= $v['prospection_id'];?>/<?= $v['history_id'];?>/update" target="_history" class="btn btn-sm btn-primary btNewImage"><i class="fa fa-forward"></i></a>
                                                                   
                                                                </td>
                                                            </tr>
                                                            <?php }}?></tbody>
                                                        </table>
                                                    </div>                                                


                                                    
                                                    <!-- DIV SEPARADO DE OPTIONAL -->                    
                                                <?php } ?>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <?php require_once("footer.php"); ?>
                        <!-- END - FOOTER -->
                    </div>
                </div>
                <!-- MODAIS -->

                <!-- [ START - MODAL HISTORY ] -->
                <div class="modal fade" id="modal-confirm-delete" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-danger">
                                <h5 class="modal-title text-white"><b>EXCLUSÃO</b></h5>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="row">                                                                  
                                    <div class="form-group col-md-12">
                                        Confirma a exclusão de: <b id="column_name"></b>
                                    </div>                           
                                </div>

                            </div>
                            <div class="modal-footer"><span id="error_msg" class="pull-left text-danger"><b></b></span>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Não</button>
                                <button type="button" class="btn btn-danger" id="modal-btn-delete">SIM</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ END - MODAL HISTORY ] -->

                <!--   Core JS Files   -->
                <!-- INCLUDE JS -->
                <?php require_once("includes_js.php"); ?>
            </body>
            </html>
            <!-- <script src="/admin/js/HISTORY.js"></script> -->
            <script>
                $(function(){
                    $("[data-mask]").inputmask(); 

                    $("#modal-confirm-delete").on("show.bs.modal", function (e) {
                        let {column_name,id} =$(e.relatedTarget).data();
                        $("#column_name",e.target).text(column_name);
                        $("#modal-btn-delete",e.target).attr({"data-column_name":column_name,"data-id":id});
                    });

                    $("body").on("click","#modal-btn-delete",function(){

                        let $this = $(this).attr("data-id");
                        $.ajax({
                            url : "//php/delete.php",
                            type : "post",
                            data : {
                                table:"history",
                                column:"history_id",
                                values:{"":0},
                                where:[["history_id","=",$this]]
                            },
                            beforeSend : function(){
                                console.log("before");
                            }
                        })
                        .done(function(r){      
                            if(r=="updated"){
                                $("[data-id='"+$this+"']").closest("tr").remove();
                                $("#modal-confirm-delete").modal("hide");
                            }
                        })
                        .fail(function(jqXHR, textStatus, msg){
                            alert(msg);
                        }); 

                    })
                });
            </script>