<?php require_once 'header-top.php' ?>

<?php if( isset($_POST['submit']) && $_POST['submit']=="LOGIN" ){
  if( checkUser() )
      redirectLogin();
}
?>

  <div id="login">
          <h3 class="text-center text-white pt-5">Login form</h3>
          <div class="container">
              <div id="login-row" class="row justify-content-center align-items-center">
                  <div id="login-column" class="col-md-6">
                      <div id="login-box" class="col-md-12">
                          <form id="login-form" class="form" action="" method="post">
                              <h3 class="h3">Login</h3>
                              <div class="form-group">
                                  <label for="username" class="form-label"><svg x="0px" y="0px" width="12px" height="13px">
                                    <path fill="#B1B7C4" d="M8.9,7.2C9,6.9,9,6.7,9,6.5v-4C9,1.1,7.9,0,6.5,0h-1C4.1,0,3,1.1,3,2.5v4c0,0.2,0,0.4,0.1,0.7 C1.3,7.8,0,9.5,0,11.5V13h12v-1.5C12,9.5,10.7,7.8,8.9,7.2z M4,2.5C4,1.7,4.7,1,5.5,1h1C7.3,1,8,1.7,8,2.5v4c0,0.2,0,0.4-0.1,0.6 l0.1,0L7.9,7.3C7.6,7.8,7.1,8.2,6.5,8.2h-1c-0.6,0-1.1-0.4-1.4-0.9L4.1,7.1l0.1,0C4,6.9,4,6.7,4,6.5V2.5z M11,12H1v-0.5 c0-1.6,1-2.9,2.4-3.4c0.5,0.7,1.2,1.1,2.1,1.1h1c0.8,0,1.6-0.4,2.1-1.1C10,8.5,11,9.9,11,11.5V12z"/>
                                  </svg> Codice Fiscale</label><br>
                                  <input type="text" name="codice_fiscale" id="username" class="form-control">
                              </div>
                              <div class="form-group">
                                  <label for="password" class="form-label"><svg x="0px" y="0px" width="15px" height="5px">
                                    <g>
                                      <path fill="#B1B7C4" d="M6,2L6,2c0-1.1-1-2-2.1-2H2.1C1,0,0,0.9,0,2.1v0.8C0,4.1,1,5,2.1,5h1.7C5,5,6,4.1,6,2.9V3h5v1h1V3h1v2h1V3h1 V2H6z M5.1,2.9c0,0.7-0.6,1.2-1.3,1.2H2.1c-0.7,0-1.3-0.6-1.3-1.2V2.1c0-0.7,0.6-1.2,1.3-1.2h1.7c0.7,0,1.3,0.6,1.3,1.2V2.9z"/>
                                    </g>
                                  </svg> Password</label><br>
                                  <input type="password" name="password" id="password" class="form-control">
                              </div>
                              <br>
                              <div class="form-group">
                                  <input type="submit" name="submit" class="btn btn-primary" value="LOGIN">
                              </div>
                              <div id="register-link" class="text-right">
                                  <br>
                                  <a href="register.php" class="form-label">Registrati qui</a>
                              </div>
                              <div class="row">
                                <p></p>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>




<?php require_once 'footer.php' ?>
