<?php
header('Content-Type: application/json');

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Attempt.php';
require_once '../../classes/Question.php';

/* 1️⃣ Vérifier student */
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Not authenticated'
    ]);
    exit;
}

$studentId = $_SESSION['user_id'];

/* 2️⃣ Lire JSON */
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['quiz_id'], $data['answers'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid data'
    ]);
    exit;
}

$quizId  = (int) $data['quiz_id'];
$answers = $data['answers'];

/* 3️⃣ Vérifier attempt ouverte */
$attemptObj = new Attempt();
$attempt = $attemptObj->getOpenAttempt($studentId, $quizId);

if (!$attempt) {
    echo json_encode([
        'success' => false,
        'message' => 'No open attempt'
    ]);
    exit;
}

/* 4️⃣ Correction */
$questionObj = new Question();
$questions = $questionObj->getQuestionsWithCorrectOption($quizId);

$score = 0;

foreach ($questions as $q) {
    $qid = $q['id_question'];
    $correct = $q['correct_option'];

    if (isset($answers[$qid]) && $answers[$qid] == $correct) {
        $score++;
    }
}

/* 5️⃣ Fermer attempt */
$attemptObj->finishAttempt($attempt['id']);

/* 6️⃣ Réponse JSON */
echo json_encode([
    'success' => true,
    'attempt_id' => $attempt['id'],
    'score' => $score,
    'total' => count($questions)
]);
exit;
