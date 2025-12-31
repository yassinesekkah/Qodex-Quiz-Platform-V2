<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';
require_once '../../classes/Quiz.php';


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


$thisCategory = $category->getById($categoryId);
// var_dump($thisCategory);

?>

<?php include '../partials/header.php'; ?>

<?php include '../partials/nav_teacher.php'; ?>

<div class="pt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">
                <?= htmlspecialchars($thisCategory['nom']) ?>
            </h2>
            <p class="text-gray-600 mt-2">
                Évaluez vos compétences à travers ces quiz
            </p>
        </div>

        <!-- Liste des quiz -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $colors = ['indigo', 'blue', 'green', 'purple', 'pink', 'orange'];
            $logos = [
                'fa-solid fa-database',
                'fa-regular fa-file-code',
                'fa-brands fa-deviantart',
                'fa-solid fa-terminal',
                'fa-solid fa-microchip',
                'fa-solid fa-server'
            ]; ?>

            <?php foreach ($quizzes as $index => $quiz): ?>
                <?php $color = $colors[$index % count($colors)]; ?>
                <?php $logo = $logos[$index % count($logos)]; ?>
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden">

                    <div class="bg-<?= $color ?>-600 text-white p-6">
                        <i class="<?= $logo ?> text-4xl mb-3"></i>
                        <h3 class="text-lg font-bold">
                            <?= htmlspecialchars($quiz['titre']) ?>
                        </h3>
                    </div>

                    <!-- Body -->
                    <div class="p-6">
                        <p class="text-gray-600 mb-4 text-sm">
                            <?= htmlspecialchars(substr($quiz['description'] ?? '', 0, 100)) ?>
                        </p>

                        <a href="../../actions/student/start_quiz.php?quiz_id=<?= $quiz['id'] ?>&category_id=<?= $categoryId ?>"
                            class="block w-full text-center bg-<?= $color ?>-600 text-white py-2 rounded-lg font-semibold hover:bg-<?= $color ?>-700 transition">
                            Passer le quiz
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>