<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';
require_once '../../classes/Quiz.php';
require_once '../../classes/Attempt.php';


Security::requireStudent();

if (!isset($_GET['category_id'])) {
    header("Location: categories.php");
    exit;
}
///check wach number
if (!ctype_digit($_GET['category_id'])) {
    header("Location: categories.php");
    exit;
}

// wach kayen f Database
$category = new Category;
$categoryId = (int) $_GET['category_id'];
$isIdOnDb = $category->getById($categoryId);

if (!$isIdOnDb) {
    header("Location: categories.php");
    exit;
}

$quizObj = new Quiz;

$quizzes = $quizObj->getActiveByCategory($categoryId);

// var_dump($thisCategory);

$studentId = $_SESSION['user_id'];
$attempt = new Attempt;

?>

<?php include '../partials/header.php'; ?>

<?php include '../partials/nav_student.php'; ?>

<div class="pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">
                <?= htmlspecialchars($isIdOnDb['nom']) ?>
            </h2>
            <p class="text-gray-600 mt-2">
                Évaluez vos compétences à travers ces quiz
            </p>
        </div>

        <!-- Liste des quiz -->
        <!-- Liste des quiz -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            $colors = ['indigo', 'blue', 'green', 'purple', 'pink', 'orange'];
            $logos = [
                'fa-solid fa-database',
                'fa-regular fa-file-code',
                'fa-brands fa-deviantart',
                'fa-solid fa-terminal',
                'fa-solid fa-microchip',
                'fa-solid fa-server'
            ];
            ?>

            <?php foreach ($quizzes as $index => $quiz): ?>
                <?php
                $color = $colors[$index % count($colors)];
                $logo  = $logos[$index % count($logos)];

                $hasOpen     = $attempt->hasOpenAttempt($studentId, $quiz['id']);
                $hasFinished = $attempt->hasFinishedAttempt($studentId, $quiz['id']);
                ?>
                <div class="bg-white rounded-xl shadow-md overflow-hidden">

                    <div class="bg-<?= $color ?>-600 text-white p-6 flex items-center justify-between">
                        <div>
                            <i class="<?= $logo ?> text-4xl mb-2"></i>
                            <h3 class="text-lg font-bold">
                                <?= htmlspecialchars($quiz['titre']) ?>
                            </h3>
                        </div>

                        <?php if ($hasFinished): ?>
                            <span class="bg-white text-<?= $color ?>-600 text-xs font-semibold px-3 py-1 rounded-full">
                                Déjà passé
                            </span>
                        <?php elseif ($hasOpen): ?>
                            <span class="bg-white text-orange-600 text-xs font-semibold px-3 py-1 rounded-full">
                                En cours
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="p-6">
                        <p class="text-gray-600 mb-4 text-sm">
                            <?= htmlspecialchars(substr($quiz['description'] ?? '', 0, 100)) ?>
                        </p>

                        <?php if ($hasFinished): ?>
                            <a href="../../pages/student/quiz_result.php?quiz_id=<?= $quiz['id'] ?>"
                                class="block w-full text-center bg-gray-600 text-white py-2 rounded-lg font-semibold">
                                Voir le résultat
                            </a>

                        <?php elseif ($hasOpen): ?>
                            <a href="../../pages/student/quiz_pass.php?quiz_id=<?= $quiz['id'] ?>"
                                class="block w-full text-center bg-orange-600 text-white py-2 rounded-lg font-semibold">
                                Continuer le quiz
                            </a>

                        <?php else: ?>
                            <a href="../../actions/student/start_quiz.php?quiz_id=<?= $quiz['id'] ?>&category_id=<?= $categoryId ?>"
                                class="block w-full text-center bg-<?= $color ?>-600 text-white py-2 rounded-lg font-semibold">
                                Passer le quiz
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<?php include '../partials/footer.php'; ?>