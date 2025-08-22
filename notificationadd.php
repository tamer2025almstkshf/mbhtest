<?php
    $notifications_respid = $_SESSION['id'];
    $notifications_notificationdate = date("Y-m-d");
    $notifications_status = 0;
    $notifications_timestamp = date("Y-m-d H:i:s");
    
    $stmt_notification = $conn->prepare("INSERT INTO notifications (resp_id, empid, target, target_id, notification, notification_date, status, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt_notification->bind_param("iisissis", $notifications_respid, $notifications_empid, $target, $target_id, $notifications_notification, $notifications_notificationdate, $notifications_status, $notifications_timestamp);
    $stmt_notification->execute();
?>