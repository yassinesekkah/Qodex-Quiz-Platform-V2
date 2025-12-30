<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';
require_once '../../classes/Quiz.php';

Security::requireStudent();

// Variables pour la navigation
$currentPage = 'dashboard';
$pageTitle = 'Tableau de bord';

// Récupérer les données de l'utilisateur
$studentId = $_SESSION['user_id'];
$userName = $_SESSION['user_nom'];





?>

<h1>Bienvenue <?= htmlspecialchars($userName) ?></h1>
<p>Prêt à tester vos connaissances ?</p>

<div>
  <a href="categories.php">📂 Voir les catégories</a>
</div>

<div>
  <a href="categories.php">📝 Passer un quiz</a>
</div>

<div>
  <a href="history.php">📊 Mon historique</a>
</div>


