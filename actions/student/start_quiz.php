<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';
require_once '../../classes/Quiz.php';
require_once '../../classes/Attempt.php';

Security::requireStudent();
///check wach kayen quiz_id f lien
if (!isset($_GET['quiz_id'])) {
    $catId = $_GET['category_id'];

    if ($catId) {
        header("Location: ../../pages/student/quizzes.php?category_id=" . $catId);
    } else {
        header("Location: ../../pages/student/categories.php");
    }
    exit;
}
$quizId = $_GET['quiz_id'];
$studentId = $_SESSION['user_id'];

/// check wach int mojab
if (!ctype_digit($_GET['quiz_id'])) {
    if (isset($_GET['category_id']) && ctype_digit($_GET['category_id'])) {
        header("Location: ../../pages/student/quizzes.php?category_id=" . $_GET['category_id']);
    } else {
        header("Location: ../../pages/student/categories.php");
    }
    exit;
}
//check wach kayen f database
$quiz = new Quiz;
$isOndbQuiz = $quiz->getById($quizId);

if (!$isOndbQuiz) {
    if (isset($_GET['category_id']) && ctype_digit($_GET['category_id'])) {
        header("Location: ../../pages/student/quizzes.php?category_id=" . $_GET['category_id']);
    } else {
        header("Location: ../../pages/student/categories.php");
    }
    exit;
}
///check wach l quiz active 
$isActive = $quiz->isActive($quizId);

if (!$isActive) {
    if (isset($_GET['category_id']) && ctype_digit($_GET['category_id'])) {
        header("Location: ../../pages/student/quizzes.php?category_id=" . $_GET['category_id']);
    } else {
        header("Location: ../../pages/student/categories.php");
    }
    exit;
}

$attempt = new Attempt;
$hasAttempt = $attempt->hasAttempt($studentId, $quizId);

if ($hasAttempt) {
    if (isset($_GET['category_id']) && ctype_digit($_GET['category_id'])) {
        header("Location: ../../pages/student/quizzes.php?category_id=" . $_GET['category_id']);
    } else {
        header("Location: ../../pages/student/categories.php");
    }
    exit;
}

$startAttempt = $attempt->createAttempt($studentId, $quizId);

if ($startAttempt) {
    header("Location: ../../pages/student/quiz_pass.php?quiz_id=" . $quizId);
    exit;
} else {
    if (isset($_GET['category_id']) && ctype_digit($_GET['category_id'])) {
        header("Location: ../../pages/student/quizzes.php?category_id=" . $_GET['category_id']);
    } else {
        header("Location: ../../pages/student/categories.php");
    }
    exit;
}
