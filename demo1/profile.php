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

$CLASS         =  new Users($_SESSION['USER_ID']);

$ROWS_USERS_TYPES = $CLASS->select()->from('users_types')->execute();
$ROWS_CITIES = $CLASS->select()->from('cities')->execute();
$ROWS_STATUS = $CLASS->select()->from('status')->where('class','=',1)->execute();
$CLASS->table = 'users';
if($method=='GET' && ($action == 'update'  || $action == 'show')){
	$ROWS = $CLASS->select()->where('user_id','=',$_SESSION['USER_ID'])->limit('1')->execute();    
	if($CLASS->rowCount() > 0)
	$CLASS->setData($ROWS[0]);

}else if($action == 'list'){    
	$ROWS = $CLASS->select()
	->leftJoin('users_types','UB.user_type_id')
	->leftJoin('cities','C.city_id')
	->leftJoin('status','S.status_id','user_status_id')
	->where('user_status_id','!=',0)->execute();    
}else if($action == 'create' && $method == 'GET'){
	$ROWS = [];        
}else{

	if(isset($in)){

		$CLASS->setData($in);
		
		if($action == 'create')
			$return = $CLASS->insert()->execute();
		if($action == 'update' || $action == 'show'){
			$return = $CLASS->update()->where('user_id','=',$_SESSION['USER_ID'])->execute();        

		}
		if($action == 'delete')
			$return = $CLASS->delete($_GET['user_id']);  

		if($return > 0 || $return == 'updated' || $return == 'deleted'){
			header('location:/admin/principal/profile/show');
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
						<h4 class="page-title">Perfil</h4>
						<ul class="breadcrumbs">
							<li class="nav-home">
								<a href="#">
									<i class="flaticon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Admin</a>
							</li>
							<li class="separator">
								<i class="flaticon-right-arrow"></i>
							</li>
							<li class="nav-item">
								<a href="#">Perfil</a>
							</li>
						</ul>
					</div>
					<!-- END - PAGE HEADER -->
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header"></div>
								<div class="card-body">

									<!--=================================| EDIT |=================================-->                    

									<?php  if($_GET['action'] == 'show' ||$_GET['action'] == 'update' || $_GET['action'] == 'create'){?>

										<form method="post">
											<div class="row">
												<div class="form-group col-md-6">
													<label>Nome de usuário</label>
													<input type="text" class="form-control" <?= $CLASS->valueN("user_name");?>" >
												</div>                            
												<div class="form-group col-md-6">
													<label>Email</label>
													<input type="text" class="form-control" <?= $CLASS->valueN("email");?>" >
												</div>                            
												<div class="form-group col-md-3">
													<label>Whatsapp</label>
													<input type="text" class="form-control" <?= $CLASS->valueN("whatsapp","cel");?>" >
												</div>                            
												<div class="form-group col-md-3">
													<label>Contatos</label>
													<input type="text" class="form-control" <?= $CLASS->valueN("contact");?>" >
												</div>                            
												<div class="form-group col-md-12">
													<label>Senha</label>
													<input type="text" class="form-control" <?= $CLASS->valueN("password");?>" >
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

												<div class="form-group col-md-6">
													<label>Cidade</label>
													<select class="form-control"  name="city_id">
														<option value="">Selecione</option>
														<?php foreach($ROWS_CITIES as $k=>$v){?>
															<option <?= $CLASS->value_select("city_id",$v["city_id"]);?>><?= $v["city_name"];?></option>
														<?php }?>
													</select>
												</div>

												<div class="form-group col-md-6">
													<label>Status</label>
													<select class="form-control"  name="user_status_id">
														<option value="">Selecione</option>
														<?php foreach($ROWS_STATUS as $k=>$v){?>
															<option <?= $CLASS->value_select("user_status_id",$v["status_id"]);?>><?= $v["status_name"];?></option>
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

									<?php  }?>

									<!--=================================| /EDIT |=================================-->

									<?php  if($_GET['action'] == 'list'){?>

										<!-- DIV SEPARADO DE OPTIONAL -->                

										<div class="col-md-12 d-flex">
											<table class="table table-hover my-0 mt-2">
												<thead>
													<tr>
														<th>User</th>
														<th>Imagem de usuário</th>
														<th>Nome de usuário</th>
														<th>Email</th>
														<th>Contatos</th>
														<th>Senha</th>
														<th>Typo de usuário</th>
														<th>Cidade</th>
														<th>Status</th><th></th>
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
															<td><?= $v['user_type'];?></td>
															<td><?= $v['city_name'];?></td>
															<td><?= $v['status_name'];?></td>
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
