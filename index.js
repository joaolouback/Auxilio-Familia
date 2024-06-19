var formSignin = document.querySelector('#signin');
var formSignup = document.querySelector('#signup');
var btnColor = document.querySelector('.btnColor');

document.querySelector('#btnSignin').addEventListener('click', () => {
  formSignin.style.left = "25px";
  formSignup.style.left = "450px";
  btnColor.style.left = "0px";
});

document.querySelector('#btnSignup').addEventListener('click', () => {
  formSignin.style.left = "-450px";
  formSignup.style.left = "25px";
  btnColor.style.left = "110px";
});

document.querySelector('#signup').addEventListener('submit', function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append('action', 'signup');

  fetch('auth.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    alert(data.message);
    if (data.status === 'success') {
      document.querySelector('#btnSignin').click(); // Mudar para o formulário de login após cadastro bem-sucedido
    }
  })
  .catch(error => console.error('Erro:', error));
});

document.querySelector('#signin').addEventListener('submit', function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  formData.append('action', 'signin');

  fetch('auth.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(data => {
    alert(data.message);
    if (data.status === 'success') {
      window.location.href = data.redirect;
    }
  })
  .catch(error => console.error('Erro:', error));
});



/* Consulta */
document.addEventListener('DOMContentLoaded', function() {
  const consultFamilyForm = document.getElementById('consult-family-form');
  const consultMemberForm = document.getElementById('consult-member-form');
  const resultDiv = document.getElementById('result');

  consultFamilyForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(consultFamilyForm);

      fetch('process.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.text())
      .then(data => {
          resultDiv.innerHTML = data;
      })
      .catch(error => console.error('Error:', error));
  });

  consultMemberForm.addEventListener('submit', function(event) {
      event.preventDefault();
      const formData = new FormData(consultMemberForm);

      fetch('process.php', {
          method: 'POST',
          body: formData
      })
      .then(response => response.text())
      .then(data => {
          resultDiv.innerHTML = data;
      })
      .catch(error => console.error('Error:', error));
  });
});


