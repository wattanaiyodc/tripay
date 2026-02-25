<?php
    $sql = "select count(*)
            from qr_request_member 
            where status != 'paid' and
                  qr_id  = :qr_id
            limit 1";
    $sth = $pdo2->prepare($sql);
    $sth->execute([
        "qr_id" => $qr_id
    ]);
    if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
      $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
      exit(json_encode($answer));
    }
    $count = $sth->fetchColumn();
    if( $count < 1){
        $sql = "update qr_request
                set  status = 'done'
                where qr_id = :qr_id";
        $sth = $pdo2->prepare($sql);
        $sth->execute([
            ":qr_id"  => $qr_id
        ]);
        if ($sth->errorInfo()[0] != "00000" && !empty($sth->errorInfo()[0])) {
          $answer["message"] = (empty($sth->errorInfo()[2])) ? $sth->errorInfo()[0] : $sth->errorInfo()[2];
          exit(json_encode($answer));
        }
    }
?>