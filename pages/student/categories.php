<?php

require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Category.php';

Security::requireStudent();

$categoryObj = new Category();
$categories = $categoryObj->getAllWithQuizcount();
// var_dump($categories);
?>

<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_student.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 ">
    <h2 class="text-3xl font-bold text-gray-900 mb-8">Catégories Disponibles</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 ">
        <?php
        $colors = ['blue', 'purple', 'green', 'red', 'yellow', 'pink', 'indigo', 'teal'];
        $logos = [
            'fa-solid fa-database',
            'fa-regular fa-file-code',
            'fa-brands fa-deviantart',
            'fa-solid fa-terminal',
            'fa-solid fa-microchip',
            'fa-solid fa-server'
        ];
        foreach ($categories as $index => $category):
            $color = $colors[$index % count($colors)];
            $logo = $logos[$index % count($logos)];
        ?>
            <a href="quizzes.php?category_id=<?= $category['id'] ?>">
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group cursor-pointer">
                    <div class="bg-gradient-to-br from-<?= $color ?>-500 to-<?= $color ?>-600 p-6 text-white">
                        <i class="<?= $logo ?> text-4xl mb-3"></i>
                        <h3 class="text-xl font-bold"><?= $category['nom'] ?></h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4"><?= $category['description'] ?></p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-500"><i class="fas fa-clipboard-list mr-2"></i><?= $category['quiz_count'] ?> quiz</span>
                            <span class="text-green-600 font-semibold group-hover:translate-x-2 transition-transform">Explorer →</span>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach ?>

        <!-- <div onclick="showStudentSection('categoryQuizzes', 'JavaScript')" class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group cursor-pointer">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 text-white">
                <i class="fas fa-laptop-code text-4xl mb-3"></i>
                <h3 class="text-xl font-bold">JavaScript</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Programmation interactive</p>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500"><i class="fas fa-clipboard-list mr-2"></i>8 quiz</span>
                    <span class="text-purple-600 font-semibold group-hover:translate-x-2 transition-transform">Explorer →</span>
                </div>
            </div>
        </div> -->

        <!-- <div onclick="showStudentSection('categoryQuizzes', 'PHP/MySQL')" class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group cursor-pointer">
            <div class="bg-gradient-to-br from-green-500 to-green-600 p-6 text-white">
                <i class="fas fa-database text-4xl mb-3"></i>
                <h3 class="text-xl font-bold">PHP/MySQL</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Backend et bases de données</p>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500"><i class="fas fa-clipboard-list mr-2"></i>10 quiz</span>
                    <span class="text-green-600 font-semibold group-hover:translate-x-2 transition-transform">Explorer →</span>
                </div>
            </div>
        </div> -->

        <!-- <div onclick="showStudentSection('studentResults')" class="bg-white rounded-xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden group cursor-pointer">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-6 text-white">
                <i class="fas fa-chart-line text-4xl mb-3"></i>
                <h3 class="text-xl font-bold">Mes Résultats</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">Consultez vos performances</p>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-500"><i class="fas fa-trophy mr-2"></i>24 tentatives</span>
                    <span class="text-orange-600 font-semibold group-hover:translate-x-2 transition-transform">Voir →</span>
                </div>
            </div>
        </div> -->
    </div>
</div>
<?php include '../partials/footer.php'; ?>