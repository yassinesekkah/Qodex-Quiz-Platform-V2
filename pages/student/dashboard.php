<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';
require_once '../../classes/Quiz.php';
require_once '../../classes/Result.php';

Security::requireStudent();

// Variables pour la navigation
$currentPage = 'dashboard';
$pageTitle = 'Tableau de bord';

// Récupérer les données de l'utilisateur
$studentId = $_SESSION['user_id'];
$userName = $_SESSION['user_nom'];

$quiz = new Quiz;
$totalQuiz = $quiz->countActiveQuizzes();

$categorie = new Category;
$totalCategorie = $categorie->countCategoriesWithActiveQuiz();

$result = new Result;
$stats = $result->getMyStats($_SESSION['user_id']);
$lastResults = $result->getLastResults($_SESSION['user_id']);


?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_student.php'; ?>

<div id="dashboard" class="section-content py-16">
  <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <h1 class="text-4xl font-bold mb-4">Tableau de bord Etudiant</h1>
      <p class="text-xl text-indigo-100 mb-6">Participez aux quiz et évaluez vos compétences.
      </p>
      <div class="flex gap-4">
        <a href="categories.php"
          class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition inline-block">
          <i class="fas fa-folder-plus mr-2"></i>Voir les catégories
        </a>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Quiz disponibles</p>
            <p class="text-3xl font-bold text-gray-900"><?= $totalQuiz ?></p>
          </div>
          <div class="bg-blue-100 p-3 rounded-lg">
            <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Catégories</p>
            <p class="text-3xl font-bold text-gray-900"><?= $totalCategorie ?></p>
          </div>
          <div class="bg-purple-100 p-3 rounded-lg">
            <i class="fas fa-folder text-purple-600 text-2xl"></i>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Quiz passés</p>
            <p class="text-3xl font-bold text-gray-900"><?= $stats['total_quiz'] ?></p>
          </div>
          <div class="bg-green-100 p-3 rounded-lg">
            <i class="fas fa-user-graduate text-green-600 text-2xl"></i>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-500 text-sm">Moyenne</p>
            <p class="text-3xl font-bold text-gray-900"><?= $stats['moyenne'] ?>%</p>
          </div>
          <div class="bg-yellow-100 p-3 rounded-lg">
            <i class="fas fa-chart-line text-yellow-600 text-2xl"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="bg-white rounded-xl shadow-md p-6">
    <h3 class="text-xl font-semibold mb-4">Derniers résultats</h3>

    <?php if (empty($lastResults)): ?>
      <p class="text-gray-500">Vous n’avez pas encore passé de quiz.</p>
    <?php else: ?>
      <ul class="divide-y">
        <?php foreach ($lastResults as $r): ?>
          <li class="py-3 flex justify-between">
            <span><?= htmlspecialchars($r['titre']) ?></span>
            <span class="font-semibold">
              <?= $r['score'] ?>/<?= $r['total_questions'] ?>
            </span>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>

</div>