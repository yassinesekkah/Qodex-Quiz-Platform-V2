<?php
require_once '../../config/database.php';
require_once '../../classes/Database.php';
require_once '../../classes/Security.php';
require_once '../../classes/Quiz.php';
require_once '../../classes/Attempt.php';
require_once '../../classes/Question.php';
///khaso ykoun student
Security::requireStudent();
////check wach id kayen fel url
if (!isset($_GET['quiz_id']) || !ctype_digit($_GET['quiz_id'])) {
    header("Location: categorie.php");
    exit;
}
/////nakhdo userId wel quizId
$studentId = $_SESSION['user_id'];
$quizId = (int)$_GET['quiz_id'];
///check wach kayen quiz f database
$quiz = new Quiz;
$quizData = $quiz->getById($quizId);

if (!$quizData) {
    header("Location: categorie.php");
    exit;
}
// var_dump($quizData);
/////check wach quiz is active
if (!$quiz->isActive($quizId)) {
    header("Location: categorie.php");
    exit;
}
///check attempt wach kayen
$attempt = new Attempt;
$hasAttempt = $attempt->hasAttempt($studentId, $quizId);
if (!$hasAttempt) {
    header('Location: quizzes.php?category_id=' . $quizData['categorie_id']);
    exit;
}
///njibo questions
$questionsObj = new Question;
$questions = $questionsObj->getAllByQuizForStudent($quizId);
$questionsCount = $questionsObj->countByQuiz($quizId);
//transfer $questions to json file

// var_dump($questionsCount);
// var_dump($questions);
// array(2) { [0]=> array(9) { ["id"]=> int(13) ["quiz_id"]=> int(3) 
//     ["question"]=> string(20) "Non non eiusmod veni" ["option1"]=> string(20) "Sunt nostrud laborum" 
//     ["option2"]=> string(19) "Rerum voluptatem Mo" ["option3"]=> string(20) "Autem facilis accusa" 
//     ["option4"]=> string(20) "Molestiae quia ex au" ["correct_option"]=> int(1) 
//     ["created_at"]=> string(19) "2025-12-25 22:23:37" } [1]=> array(9) { ["id"]=> int(14) 
//         ["quiz_id"]=> int(3) ["question"]=> string(19) "Eaque rerum numquam" 
//         ["option1"]=> string(19) "Voluptas laboriosam" ["option2"]=> string(19) "Sit reiciendis nemo" 
//         ["option3"]=> string(20) "Laboris necessitatib" ["option4"]=> string(19) 
//     "Fugiat ut facilis a" ["correct_option"]=> int(2) ["created_at"]=> string(19) "2025-12-25 22:23:37" } }

if (empty($questions)) {
    header('Location: quizzes.php?category_id=' . $quizData['categorie_id']);
    exit;
}
?>
<?php include '../partials/header.php'; ?>
<?php include '../partials/nav_teacher.php'; ?>

<div id="takeQuiz" class="student-section py-16">
    <div class="bg-gradient-to-r from-green-600 to-teal-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold mb-2" id="quizTitle"></h1>
                    <p class="text-green-100">Question <span id="currentQuestion">1</span> sur <span id="totalQuestions"><?= $questionsCount ?></span></p>
                </div>
                <div class="text-right">
                    <div class="text-sm text-green-100 mb-1">Temps restant</div>
                    <div class="text-3xl font-bold" id="timer">30:00</div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <form method="POST" action="../../actions/student/submit_quiz.php">
            <input type="hidden" name="quiz_id" value="<?= $quizId ?>">

            <div class="bg-white rounded-xl shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6" id="questionText">

                </h3>

                <div class="space-y-4">
                    <div onclick="selectAnswer(this)" class="answer-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 option-radio">
                                <div class="w-4 h-4 rounded-full bg-green-600 hidden option-selected"></div>
                            </div>
                            <span class="text-lg" id="option1"></span>
                        </div>
                    </div>

                    <div onclick="selectAnswer(this)" class="answer-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 option-radio">
                                <div class="w-4 h-4 rounded-full bg-green-600 hidden option-selected"></div>
                            </div>
                            <span class="text-lg" id="option2"></span>
                        </div>
                    </div>

                    <div onclick="selectAnswer(this)" class="answer-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 option-radio">
                                <div class="w-4 h-4 rounded-full bg-green-600 hidden option-selected"></div>
                            </div>
                            <span class="text-lg" id="option3"></span>
                        </div>
                    </div>

                    <div onclick="selectAnswer(this)" class="answer-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full border-2 border-gray-300 flex items-center justify-center mr-4 option-radio">
                                <div class="w-4 h-4 rounded-full bg-green-600 hidden option-selected"></div>
                            </div>
                            <span class="text-lg" id="option4"></span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-between mt-8">
                    <button onclick="previousQuestion()" type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Précédent
                    </button>
                    <button id="suivantBtn" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
    const questions = <?= json_encode($questions, JSON_UNESCAPED_UNICODE); ?>;
    console.log(questions);

    let index = 0;
    const suivantBtn = document.getElementById("suivantBtn");

    function showQuestion() {
        const q = questions[index];

        document.getElementById("questionText").innerText = q.question;
        document.getElementById("option1").innerText = q.option1;
        document.getElementById("option2").innerText = q.option2;
        document.getElementById("option3").innerText = q.option3;
        document.getElementById("option4").innerText = q.option4;
        if (index < questions.length - 1) {
            suivantBtn.type = "button";
            suivantBtn.innerText = "Suivant";
            suivantBtn.onclick = nextQuestion;
        } else {
            suivantBtn.type = "submit";
            suivantBtn.innerText = "Soumettre";
            suivantBtn.onclick = null;
        }
    }
    showQuestion();

    function nextQuestion() {

        if (index < questions.length - 1) {
            index++;
            showQuestion();
        }
    }

    function previousQuestion() {
        if (index > 0) {
            index--;
            showQuestion();
        }
    }
</script>
<?php include '../partials/footer.php'; ?>