import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.2/firebase-app.js";
import { getAnalytics } from "https://www.gstatic.com/firebasejs/9.6.2/firebase-analytics.js";
import { getAuth, signInWithPopup, GoogleAuthProvider, GithubAuthProvider, FacebookAuthProvider } from "https://www.gstatic.com/firebasejs/9.6.2/firebase-auth.js";

const firebaseConfig = {

    apiKey: "AIzaSyBk0grJFp7A7OUmrzA8z3N0Uz5fsDETPRg",
    authDomain: "uptask-338117.firebaseapp.com",
    projectId: "uptask-338117",
    storageBucket: "uptask-338117.appspot.com",
    messagingSenderId: "1091955488079",
    appId: "1:1091955488079:web:a984b618714fab6bc4413a",
    measurementId: "G-JTS7ESBEY4"

};


// Initialize Firebase

const app = initializeApp(firebaseConfig);
const analytics = getAnalytics(app);

document.getElementById('btnloging').addEventListener('click', function (e) {
    e.preventDefault();
    const auth = getAuth();
    const provider = new GoogleAuthProvider();
    signInWithPopup(auth, provider)
        .then((result) => {
            const credential = GoogleAuthProvider.credentialFromResult(result);
            const token = credential.accessToken;
            const user = result.user;
            login(user);
        })
        .catch((error) => {
            const errorCode = error.code;
            const errorMessage = error.message;
            const email = error.email;
            const credential = GoogleAuthProvider.credentialFromError(error);
            // ...
        });
    });

    document.getElementById('btnloginh').addEventListener('click', function (e) {
    e.preventDefault();
    const auth = getAuth();
    const provider = new GithubAuthProvider();
    signInWithPopup(auth, provider)
        .then((result) => {
            const credential = GithubAuthProvider.credentialFromResult(result);
            const token = credential.accessToken;

            const user = result.user;
            login(user);
        })
        .catch((error) => {
            const errorCode = error.code;
            const errorMessage = error.message;
            const email = error.email;
            const credential = GithubAuthProvider.credentialFromError(error);
            // ...
        });
    });

document.getElementById('btnloginf').addEventListener('click', function (e) {
    e.preventDefault();
    const auth = getAuth();
    const provider = new FacebookAuthProvider();
    signInWithPopup(auth, provider)
        .then((result) => {
            const user = result.user;

            const credential = FacebookAuthProvider.credentialFromResult(result);
            const accessToken = credential.accessToken;
            console.log(user);
        })
        .catch((error) => {
            // Handle Errors here.
            const errorCode = error.code;
            const errorMessage = error.message;
            // The email of the user's account used.
            const email = error.email;
            // The AuthCredential type that was used.
            const credential = FacebookAuthProvider.credentialFromError(error);
        });   
});


async function login(user) {
    const datos = new FormData();
    datos.append('email', user.providerData[0].email);
    datos.append('nombre', user.providerData[0].displayName);
    datos.append('confirmado', '1');
    
    try {
        const url = "http://localhost/auth/login_social";
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();
        
        if (resultado.tipo === 'exito') {            
            window.open('http://localhost/dashboard', '_self');
        }
        

    } catch (error) {
        console.log(error);
    }
}
