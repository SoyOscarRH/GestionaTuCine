
<?php 

/*==========================================================================================================
====================================       HEADER OF ALL HTML               ================================
============================================================================================================

HEADER OF ALL HTML */

if (!isset($ColorOfNavbar)) $ColorOfNavbar = "teal lighten-3";                  //Color of NavBar
if (!isset($ColorOfNavbarMobile)) $ColorOfNavbarMobile = "teal lighten-2";      //Color of NavBar on Mobile
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
            <meta name="theme-color" content="#80cbc4">

            <!-- Please UFT IS LOVE -->
            <meta charset="UTF-8">

            <title><?php echo $HTMLTitle;?></title>

        <!--  +++++++++++++++++++++++++++++++++++++++++++++++++ -->
        <!--  +++++++++++++++   PAGE STYLE  +++++++++++++++++++ -->
        <!--  +++++++++++++++++++++++++++++++++++++++++++++++++ -->

            <!-- Icon of the Page -->
            <link rel="shortcut icon" href="<?php echo $HTMLDocumentRoot;?>Media/favicon.ico" type="image/x-icon">
            <link rel="icon" href="<?php echo $HTMLDocumentRoot;?>Media/favicon.ico" type="image/x-icon">

            <!-- Google Material Fonts -->
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

            <!-- Materialize -->
            <link href="Style/CSS/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
            <link href="Style/CSS/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

            <script src="https://code.jquery.com/jquery-3.2.1.min.js">  </script>
            <script src="Javascript/Materialize/materialize.js">        </script>
            <script src="Javascript/Materialize/init.js">               </script>
    
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

            <nav class="<?php echo $ColorOfNavbar;?>">
            <div class="nav-wrapper container">
                
                <!-- Name of the Page -->
                <div class="brand-logo white-text" style="font-size: 1.35rem;">
                    <?php if (!WeAreAtMobile()) : ?>

                        <a href="<?php echo $HTMLDocumentRoot; ?>"><i class="material-icons">home</i></a>

                    <?php endif;?>
                    <?php echo $HTMLTitle; ?>
                </div>

                <?php if (WeAreAtMobile()): ?> 

                    <a href="<?php echo $HTMLDocumentRoot; ?>" class="brand-logo white-text right">
                        <i class="material-icons">home</i>
                    </a>

                <?php endif;?>

                <!-- Menu for Mobile -->
                <a href="#" data-activates="mobile-demo" class="button-collapse white-text">
                    <i class="material-icons">menu</i>
                </a>
                 
                <!-- Links for Normal Web -->
                <ul class="right hide-on-med-and-down">
                    <?php foreach($LinksForPagesDesktop as $NameOfLink => $Link): ?>

                        <li>
                            <a class="white-text" href="<?php echo $Link ?>">
                                <?php echo $NameOfLink ?>
                            </a>
                        </li>

                    <?php endforeach;?>
                </ul>

            </div>
            </nav>

        </div>

        <!-- Links for Mobile Web -->
        <ul class="side-nav <?php echo $ColorOfNavbarMobile;?>" id="mobile-demo">

            <br><br>
            <h4 class="center-align white-text" style="font-weight: 300;"><b>Menú</b> de Páginas</h4>
            <br><br>

            <?php foreach($LinksForPagesMobile as $NameOfLink => $Link): ?>
                <li>
                    <a href="<?php echo$Link;?>">
                        <span class="white-text flow-text"><?php echo $NameOfLink; ?></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

    </header>
