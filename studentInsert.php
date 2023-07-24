<?php
include "config.php";
if (!isset($_SESSION['user_id'])) {
  header("location:login.php");
  exit();
}
$userid = $_SESSION['user_id'];

$st_name = $father_name = $email = $phone = $status = $class = $dob = $gender = $note = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $st_name = mysqli_real_escape_string($conn, $_POST['student_name']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $status = $_POST['status'];
    $class = $_POST['class'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $note = mysqli_real_escape_string($conn, $_POST['notes']);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        echo "<script>alert('Error from server side your Email is not valid')</script>";
        header("location:studentInsert.php");
        exit();
    } if(!preg_match("/^[0-9]{10}$/",$phone)){
        echo "<script>alert('Error from server side your Phone is not valid')</script>";
        exit();
    }
  $duplicacy = mysqli_query($conn, "SELECT * FROM student WHERE email='$email' OR phone='$phone'");

  if (mysqli_num_rows($duplicacy) > 0) {
    echo "<script>alert('Duplicate entry found');</script>";
  } else {
    $stmt = $conn->prepare("INSERT INTO student (student_name, father_name, phone, email, class, gender, note, date_of_birth, created_time, status, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)");
  
  // Bind parameters to the prepared statement
  $stmt->bind_param("ssssssssii", $st_name, $father_name, $phone, $email, $class, $gender, $note, $dob, $status, $userid);
  
  // Execute the query
  if ($stmt->execute()) {
    echo "<script>alert('Student added successfully');</script>";
  } else {
    echo "<script>alert('Failed to add student');</script>";
  }
  
  // Close the statement
  $stmt->close();
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
    <input type="text" name="student_name" id="student-name" required>

    <label for="father-name">Father Name:</label>
    <input type="text" name="father_name" id="father-name" required>

    <label for="phone">Phone:</label>
    <input type="tel" name="phone" minlength="10" maxlength="10" id="phone" required>

    <label for="email">Email:</label>
    <input type="email" 
    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" 
    name="email" id="email" required>

    <label for="class">Class:</label>
    <select id="class" name="class" required>
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
        <input type="radio" id="male" name="gender" value="M" required>
        <label for="male">Male</label>
        <input type="radio" id="female" name="gender" value="F" required>
        <label for="female">Female</label>
      </div>
    </div>

    <label for="notes">Note:</label>
    <textarea id="notes" name="notes"></textarea>

    <label for="dob">Date of Birth:</label>
    <input type="date" max="<?php echo date("Y-m-d");
    ?>"id="dob" name="dob" required>

    <label for="status">Status:</label>
    <select id="status" name="status" required>
      <option value="1">Active</option>
      <option value="0">Inactive</option>
    </select>
    <div style="display:flex">
        <label for="terms">I agree to the Terms and Conditions:</label>
    <input type="checkbox" id="terms" name="terms" required>

    </div>


    <button type="submit" name="submit">Add Student</button>
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
    const termsCheckbox = document.getElementById("terms");
  if (!termsCheckbox.checked) {
    alert("Please agree to the Terms and Conditions.");
    return false;
  }
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
    }
  </script>
</body>
</html>
