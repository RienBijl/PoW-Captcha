<?php

require "server/CaptchaGenerator.php";

$g = new CapthaGenerator(1000);

$toSolve = $g->prime();

for ($i = 0; $i < $toSolve["max"] + 1; $i++) {
    $decrypted = openssl_decrypt($toSolve["to_solve"], "AES-256-CBC", $toSolve["initialization"] . "-" .$i);
    if ($decrypted !== null && $g->check($decrypted)) {
        echo "The secret was: " . $decrypted;
        break;
    }
}