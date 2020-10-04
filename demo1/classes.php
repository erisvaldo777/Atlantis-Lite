<?php
require_once 'pdo/Config.inc.php';
require_once '../cdn/php/singularis.php'; 
require_once '../cdn/php/Sql.class.php'; 
require_once 'class/Classes.class.php'; 


$error    = '';
$return   = '';

$method   =  $_SERVER['REQUEST_METHOD'];
$in       =  ${'_'.$method};

$action = isset($_POST['action']) ? $in['action'] : $_GET['action'];

$CLASS         =  new Classes($_SESSION['USER_ID']);

$ROWS_COURSES = $CLASS->select()->from('courses')->where('course_status_id','=',1)->execute();
$ROWS_CITIES = $CLASS->select()->from('cities')->execute();
$ROWS_USERS = $CLASS->select()->from('users')->execute();
$ROWS_STATUS = $CLASS->select()->from('status')->where('class','=',1)->execute();

$CLASS->table = 'classes';

if($method=='GET' && $action == 'update' ){         
    $ROWS = $CLASS->select()->where('class_id','=',$_GET["id"])->limit('1')->execute()[0];    
    $CLASS->setData($ROWS);

}else if($action == 'list'){    
    $ROWS = $CLASS->select()
    ->leftJoin('courses','B.course_id')
    ->leftJoin('cities','C.city_id')
    ->leftJoin('status','D.status_id','class_status_id')
    ->where('class_status_id','!=',0)->order_by('dt_start asc')->execute();
    
}else if($action == 'create' && $method == 'GET'){
    $ROWS = [];        
}else{

    if(isset($in)){

        $CLASS->setData($in);
                
        if($action == 'create')
            $return = $CLASS->insert()->execute();
        if($action == 'update')
            $return = $CLASS->update()->where('class_id','=',$_GET['id'])->execute();        
        if($action == 'delete')
            $return = $CLASS->delete($_GET['class_id']);  

        if($return > 0 || $return == 'updated' || $return == 'deleted'){
            header('location:/admin/principal/classes/list');
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
                        <h4 class="page-title">Turmas</h4>
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

                                                <div class="form-group col-md-4">
                                                    <label>Nome da turma</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("class_name");?>" >
                                                </div>                            
                                                <div class="form-group col-md-4">
                                                    <label>Curso vinculado</label>
                                                    <select class="form-control"  name="course_id">
                                                        <option value="">Selecione</option>
                                                        <?php foreach($ROWS_COURSES as $k=>$v){?>
                                                            <option <?= $CLASS->value_select("course_id",$v["course_id"]);?>><?= $v["course_name"];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-2">
                                                    <label>Data início</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("dt_start","dmy");?>" >
                                                </div>                            
                                                <div class="form-group col-md-2">
                                                    <label>Data fim</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("dt_end","dmy");?>" >
                                                </div>                            
                                                <div class="form-group col-md-4">
                                                    <label>Cidade</label>
                                                    <select class="form-control"  name="city_id">
                                                        <option value="">Selecione</option>
                                                        <?php foreach($ROWS_CITIES as $k=>$v){?>
                                                            <option <?= $CLASS->value_select("city_id",$v["city_id"]);?>><?= $v["city_name"];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>Secretário da turma</label>
                                                    <select class="form-control"  name="user_id">
                                                        <option value="">Selecione</option>
                                                        <?php foreach($ROWS_USERS as $k=>$v){?>
                                                            <option <?= $CLASS->value_select("user_id",$v["user_id"]);?>><?= $v["user_name"];?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Preço à vista</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("price_cash","decimal6");?>" >
                                                </div>                            
                                                <div class="form-group col-md-2">
                                                    <label>Preço parcelado</label>
                                                    <input type="text" class="form-control" <?= $CLASS->valueN("price_split","decimal6");?>" >
                                                </div>  

                                                <div class="form-group col-md-12">
                                                    <label>Status</label>
                                                    <select class="form-control"  name="class_status_id">
                                                        <option value="">Selecione</option>
                                                        <?php foreach($ROWS_STATUS as $k=>$v){?>
                                                            <option <?= $CLASS->value_select("class_status_id",$v["status_id"]);?>><?= $v["status_name"];?></option>
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
                                                    <a class="btn btn-secondary" href="/admin/principal/classes/list"><i class="fa fa-reply"></i> Voltar</a>
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
                                                        <th>Turma</th>
                                                        <th>Curso vinculado</th>
                                                        <th>Realização</th>                                                        
                                                        <th>Cidade</th>
                                                        <th>Status</th><th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if($CLASS->rowCount() == 0){ ?>
                                                        <tr>
                                                            <td align="center" colspan="7">Nenhum resultado!</td>
                                                        </tr>
                                                    <?php }else{ foreach($ROWS as $k => $v){?>
                                                        <tr>
                                                            <td><?= $v['class_name'];?></td>
                                                            <td><?= $v['course_name'];?></td>                         
                                                            <td><?= $CLASS->toDate($v['dt_start']).'<br>'.$CLASS->toDate($v['dt_end']);?></td>
                                                            <td><?= $v['city_name'];?></td>
                                                            <td><?= $v['status_name'];?></td>
                                                            <td class="text-right">
                                                                <a href="<?= $v['class_id'];?>/update" class="btn btn-success btNewImage btn-sm"><i class="fa fa-edit"></i></a>
                                                                <a href="../classes-subscriptions/<?= $v['class_id'];?>/list" class="btn btn-warning btNewImage btn-sm"><i class="fa fa-edit"></i></a>
                                                                <button class="btn btn-danger btn-sm" data-row="<? $k;?>" data-column_name="<?= $v["class_name"]; ?>" data-id="<?= $v["class_id"]; ?>" data-toggle="modal" data-target="#modal-confirm-delete" type="button"><i class="fa fa-trash"></i> </button>
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

            <!-- [ START - MODAL CLASSES ] -->
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
            <!-- [ END - MODAL CLASSES ] -->

            <!--   Core JS Files   -->
            <!-- INCLUDE JS -->
            <?php require_once("includes_js.php"); ?>
        </body>
        </html>
        <!-- <script src="/admin/js/CLASSES.js"></script> -->
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
                            table:"classes",
                            column:"class_id",
                            values:{"class_status_id":0},
                            where:[["class_id","=",$this]]
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
