<?php

include 'DbConnection.php';


class voteSystem {

	private $conn;
    
    public function __construct()
    {
        $database   = new DbConnection();
        $db         = $database->dbConnection();
        $this->conn = $db;
    }


    public function addVote($channel, $userID, $username, $nominee, $reason) {


    	$stmt = $this->conn->prepare("INSERT INTO votes (channel_id, user_id, user_name, nominee, reason) VALUES (:channel_id, :user_id, :user_name, :nominee, :reason)");
		$stmt->bindParam(':channel_id', $channel);
		$stmt->bindParam(':user_id', $username);
		$stmt->bindParam(':user_name', $userID);
		$stmt->bindParam(':nominee', $nominee);
		$stmt->bindParam(':reason', $reason);
		$stmt->execute();
    }

    public function checkVote($channel, $userID, $username, $nominee, $reason) {
        $stmt = $this->conn->query("SELECT * FROM votes WHERE user_id = '" . $username . "' AND user_name = '" . $userID . "' AND DATE_FORMAT(votedate, '%c') = MONTH(CURDATE())");
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result[0]["ID"];
    }

    public function winners() {

        $stmt = $this->conn->prepare("SELECT nominee, COUNT(nominee) as total FROM `votes` WHERE DATE_FORMAT(votedate, '%c') = MONTH(CURDATE()) GROUP BY nominee ORDER BY total");
        $stmt->execute();
        $nomineeVotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $max = 0;
        $numVotes = array();

        foreach ($nomineeVotes as $key => $value) {
                array_push($numVotes, $value['total']);
        }

        $max_value  = max(array_values($numVotes));

        foreach ($nomineeVotes as $key => $value) {
            if ($value['total'] == $max_value) {
                $winners .= $value['nominee'] . " ";
            }
        }

        return $winners;
    }

    public function repeatVote() {
        
        $stmt = $this->conn->prepare("SELECT nominee, COUNT(nominee) as total FROM `votes` WHERE DATE_FORMAT(votedate, '%c') = MONTH(CURDATE()) GROUP BY nominee ORDER BY total");
        $stmt->execute();
        $nomineeVotes = $stmt->fetchAll();

        $max = 0;
        $numbeVotes = array();

        
        foreach ($nomineeVotes as $key => $value) {
                array_push($numbeVotes, $value['total']);
        }
        
        //What is the maximum value?
        $max_value  = max(array_values($numbeVotes));
        //Getting key with the maximum value
        $key_name = array_keys($numbeVotes, $max_value);
        //Votes repeats if > 1
        if(count($key_name) > 1) {
            return true;
        } else {
            return false;
        }
    }

    public function totalVote() {
        $stmt = $this->conn->query("SELECT user_name, nominee, reason, votedate FROM votes WHERE DATE_FORMAT(votedate, '%c') = MONTH(CURDATE()) ORDER BY nominee");
        $stmt->execute();
        $template_array = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $template_array;
    }
}

?>