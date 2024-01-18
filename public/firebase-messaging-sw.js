// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');

/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: 'AIzaSyDLG372eSezg-U6yE47UYzgqIgLIj1m-bU',
        authDomain: 'safaidaar.firebaseapp.com',
        databaseURL: 'https://safaidaar.firebaseio.com',
        projectId: 'safaidaar',
        storageBucket: 'safaidaar.appspot.com',
        messagingSenderId: '328612510203',
        appId: '1:328612510203:web:0badf4d2f49619a2e0735e',
        measurementId: 'G-RP43NNKS42',
});


// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);

    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };

    return self.registration.showNotification(
        title,
        options,
    );
});