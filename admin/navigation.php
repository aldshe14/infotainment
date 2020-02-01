<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">
	
    <a href="index.php" class="navbar-brand">
        <!-- Logo Image -->
        <img src="img/logo.png" width="215" height="50" alt="" class="d-inline-block align-middle mr-2">
    </a>
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='index.php') echo "active"; ?>">
            <a class="nav-link" href="index.php"><i class="fas fa-home"></i> HOME</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='displays.php') echo "active"; ?>">
            <a class="nav-link" href="displays.php"><i class="fas fa-tv"></i> DISPLAYS</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='images.php') echo "active"; ?>">
            <a class="nav-link" href="images.php"><i class="fas fa-images"></i> IMAGES</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='layouts.php') echo "active"; ?>">
            <a class="nav-link" href="layouts.php"><i class="far fa-window-maximize"></i> LAYOUTS</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='kalendarinfo.php') echo "active"; ?>">
            <a class="nav-link" href="kalendarinfo.php"><i class="fas fa-calendar-alt"></i> KALENDARINFO</a>
        </li>
        <li class="nav-item <?php if(basename($_SERVER["SCRIPT_FILENAME"])=='layouttimetable.php') echo "active"; ?>">
            <a class="nav-link" href="layouttimetable.php"><i class="fas fa-calendar-alt"></i> Layouttimetable</a>
        </li>
        <li class="nav-item dropdown 
                <?php
                     if(basename($_SERVER["SCRIPT_FILENAME"])=='supplierplan.php' || basename($_SERVER["SCRIPT_FILENAME"])=='fehlendeLehrer.php' || basename($_SERVER["SCRIPT_FILENAME"])=='suppliertabelle.php' ) {  
                        echo ' active"';
                    }
                ?>
        ">
            <a class="nav-link dropdown-toggle" href="#" id="suppliereb" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-calendar-check"></i> SUPPLIERPLAN</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="supplierplan.php"><i class="fas fa-calendar"></i> SUPPLIERPLAN</a>
                <a class="dropdown-item" href="fehlendeLehrer.php"><i class="fas fa-address-book"></i> FEHLENDELEHRER</a>
                <a class="dropdown-item" href="suppliertabelle.php"><i class="fas fa-table"></i> SUPPLIERTABELLE</a>
                <a class="dropdown-item" href="importStundenplan.php"><i class="fas fa-upload"></i> IMPORT STUNDENPLAN</a>
            </div>
        </li>

        <?php
            //----------------Admin Menu----------------
        if(isset($_SESSION['user_id']) && isset($_SESSION['loggedin'])){
                
            if($_SESSION['role']==777){
                echo '<li class="nav-item dropdown';
                    if(basename($_SERVER["SCRIPT_FILENAME"])=='user_reset.php' || basename($_SERVER["SCRIPT_FILENAME"])=='users.php') {  
                        echo ' active';
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
                </li>
                <li class="nav-item '; if(basename($_SERVER["SCRIPT_FILENAME"])=='settings.php') echo 'active'; echo '">
                    <a class="nav-link" href="settings.php"><i class="fas fa-cog"></i> SETTINGS</a>
                </li>
                ';
            }
            echo '<li class="nav-item ';
            if(basename($_SERVER["SCRIPT_FILENAME"])=='logout.php'){  
                echo 'active"';
            }
            echo '"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> LOGOUT</a></li>';

            
        }
        ?>

        </ul>
    </div>
</nav>
