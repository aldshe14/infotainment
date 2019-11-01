<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
	  
    <a class="navbar-brand" href="index.php">
        <img src="img/logo.png" width="20%" height="100%" alt="Logo Presidenti">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='index.php') echo "active"; ?>">
            <a class="nav-link" href="index.php">HOME</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='presidenti.php') echo "active"; ?>">
            <a class="nav-link" href="presidenti.php">MENU1</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='dekorime.php') echo "active"; ?>">
            <a class="nav-link" href="dekorime.php">MENU2</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='nenshtetesi.php') echo "active"; ?>">
            <a class="nav-link" href="nenshtetesi.php"><i class="fas fa-user"></i> </i> MENU3</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='dekret.php') echo "active"; ?>">
            <a class="nav-link" href="dekret.php">Settings</a>
        </li>

        <?php
            //----------------Admin Menu----------------
        if(isset($_SESSION['user_id']) && isset($_SESSION['loggedin'])){
            
            
            if($_SESSION['role']==777){
                echo '<li class="nav-item dropdown';
                    if(basename($_SERVER["SCRIPT_FILENAME"])=='user_reset.php' || basename($_SERVER["SCRIPT_FILENAME"])=='users.php') {  
                        echo ' active"';
                    }
                    echo '">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i> USERS
                    </a>';
                    echo '
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item';
                        if(basename($_SERVER["SCRIPT_FILENAME"])=='users.php') echo " active";
                    echo '" href="users.php">All Users</a>';
                    echo '
                        <a class="dropdown-item';
                        if(basename($_SERVER["SCRIPT_FILENAME"])=='user_reset.php') echo " active";
                    echo '" href="user_reset.php">Password Reset</a>
                    </div>
                </li>';
                
                echo '
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">Action</a>
                <a class="dropdown-item" href="#">Another action</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Something else here</a>
                </div>';
            }
            echo '<li class="nav-item ';
            if(basename($_SERVER["SCRIPT_FILENAME"])=='logout.php'){  
                echo 'active"';
            }
            echo '"><a class="nav-link" href="logout.php">LOGOUT</a></li>';

            
        }
        ?>

        </ul>
    </div>
</nav>