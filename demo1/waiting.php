<!DOCTYPE html>
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

			


				<div class="row">
					<!--=================================| EDIT |=================================-->                    


					<!--=================================| /EDIT |=================================-->


					<h1>OBRIGADO... AGUARDE O MODERADOR AUTORIZAR SEU ACESSO</h1>



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