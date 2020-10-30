<?php
require_once '../pdo/Config.inc.php';
require_once '../../cdn/php/singularis.php'; 
require_once '../../cdn/php/Sql.class.php'; 
require_once '../class/Subscriptions.class.php'; 


$error    = '';
$return   = '';

$method   =  $_SERVER['REQUEST_METHOD'];
$in       =  ${'_'.$method};

$action = isset($_POST['action']) ? $in['action'] : $_GET['action'];

$CLASS         =  new Subscriptions($_SESSION['USER_ID']);



$ROWS_CLIENTS = $CLASS->select()->from('clients')->where('client_status_id','=',1)->execute();
$ROWS_CLASSES = $CLASS->select()->from('classes')->where('class_status_id','=',1)->execute();
$ROWS_USERS = $CLASS->select()->from('users')->where('user_status_id','=',1)->execute();
$ROWS_STATUS = $CLASS->select()->from('status')->where('class','=',1)->execute();
$CLASS->table = 'subscriptions';
if($method=='GET' && $action == 'update' ){         
    $ROWS = $CLASS->select()->where('subscription_id','=',$_GET["id"])->limit('1')->execute()[0];    
    $CLASS->setData($ROWS);

}else if($action == 'list'){    
    $ROWS = $CLASS->select()
    ->leftJoin('clients','B.client_id')
    ->leftJoin('classes','C.class_id')
    ->leftJoin('users','D.user_id','created_user_id')
    ->leftJoin('status','E.status_id','subscription_status_id')
    ->where('subscription_status_id','!=',0)->execute();    
}else if($action == 'create' && $method == 'GET'){
    $ROWS = [];        
}else{

    if(isset($in)){

        $CLASS->setData($in);
        
        
        
        if($action == 'create')
            $return = $CLASS->insert()->execute();
        if($action == 'update')
            $return = $CLASS->update()->where('subscription_id','=',$_GET['id'])->execute();        
        if($action == 'delete')
            $return = $CLASS->delete($_GET['subscription_id']);  

        if($return > 0 || $return == 'updated' || $return == 'deleted'){
            header('location:/admin/principal/subscriptions/list');
            exit;
        };

        if($return == 'no-created'||$return == 'no-updated'||$return == 'no-deleted'){
            $error = 'Não foi possivel fazer alteração!';            
        }     
    }
}

if($method == 'POST' && ($action == 'show' || $action == 'update')){
    $ROWS = $_POST;
    $CLASS->setData($ROWS);    
}

require_once("../head.php"); ?>
<style type="text/css">
@media print {
.no-print {
  display: none; 
}
 }

</style>
<body>
    <div class="wrapper">
        <!-- HEADER -->

        <div class="container">
            <div class="content">
                <div class="page-inner">
                    <!-- PAGE HEADER -->
                    <!-- END - PAGE HEADER -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- <div class="card-header"></div> -->
                                <div class="card-body">

                                    <!--=================================| /EDIT |=================================-->
                                    
                                    <?php  if($_GET['action'] == 'list'){?>

                                        <!-- DIV SEPARADO DE OPTIONAL -->                
                                        <div class="row">   
                                            <div class="col-md-12">
                                                <h2><b>Nome do curso:</b> Nome do curso</h2>
                                                <h5><b>Data de realização:</b> 10/10/2020</h5>
                                                <hr>
                                            </div>
                                        </div>  
                                        <div class="col-md-12 d-flex">
                                            <table class="table table-hover my-0">
                                                <thead>
                                                    <tr>
                                                        <th>Cliente</th>
                                                        <th>Turma</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if($CLASS->rowCount() == 0){ ?>
                                                        <tr>
                                                            <td align="center" colspan="6">Nenhum resultado!</td>
                                                        </tr>
                                                    <?php }else{ foreach($ROWS as $k => $v){?>
                                                        <tr>
                                                            <td><?= $v['client_name'];?></td>
                                                            <td><?= $v['class_name'];?></td>
                                                            <td><?= $v['status_name'];?></td>                                    
                                                        </tr>
                                                        <?php }}?></tbody>
                                                    </table>
                                                </div>                                                


                                                <div class="col-md-12">
                                                    <hr>


                                                </div>
                                                <div class="form-group">
                                                    <button onclick="print()" class="btn btn-primary no-print"><i class="fa fa-plus"></i> Imprimir</button>
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

                    <!-- END - FOOTER -->
                </div>
            </div>
            <!-- MODAIS -->



            <!--   Core JS Files   -->
            <!-- INCLUDE JS -->

        </body>
        </html>
        <!-- <script src="/admin/js/SUBSCRIPTIONS.js"></script> -->
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
                        url : "/admin/php/delete.php",
                        type : "post",
                        data : {
                            table:"subscriptions",
                            column:"subscription_id",
                            values:{"subscription_status_id":0},
                            where:[["subscription_id","=",$this]]
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