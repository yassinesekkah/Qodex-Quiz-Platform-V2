<?php
/**
 * Page: Créer un Quiz
 * Formulaire de création de quiz
 */

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';

// Vérifier que l'utilisateur est enseignant
Security::requireTeacher();

// Variables pour la navigation
$currentPage = 'quiz';
$pageTitle = 'Créer un Quiz';

// Récupérer les catégories
$teacherId = $_SESSION['user_id'];
$userName = $_SESSION['user_nom'];

$categoryObj = new Category();
$categories = $categoryObj->getAllByTeacher($teacherId);

// Messages
$error = $_SESSION['quiz_error'] ?? '';
unset($_SESSION['quiz_error']);
?>
<?php include '../partials/header.php'; ?>

<?php include '../partials/nav_teacher.php'; ?>

<div class="pt-16">
    <div class="min-h-screen py-12 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg p-8">
                <!-- Header -->
                <div class="mb-8">
                    <a href="quiz.php" class="text-indigo-600 hover:text-indigo-700 mb-4 inline-block">
                        <i class="fas fa-arrow-left mr-2"></i>Retour aux quiz
                    </a>
                    <h2 class="text-3xl font-bold text-gray-900">Créer un nouveau Quiz</h2>
                    <p class="text-gray-600 mt-2">Remplissez les informations pour créer votre quiz</p>
                </div>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <?php if (empty($categories)): ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Vous devez d'abord créer une catégorie.
                        <a href="categories.php" class="underline font-semibold">Créer une catégorie</a>
                    </div>
                <?php else: ?>
                    <form action="../../actions/quiz_create.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-heading mr-2"></i>Titre du Quiz *
                            </label>
                            <input type="text" name="titre" required 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                   placeholder="Ex: Quiz HTML/CSS Niveau 1">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-folder mr-2"></i>Catégorie *
                            </label>
                            <select name="categorie_id" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Sélectionnez une catégorie</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                <i class="fas fa-align-left mr-2"></i>Description
                            </label>
                            <textarea name="description" rows="4" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                      placeholder="Décrivez votre quiz..."></textarea>
                        </div>

                        <div class="flex gap-4">
                            <a href="quiz.php" 
                               class="flex-1 text-center px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                Annuler
                            </a>
                            <button type="submit" 
                                    class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                                <i class="fas fa-plus-circle mr-2"></i>Créer le Quiz
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../partials/footer.php'; ?>

