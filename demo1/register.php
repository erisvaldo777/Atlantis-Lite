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

$CLASS         =  new Users();

$ROWS_STATUS 		= $CLASS->select()->from('status')->where('class','=',1)->execute();
$ROWS_USERS_TYPES 	= $CLASS->select()->from('users_types')->execute();
$ROWS_CITIES 		= $CLASS->select()->from('cities')->execute();

if($method=='GET' && $action == 'update' ){         
	$ROWS = $CLASS->select()->where('user_id','=',$_GET["id"])->limit('1')->execute()[0];    
	$CLASS->setData($ROWS);

}else if($action == 'list'){    
	$ROWS = $CLASS->select()
	->leftJoin('status','S.status_id','user_status_id')
	->leftJoin('users_types','UC.user_type_id')
	->leftJoin('cities','C.city_id')
	->where('user_status_id','!=',0)->execute();    
}else if($action == 'create' && $method == 'GET'){
	$ROWS = [];        
}else{

	if(isset($in)){
$CLASS->table = 'users';
		$CLASS->setData($in);
        //$CLASS->where('client_id','=',$_SESSION['CLIENT_ID']);        

		if($action == 'create')
			$return = $CLASS->insert()->execute();
		if($action == 'update')
			$return = $CLASS->update()->where('user_id','=',$_GET['id'])->execute();        
		if($action == 'delete')
			$return = $CLASS->delete($_GET['user_id']);  

		if($return > 0 || $return == 'updated' || $return == 'deleted'){
			header('location:/public/register/waiting');
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
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Forms - Atlantis Lite Bootstrap 4 Admin Dashboard</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="/cdn/img/icon.ico" type="image/x-icon"/>
	
	<!-- Fonts and icons -->
	<script src="/cdn/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['../assets/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="/cdn/css/bootstrap.min.css">
	<link rel="stylesheet" href="/cdn/css/atlantis.min.css">
	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="/cdn/css/demo.css">
</head>
<body>
	<div class="wrapper sidebar_minimize">
		<main class="content" style="max-width: 700px">
			<div class="container-fluid p-0">
				<!-- CONTENT -->

				<div class="row mb-2 mb-xl-3">
					<div class="col-auto d-none d-sm-block">
						<h3><strong>Cadastro</strong> de Usuario</h3>
					</div>

					<div class="col-auto ml-auto text-right mt-n1">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb bg-transparent p-0 mt-1 mb-0">
								<li class="breadcrumb-item"><a href="/admin/#">Início</a></li>
								<li class="breadcrumb-item"><a href="/admin/#">Cadastros</a></li>
								<li class="breadcrumb-item active" aria-current="page">Usuários</li>
							</ol>
						</nav>
					</div>
				</div>


				<div class="row">
					<!--=================================| EDIT |=================================-->                    

					<?php  if($_GET['action'] == 'update' || $_GET['action'] == 'create'){?>
						<div class="col-md-12">
							<div class="card">
								<div class="card-body">
									<form method="post">
										<div class="row">                            
                           
											<div class="form-group col-md-12">
												<label>Nome de usuário</label>
												<input type="text" class="form-control" <?= $CLASS->valueN("user_name");?>" >
											</div>                            
											<div class="form-group col-md-12">
												<label>Email</label>
												<input type="text" class="form-control" <?= $CLASS->valueN("email");?>" >
											</div>                            
											<div class="form-group col-md-12">
												<label>Whatsapp</label>
												<input type="text" class="form-control" <?= $CLASS->valueN("whatsapp","cel");?>" >
											</div>  
											<div class="form-group col-md-12">
												<label>Contatos</label>
												<input type="text" class="form-control" <?= $CLASS->valueN("contact");?>" >
											</div>                            
											<div class="form-group col-md-12">
												<label>Senha</label>
												<input type="password" class="form-control" <?= $CLASS->valueN("password");?>" >
											</div>                            
											<div class="form-group col-md-12">
												<label>Status</label>
												<select class="form-control"  name="user_status_id">
													<option value="">Selecione</option>
													<?php foreach($ROWS_STATUS as $k=>$v){?>
														<option <?= $CLASS->value_select("user_status_id",$v["status_id"]);?>><?= $v["status_name"];?></option>
													<?php }?>
												</select>
											</div>

											<div class="form-group col-md-12">
												<label>Typo de usuário</label>
												<select class="form-control"  name="user_type_id">
													<option value="">Selecione</option>
													<?php foreach($ROWS_USERS_TYPES as $k=>$v){?>
														<option <?= $CLASS->value_select("user_type_id",$v["user_type_id"]);?>><?= $v["user_type"];?></option>
													<?php }?>
												</select>
											</div>

											<div class="form-group col-md-12">
												<label>Cidade</label>
												<select class="form-control"  name="city_id">
													<option value="">Selecione</option>
													<?php foreach($ROWS_CITIES as $k=>$v){?>
														<option <?= $CLASS->value_select("city_id",$v["city_id"]);?>><?= $v["city_name"];?></option>
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
												<a class="btn btn-secondary" href="///users/list"><i class="fa fa-reply"></i> Voltar</a>
												<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Salvar</button>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
					<?php  }?>

					<!--=================================| /EDIT |=================================-->


					<?php  if($_GET['action'] == 'list'){?>

						<!-- DIV SEPARADO DE OPTIONAL -->
						<div class="col-md-12">
							<div class="card">

								<div class="card-body">
									<!-- OPCIONAIS -->
									<div class="row">                                    
										<div class="col-md-12 d-flex" v-else>
											<table class="table table-hover my-0 mt-2">
												<thead>
													<tr>
														<th>User</th>
														<th>Imagem de usuário</th>
														<th>Nome de usuário</th>
														<th>Email</th>
														<th>Contatos</th>
														<th>Senha</th>
														<th>Status</th>
														<th>Typo de usuário</th>
														<th>Cidade</th><th></th>
													</tr>
												</thead>
												<tbody>
													<?php if(count($ROWS)==0){ ?>
														<tr>
															<td align="center" colspan="">Nenhum resultado!</td>
														</tr>
													<?php }else{ foreach($ROWS as $k => $v){?>
														<tr>
															<td><?= $v['user_id'];?></td>
															<td><?= $v['src'];?></td>
															<td><?= $v['user_name'];?></td>
															<td><?= $v['email'];?></td>
															<td><?= $v['contact'];?></td>
															<td><?= $v['password'];?></td>
															<td><?= $v['status_name'];?></td>
															<td><?= $v['user_type'];?></td>
															<td><?= $v['city_name'];?></td>
															<td class="text-right">
																<a href="update/<?= $v['user_id'];?>" class="btn btn-success btNewImage"><i class="fa fa-edit"></i></a>
																<button class="btn btn-danger" data-row="<? $k;?>" data-column_name="<?= $v["src"]; ?>" data-id="<?= $v["user_id"]; ?>" data-toggle="modal" data-target="#modal-confirm-delete" type="button"><i class="fa fa-trash"></i> </button>
															</td>
														</tr>
														<?php }}?></tbody>
													</table>
												</div>                                                


												<div class="col-md-12">
													<hr>

												</div>
											</div>
											<div class="form-group">
												<a href="create" class="btn btn-primary"><i class="fa fa-plus"></i> Novo</a>
											</div>
										</div>


									</div>
								</div>
								<!-- DIV SEPARADO DE OPTIONAL -->                    
							<?php } ?>



						</div>
					</main>
				</div>
				<!--   Core JS Files   -->
				<script src="/cdn/js/core/jquery.3.2.1.min.js"></script>
				<script src="/cdn/js/core/popper.min.js"></script>
				<script src="/cdn/js/core/bootstrap.min.js"></script>
				<!-- jQuery UI -->
				<script src="/cdn/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
				<script src="/cdn/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

				<!-- jQuery Scrollbar -->
				<script src="/cdn/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
				<!-- Atlantis JS -->
				<script src="/cdn/js/atlantis.min.js"></script>
				<!-- Atlantis DEMO methods, don't include it in your project! -->
				<script src="/cdn/js/setting-demo2.js"></script>
			</body>
			</html>
			<?php include("includes_js.php");  ?>
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