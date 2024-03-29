<?php
/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
$session = Yii::$app->session;
$type = $session->get('user-type');
$label = $session->get('label');
$username = $session->get('username');
?>
<?php $active = Yii::$app->controller->id; ?>
<div class="sidebar" data-color="rose" data-background-color="black" data-image="../assets/img/sidebar-1.jpg">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->

    <div class="logo">
        <a href="#" class="simple-text logo-mini">
            8W
        </a>

        <a href="#" class="simple-text logo-normal">
            Dashboard
        </a>

    </div>

    <div class="sidebar-wrapper">
        <div class="user">
            <div class="photo">
                <img src="../admin/img/faces/avatar.jpg" />
            </div>
            <div class="user-info">
                <a data-toggle="collapse" href="#collapseExample" class="username">
                    <span>
                        <?php echo $username; ?>
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="collapse" id="collapseExample">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> MP </span>
                                <span class="sidebar-normal"> My Profile </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> EP </span>
                                <span class="sidebar-normal"> Edit Profile </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo Url::toRoute("/logout"); ?>">
                                <span class="sidebar-mini"> S </span>
                                <span class="sidebar-normal"> Logout </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav">
            <li class="nav-item <?php if($active == "dashboard"){echo 'active';} ?> ">
                <a class="nav-link" href="<?php echo Url::toRoute("/dashboard"); ?>">
                    <i class="material-icons">dashboard</i>
                    <p> Dashboard </p>
                </a>
            </li>
            <li class="nav-item  <?php if($active == "users" && $type == 'admin'){echo 'active';} ?>">
                <a class="nav-link" href="<?php echo Url::toRoute("/users"); ?>" title="Users">
                    <i class="material-icons">person</i>
                    <span data-localize="sidebar.nav.element.ELEMENTS">Users</span>
                </a>
            </li>
            <li class="nav-item  <?php if($active == "songs"){echo 'active';} ?>">
                <a class="nav-link" href="<?php echo Url::toRoute("/songs"); ?>" title="Songs">
                    <i class="fa fa-music"></i>
                    <span data-localize="sidebar.nav.element.ELEMENTS">Songs</span>
                </a>
            </li>
            <li class="nav-item  <?php if($active == "videos"){echo 'active';} ?>">
                <a class="nav-link" href="<?php echo Url::toRoute("/videos"); ?>" title="Videos">
                    <i class="fa fa-video-camera"></i>
                    <span data-localize="sidebar.nav.element.ELEMENTS">Videos</span>
                </a>
            </li>
        </ul>
    </div>
</div>
