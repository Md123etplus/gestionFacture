<?php
define('ROOT', str_replace('Traitement\Utilisateurs.php', '', $_SERVER['SCRIPT_FILENAME']));
require_once ROOT . 'BD\Utilisateur.php';
if (empty($_POST)&& empty($_GET)) {
    // $users = getAllUsers();
    // $_SESSION['users'] = $users;
    // header('Location: ');
    // include('..\IHM\utilisateur\index.php');
    include(ROOT.'IHM\utilisateur\index.php');
    exit();
}
else if(isset($_GET['action'])){
    $action=$_GET['action'];
    switch($action){
        case "loadData":
            // $users = getAllUsers();
            // $_SESSION['users'] = $users;

            header('Location: ');
            include(ROOT.'IHM\Admin\dashboard.php');
            break;
        default:
            echo "Action non reconnue";
    }
}else{
    echo "Action non reconnue";
}
