<?php 

require_once '../../config/database.php';
require_once '../../classes/Security.php';
require_once '../../classes/quiz.php';

///daba etu kayeclicki 3la categorie 
/// kaydoz lpage quiz wel affichage ghi les quiz dyal had l category
/// safi khalin

// nebdaw f role khaso ykon etudiant
Security::requireStudent();

//daba khasna nemchiw n9ado l click 3la specific category bach ghadi n3arfoha ndiro GET za3ma lel id f url
/// ila makanch category_id f lien raj3o lpage category
if(!isset($_GET['category_id'])){
    header("Location: categories.php");
    exit;
}
///check wach number
if(!ctype_digit($_GET['category_id'])){
     header("Location: categories.php");
    exit;
}