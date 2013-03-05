<!doctype html>
<html lang="en" ng-app="myApp">
<head>
  <meta charset="utf-8">
  <title>My AngularJS App</title>
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css" rel="stylesheet">
  <link rel="stylesheet" href="app/css/app.css"/>
	<link rel="stylesheet" href="app/css/ng-grid.css"/>
</head>
<body ng-controller="MyCtrl">
	<div class="container">
		<form name="membre" ng-submit="save(membre)">
			<fieldset>
			<legend>Ajouter un utilisateur</legend>
		   <div class="form-item">
				<label>Prénom:</label>
			 <input ng-model="membre.prenom" type="text" required/><br />
			</div>
		  <div class="form-item"> 
			<label>Nom:</label>
			 <input ng-model="membre.nom" type="text" required/><br />
			</div>
			<div class="form-item">
			 <label>Adresse:</label>
			 <input ng-model="membre.adresse" type="text" required/><br />
			</div>
			<div class="form-item">
			 <label>Profession:</label>
			 <input ng-model="membre.profession" type="text" required/><br />
			</div>
			<div class="form-item">
			 <label>Email:</label>
			 <input ng-model="membre.email" type="email" required /><br />
			</div>
		   <button class="btn btn-primary">Save</button>
			<fieldset>
		</form>
		<h2>Tableau des utilisateurs</h2>
		<div class="gridStyle" ng-grid="gridOptions"></div>
	
	</div>

  <!-- In production use:
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js"></script>
  -->
  <script type="text/javascript" src="app/lib/jquery-1.8.2.min.js"></script>
	<script src="app/lib/angular/angular.js"></script>
	<script src="app/lib/ng-grid/ng-grid-1.9.0.debug.js"></script>
	<script src="app/lib/ng-grid/ng-grid-flexible-height.js"></script>
  <script src="app/js/app.js"></script>
  <script src="app/js/services.js"></script>
  <script src="app/js/controllers.js"></script>
  <script src="app/js/filters.js"></script>
  <script src="app/js/directives.js"></script>
	<script src="app/js/main.js"></script>
</body>
</html>


