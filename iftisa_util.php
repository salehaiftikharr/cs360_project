<?php

// Helper Methods for Messaging

function getUserName($db, $uid) {
	$stmt = $db->prepare("SELECT name FROM User WHERE uid = :uid");
    	$stmt->execute([':uid' => $uid]);
    	$row = $stmt->fetch();
    	return $row ? $row['name'] : 'Unknown';
}

function insertMessage($db, $from_uid, $to_uid, $contents) {
    	$now = date('Y-m-d H:i:s');
    	$stmt = $db->prepare("INSERT INTO Message (from_uid, to_uid, postTime, contents)
                          	VALUES (:from_uid, :to_uid, :postTime, :contents)");
    	$stmt->execute([
        	':from_uid' => $from_uid,
        	':to_uid' => $to_uid,
        	':postTime' => $now,
        	':contents' => $contents
   	 ]);
}

function getMessagesBetweenUsers($db, $uid1, $uid2) {
    	$query = "SELECT * FROM Message
              	WHERE (from_uid = :uid1 AND to_uid = :uid2) OR (from_uid = :uid2 AND to_uid = :uid1)
              	ORDER BY postTime ASC";
    	$stmt = $db->prepare($query);
    	$stmt->execute([':uid1' => $uid1, ':uid2' => $uid2]);
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getSearchResults($db, $term, $uid) {
    	$query = "SELECT uid, name FROM User WHERE LOWER(name) LIKE LOWER(:term) AND uid != :uid";
    	$stmt = $db->prepare($query);
    	$stmt->execute([
        	':term' => '%' . $term . '%',
        	':uid' => $uid
    	]);
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUserConversations($db, $uid) {
    	$query = "SELECT DISTINCT U.uid, U.name
              	FROM User U
              	JOIN Message M ON (M.from_uid = U.uid OR M.to_uid = U.uid)
              	WHERE U.uid != :uid AND (M.from_uid = :uid OR M.to_uid = :uid)";
    	$stmt = $db->prepare($query);
    	$stmt->execute([':uid' => $uid]);
    	return $stmt->fetchAll(PDO::FETCH_ASSOC);
	
}

// Helper Methods for Posts

function insertWorkout($db, $uid, $date) {
    	$stmt = $db->prepare("INSERT INTO Workout (uid, date) VALUES (:uid, :date)");
    	$stmt->execute([':uid' => $uid, ':date' => $date]);
    	return $db->lastInsertId();
}

function insertPost($db, $uid, $wid, $caption, $postTime, $date, $isPrivate) {
    	$stmt = $db->prepare("INSERT INTO Post (uid, wid, caption, postTime, date, isPrivate)
                          	VALUES (:uid, :wid, :caption, :postTime, :date, :isPrivate)");
    	$stmt->execute([
        	":uid" => $uid,
        	":wid" => $wid,
        	":caption" => $caption,
        	":postTime" => $postTime,
        	":date" => $date,
        	":isPrivate" => $isPrivate
    	]);
}

function insertRepScheme($db, $wid, $exerciseName, $reps, $sets, $weights, $restTime) {
    	$stmt = $db->prepare("INSERT IGNORE INTO Exercise (name) VALUES (:name)");
    	$stmt->execute([':name' => $exerciseName]);

    	$stmt = $db->prepare("INSERT INTO RepScheme (reps, sets, weights, restTime, wid, name)
                          	VALUES (:reps, :sets, :weights, :restTime, :wid, :name)");
    	$stmt->execute([
        	':reps' => $reps,
        	':sets' => $sets,
        	':weights' => $weights,
        	':restTime' => $restTime,
        	':wid' => $wid,
        	':name' => $exerciseName
    	]);
}

function getRepSchemes($db, $wid) {
    	$stmt = $db->prepare("SELECT * FROM RepScheme WHERE wid = :wid");
    	$stmt->execute([':wid' => $wid]);
    	return $stmt->fetchAll();
}
