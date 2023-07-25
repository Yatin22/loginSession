<?php
include "config.php";
if (!isset($_SESSION['user_id'])) {
  header("location:login.php");
  exit();
}

$studentid = $_GET['id'];

$getRecordsStmt = $conn->prepare("SELECT * FROM student WHERE student_id = ?");
$getRecordsStmt->bind_param("i", $studentid);
$getRecordsStmt->execute();
$result = $getRecordsStmt->get_result();
$record = $result->fetch_assoc();
$getRecordsStmt->close();

if (!$record) {
    echo "<script>alert('Student record not found.');</script>";
    exit();
}

$valSName = $record['student_name'];
$valFName = $record['father_name'];
$valemail = $record['email'];
$valphone = $record['phone'];
$valnote = $record['note'];
$valdob = $record['date_of_birth'];
$valclass = $record['class'];
$valgender = $record['gender'];
$valstatus = $record['status'];

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   $st_name = htmlspecialchars($_POST['student_name'], ENT_QUOTES, 'UTF-8');
   $father_name = htmlspecialchars($_POST['father_name'], ENT_QUOTES, 'UTF-8');
   $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
   $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');
   $status = intval($_POST['status']);
   $class = intval($_POST['class']);
   $dob = $_POST['dob'];
   $gender = $_POST['gender'];
   $note = htmlspecialchars($_POST['notes'], ENT_QUOTES, 'UTF-8');
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

   // Check if the email contains only allowed characters
   if (!preg_match('/^[a-zA-Z0-9._-@\s]+$/', $email)) {
       echo "<script>alert('Error from server side: Your Email contains invalid characters.')</script>";
       header("location: studentInsert.php");
       exit();
   }

   if (!preg_match("/^[0-9]{10}$/", $phone)) {
      echo "<script>alert('Error from server side: Your Phone is not valid');</script>";
      exit();
   }

   $updateStmt = $conn->prepare("UPDATE student SET 
    student_name = ?,
    father_name = ?,
    phone = ?,
    email = ?,
    class = ?,
    gender = ?,
    note = ?,
    status = ?,
    date_of_birth = ?,
    updated_time = NOW()
    WHERE student_id = ?");
   $updateStmt->bind_param("ssssissssi", $st_name, $father_name, $phone, $email, $class, $gender, $note, $status, $dob, $studentid);
   $updateResult = $updateStmt->execute();
   $updateStmt->close();

   if ($updateResult) {
      echo "<script>alert('Student updated successfully');</script>";
      header("location:studentTable.php");
   } else {
      echo "<script>alert('Failed to update student');</script>";
   }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Update Student</title>
  <link rel="stylesheet" href="style3.css">
</head>
<body>
  <h1>Update Student - <?php
    echo $valSName;
  ?></h1>

  <form class="add-student-form" method="POST" onsubmit="return validateForm()">
    <label for="student-name">Student Name(required):</label>
    <input type="text" name="student_name" id="student-name" value="<?php echo $valSName ?>" required>

    <label for="father-name">Father Name(required):</label>
    <input type="text" name="father_name" value="<?php echo $valFName ?>" id="father-name" required>

    <label for="phone">Phone(required):</label>
    <input type="tel" name="phone" minlength="10" maxlength="10" id="phone"value="<?php echo $valphone ?>" required>

    <label for="email">Email(required):</label>
    <input type="email" name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="<?php echo $valemail  ?>" required>

    <label for="class">Class(required):</label>
    <select id="class" name="class" value="<?php echo $valclass ?>" required>
      <option value="">Select Class</option>
      <option value="1">Class 1</option>
      <option value="2">Class 2</option>
      <option value="3">Class 3</option>
      <option value="4">Class 4</option>
      <option value="5">Class 5</option>
      <option value="6">Class 6</option>
      <option value="7">Class 7</option>
      <option value="8">Class 8</option>
      <option value="9">Class 9</option>
      <option value="10">Class 10</option>
      <option value="11">Class 11</option>
      <option value="12">Class 12</option>
    </select>

    <div class="gender-container">
      <label for="gender">Gender(required):</label>
      
      <div class="gender-radio">
        
        <input type="radio" id="male" name="gender" value="M" <?php if($valgender=='M'||$valgender=='m')echo 'checked' ?> required>
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="F" <?php if($valgender=='F'||$valgender=='f') echo'checked';?> required>
        <label for="female">Female</label>
      </div>
    </div>

    <label for="notes">Note:</label>
    <textarea id="notes" name="notes" ><?php echo $valnote ?></textarea>

    <label for="dob">Date of Birth(required):</label>
    <input type="date"  max="<?php echo date("Y-m-d");
    ?>" id="dob" name="dob" value="<?php echo $valdob ?>" required>

    <label for="status">Status(required):</label>
    <select id="status" name="status" required>
      <option value="1" <?php if($valstatus==1)echo 'selected' ?>>Active</option>
      <option value="0"  <?php if($valstatus==0)echo 'selected' ?>>Inactive</option>
    </select>

    <button type="submit" name="submit" onclick="return ConfirmUpdate()">Update Student</button>
  </form>

  <a href="studentTable.php" class="back-link">Back to Student Information</a>

  <script >
function validateForm() {
  const studentName = document.getElementById("student-name").value.trim();
  const fatherName = document.getElementById("father-name").value.trim();
  const phone = document.getElementById("phone").value.trim();
  const email = document.getElementById("email").value.trim();
  const classSelect = document.getElementById("class").value;
  const genderMale = document.getElementById("male").checked;
  const genderFemale = document.getElementById("female").checked;
  const dob = document.getElementById("dob").value;
  if (studentName === "" || fatherName === "" || phone === "" || email === "" || classSelect === "" || dob === "") {
    alert("Please fill in all the required fields.");
    return false;
  }

  if (!/^[a-zA-Z\s]+$/.test(studentName) || !/^[a-zA-Z\s]+$/.test(fatherName)) {
    alert("Student name and father name must contain only alphabets and spaces.");
    return false;
  }

  // Check if the phone number contains exactly 10 digits
  if (!/^\d{10}$/.test(phone)) {
    alert("Phone number must contain exactly 10 digits.");
    return false;
  }

  // Check if either Male or Female is selected
  if (!(genderMale || genderFemale)) {
    alert("Please select a gender.");
    return false;
  }

  // Check if the DOB is not in the future
  const currentDate = new Date().toISOString().split("T")[0];
  if (dob > currentDate) {
    alert("Date of Birth cannot be in the future.");
    return false;
  }
  if (!/^[a-zA-Z0-9._\-@\s]+$/.test(email)) {
    alert("Email contains invalid characters.");
    return false;
  }
}
function ConfirmUpdate(){
  var x=confirm('Are you sure want update this record?');
  if(x){
    return true;
  }
  return false;
}
  </script>
</body>
</html>
