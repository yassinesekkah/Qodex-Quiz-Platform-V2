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

<!-- Results Table -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Temps</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">Les Bases de HTML5</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">HTML/CSS</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-bold text-green-600">18/20</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">04 Déc 2024</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">28:45</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Réussi
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">CSS Avancé</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">HTML/CSS</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-bold text-green-600">15/20</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">03 Déc 2024</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">24:12</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Réussi
                                        </span>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">JavaScript Fondamentaux</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">JavaScript</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-bold text-red-600">8/20</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">02 Déc 2024</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">30:00</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>Échoué
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include '../partials/footer.php'; ?>
