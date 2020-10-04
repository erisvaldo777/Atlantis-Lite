<div class="sidebar sidebar-style-2">			
			<div class="sidebar-wrapper scrollbar scrollbar-inner">
				<div class="sidebar-content">
					<div class="user">
						<div class="avatar-sm float-left mr-2">
							<img src="/cdn/img/profile.jpg" alt="..." class="avatar-img rounded-circle">
						</div>
						<div class="info">
							<a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
								<span>
									<?= explode(' ',$_SESSION['USER_NAME'])[0]; ?>
									<span class="user-level">Usuário</span>
									<span class="caret"></span>
								</span>
							</a>
							<div class="clearfix"></div>

							<div class="collapse in" id="collapseExample">
								<ul class="nav">
									<li>
										<a href="#profile">
											<span class="link-collapse">My Profile</span>
										</a>
									</li>
									<li>
										<a href="#edit">
											<span class="link-collapse">Edit Profile</span>
										</a>
									</li>
									<li>
										<a href="#settings">
											<span class="link-collapse">Settings</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<ul class="nav nav-primary">
						<!-- <li class="nav-item active">
							<a data-toggle="collapse" href="#dashboard" class="collapsed" aria-expanded="false">
								<i class="fas fa-home"></i>
								<p>Dashboard</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="dashboard">
								<ul class="nav nav-collapse">
									<li>
										<a href="../demo1/index.html">
											<span class="sub-item">Dashboard 1</span>
										</a>
									</li>
									<li>
										<a href="../demo2/index.html">
											<span class="sub-item">Dashboard 2</span>
										</a>
									</li>
								</ul>
							</div>
						</li> -->
						<li class="nav-section">
							<span class="sidebar-mini-icon">
								<i class="fa fa-ellipsis-h"></i>
							</span>
							<h4 class="text-section">principal</h4>
						</li>
						<li class="nav-item">
							<a data-toggle="collapse" href="#base">
								<i class="fas fa-layer-group"></i>
								<p>Cadastros</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="base">
								<ul class="nav nav-collapse">
									<li>
										<a href="/admin/principal/courses/list">
											<span class="sub-item">Cursos</span>
										</a>
									</li>
									<li>
										<a href="/admin/principal/classes/list">
											<span class="sub-item">Turmas</span>
										</a>
									</li>
									<li>
										<a href="/admin/principal/clients/list">
											<span class="sub-item">Clientes</span>
										</a>
									</li>
									<!-- <li>
										<a href="components/panels.html">
											<span class="sub-item">Panels</span>
										</a>
									</li> -->
									<li>
										<a href="/admin/principal/users/list">
											<span class="sub-item">Usuários</span>
										</a>
									</li>
									
								</ul>
							</div>
						</li>
						<!-- <li class="nav-item">
							<a data-toggle="collapse" href="#admin">
								<i class="fas fa-layer-group"></i>
								<p>Administração</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="admin">
								<ul class="nav nav-collapse">
									<li>
										<a href="/admin/principal/rules/list">
											<span class="sub-item">Permissões</span>
										</a>
									</li>
									
									
								</ul>
							</div>
						</li> -->
						<li class="nav-item">
							<a data-toggle="collapse" href="#sidebarLayouts">
								<i class="fas fa-th-list"></i>
								<p>Relatórios</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="sidebarLayouts">
								<ul class="nav nav-collapse">
									<li>
										<a href="/admin/principal/courses/list" target="_blank">
											<span class="sub-item">Inscritos</span>
										</a>
									</li>
									<li>
										<a href="#" target="_blank">
											<span class="sub-item">A implementar</span>
										</a>
									</li>
									
								</ul>
							</div>
						</li>
						
						
						<li class="mx-4 mt-2">
							<a href="#" class="btn btn-primary btn-block"><span class="btn-label mr-2"> <i class="fa fa-heart"></i> </span>Avançado</a> 
						</li>
					</ul>
				</div>
			</div>
		</div>