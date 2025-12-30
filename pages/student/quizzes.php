<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';
require_once '../../classes/Quiz.php';

///daba etu kayeclicki 3la categorie 
/// kaydoz lpage quiz wel affichage ghi les quiz dyal had l category
/// safi khalin

// nebdaw f role khaso ykon etudiant
Security::requireStudent();

//daba khasna nemchiw n9ado l click 3la specific category bach ghadi n3arfoha ndiro GET za3ma lel id f url
/// ila makanch category_id f lien raj3o lpage category
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

        <!-- Aucun quiz -->
        <?php if (empty($quizzes)): ?>
            <div class="bg-white rounded-xl shadow-md p-8 text-center">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aucun quiz</h3>
                <p class="text-gray-600 mb-6">
                    Il n’y a pas encore de quiz dans cette catégorie. Essayez une autre catégorie.
                </p>
                <a href="categories.php"
                    class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                    Retour aux catégories
                </a>
            </div>
        <?php else: ?>

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

                            <a href="quiz_start.php?quiz_id=<?= $quiz['id'] ?>"
                                class="block w-full text-center bg-<?= $color ?>-600 text-white py-2 rounded-lg font-semibold hover:bg-<?= $color ?>-700 transition">
                                Passer le quiz
                            </a>
                        </div>

                    </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php include '../partials/footer.php'; ?>