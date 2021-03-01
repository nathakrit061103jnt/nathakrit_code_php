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

### 2.2 POST การรับข้อมูล Json เเล้วนำมาบัทึกลงฐานข้อมูลบันทึกข้อมูล

```PHP
        <?php
        //กำหนดค่า Access-Control-Allow-Origin เพื่อให้สิทธิการเข้าถึงข้อมูล
        header("Access-Control-Allow-Origin: *");

        header("Content-Type: application/json; charset=UTF-8");

        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");

        header("Access-Control-Max-Age: 3600");

        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        //เชื่อมต่อฐานข้อมูล
        include_once "./config/connectDB.php";

        //อ่านข้อมูลที่ส่งมาแล้วเก็บไว้ที่ตัวแปร data
        $data = file_get_contents("php://input");

        //แปลงข้อมูลที่อ่านได้ เป็น array แล้วเก็บไว้ที่ตัวแปร result
        $result = json_decode($data, true);

        //ตรวจสอบว่าเป็น Method  POST หรือไม่
        if ($requestMethod == 'POST') {

                if ($result['action'] == "createEmployees") {

                        $firstName = mysqli_real_escape_string($conn, $result['first_name']);
                        $lastName = mysqli_real_escape_string($conn, $result['last_name']);

                        //คำสั่ง SQL สำหรับเพิ่มข้อมูลใน Database
                        $sql = "INSERT INTO employees (id,first_name,last_name) VALUES (NULL,'$firstName','$lastName')";

                        if (mysqli_query($conn, $sql)) {
                                echo json_encode([
                                        'status' => 200,
                                        'message' => 'New record created successfully',
                                        'error' => false,
                                ]);
                        } else {
                                $mes = "Error: " . $sql . "<br>" . mysqli_error($conn);
                                echo json_encode([
                                        'status' => 404,
                                        'message' => $mes,
                                        'error' => true,
                                ]);

                        }

                        mysqli_close($conn);

                }

        }
        ?>
```

## 3. การอัปโหลดโหลดภาพ เเละบันทึกลงฐานข้อมูล mysql

```PHP
// ต้องใส่  enctype="multipart/form-data" ถ้าจะทำการอัปโหลดภาพ
 <form action="" method="post" enctype="multipart/form-data">
     <div class="row clearfix">
         <div class="col-sm-4">
             <div class="form-group">
                 <div class="form-line">
                     <input type="file" name="dt_image1" required class="form-control" placeholder="รูปภาพที่ 1">
                 </div>
             </div>
         </div>
     </div>
     <div class="row clearfix">
         <div class="col-sm-12">
             <button type="submit" name="submitInsertData"
                 class="btn btn-primary btn-block btn-lg">เพิ่มพอพัก</button>
         </div>
     </div>
 </form>



<?php
if (isset($_POST["submitInsertData"])) {

   $dt_image1_time = md5(date("Y-m-d h:i:s"));
   $dt_image1 = uniqid() . $dt_image1_time . $_FILES["bk_slip"]["name"]; 

    $sql = "INSERT INTO my_table (`dt_id`,`dt_image1`)
            VALUES (NULL, '" . $dt_image1 . "');";

    if (mysqli_query($conn, $sql)) {
        echo "<script>";
        echo "alert('บันทึกข้อมูลสำเร็จ');";
        echo "</script>";
        // path ที่จะใช้เก็บภาพที่อัปโหลดไป
        $path = "../../images/drm/";
        move_uploaded_file($_FILES["dt_image1"]["tmp_name"], "$path/$dt_image1");

    } else {
        echo "<script>";
        echo "alert('ไม่สามารถลบข้อมูลได้');";
        echo "</script>";
    }

}

```

## 4. การอัปภาพ จาก VueJS2 มาให้ PHP เเบบบ Base 64 พร้อม Alert เเบบ SweetAlert2

### 4.1 โค้ด VueJS ในการอัปภาพ

#### 1) ส่วนของ HTML เเละ Javascript

```html
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- vuejs2 cdn -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
    <!-- sweetalert2 cdn -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- axios cdn -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
    <title>VueJS Upload</title>
</head>

<body>
    <div class=" " id="app">
        <input type="file" @change="convertImagesBase64">
        <br>
        <img v-if="img !== null" :src="img" class="img-fluid" width="250"> <br>
        <button type="btn" @click="uploadImage">Upload</button>
        </form>
    </div>
</body>

<script>
var app = new Vue({
    el: '#app',
    data: {
        img: null,
        img_type: null,
    },
    methods: {
        uploadImage() {
            const payload = {
                action: "upload_image",
                img: this.img,
                img_type: this.img_type
            }
            axios.post('./test_upload.php', payload).then(res => {
                console.log('res', res.data)
            }).catch(error => console.error(error))
        },
        convertImagesBase64(e) {
            let reader = new FileReader();
            const fileType = this.checkImageTypeUpload(e);
            this.img_type = fileType;

            if (fileType !== null) {
                reader.onload = (e) => {
                    this.img = e.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            } else {
                this.img = null
                this.img_type = null
                Swal.fire({
                    icon: 'error',
                    title: 'ไฟล์ที่ Upload',
                    text: 'กรุณา Upload ไฟล์รูปภาพที่เป็นนามสกุลไฟล์ .jpeg , .jpg , .png!',
                });
            }
        },
        checkImageTypeUpload(e) {
            let imageType = e.target.files[0].type;
            switch (imageType) {
                case 'image/jpg':
                    return "jpg";
                    break;
                case 'image/jpeg':
                    return "jpeg";
                    break;
                case 'image/png':
                    return "png";
                    break;
                default:
                    return null;
            }
        },
        checkFileTypeUpload(e) {
            let imageType = e.target.files[0].type;
            switch (imageType) {
                case 'image/jpg':
                    return "jpg";
                    break;
                case 'image/jpeg':
                    return "jpeg";
                    break;
                case 'image/png':
                    return "png";
                    break;
                case 'application/pdf':
                    return "pdf";
                    break;
                case 'application/msword':
                    return "doc";
                    break;
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    return "doc";
                    break;
                default:
                    return null;
            }
        },
    },
})
</script>

</html>

```

#### 2) ส่วนของ PHP Server ที่ Upload Images

```PHP
<?php

header("Access-Control-Allow-Origin: *");

header("Content-Type: application/json; charset=UTF-8");

header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");

header("Access-Control-Max-Age: 3600");

header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod == 'POST') {

    $input = json_decode(file_get_contents("php://input"));
    $action = $input->action;

    if ($action == "upload_image") {

        // รับค่าจาก Json Object มาเก็บไว้ในตัวแปร
        $img = $input->img;
        $img_type = $input->img_type;

        // $img = mysqli_real_escape_string($conn, $input["img"]);
        // $img_type = mysqli_real_escape_string($conn, $input["img_type"]);

        if ($img_type !== null) {
            // path ที่ต้องการอัปไปเก็บ
            $folderPath = "./uploads/";

            $file_parts = explode(";base64,", $img);

            // $file_type_aux = explode($folderPath, $file_parts[0]);
            // $file_type = $file_type_aux[1];
            $file_base64 = base64_decode($file_parts[1]);

            //ชื่อไฟล์
            $img = uniqid() . "." . "$img_type";

            file_put_contents($folderPath . $img, $file_base64);
        } else {
            $img = null;
        }
    }
}
?>
```
