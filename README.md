# ภาษา PHP

## 1. การเชื่อมต่อฐานข้อมูล MySQLi Procedural

        <?php
        $hostname = "localhost";
        $username = "root";
        $password = "my-password";
        $database = "my_database";

        // เชื่อมต่อฐานข้อมูล
        $conn = mysqli_connect($hostname, $username, $password, $database);
        //เช็กว่าเชื่อมต่อฐานข้อมูลได้หรือไม่
        if (!$conn) {
             die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM MyTable";
        $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                // ทำการวนซ้ำเพื่อเเสดงข้อมูล
                while ($item = mysqli_fetch_assoc($result)) {
                    echo "id: {$item["id"]} - Name: {$item["firstname"]} lastname {$item["lastname"]} <br>";
            }

        } else {
        echo "ไม่พบข้อมูล";
        }

        //ปิดฐานข้อมูล
        mysqli_close($conn);
        ?>
