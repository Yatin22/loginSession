<?php
include "config.php";
if (!isset($_SESSION['user_id'])) {
  header("location:login.php");
  exit();
}
$studentid=$_GET['id'];
$getRecords=mysqli_query($conn,"Select * from student where student_id=$studentid");
while($record=mysqli_fetch_assoc($getRecords)){
    $valSName=$record['student_name'];
    $valFName=$record['father_name'];
    $valemail=$record['email'];
    $valphone=$record['phone'];
    $valnote=$record['note'];
    $valdob=$record['date_of_birth'];
    $valclass=$record['class'];
    $valgender=$record['gender'];
    $valstatus=$record['status'];

}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
   $st_name = $_POST['student_name'];
    $father_name = $_POST['father_name'];
   $email = $_POST['email'];
   $phone = $_POST['phone'];
  $status = $_POST['status'];
  $class = $_POST['class'];
  $dob = $_POST['dob'];
  $gender = $_POST['gender'];
  $note = $_POST['notes'];
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "<script>alert('Error from server side: Your Email is not valid');</script>";
    exit();
}

if (!preg_match("/^[0-9]{10}$/", $phone)) {
    echo "<script>alert('Error from server side: Your Phone is not valid');</script>";
    exit();
}
$update = mysqli_query($conn, "UPDATE student SET 
    student_name = '$st_name',
    father_name = '$father_name',
    phone = '$phone',
    email = '$email',
    class = '$class',
    gender = '$gender',
    note = '$note',
    status=$status,
    date_of_birth = '$dob',
    updated_time = NOW()
WHERE student_id = $studentid");
    if ($update) {
      echo "<script>alert('Student updated successfully');</script>";
      header("location:studentTable.php");
    } else {
      echo "<script>alert('Failed to add student');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Student</title>
  <link rel="stylesheet" href="style3.css">
</head>
<body>
  <h1>Add Student</h1>

  <form class="add-student-form" method="POST" onsubmit="return validateForm()">
    <label for="student-name">Student Name:</label>
    <input type="text" name="student_name" id="student-name" value="<?php echo $valSName ?>" required>

    <label for="father-name">Father Name:</label>
    <input type="text" name="father_name" value="<?php echo $valFName ?>" id="father-name" required>

    <label for="phone">Phone:</label>
    <input type="tel" name="phone" minlength="10" maxlength="10" id="phone"value="<?php echo $valphone ?>" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="<?php echo $valemail  ?>" required>

    <label for="class">Class:</label>
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
      <label for="gender">Gender:</label>
      
      <div class="gender-radio">
        
        <input type="radio" id="male" name="gender" value="M" <?php if($valgender=='M'||$valgender=='m')echo 'checked' ?> required>
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="F" <?php if($valgender=='F'||$valgender=='f') echo'checked';?> required>
        <label for="female">Female</label>
      </div>
    </div>

    <label for="notes">Note:</label>
    <textarea id="notes" name="notes" ><?php echo $valnote ?></textarea>

    <label for="dob">Date of Birth:</label>
    <input type="date"  max="<?php echo date("Y-m-d");
    ?>" id="dob" name="dob" value="<?php echo $valdob ?>" required>

    <label for="status">Status:</label>
    <select id="status" name="status" required>
      <option value="1" <?php if($valstatus==1)echo 'selected' ?>>Active</option>
      <option value="0"  <?php if($valstatus==0)echo 'selected' ?>>Inactive</option>
    </select>

    <button type="submit" name="submit">Update Student</button>
  </form>

  <a href="studentTable.php" class="back-link">Back to Student Information</a>

  <script >
function validateForm(){
        const studentName = document.getElementById("student-name").value.trim();
    const fatherName = document.getElementById("father-name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const classSelect = document.getElementById("class").value;
    const genderMale = document.getElementById("male").checked;
    const genderFemale = document.getElementById("female").checked;
    const dob = document.getElementById("dob").value;
    // Check if the required fields are empty
    if (studentName === "" || fatherName === "" || phone === "" || email === "" || classSelect === "" || dob === "" || !(genderMale || genderFemale)) {
      alert("Please fill in all the required fields.");
      return false;
    }

    // Check if the phone number contains only numbers
    if (!/^\d+$/.test(phone)) {
      alert("Phone number must contain only numbers.");
      return false;
    }

  </script>
</body>
</html>
