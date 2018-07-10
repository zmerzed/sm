/**
 * Created by remz on 7/10/2018.
 */
var app = angular.module("smApp", []);
app.run(function() {
   console.log('sm app run....')
});
app.filter('dataURLtoBlob', function() {
    return function(dataurl) {
        var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new Blob([u8arr], {type:mime});

    }
});