<?php

class Attempt 
{
    private $db;

    public function __construct()
    {
        $this -> db = Database::getInstance();
    }

    public function hasAttempt ($studentId, $quizId)
    {
        $sql = "SELECT count(*) as attempts  FROM attempts a
                WHERE a.student_id = ? AND a.quiz_id = ?";
        
        $rows = $this -> db -> query($sql, [$studentId, $quizId]);
        $result =  $rows -> fetch();
        $attempts = $result['attempts'];
        return $attempts > 0 ;
    }
}