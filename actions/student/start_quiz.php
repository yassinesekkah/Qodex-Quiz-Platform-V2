<?php 

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';
require_once '../../classes/Quiz.php';

Security::requireStudent();

if (!isset($_GET['quiz_id'])) {
    $catId = $_GET['category_id'];

    if($catId){
        header("Location: ../../pages/student/quizzes.php?category_id=" . $catId);
    }else{
        header("Location: ../../pages/student/categories.php");
    }
    exit;
}

$quizId = $_GET['quiz_id'];
$userId = $_SESSION['user_id'];

if (!ctype_digit($_GET['quiz_id'])) {
    header("Location: ../../pages/student/quizzes.php?category_id=" . $catId);
    exit;
}




