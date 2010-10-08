<!DOCTYPE HTML>
<html lang="en">
<head>
    <title>Slicr::link::edit</title>
	<link type="text/css" rel="stylesheet" media="all" href="/css/style.css" />
    <script src="/js/prototype.js"></script>
    <script src="/js/scriptaculous/scriptaculous.js"></script>
    </head>
<body>

    <div id="main">
		<div class="reps">
						<div id="header" class="wrap">
				<div class="wrap">
					<h1 class="float-left">PICO - site title</h1>
					<a href="/">view site</a> |
					<a href="/user/logout">logout</a>

				</div>
			</div>
            <div id="main-menu" class="menu">
                	<ul>
		<li>
			<a href="/admin/image">
			Images
			</a>
		</li>

		<li>
			<a href="/admin/page">
			Pages
			</a>
		</li>
		<li>
			<a href="/admin/label">
			Labels
			</a>
		</li>

		<li>
			<a href="/admin/link">
			Links
			</a>
		</li>
	</ul>
	            </div>
            <div id="sub-menu" class="menu">
                	<ul>

		<li>
			<a href="/admin/link/list/1/">
			Main menu
			</a>
		</li>
		<li>
			<a href="/admin/link/list/2/">
			Footer menu
			</a>
		</li>

	</ul>
	            </div>
            			<div id="main-content">
                <div id="left">
                    <h4>Main menu</h4>
<p>Your Main Menu items go here</p>
	<form id="form-element-0" method="post" enctype="multipart/form-data" action="/admin/link/add/11" class="form">
		<fieldset id="viewport" legend="Add link" type="fieldset" class="fieldset-fieldset">

			<legend>
			Add link
			</legend>
			<div class="formElementWrapper">
				<label for="text-element-0">
				Title	<input type="text" value="New menu item" name="title" id="text-element-0" class="input-text"/>
					
				</label>
				
			</div>
			<div class="formElementWrapper">

				<label for="text-element-1">
				Url	<input type="text" name="url" id="text-element-1" class="input-text"/>
					
				</label>
				
			</div>
			<div class="formElementWrapper">
				<label for="select-element-0">
				Parent item	<select type="select" value="11" name="parent_id" id="select-element-0" class="select-select">
						<option value="1">

						Main 1
						</option>
						<option value="2">
						Main 2
						</option>
						<option value="3">
						Main 3
						</option>
						<option value="4">
						Main 4
						</option>

						<option value="5">
						Main Sub 1
						</option>
						<option value="6">
						Main Sub 2
						</option>
						<option value="7">
						Main Sub 3
						</option>
						<option value="8">

						Main Sub 4
						</option>
						<option value="9">
						Main Subsub 1
						</option>
						<option value="10">
						Main Subsub 2
						</option>
						<option value="11" selected="selected">
						Main Subsub 3
						</option>

					</select>
					
				</label>
				
			</div>
			<input type="submit" value="Add link" name="save" id="submit-element-2" class="input-submit"/>
		</fieldset>
	</form>
	                </div>
                <div id="middle">
                    	<div class="pico-linktree">

		<ul>
			<li>
				<a href="/admin/link/edit/1/">
				Main 1
				</a>
			</li>
			<li>
				<a href="/admin/link/edit/2/">
				Main 2
				</a>

			</li>
			<li>
				<a href="/admin/link/edit/3/">
				Main 3
				</a>
			</li>
			<li class="active">
				<a href="/admin/link/edit/4/">
				Main 4
				</a>

				<div class="itemGroup">
					<ul>
						<li>
							<a href="/admin/link/edit/5/">
							Main Sub 1
							</a>
						</li>
						<li>
							<a href="/admin/link/edit/6/">

							Main Sub 2
							</a>
						</li>
						<li class="active">
							<a href="/admin/link/edit/7/">
							Main Sub 3
							</a>
							<div class="itemGroup">
								<ul>
									<li>

										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">

										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">

														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>

														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>

																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">

																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>

																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">

														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>

										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="/admin/link/edit/8/">
							Main Sub 4
							</a>

						</li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
	                </div>
                <!--<table id="main-left" class="reps">-->
                <!--    <tr>-->

                <!--        <td id="left">-->
                <!--            <h4>Main menu</h4>
<p>Your Main Menu items go here</p>
	<form id="form-element-0" method="post" enctype="multipart/form-data" action="/admin/link/add/11" class="form">
		<fieldset id="viewport" legend="Add link" type="fieldset" class="fieldset-fieldset">
			<legend>
			Add link
			</legend>
			<div class="formElementWrapper">
				<label for="text-element-0">
				Title	<input type="text" value="New menu item" name="title" id="text-element-0" class="input-text"/>
					
				</label>
				
			</div>
			<div class="formElementWrapper">
				<label for="text-element-1">
				Url	<input type="text" name="url" id="text-element-1" class="input-text"/>
					
				</label>
				
			</div>
			<div class="formElementWrapper">
				<label for="select-element-0">
				Parent item	<select type="select" value="11" name="parent_id" id="select-element-0" class="select-select">
						<option value="1">
						Main 1
						</option>
						<option value="2">
						Main 2
						</option>
						<option value="3">
						Main 3
						</option>
						<option value="4">
						Main 4
						</option>
						<option value="5">
						Main Sub 1
						</option>
						<option value="6">
						Main Sub 2
						</option>
						<option value="7">
						Main Sub 3
						</option>
						<option value="8">
						Main Sub 4
						</option>
						<option value="9">
						Main Subsub 1
						</option>
						<option value="10">
						Main Subsub 2
						</option>
						<option value="11" selected="selected">
						Main Subsub 3
						</option>
					</select>
					
				</label>
				
			</div>
			<input type="submit" value="Add link" name="save" id="submit-element-2" class="input-submit"/>
		</fieldset>
	</form>
	-->
                <!--        </td>-->
                <!--        <td id="middle" class="reps">-->
                <!--            	<div class="pico-linktree">
		<ul>
			<li>
				<a href="/admin/link/edit/1/">
				Main 1
				</a>
			</li>
			<li>
				<a href="/admin/link/edit/2/">
				Main 2
				</a>
			</li>
			<li>
				<a href="/admin/link/edit/3/">
				Main 3
				</a>
			</li>
			<li class="active">
				<a href="/admin/link/edit/4/">
				Main 4
				</a>
				<div class="itemGroup">
					<ul>
						<li>
							<a href="/admin/link/edit/5/">
							Main Sub 1
							</a>
						</li>
						<li>
							<a href="/admin/link/edit/6/">
							Main Sub 2
							</a>
						</li>
						<li class="active">
							<a href="/admin/link/edit/7/">
							Main Sub 3
							</a>
							<div class="itemGroup">
								<ul>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="/admin/link/edit/8/">
							Main Sub 4
							</a>
						</li>
					</ul>
				</div>
			</li>
		</ul>
		<ul>
			<li>
				<a href="/admin/link/edit/1/">
				Main 1
				</a>
			</li>
			<li>
				<a href="/admin/link/edit/2/">
				Main 2
				</a>
			</li>
			<li>
				<a href="/admin/link/edit/3/">
				Main 3
				</a>
			</li>
			<li class="active">
				<a href="/admin/link/edit/4/">
				Main 4
				</a>
				<div class="itemGroup">
					<ul>
						<li>
							<a href="/admin/link/edit/5/">
							Main Sub 1
							</a>
						</li>
						<li>
							<a href="/admin/link/edit/6/">
							Main Sub 2
							</a>
						</li>
						<li class="active">
							<a href="/admin/link/edit/7/">
							Main Sub 3
							</a>
							<div class="itemGroup">
								<ul>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="/admin/link/edit/8/">
							Main Sub 4
							</a>
						</li>
					</ul>
				</div>
			</li>
			<li>
				<a href="/admin/link/edit/1/">
				Main 1
				</a>
				<a href="/admin/link/edit/1/">
				Main 1
				</a>
			</li>
			<li>
				<a href="/admin/link/edit/2/">
				Main 2
				</a>
				<a href="/admin/link/edit/2/">
				Main 2
				</a>
			</li>
			<li>
				<a href="/admin/link/edit/3/">
				Main 3
				</a>
				<a href="/admin/link/edit/3/">
				Main 3
				</a>
			</li>
			<li class="active">
				<a href="/admin/link/edit/4/">
				Main 4
				</a>
				<div class="itemGroup">
					<ul>
						<li>
							<a href="/admin/link/edit/5/">
							Main Sub 1
							</a>
						</li>
						<li>
							<a href="/admin/link/edit/6/">
							Main Sub 2
							</a>
						</li>
						<li class="active">
							<a href="/admin/link/edit/7/">
							Main Sub 3
							</a>
							<div class="itemGroup">
								<ul>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="/admin/link/edit/8/">
							Main Sub 4
							</a>
						</li>
					</ul>
				</div>
				<a href="/admin/link/edit/4/">
				Main 4
				</a>
				<div class="itemGroup">
					<ul>
						<li>
							<a href="/admin/link/edit/5/">
							Main Sub 1
							</a>
						</li>
						<li>
							<a href="/admin/link/edit/6/">
							Main Sub 2
							</a>
						</li>
						<li class="active">
							<a href="/admin/link/edit/7/">
							Main Sub 3
							</a>
							<div class="itemGroup">
								<ul>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="/admin/link/edit/8/">
							Main Sub 4
							</a>
						</li>
					</ul>
					<ul>
						<li>
							<a href="/admin/link/edit/5/">
							Main Sub 1
							</a>
						</li>
						<li>
							<a href="/admin/link/edit/6/">
							Main Sub 2
							</a>
						</li>
						<li class="active">
							<a href="/admin/link/edit/7/">
							Main Sub 3
							</a>
							<div class="itemGroup">
								<ul>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="/admin/link/edit/8/">
							Main Sub 4
							</a>
						</li>
						<li>
							<a href="/admin/link/edit/5/">
							Main Sub 1
							</a>
							<a href="/admin/link/edit/5/">
							Main Sub 1
							</a>
						</li>
						<li>
							<a href="/admin/link/edit/6/">
							Main Sub 2
							</a>
							<a href="/admin/link/edit/6/">
							Main Sub 2
							</a>
						</li>
						<li class="active">
							<a href="/admin/link/edit/7/">
							Main Sub 3
							</a>
							<div class="itemGroup">
								<ul>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
								</ul>
							</div>
							<a href="/admin/link/edit/7/">
							Main Sub 3
							</a>
							<div class="itemGroup">
								<ul>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
								</ul>
								<ul>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
									<li>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
										<a href="/admin/link/edit/9/">
										Main Subsub 1
										</a>
									</li>
									<li>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
										<a href="/admin/link/edit/10/">
										Main Subsub 2
										</a>
									</li>
									<li class="active">
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
										<a href="/admin/link/edit/11/">
										Main Subsub 3
										</a>
										<div class="itemForm">
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
											<form id="form-element-1" method="post" enctype="multipart/form-data" class="form">
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
												<fieldset id="viewport" legend="Edit Main Subsub 3" type="fieldset" class="fieldset-fieldset">
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
													<legend>
													Edit Main Subsub 3
													</legend>
													<div class="formElementWrapper">
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
														<label for="text-element-3">
														Title	<input type="text" value="Main Subsub 3" name="title" id="text-element-3" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
														<label for="text-element-4">
														Url	<input type="text" value="http://google.nl" name="url" id="text-element-4" class="input-text"/>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
														<label for="select-element-1">
														Parent item	<select type="select" value="0000000007" name="parent_id" id="select-element-1" class="select-select">
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
																<option value="1">
																Main 1
																</option>
																<option value="2">
																Main 2
																</option>
																<option value="3">
																Main 3
																</option>
																<option value="4">
																Main 4
																</option>
																<option value="5">
																Main Sub 1
																</option>
																<option value="6">
																Main Sub 2
																</option>
																<option value="7" selected="selected">
																Main Sub 3
																</option>
																<option value="8">
																Main Sub 4
																</option>
																<option value="9">
																Main Subsub 1
																</option>
																<option value="10">
																Main Subsub 2
																</option>
																<option value="11">
																Main Subsub 3
																</option>
															</select>
															
														</label>
														
													</div>
													<div class="formElementWrapper">
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
														<label for="text-element-5">
														Priority	<input type="text" value="4" name="priority" id="text-element-5" class="input-text"/>
															
														</label>
														
													</div>
													<input type="submit" value="Save changes" name="save" id="submit-element-6" class="input-submit"/>
													<input type="submit" value="Delete Main Subsub 3" name="delete" id="submit-element-7" class="input-submit"/>
												</fieldset>
											</form>
										</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="/admin/link/edit/8/">
							Main Sub 4
							</a>
							<a href="/admin/link/edit/8/">
							Main Sub 4
							</a>
						</li>
					</ul>
				</div>
			</li>
		</ul>
	</div>
	-->
                <!--        </td>-->
                <!--        <td id="right">-->
                <!--            -->
                <!--        </td>-->

                <!--    </tr>-->
                <!--</table>-->
                <!--<div id="main-left" class="reps">-->
                <!--    -->
                <!--</div>-->
                <!---->
                <!--<div id="menu-left" class="reps">-->
                <!--    <h2 class="buttonbar">Actions</h2>-->
                <!--        <div class="wrap">-->

                <!--        
Notice: Undefined variable: content in /home/matthijs/Development/Sites/Pico/pico/Application/modules/Admin/helper/LinkList.php on line 21

Notice: Undefined variable: content in /home/matthijs/Development/Sites/Pico/pico/Application/modules/Admin/helper/LinkList.php on line 21

Notice: Undefined variable: content in /home/matthijs/Development/Sites/Pico/pico/Application/modules/Admin/helper/LinkList.php on line 21

Notice: Undefined variable: content in /home/matthijs/Development/Sites/Pico/pico/Application/modules/Admin/helper/LinkList.php on line 21

Notice: Undefined variable: content in /home/matthijs/Development/Sites/Pico/pico/Application/modules/Admin/helper/LinkList.php on line 21

Notice: Undefined variable: content in /home/matthijs/Development/Sites/Pico/pico/Application/modules/Admin/helper/LinkList.php on line 21

Notice: Undefined variable: content in /home/matthijs/Development/Sites/Pico/pico/Application/modules/Admin/helper/LinkList.php on line 21
	<ul>
		<li/>
		<li/>
		<li/>
		<li/>
		<li/>
		<li/>
		<li/>
	</ul>
	-->
                <!--        </div>-->
                <!--</div>-->
                <!---->
			</div>
		</div>
    </div>
</body>
</html>


