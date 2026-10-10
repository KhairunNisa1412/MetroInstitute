<?php
    $nilaimhs = [
        "Khairun Nisa" => 80,
        "Cordelia" => 100,
        "Budiman" => 10,
        "Nabila" => 66,
        "Taro" => 70,
    ];

    foreach ($nilaimhs as $nama => $nilai){
        if ($nilai >= 80){
            $grade = "A";
        } elseif ($nilai >= 70){
            $grade = "B";
        } elseif ( $nilai >= 60){
            $grade = "C";
        } else {
            $grade = "D";
        }

        echo "Nama: $nama | Nilai: $nilai | Grade: $grade \n";
    }
?>
