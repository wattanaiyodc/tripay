<?php
session_start();
unset($_SESSION["trip_id"]);

echo json_encode(["success" => 1]);
