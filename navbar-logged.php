

<nav id="navbar_logged" class="navbar">
  <ul>
    <li><a class="nav-link scrollto active" href="index.php">Home</a></li>
    <li><a class="nav-link" href="#about">About</a></li>
    <li class="nav-item dropdown">
         <a class="getstarted dropdown-toggle" href="profile.php" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
           <?php echo "Benvenuto, " . $_SESSION['utente'][1]; ?>
         </a>
         <ul class="dropdown-menu" style="z-index:1021" aria-labelledby="navbarDropdownMenuLink">
           <?php if(isset($_SESSION['utente'][2])){
                 switch($_SESSION['utente'][2]){
                   case "admin":
                    echo "<li><a class='dropdown-item' href='admin.php'>Pannello Amministrazione</a></li>";
                    break;
                   default:
                    echo "<li><a class='dropdown-item' href='profile.php'>Profilo</a></li>";
                    break;
                 }
               }
                 ?>
           <li><a class="dropdown-item" href="logout.php">Logout</a></li>
         </ul>
       </li>
  </ul>
  <i class="bi bi-list mobile-nav-toggle"></i>
</nav><!-- .navbar -->
