
var keycloak = new Keycloak({
  url: 'http://localhost:8888/',
  realm: 'medapp',
  clientId: 'fhir-client' // must be a PUBLIC client
});

window.onload = function () {
  keycloak.init({
    onLoad: 'login-required',
    checkLoginIframe: false,
    flow: 'standard', // for auth code flow
    responseMode: 'fragment'
  }).then(function (authenticated) {
    if (authenticated && keycloak.tokenParsed && keycloak.tokenParsed.email) {
      console.log("Authenticated as", keycloak.tokenParsed.email);
      loadData(keycloak.tokenParsed.email); // pass email in
    } else {
      console.error("Keycloak authentication failed.");
      alert("Failed to authenticate with Keycloak.");
    }
  }).catch(function (err) {
    console.error("Keycloak Init Error", err);
    alert("Keycloak Init Error: " + JSON.stringify(err));
  });
};

function loadData(email) {
  $.ajax({
    type: "POST",
    url: "login_page.php",
    data: { email: email },
    async: false,
    success: function (response) {
      if (response == 'OK') {
        console.log("Session established for", email);
        window.location.href = 'http://localhost/doctor-main/Doctor_MainPage.php';
      } else {
        console.error("Login failed");
        console.error(response);
        //window.location.href = 'http://localhost:80/doctor-main/ErrorPage.php';
      }
    },
    error: function (xhr, status, error) {
      console.error("AJAX error:", error);
      alert("Failed to connect to backend.");
    }
  });
}
