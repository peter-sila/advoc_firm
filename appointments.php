<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Appointment - WE GOT YOU Advocates</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/global.css">
  <link rel="stylesheet" href="css/appointments.css">
  <link rel="stylesheet" href="stat/css/footers.css">
  <link rel="stylesheet" href="stat/css/headers.css">
</head>
<body>
  <?php include "stat/header.html"; ?>

  <div class="form-wrapper">
    <h2><i class="fas fa-calendar-check"></i> Book Your Consultation</h2>
    <div id="successMsg" class="form-success">Thank you! Your appointment request has been submitted successfully.</div>
    <div id="errorMsg" class="form-error">Please fill in all required fields correctly.</div>

    <form id="appointmentForm" action="php/process_appointment.php" method="POST" enctype="multipart/form-data">
      <label>Full Name <span>*</span></label>
      <input type="text" name="name" placeholder="Your Full Name" required>

      <label>Email Address <span>*</span></label>
      <input type="email" name="email" placeholder="e.g. yourname@example.com" required>

      <label>Phone Number <span>*</span></label>
      <input type="tel" name="phone" placeholder="+254712345678" required>

      <label>Location</label>
      <input type="text" name="location" placeholder="City / Town">

      <label>Select Area of Expertise <span>*</span></label>
      <select name="expertise" required>
        <option value="">-- Select an area --</option>
        <option value="General Consultation">General Consultation</option>
        <option value="Family Law">Family Law</option>
        <option value="Criminal Law">Criminal Law</option>
        <option value="Corporate Law">Corporate Law</option>
        <option value="Employment Law">Employment Law</option>
        <option value="Real Estate">Real Estate Law</option>
        <option value="Civil Litigation">Civil Litigation</option>
      </select>

      <label>Nature of Meeting <span>*</span></label>
      <div class="checkbox-group">
        <label><input type="checkbox" name="meeting" value="Physical"> Physical Meeting</label>
        <label><input type="checkbox" name="meeting" value="Virtual"> Virtual Consultation</label>
      </div>

      <label>Preferred Date <span>*</span></label>
      <input type="date" name="date" required>

      <label>Preferred Time <span>*</span></label>
      <div class="time-group">
        <input type="number" name="hour" min="1" max="12" placeholder="Hours" required>
        <input type="number" name="minute" min="0" max="59" placeholder="Minutes" required>
        <select name="ampm" required>
          <option value="AM">AM</option>
          <option value="PM">PM</option>
        </select>
      </div>

      <label>Upload Relevant Documents <small>(Optional - Max 5MB)</small></label>
      <input type="file" name="documents">

      <label>Describe Your Matter <span>*</span></label>
      <textarea name="message" rows="5" placeholder="Briefly describe your legal matter..." required></textarea>

      <button type="submit"><i class="fas fa-paper-plane"></i> Submit Appointment Request</button>
    </form>
  </div>

  <?php include "stat/footer.html"; ?>
  <script src="js/appointments.js"></script>
</body>
</html>

