<?php
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Attempt.php';

Security::requireStudent();

if (!isset($_GET['attempt_id']) || !ctype_digit($_GET['attempt_id'])) {
    header('Location: categories.php');
    exit;
}

$attemptId = (int) $_GET['attempt_id'];
$studentId = $_SESSION['user_id'];

$db = Database::getInstance();

$sql = "SELECT a.*, q.titre
        FROM attempts a
        JOIN quiz q ON q.id = a.quiz_id
        WHERE a.id = ? AND a.student_id = ? AND a.is_finished = 1
        LIMIT 1";

$stmt = $db->query($sql, [$attemptId, $studentId]);
$result = $stmt->fetch();

if (!$result) {
    header('Location: categories.php');
    exit;
}

// ⚠️ مؤقت: score ما عندكش ف attempts
// دابا غير مثال
$score = 0;
$total = 0;
$percentage = 0;
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_teacher.php'; ?>

<div class="pt-20 max-w-3xl mx-auto">
    <div class="bg-white shadow-lg rounded-xl p-8 text-center">
        <h1 class="text-3xl font-bold mb-4">
            Résultat du Quiz
        </h1>

        <h2 class="text-xl text-gray-700 mb-6">
            <?= htmlspecialchars($result['titre']) ?>
        </h2>

        <div class="text-5xl font-bold text-green-600 mb-4">
            <?= $percentage ?> %
        </div>

        <p class="text-gray-600 mb-6">
            Score : <?= $score ?> / <?= $total ?>
        </p>

        <a href="categories.php"
           class="inline-block bg-green-600 text-white px-6 py-3 rounded-lg">
            Retour aux catégories
        </a>
    </div>
</div>

<?php include '../partials/footer.php'; ?>
