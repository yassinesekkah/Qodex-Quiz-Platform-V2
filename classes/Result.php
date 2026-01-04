<?php

/**
 * Classe Result
 * Gère les résultats des quiz (US7 - Voir ses résultats)
 * 
 * SÉCURITÉ: L'utilisateur ne peut voir QUE ses propres résultats
 */

class Result
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère les résultats d'un étudiant (ses propres résultats SEULEMENT)
     * @param int $etudiantId - L'ID de l'étudiant
     * @return array - Liste des résultats
     */
    public function getMyResults($studentId)
    {
        $sql = "SELECT 
                r.*,
                q.titre AS quiz_titre,
                c.nom AS categorie_nom,
                a.started_at,
                a.completed_at,
                TIMESTAMPDIFF(SECOND, a.started_at, a.completed_at) AS duration_seconds
            FROM results r
            JOIN attempts a ON a.id = r.attempt_id
            JOIN quiz q ON q.id = r.quiz_id
            JOIN categories c ON c.id = q.categorie_id
            WHERE r.etudiant_id = ?
            ORDER BY r.created_at DESC";

        return $this->db->query($sql, [$studentId])->fetchAll();
    }


    /**
     * Récupère un résultat par ID (vérifie que c'est bien le propriétaire)
     * @param int $resultId
     * @param int $etudiantId
     * @return array|false
     */
    public function getById($resultId, $etudiantId)
    {
        $sql = "SELECT r.*, q.titre as quiz_titre
                FROM results r
                LEFT JOIN quiz q ON r.quiz_id = q.id
                WHERE r.id = ? AND r.etudiant_id = ?";

        $result = $this->db->query($sql, [$resultId, $etudiantId]);
        return $result->fetch();
    }

    /**
     * Enregistre un nouveau résultat
     * @param int $quizId
     * @param int $etudiantId
     * @param int $score
     * @param int $totalQuestions
     * @return int|false
     */
    public function save($quizId, $etudiantId, $score, $totalQuestions)
    {
        $sql = "INSERT INTO results (quiz_id, etudiant_id, score, total_questions, created_at) 
                VALUES (?, ?, ?, ?, NOW())";

        try {
            $this->db->query($sql, [$quizId, $etudiantId, $score, $totalQuestions]);
            return $this->db->getConnection()->lastInsertId();
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function saveFromAttempt(
        int $attemptId,
        int $quizId,
        int $etudiantId,
        int $score,
        int $totalQuestions,
        float $percentage
    ): bool {
        $sql = "INSERT INTO results
            (attempt_id, quiz_id, etudiant_id, score, total_questions, percentage, completed_at, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $this->db->query($sql, [
            $attemptId,
            $quizId,
            $etudiantId,
            $score,
            $totalQuestions,
            $percentage
        ]);

        return true;
    }

    public function getByAttempt(int $attemptId, int $etudiantId)
    {
        $sql = "SELECT 
                r.*,
                q.titre AS quiz_titre,
                c.nom AS categorie_nom,
                a.started_at,
                a.completed_at
            FROM results r
            JOIN attempts a ON a.id = r.attempt_id
            JOIN quiz q ON q.id = r.quiz_id
            JOIN categories c ON c.id = q.categorie_id
            WHERE r.attempt_id = ? AND r.etudiant_id = ?
            LIMIT 1";

        return $this->db->query($sql, [$attemptId, $etudiantId])->fetch();
    }



    /**
     * Calcule les statistiques d'un étudiant
     * @param int $etudiantId
     * @return array
     */
    public function getMyStats($etudiantId)
    {
        $sql = "SELECT 
                    COUNT(*) as total_quiz,
                    AVG(score / total_questions * 100) as moyenne,
                    MAX(score / total_questions * 100) as meilleur_score
                FROM results
                WHERE etudiant_id = ?";

        $result = $this->db->query($sql, [$etudiantId]);
        return $result->fetch();
    }

    public function getLastResults($studentId)
    {
        $sql = "SELECT q.titre, r.score, r.total_questions, r.completed_at
            FROM results r
            JOIN quiz q ON q.id = r.quiz_id
            WHERE r.etudiant_id = ?
            ORDER BY r.completed_at DESC
            LIMIT 5";

        $res = $this->db->query($sql, [$studentId]);
        return $res->fetchAll();
    }
}
