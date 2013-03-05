function MyCtrl($scope) {
		$.getJSON('json/inscrits2.json', function(data) {
        $scope.$apply(function(){
            $scope.myData = data;
        });
    });
    
		$scope.gridOptions = { 
			data : 'myData',
			enableCellSelection: true,
			canSelectRows: false
		};// $scope.myData is also acceptable but will not update properly. OK to use the object if you don't care about updating the data in the grid.
		
		$scope.save = function(membre){
		  $scope.myData.push(angular.copy(membre));
			$http.put('json/aaa.json', $scope.membre);
			console.log($scope.myData);
		};
}