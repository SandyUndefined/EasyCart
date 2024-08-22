var functions = require('firebase-functions');
var admin = require('firebase-admin');

var serviceAccount = require("./serviceAccountKey.json");
admin.initializeApp({
  credential: admin.credential.cert(serviceAccount),
  databaseURL: "YOUR_DATABASE_URL"
});

var delivery = require('./products/delivery');

//Multivendor delivery function
exports.deliveryDispatch = delivery.dispatch

//Delete auth user function
exports.deleteUser = functions.https.onCall(async (data, context) => {
    await admin.auth().deleteUser(data.uid).then(() => {
        console.log('Successfully deleted user');
        return { result: 'Successfully deleted user'};
    }).catch((error) => { 
        console.log('Error deleting user:', error);
        return { result: 'Error deleting user'};
    });
});