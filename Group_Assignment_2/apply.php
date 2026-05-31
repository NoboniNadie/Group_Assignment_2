<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Apply Form</title>
    <link rel="stylesheet" href="styles/styling.css">
    <img src="Image\logo.jpg" alt="Group Photo" width="100" style="float: left;">
</head>

  <?php include 'header.inc'; ?>
  
  <?php 
    require_once('settings.php');
    $conn = @mysqli_connect("localhost", "root", "", "learnova_db");
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
        $query = "SELECT * FROM eoi";
        $result = mysqli_query($dbconn, $query);
        mysqli_close($conn);

        
    
    }
  ?>   
  
  <!--Body of HTML Page-->
<body>
    <h1>Job Application Form</h1>
        <aside>
        <h3>How to make application smooth:</h3>
        <ul>
            <li>Have your resume ready</li>
            <li>Double check all details</li>
            <li>Make sure all information is accurate</li>
        </ul>
    </aside>
    <!-- Form test and all Job form questions -->
     <!-- novalidate disables client-side validation, action changed to process_eoi.php -->
    <form action="process_eoi.php" method="POST" novalidate>
     <strong> <label for="jobRef">Reference number:</label></strong>
    <input type="text" id="jobRef" name="jobRef" 
           pattern="[A-Za-z0-9]{5}" 
           title="Your Job reference number must be exactly 5 characters"
           maxlength="5"
           required>
    <br><br>  
    <!--Above is the Job Reference Number -->
<strong><label for="firstName">First name:</label></strong>
    <input type="text" id="firstName" name="firstName" 
           pattern="[A-Za-z]{1,20}" 
           title="Maximum name length of 20 character, letters only"
           maxlength="20"
           required>
    <br><br>
    <strong><label for="lastName">Last name:</label></strong>
    <input type="text" id="lastName" name="lastName" 
           pattern="[A-Za-z]{1,20}" 
           title="Maximum last name lenght of 20 characters, letters only"
           maxlength="20"
           required>
         <!--Above is both first and last name fields -->
    <br><br>     
    <strong><label for="dob">Date of birth:</label></strong>
    <input type="text" id="dob" name="dob" 
           placeholder="dd/mm/yyyy"
           pattern="(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/(19|20)\d\d"
           title="Enter a valid date in d/m/y format"
           required>
    <br><br>
 <!-- Above is the date of birth field -->
    
    <fieldset>
        <strong><legend>Gender:</legend></strong>
        
        <input type="radio" id="male" name="gender" value="male" required>
        <label for="male">Male</label>
        
        <input type="radio" id="female" name="gender" value="female">
        <label for="female">Female</label>
    </fieldset>
    <br><br>
    <!-- Gender button option thing above -->
    
    <strong><label for="street">Street Address:</label></strong>
    <input type="text" id="street" name="street"
           title="Enter street address"
           maxlength="40"
           required>
    <br><br>
<!-- Street Adress, capped at 40 letters-->
    
    <strong><label for="suburb">Suburb/Town:</label></strong>
    <input type="text" id="suburb" name="suburb" 
    title="enter suburb"
           maxlength="40"
           required>
    <br><br>
<!-- Suburb/town, pretty self explanatory-->

    <strong><label for="state">State:</label></strong>
    <select id="state" name="state" required>
        <option value="">-- Please select --</option>
        <option value="VIC">VIC</option>
        <option value="NSW">NSW</option>
        <option value="QLD">QLD</option>
        <option value="NT">NT</option>
        <option value="WA">WA</option>
        <option value="SA">SA</option>
        <option value="TAS">TAS</option>
        <option value="ACT">ACT</option>
    </select>
    <br><br>
<!-- Do these notes in the code get read? question I shouldve asked-->
 <!-- If they do, above is state dropdown box-->
  
    <strong><label for="postcode">Postcode:</label></strong>
    <input type="text" id="postcode" name="postcode" 
           pattern="\d{4}" 
           title="Please enter 4 digit post code"
           maxlength="4"
           required>
    <br><br>
    <!-- 4 Digit Post Code-->
    
    <strong><label for="email">Email address:</label></strong>
    <input type="email" id="email" name="email"
    title="Please enter a valid email address" 
           placeholder="example@domain.com"
           required>
    <br><br>
<!-- User email -->
    
    <strong><label for="phone">Phone number:</label></strong>
    <input type="tel" id="phone" name="phone" 
           pattern="[0-9\s\-\(\)]{8,12}"
           title="Please enter a valid phone number"
           placeholder="e.g. 0468 591 614"
           required>
    <br><br>
    <!-- Phone number form -->
    <!-- That is my actual phone number, I didnt wanna accidentally put a real one for some random person, please do not call it -->
      <fieldset>
       <strong> <legend>Skills: (select at least one)</legend></strong>
        
        <input type="checkbox" id="html" name="skills[]" value="html">
        <label for="Html">HTML</label>
        
        <input type="checkbox" id="css" name="skills[]" value="css">
        <label for="Css">CSS</label>
        
        <input type="checkbox" id="javascript" name="skills[]" value="javascript">
        <label for="Javascript">JavaScript</label>
        
        <input type="checkbox" id="php" name="skills[]" value="php">
        <label for="Php">PHP</label>
        
        <input type="checkbox" id="python" name="skills[]" value="python">
        <label for="Python">Python</label>
        
        <input type="checkbox" id="Sql" name="skills[]" value="sql">
        <label for="Sql">SQL</label>

        <input type="checkbox" id="Experienced in education" name="skills[]" value="sql">
        <label for="Experienced in education">Education</label>
    <br><br>
        <input type="checkbox" id="Swin represent" name="skills[]" value="sql">
        <label for="Swin represent">Went to Swinburne, (instant job)</label>

        <input type="checkbox" id="None" name="skills[]" value="sql">
        <label for="None">None, im just applying for fun</label>
    </fieldset>
    <br><br>
     <!-- Skills box menu, got a bit carried away with the last 2, oh well, why not, make it fun -->
    
    <strong><label for="otherSkills">Other skills:</label></strong>
    <br><br>
    <textarea id="otherSkills" name="otherSkills" 
              rows="4" 
              cols="50"
              placeholder="Please list any additional skills..."></textarea>
    <br><br>
    <!-- other skills, I added a little break after the text to make sure the box to write in was under, looked better -->
        
    <button type="submit">Submit Application</button>
    <button type="reset">Restart Form</button>
   <!-- One very important button and one somewhat important button -->

    </form>
</body>
    
    <?php include 'footer.inc'; ?>

<hr>
</html>
