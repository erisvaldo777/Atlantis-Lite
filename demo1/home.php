<?php
require_once 'pdo/Config.inc.php';
//require_once 'class/Permissions.class.php'; 



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
					
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								
								<div class="card-body">

									<!--=================================| EDIT |=================================-->                    

									

									<form method="post">
										<div class="row">
											<div class="form-group col-md-6">
												<H2>BEM VINDO AO SISTEMA CORTEX!</H2>
											</div>                            

											
										</div>


									</form>



									<!--=================================| /EDIT |=================================-->


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
