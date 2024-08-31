
app.controller('checkoutAlertCtrl', function($scope , $http, $timeout , DBService, $interval) {
    $scope.loading = false;

    $scope.checkoutAlert = function() {
        DBService.postCall($scope.filter, '/api/sitting/checkout-alert').then((data) => {
            if (data.success) {
                $scope.speak(data.message);
            }
        });
    }
    setInterval($scope.checkoutAlert, 20000);

    $scope.speak = function(message) {
        const utterance = new SpeechSynthesisUtterance(message);
        const voices = speechSynthesis.getVoices();
        utterance.voice = voices[0];
        utterance.rate = 0.8; 
        utterance.pitch = 1;
        utterance.volume = 1;     
        speechSynthesis.speak(utterance);
    }
});