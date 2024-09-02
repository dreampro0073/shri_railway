app.controller('checkoutAlertCtrl', function($scope , $http, $timeout , DBService, $interval) {
    $scope.loading = false;

    $scope.checkoutAlert = function() {
        var autoAlertStatus = auto_alert_status;
        if(autoAlertStatus == 1){
            DBService.postCall($scope.filter, '/api/sitting/checkout-alert').then((data) => {
                if (data.success) {
                    $scope.speak(data.message);
                }
            });
        }
    }
    
    setInterval($scope.checkoutAlert, 15000);

    $scope.speak = function(message) {
        // const utterance = new SpeechSynthesisUtterance(message);
        // const voices = speechSynthesis.getVoices();
        // // utterance.voice = voices[0];
        // utterance.voice = voices.find(voice => voice.lang === 'en-US');
        // utterance.rate = 0.8; 
        // utterance.pitch = 1.2;
        // utterance.volume = 1;     
        // speechSynthesis.speak(utterance);

        const utterance = new SpeechSynthesisUtterance(message);

     
        let voices = speechSynthesis.getVoices();

        let indianVoice = voices.find(voice => voice.lang.includes('en-IN'));
        if (indianVoice) {
          utterance.voice = indianVoice;
        } else {
          utterance.voice = voices[0];
        }
        utterance.pitch = 0.8; // Lower pitch for a deeper voice
        utterance.rate = 0.9;  // Slightly slower rate for a more deliberate pace
        speechSynthesis.speak(utterance);
    }

});

app.controller('dashboardCtrl', function($scope , $http, $timeout , DBService, $interval) {
    $scope.auto_alert_status = auto_alert_status;
    $scope.changeAlert = () => {
        DBService.postCall($scope.filter, '/api/set-checkout-alert').then((data) => {
            if (data.success) {
                auto_alert_status = data.auto_alert_status;
                $scope.auto_alert_status = data.auto_alert_status;
                // console.log(data);
            }
        }); 
    }
   
    
});