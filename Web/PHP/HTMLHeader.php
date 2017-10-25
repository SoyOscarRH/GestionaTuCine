
<?php 

/*==========================================================================================================
====================================       HEADER OF ALL HTML               ================================
============================================================================================================

HEADER OF ALL HTML */




?>


<!DOCTYPE html>
<HTML>


    <!--  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <!--  +++++++++++++++++++++++++++++++++++++++       HEADINGS            ++++++++++++++++++++++++++++++++++++++ -->
    <!--  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <HEAD>

        <!--  +++++++++++++++++++++++++++++++++++++++++++++++++ -->
        <!--  +++++++++++++++   PAGE INFO   +++++++++++++++++++ -->
        <!--  +++++++++++++++++++++++++++++++++++++++++++++++++ -->

            <!-- Page Info -->
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />  

            <!-- How we should see it -->
            <meta name="viewport" content="width=device-width, initial-scale=1"/>

            <!-- Color in Android Header -->
            <meta name="theme-color" content="#2196F3">

            <title><?php echo $HTMLTitle;?></title>

        <!--  +++++++++++++++++++++++++++++++++++++++++++++++++ -->
        <!--  +++++++++++++++   PAGE STYLE  +++++++++++++++++++ -->
        <!--  +++++++++++++++++++++++++++++++++++++++++++++++++ -->

            <!-- Icon of the Page -->
            <link href="MediaAndStyle/favicon.ico" rel="shortcut icon" type="image/x-icon"/>

            <!-- Google Material Fonts -->
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

            <!-- Materialize -->
            <link href="Style/CSS/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
            <link href="Style/CSS/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

    </HEAD>


        

        


<!--  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<!--  +++++++++++++++++++++++++++++++++++++++       BODY                ++++++++++++++++++++++++++++++++++++++ -->
<!--  ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
<BODY>


    <!--  +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ -->
    <!--  +++++++++++++++++    NAVITAGION BAR       +++++++++++++++++++++ -->
    <!--  ++++++++++++++++++++++++*++++++++++++++++++++++++++++++++++++++ -->
    <header>
        <div class="navbar-fixed">

            <nav class="teal lighten-3">
            <div class="nav-wrapper container">
                
                <!-- Name of the Page -->
                <div class="brand-logo white-text" style="font-size: 1.35rem;">
                    <?php if (!WeAreAtMobile()) 
                        echo '<a href="'.$HTMLDocumentRoot.'"><i class="material-icons">home</i></a>';
                        echo $HTMLTitle."\n";
                    ?>
                </div>

                <?php if (WeAreAtMobile()) 
                    echo '<a href="'.$HTMLDocumentRoot.'" class="brand-logo white-text right"><i class="material-icons">home</i></a>';
                ?>
                
                <!-- Menu for Mobile -->
                <a href="#" data-activates="mobile-demo" class="button-collapse white-text">
                    <i class="material-icons">menu</i>
                </a>
                 
                <!-- Links for Normal Web -->
                <ul class="right hide-on-med-and-down">
                    <?php
                        echo "\n\t\t\t\t\t";
                        foreach($LinksForPages as $NameOfLink => $Link){
                            echo '<li> <a class="white-text" href="'.$Link.'">'.$NameOfLink.'</a></li>';
                            echo "\n\t\t\t\t\t";
                        }
                        echo "\n";
                    ?>
                </ul>

            </div>
            </nav>

        </div>

        <!-- Links for Mobile Web -->
        <ul class="side-nav" id="mobile-demo">
            <?php
                echo "\n\t\t\t";
                foreach($LinksForPages as $NameOfLink => $Link){
                    echo '<li> <a href="'.$Link.'">'.$NameOfLink.'</a></li>';
                    echo "\n\t\t\t";
                }
                echo "\n";
            ?>
        </ul>

    </header>
