var app = angular.module('mendApp', []);
app.controller('crudController', function($scope, $http) {

    $scope.url = '../test_back/index.php';

    $scope.fetchData = function() {
        $http.get($scope.url)
            .then(function(response) {
                $scope.tasks = response.data.tasks_list;
            });
    }

    $scope.addTask = function() {

        var data = { name: $scope.new_task_name };
        $http.post($scope.url, data, 'application/x-www-form-urlencoded')
            .then(function(response) {
                $scope.fetchData();
                console.log(response);
            });

        $scope.new_task_name = null;
    }

    $scope.deleteTask = function(id) {
        console.log(id);
        $http.delete($scope.url + '?id=' + id)
            .then(function(response) {
                $scope.fetchData();
                console.log(response);
            });
    }
})