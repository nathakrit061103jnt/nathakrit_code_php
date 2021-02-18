# ภาษา PHP

## 1. การเชื่อมต่อฐานข้อมูล MySQLi Procedural

```PHP
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

        //การเข้ารหัสให้ ฐานข้อมูลสามารถอ่านภาษาไทยได้
        mysqli_set_charset($conn,"utf8");

        ?>
```
