<?php
require_once 'pdo/Config.inc.php';
require_once '../cdn/php/singularis.php'; 
require_once '../cdn/php/Sql.class.php'; 
require_once 'class/Clients.class.php'; 


$error    = '';
$return   = '';

$method   =  $_SERVER['REQUEST_METHOD'];
$in       =  ${'_'.$method};

$action = isset($_POST['action']) ? $in['action'] : $_GET['action'];

$CLASS         =  new Clients($_SESSION['USER_ID']);



$ROWS_CITIES = $CLASS->select()->from('cities')->execute();
$ROWS_STATES = $CLASS->select()->from('states')->execute();
$ROWS_STATUS = $CLASS->select()->from('status')->where('class','=',1)->execute();
$CLASS->table = 'clients';
if($method=='GET' && $action == 'update' ){         
    $ROWS = $CLASS->select()->where('client_id','=',$_GET["id"])->limit('1')->execute()[0];    
    $CLASS->setData($ROWS);

}else if($action == 'list'){    
    $ROWS = $CLASS->select()
    ->leftJoin('cities','C.city_id')
    ->leftJoin('states','D.state_id')
    ->leftJoin('status','E.status_id','client_status_id')
    ->where('client_status_id','!=',0)
    ->where('created_by_user_id','=',$_SESSION['USER_ID'])->execute();    
}else if($action == 'create' && $method == 'GET'){
    $ROWS = [];        
}else{

    if(isset($in)){

        $CLASS->setData($in);
        
            
        if($action == 'create')
            $return = $CLASS->insert()->execute();
        if($action == 'update')
            $return = $CLASS->update()->where('client_id','=',$_GET['id'])->execute();        
        if($action == 'delete')
            $return = $CLASS->delete($_GET['client_id']);  

        if($return > 0 || $return == 'updated' || $return == 'deleted'){
            header('location:/admin/principal/clients/list');
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
                        <h4 class="page-title">Clientes</h4>
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

                                    <!--=================================| EDIT |=================================-->                    

                                    <?php  if($_GET['action'] == 'update' || $_GET['action'] == 'create'){?>

                                        <form method="post">
                                            <div class="row">                            

                                                <div class="form-group col-md-6">
                                                    <label>Nome do cliente</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("client_name");?>  required>
                                                </div>                            
                                                <div class="form-group col-md-6">
                                                    <label>Nome crachá</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("nickname");?> required>
                                                </div>                            
                                                <div class="form-group col-md-4">
                                                    <label>Email</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("email");?>>
                                                </div>                            
                                                <div class="form-group col-md-4">
                                                    <label>Whatsapp</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("whatsapp_number","cel");?>  required>
                                                </div>                            
                                                <div class="form-group col-md-4">
                                                    <label>Números de contato</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("cell_phone");?>>
                                                </div>                            
                                                <div class="form-group col-md-4">
                                                    <label>Profissão</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("job");?>>
                                                </div>                            
                                                <div class="form-group col-md-8">
                                                    <label>Local de trabalho</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("workspace");?>>
                                                </div>                            
                                                <div class="form-group col-md-12">
                                                    <label>Endereço</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("address");?>>
                                                </div>                            
                                                <div class="form-group col-md-3">
                                                    <label>CEP</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("zip_code","cep");?>>
                                                </div>                            
                                                <div class="form-group col-md-6">
                                                    <label>Id uf</label>
                                                    <select class="form-control"  name="state_id" required>
                                                        <option value="">Selecione</option>
                                                        <?php foreach($ROWS_STATES as $k=>$v){?>
                                                            <option <?= $CLASS->value_select("state_id",$v["state_id"]);?>><?= $v["state_abbr"];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-3">
                                                    <label>Cidade</label>
                                                    <select class="form-control"  name="city_id" required>
                                                        <option value="">Selecione</option>
                                                        <?php foreach($ROWS_CITIES as $k=>$v){?>
                                                            <option <?= $CLASS->value_select("city_id",$v["city_id"]);?>><?= $v["city_name"];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                
                                                
                                                                          
                                                <div class="form-group col-md-6">
                                                    <label>Indicado Por</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("indicated_by");?>"  required>
                                                </div>                            
                                                <div class="form-group col-md-6">
                                                    <label>Status</label>
                                                    <select class="form-control"  name="client_status_id" required>
                                                        <option value="">Selecione</option>
                                                        <?php foreach($ROWS_STATUS as $k=>$v){?>
                                                            <option <?= $CLASS->value_select("client_status_id",$v["status_id"]);?>><?= $v["status_name"];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="row" style="display:<?= $error!=''?'block':'none'; ?>">
                                                <div class="col-md-12">
                                                    <div class="alert alert-danger" style="padding: 15px"><b><?= $error; ?></b></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <a class="btn btn-secondary" href="/admin/principal/clients/list"><i class="fa fa-reply"></i> Voltar</a>
                                                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Salvar</button>
                                                </div>
                                            </div>
                                        </form>

                                    <?php  }?>

                                    <!--=================================| /EDIT |=================================-->
                                    
                                    <?php  if($_GET['action'] == 'list'){?>

                                        <!-- DIV SEPARADO DE OPTIONAL -->                

                                        <div class="col-md-12 d-flex">
                                            <table class="table table-hover my-0 mt-2">
                                                <thead>
                                                    <tr>
                                                        <th>Nome do cliente</th>
                                                        <th>Email</th>
                                                        <th>Whatsapp</th>
                                                        <th>Cidade</th>                                                        
                                                        <th>Status</th><th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if($CLASS->rowCount() == 0){ ?>
                                                        <tr>
                                                            <td align="center" colspan="15">Nenhum resultado!</td>
                                                        </tr>
                                                    <?php }else{ foreach($ROWS as $k => $v){?>
                                                        <tr>
                                                            <td><?= $v['client_name'];?></td>
                                                            <td><?= $v['email'];?></td>
                                                            <td nowrap=""><?= $CLASS->mask($v["whatsapp_number"],"cel");?></td>
                                                            <td><?= $v['city_name'].'/'.$v['state_abbr'];?></td>        
                                                            <td><?= $v['status_name'];?></td>
                                                            <td class="text-right" nowrap="">
                                                                <a title="Editar" href="<?= $v['client_id'];?>/update" class="btn btn-sm btn-success btNewImage"><i class="fa fa-edit"></i></a>
                                                                <a title="Prospectar" href="/admin/cadastros/prospection/<?= $v['client_id'];?>/list" class="btn btn-sm btn-primary     btNewImage"><i class="fa fa-th-list"></i></a>
                                                                <button title="Excluir" class="btn btn-sm btn-danger" data-row="<? $k;?>" data-column_name="<?= $v["client_name"]; ?>" data-id="<?= $v["client_id"]; ?>" data-toggle="modal" data-target="#modal-confirm-delete" type="button"><i class="fa fa-trash"></i> </button>
                                                            </td>
                                                        </tr>
                                                        <?php }}?></tbody>
                                                    </table>
                                                </div>                                                


                                                <div class="col-md-12">
                                                    <hr>


                                                </div>
                                                <div class="form-group">
                                                    <a href="create" class="btn btn-primary"><i class="fa fa-plus"></i> Novo</a>

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

            <!-- [ START - MODAL CLIENTS ] -->
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
            <!-- [ END - MODAL CLIENTS ] -->

            <!--   Core JS Files   -->
            <!-- INCLUDE JS -->
            <?php require_once("includes_js.php"); ?>
        </body>
        </html>
        <!-- <script src="/admin/js/CLIENTS.js"></script> -->
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
                            table:"clients",
                            column:"client_id",
                            values:{"client_status_id":0},
                            where:[["client_id","=",$this]]
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
