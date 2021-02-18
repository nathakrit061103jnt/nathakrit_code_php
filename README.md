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

## 2. การสร้าง RestApi เเบบง่าย

- GET Data ดึงข้อมูลจากฐานข้อมูล เเล้ว เเสดงออกมาเป็น Json
- POST ทำการับข้อมูล Json มาเเล้วนำมาบันทึกลงฐานข้อมูล

### 2.1 Get Data Echo Json

```PHP
     <?php
     //กำหนดค่า Access-Control-Allow-Origin เพื่อให้สิทธิการเข้าถึงข้อมูล
     header("Access-Control-Allow-Origin: *");

     header("Content-Type: application/json; charset=UTF-8");

     header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");

     header("Access-Control-Max-Age: 3600");

     header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

     include_once "./config/connectDB.php";

     $requestMethod = $_SERVER["REQUEST_METHOD"];

     //ตรวจสอบว่าเป็น Method GET หรือไม่
     if ($requestMethod == 'GET') {
     //ตรวจสอบการส่งค่า id
     if (isset($_GET['get_all_employees'])) {
             //คำสั่ง SQL กรณี มีการส่งค่า id มาให้แสดงเฉพาะข้อมูลของ id นั้น
             $sql = "SELECT * FROM employees";
     }
     $result = mysqli_query($conn, $sql);

     //สร้างตัวขึ้นมาเพื่อรอรับข้อมูล
     $arr;

     while ($row = mysqli_fetch_assoc($result)) {
             // รับข้อมูลเเล้ว Push ใส่ array
             $arr[] = $row;
     }

     //นำข้อมูลเเสดงออกเป็น Json Data
     echo json_encode();
     }
```
