<?php
require_once 'pdo/Config.inc.php';
require_once '../cdn/php/singularis.php'; 
require_once '../cdn/php/Sql.class.php'; 
require_once 'class/Users.class.php'; 


$error    = '';
$return   = '';

$method   =  $_SERVER['REQUEST_METHOD'];
$in       =  ${'_'.$method};

$action = isset($_POST['action']) ? $in['action'] : $_GET['action'];

$CLASS         =  new Users($_SESSION['CLIENT_ID']);



$CLASS->table = 'users';
if($method=='GET' && $action == 'update' ){         
    $ROWS = $CLASS->select()->where('user_id','=',$_GET["id"])->limit('1')->execute()[0];    
    $CLASS->setData($ROWS);

}else if($action == 'list'){    
    $ROWS = $CLASS->select()
->where('user_status_id','!=',0)->execute();    
}else if($action == 'create' && $method == 'GET'){
    $ROWS = [];        
}else{

    if(isset($in)){
        
        $CLASS->setData($in);
        
        
        
        if($action == 'create')
            $return = $CLASS->insert()->execute();
        if($action == 'update')
            $return = $CLASS->update()->where('user_id','=',$_GET['id'])->execute();        
        if($action == 'delete')
            $return = $CLASS->delete($_GET['user_id']);  

        if($return > 0 || $return == 'updated' || $return == 'deleted'){
            header('location:///users/list');
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
    <h4 class="page-title"></h4>
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
                            
                                            <div class="form-group col-md-12">
                                            <label>User</label>
                                            <input type="text" class="form-control" <?= $CLASS->valueN("user_id");?>"  required>
                                            </div>                            
                                            <div class="form-group col-md-12">
                                            <label>Letter Key</label>
                                            <input type="text" class="form-control" <?= $CLASS->valueN("letter_key");?>"  required>
                                            </div>                            
                                            <div class="form-group col-md-12">
                                            <label>User Name</label>
                                            <input type="text" class="form-control" <?= $CLASS->valueN("user_name");?>" >
                                            </div>                            
                                            <div class="form-group col-md-12">
                                            <label>Email</label>
                                            <input type="text" class="form-control" <?= $CLASS->valueN("email");?>" >
                                            </div>                            
                                            <div class="form-group col-md-12">
                                            <label>Whatsapp</label>
                                            <input type="text" class="form-control" <?= $CLASS->valueN("whatsapp");?>" >
                                            </div>                            
                                            <div class="form-group col-md-12">
                                            <label>Password</label>
                                            <input type="text" class="form-control" <?= $CLASS->valueN("password");?>" >
                                            </div>                            
                                            <div class="form-group col-md-12">
                                            <label>Level</label>
                                            <input type="text" class="form-control" <?= $CLASS->valueN("level_id");?>" >
                                            </div>                            
                                            <div class="form-group col-md-12">
                                            <label>User Status</label>
                                            <input type="text" class="form-control" <?= $CLASS->valueN("user_status_id");?>" >
                                            </div>                            </div>

                                <div class="row" style="display:<?= $error!=''?'block':'none'; ?>">
                                <div class="col-md-12">
                                <div class="alert alert-danger" style="padding: 15px"><b><?= $error; ?></b></div>
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-md-12">
                                <a class="btn btn-secondary" href="///users/list"><i class="fa fa-reply"></i> Voltar</a>
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
                        <th>User</th>
                        <th>Letter Key</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Whatsapp</th>
                        <th>Password</th>
                        <th>Level</th>
                        <th>User Status</th><th></th>
                </tr>
                </thead>
                <tbody>
                <?php if($CLASS->rowCount() == 0){ ?>
                    <tr>
                    <td align="center" colspan="8">Nenhum resultado!</td>
                    </tr>
                    <?php }else{ foreach($ROWS as $k => $v){?>
                        <tr>
                        <td><?= $v['user_id'];?></td>
                                    <td><?= $v['letter_key'];?></td>
                                    <td><?= $v['user_name'];?></td>
                                    <td><?= $v['email'];?></td>
                                    <td><?= $v['whatsapp'];?></td>
                                    <td><?= $v['password'];?></td>
                                    <td><?= $v['level_id'];?></td>
                                    <td><?= $v['user_status_id'];?></td>
                                    <td class="text-right">
                        <a href="update/<?= $v['user_id'];?>" class="btn btn-sm btn-success btNewImage"><i class="fa fa-edit"></i></a>
                        <button class="btn btn-sm btn-danger" data-row="<? $k;?>" data-column_name="<?= $v["letter_key"]; ?>" data-id="<?= $v["user_id"]; ?>" data-toggle="modal" data-target="#modal-confirm-delete" type="button"><i class="fa fa-trash"></i> </button>
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
   
                            <!-- [ START - MODAL USERS ] -->
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
                            <!-- [ END - MODAL USERS ] -->
    
    <!--   Core JS Files   -->
    <!-- INCLUDE JS -->
    <?php require_once("includes_js.php"); ?>
</body>
</html>
                            <!-- <script src="/admin/js/USERS.js"></script> -->
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
                                                table:"users",
                                                column:"user_id",
                                                values:{"user_status_id":0},
                                                where:[["user_id","=",$this]]
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