<?php
require_once 'core/init.php';

$participant_id = trim(Input::get("delegate", "get"));

// Preventing scanning before accreditation
$today = date('Y-m-d');

if ($today != "2022-08-10") {
    // echo '<h1 style="color: red; font-size: 250%; text-align: center; margin-top: 10%;">ACCREDITATION FOR THE KIGALI GLOBAL DIALOGUE WILL START ON 10TH AUGUST 2022</h1>';
    echo '<script>
    self.location="https://www.orfonline.org/orf-kgd/";
    </script>';
} else {
    // Check if badge is not yet issued
    $sql = "SELECT id FROM future_participants WHERE id=" . $participant_id . " AND issue_badge_status=1";
    $is_issued = DB::getInstance()->query($sql);

    if ($is_issued->count()) {
        // Badge is already issued
        echo '<script>
    self.location="https://www.orfonline.org/orf-kgd/";
    </script>';
    } else {
        // Issuing the badge
        $sql = "UPDATE future_participants SET issue_badge_status=1 WHERE id=" . $participant_id;
        $issue = DB::getInstance()->query($sql);

        echo '<h1 style="color: green; font-size: 250%; text-align: center; margin-top: 10%;">BADGE ISSUED SUCCESSFULLY</h1>';
    }
}
