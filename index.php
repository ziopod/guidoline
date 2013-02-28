<!doctype html>
<html lang="en" ng-app="myApp">
<head>
  <meta charset="utf-8">
  <title>My AngularJS App</title>
  <link rel="stylesheet" href="app/css/app.css"/>
	<link rel="stylesheet" href="app/css/ng-grid.css"/>
</head>
<body ng-controller="MyCtrl">

	<div class="gridStyle" ng-grid="gridOptions"></div>

  <!-- In production use:
  <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.0.4/angular.min.js"></script>
  -->
  <script type="text/javascript" src="app/lib/jquery-1.8.2.min.js"></script>
	<script src="app/lib/angular/angular.js"></script>
	<script src="app/lib/ng-grid/ng-grid-1.9.0.debug.js"></script>
  <script src="app/js/app.js"></script>
  <script src="app/js/services.js"></script>
  <script src="app/js/controllers.js"></script>
  <script src="app/js/filters.js"></script>
  <script src="app/js/directives.js"></script>
	<script src="app/js/main.js"></script>
</body>
</html>
