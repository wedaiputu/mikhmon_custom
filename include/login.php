<?php
session_start();
?>

<div style="padding-top: 5%;" class="login-box">
  <div class="card">
    <div class="card-header">
      <h3>Silakan Login</h3>
    </div>
    <div class="card-body">
      <div class="text-center pd-5">
        <img src="img/logoJinom.png" alt="MIKHMON Logo">
      </div>
      <div class="text-center">
        <span style="font-size: 25px; margin: 10px;">Jinom Voucher</span>
      </div>
      <center>
        <form id="loginForm" autocomplete="off">
          <table class="table" style="width:90%">
            <tr>
              <td class="align-middle text-center">
                <input style="width: 100%; height: 35px; font-size: 16px;" class="form-control" type="text" name="user" id="user" placeholder="User" required autofocus>
              </td>
            </tr>
            <tr>
              <td class="align-middle text-center">
                <input style="width: 100%; height: 35px; font-size: 16px;" class="form-control" type="password" name="password" id="password" placeholder="Password" required>
              </td>
            </tr>
            <tr>
              <td class="align-middle text-center">
                <input style="width: 100%; margin-top:20px; height: 35px; font-weight: bold; font-size: 17px;" class="btn-login" type="submit" name="login" value="Login">
              </td>
            </tr>
          </table>
        </form>
      </center>
    </div>
  </div>
</div>

<!-- Tempat untuk menampilkan error -->
<div id="errorMessage" style="color: red; font-weight: bold; text-align: center; margin-top: 10px;"></div>

<!-- Tempat untuk debugging response API -->
<div id="debugResponse" style="margin-top: 20px; text-align: center;"></div>

<script>
  // document.getElementById('loginForm').addEventListener('submit', function(event) {
  //   event.preventDefault();

  //   let user = document.getElementById('user').value;
  //   let password = document.getElementById('password').value;
  //   let errorMessage = document.getElementById('errorMessage');
  //   let debugResponse = document.getElementById('debugResponse');

  //   errorMessage.innerHTML = "";
  //   debugResponse.innerHTML = "";

  //   console.log("Mengirim data login:", {
  //     user,
  //     password
  //   });

  //   fetch('http://127.0.0.1:8000/api/login', {
  //       method: 'POST',
  //       headers: {
  //         'Content-Type': 'application/json'
  //       },
  //       credentials: 'include', // Pastikan cookie bisa dikirim jika API membutuhkan sesi
  //       body: JSON.stringify({
  //         user,
  //         password
  //       })
  //     })
  //     .then(response => response.json())
  //     .then(data => {
  //       console.log("Response API:", data);

  //       debugResponse.innerHTML = `
  //       <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid #ddd; text-align: left;">
  //         <strong>Debug API Response:</strong><br>
  //         <pre>${JSON.stringify(data, null, 2)}</pre>
  //         <strong>User:</strong> ${user} <br>
  //         <strong>Password:</strong> ${password} <br>
  //         <strong>Token:</strong> ${data.token ? data.token : 'Token tidak ditemukan'}
  //       </div>`;

  //       if (data.token) {
  //         return fetch('./include/session_login.php', {
  //           method: 'POST',
  //           headers: {
  //             'Content-Type': 'application/json'
  //           },
  //           body: JSON.stringify({
  //             token: data.token,
  //             user: data.user?.user || user,
  //             email: data.user?.email || 'N/A'
  //           })
  //         });
  //       } else {
  //         throw new Error("Data user tidak ditemukan atau token tidak diterima.");
  //       }
  //       console.log(data.token);
        
  //     })
  //     .then(() => {
  //       console.log("Session berhasil dibuat, redirect ke dashboard...");
  //       // window.location.href = './admin.php?id=sessions';
        
  //       window.location.href = '/admin.php?id=sessions';
  //     })
  //     .catch(error => {
  //       console.error("Error:", error);

  //       errorMessage.innerHTML = `
  //       <div style="color: red; padding: 10px; background: #ffe6e6; border: 1px solid red; border-radius: 5px;">
  //           <strong>Login Gagal!</strong><br>
  //           ${error.message} <br>
  //           <strong>User:</strong> ${user} <br>
  //           <strong>Password:</strong> ${password}
  //       </div>`;
  //     });
  // });


  document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    let user = document.getElementById('user').value;
    let password = document.getElementById('password').value;
    let errorMessage = document.getElementById('errorMessage');
    let debugResponse = document.getElementById('debugResponse');

    errorMessage.innerHTML = "";
    debugResponse.innerHTML = "";

    console.log("Mengirim data login:", { user, password });

    fetch('http://voucher.jinom.net/api/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        credentials: 'include', 
        body: JSON.stringify({ user, password })
    })
    .then(response => response.json())
    .then(data => {
        console.log("Response API:", data);

        debugResponse.innerHTML = `
        <div style="background: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid #ddd; text-align: left;">
          <strong>Debug API Response:</strong><br>
          <pre>${JSON.stringify(data, null, 2)}</pre>
          <strong>User:</strong> ${user} <br>
          <strong>Token:</strong> ${data.token ? data.token : 'Token tidak ditemukan'}
        </div>`;

        if (data.token) {
            // Simpan ke localStorage
            localStorage.setItem('authUser', JSON.stringify({
                token: data.token,
                user: data.user?.user || user,
                email: data.user?.email || 'N/A'
            }));

            return fetch('./include/session_login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    token: data.token,
                    user: data.user?.user || user,
                    email: data.user?.email || 'N/A'
                })
            });
        } else {
            throw new Error("Data user tidak ditemukan atau token tidak diterima.");
        }
    })
    .then(() => {
        console.log("Session berhasil dibuat, redirect ke dashboard...");
        window.location.href = '/admin.php?id=sessions';
    })
    .catch(error => {
        console.error("Error:", error);
        errorMessage.innerHTML = `
        <div style="color: red; padding: 10px; background: #ffe6e6; border: 1px solid red; border-radius: 5px;">
            <strong>Login Gagal!</strong><br>
            ${error.message} <br>
            <strong>User:</strong> ${user}
        </div>`;
    });
});

</script>