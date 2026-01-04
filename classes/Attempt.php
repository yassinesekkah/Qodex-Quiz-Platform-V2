<?php

class Attempt
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function hasAttempt($studentId, $quizId)
    {
        $sql = "SELECT count(*) as attempts  FROM attempts a
                WHERE a.student_id = ? AND a.quiz_id = ?";

        $rows = $this->db->query($sql, [$studentId, $quizId]);
        $result =  $rows->fetch();
        $attempts = $result['attempts'];

        return $attempts > 0;
    }

    public function createAttempt($studentId, $quizId)
    {
        $sql = ("INSERT INTO attempts (student_id, quiz_id, started_at, is_finished) VALUES (?, ?, NOW(), 0);");
        $result = $this->db->query($sql, [$studentId, $quizId]);
        return true;
    }

    public function finishAttempt($attemptId)
    {
        $sql = "UPDATE attempts
            SET is_finished = 1,
                completed_at = NOW()
            WHERE id = ?";

        $this->db->query($sql, [$attemptId]);
    }
    public function getOpenAttempt($studentId, $quizId)
    {
        $sql = "SELECT *
            FROM attempts
            WHERE student_id = ?
              AND quiz_id = ?
              AND is_finished = 0
            LIMIT 1";

        $stmt = $this->db->query($sql, [$studentId, $quizId]);
        return $stmt->fetch();
    }
    public function hasOpenAttempt($studentId, $quizId)
    {
        $sql = "SELECT COUNT(*) 
            FROM attempts
            WHERE student_id = ? AND quiz_id = ? AND is_finished = 0";

        return $this->db->query($sql, [$studentId, $quizId])->fetchColumn() > 0;
    }

    public function hasFinishedAttempt($studentId, $quizId)
    {
        $sql = "SELECT COUNT(*) 
            FROM attempts
            WHERE student_id = ? AND quiz_id = ? AND is_finished = 1";

        return $this->db->query($sql, [$studentId, $quizId])->fetchColumn() > 0;
    }
}
