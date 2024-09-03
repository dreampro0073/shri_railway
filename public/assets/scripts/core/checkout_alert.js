app.controller('checkoutAlertCtrl', function($scope , $http, $timeout , DBService, $interval) {
    $scope.loading = false;

    $scope.checkoutAlert = function() {

        var autoAlertStatus = auto_alert_status;
        if(autoAlertStatus == 1 && authCheck == 1){
            DBService.postCall($scope.filter, '/api/sitting/checkout-alert').then((data) => {
                if (data.success) {
                    $scope.speak(data.message);
                }
            });
        }
    }
    
    setInterval($scope.checkoutAlert, 10000);

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

     
        // let voices = speechSynthesis.getVoices();

        // let indianVoice = voices.find(voice => voice.lang.includes('hi-IN'));
        // utterance.voice = voices.find(voice => voice.lang === 'en-US');

        // if (indianVoice) {
        //   utterance.voice = indianVoice;
        // } else {
        //   utterance.voice = voices[0];
        // }
        // utterance.lang = 'hi-IN'; // Set language to Hindi
        
        // utterance.voice = voices.find(voice => voice.lang === 'en-US');
        // utterance.pitch = 0.87; // Lower pitch for a deeper voice
        // utterance.rate = 0.78;  // Slightly slower rate for a more deliberate pace
        // speechSynthesis.speak(utterance);

        // Set language to Hindi
        utterance.lang = 'hi-IN'; 
        
        // Get the list of voices available on the browser
        const voices = window.speechSynthesis.getVoices();
        
        // Select a female voice (usually includes the word "female" or "फ़ीमेल" in the name)
        const femaleVoice = voices.find(voice => voice.lang === 'hi-IN' && (voice.name.includes('Female') || voice.name.includes('फ़ीमेल')));
        
        // If a female voice is found, set it to the speech
        if (femaleVoice) {
            utterance.voice = femaleVoice;
        }
        
        // Set pitch, rate, and volume
        utterance.pitch = 1;
        utterance.rate = 1;
        utterance.volume = 1;

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