<?php

//Account prova username: AAAAAAAAAAAAAAAA, password: admin SHA-1() D033E22AE348AEB5660FC2140AEC35850C4DA997
//Account Rossi, password: vaccino;

define("INDIRIZZODB","localhost");
define("NOME_UTENTE","contromossa");
define("PASSWORD","");
define("DB","my_contromossa");



function connect_db(){
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
  $conn = new mysqli( INDIRIZZODB , NOME_UTENTE, PASSWORD, DB);
  if($conn -> connect_errno > 0)
    header("Location_error.php?tipo=dbconn");
    return $conn;
}


function checkUser(){
  $conn = connect_db();

  $stmt = $conn -> prepare("SELECT codice_fiscale, CONCAT(nome,' ', cognome) AS nominativo, ruolo FROM dati_persone WHERE codice_fiscale=? AND password = SHA1(?) AND is_verificato=1");
  $stmt -> bind_param("ss", $conn->real_escape_string(strtoupper($_POST['codice_fiscale'])),  $conn->real_escape_string($_POST['password']) );
  $stmt -> execute();
  $result = $stmt -> get_result();
  $stmt -> close();
  $conn -> close();

  if($row = $result -> fetch_assoc() ){
    $_SESSION['utente'] = array( $row['codice_fiscale'], $row['nominativo'], $row['ruolo'] );
  }
  return true;
  }

//Funzione di Redirect in base ai ruoli
function redirectAdminPage(){
  if( !isset($_SESSION['utente'][0]))
      header("Location: login.php");

  switch($_SESSION['utente'][2]){
        case 'cittadino':
          header("Location: profile.php");
          break;
        case 'prioritario':
          header("Location: profile.php");
          break;
        case 'admin':
          break;
  }
}
function redirectNotLogged(){
  if( !isset($_SESSION['utente'][0]))
      header("Location: login.php");
}

function redirectLogin(){
    switch($_SESSION['utente'][2]){
          case 'cittadino':
            header("Location: profile.php");
            break;
          case 'prioritario':
            header("Location: profile.php");
            break;
          case 'admin':
            header("Location: admin.php");
            break;
    }
}

function svg_esito($bool){
    if($bool == 1){
     return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                  <path d="M13.485 1.431a1.473 1.473 0 0 1 2.104 2.062l-7.84 9.801a1.473 1.473 0 0 1-2.12.04L.431 8.138a1.473 1.473 0 0 1 2.084-2.083l4.111 4.112 6.82-8.69a.486.486 0 0 1 .04-.045z"/>
                  </svg>';
    }
    else if ($bool == 0)
    {
      return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                    <path d="M1.293 1.293a1 1 0 0 1 1.414 0L8 6.586l5.293-5.293a1 1 0 1 1 1.414 1.414L9.414 8l5.293 5.293a1 1 0 0 1-1.414 1.414L8 9.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L6.586 8 1.293 2.707a1 1 0 0 1 0-1.414z"/>
                    </svg>';
    }
}


function genere($value){
  switch($value){
    case 0:
        $string = "Maschio";
        break;
    case 1:
        $string = "Femmina";
        break;
    default;
        $string = "Non Specificato";
        break;
  }
  return $string;
}


?>
