angular.module('smApp')
.controller('profileController', function(
    $scope,
    $http,
    $filter
) {
    var apiUrl = ROOTURL + '/wp-json/v1/client/upload';
    
    $scope.upload = function(formJson) {

        // var formData = new FormData();
        //
        // for (var i in formJson)
        // {
        //
        // }
       // AjaxService.upload({test})

        console.log($filter('dataURLtoBlob')(CAMERA_DATA_URL));
        console.log(USER_ID);
        var blob = $filter('dataURLtoBlob')(CAMERA_DATA_URL);
        var formData = new FormData();
        formData.append("myFile", blob, "thumb.jpg");
        formData.append("userId", USER_ID);
        $http.post(apiUrl, formData, {headers: {'Content-Type': undefined, 'Process-Data':false}});
    }
});