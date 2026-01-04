<?php
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Result.php';

Security::requireStudent();

$studentId = $_SESSION['user_id'];

$resultObj = new Result();
$results = $resultObj->getMyResults($studentId);
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_student.php'; ?>

<div class="bg-white rounded-xl shadow-md overflow-hidden py-20">
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
                <?php foreach ($results as $res): ?>
                    <?php
                    $score = (int) $res['score'];
                    $total = (int) $res['total_questions'];
                    $percentage = round(($score / $total) * 100);
                    $success = $percentage >= 50;

                    $minutes = floor($res['duration_seconds'] / 60);
                    $seconds = $res['duration_seconds'] % 60;
                    $duration = $minutes . ':' . str_pad($seconds, 2, '0', STR_PAD_LEFT);
                    ?>

                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">
                            <?= htmlspecialchars($res['quiz_titre']) ?>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($res['categorie_nom']) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 font-bold">
                            <?= $score ?>/<?= $total ?>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= date('d M Y', strtotime($res['created_at'])) ?>
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            <?= $duration ?>
                        </td>

                        <td class="px-6 py-4">
                            <?php if ($success): ?>
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full">Réussi</span>
                            <?php else: ?>
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full">Échoué</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../partials/footer.php'; ?>