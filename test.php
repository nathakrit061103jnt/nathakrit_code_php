<?php

if (mysqli_num_rows($result) > 0) {
    // ทำการวนซ้ำเพื่อเเสดงข้อมูล
    while ($item = mysqli_fetch_assoc($result)) {
        echo "id: {$item["id"]} - Name: {$item["firstname"]} lastname {$item["lastname"]} <br>";
    }
} else {
    echo "ไม่พบข้อมูล";
}