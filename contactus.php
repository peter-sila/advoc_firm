<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact Us - WE GOT YOU Advocates</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
   <link rel="stylesheet" href="css/global.css">
   <link rel="stylesheet" href="css/contactus.css">
   <link rel="stylesheet" href="stat/css/footers.css">
   <link rel="stylesheet" href="stat/css/headers.css">
</head>
<body>
   <?php include "stat/header.html"; ?>

   <section class="intro">
      <h2>Contact WE GOT YOU Advocates</h2>
      <p>We're ready to help you with your legal needs. Reach out to us today for a consultation with our expert team.</p>
   </section>

   <section class="contact-container">
      <div class="contact-info">
         <h3>Contact Information</h3>
         <p><strong>Email:</strong> <a href="mailto:petersila2002@gmail.com">petersila2002@gmail.com</a></p>
         <p><strong>Phone:</strong> <a href="tel:+254794178635">+254 794 178 635</a></p>
         <p><strong>Alternative:</strong> <a href="tel:+254782416883">+254 782 416 883</a></p>
         <p><strong>Location:</strong> Nairobi, Kenya</p>
         <p><strong>Address:</strong> Professional Complex, Law Courts Precinct</p>
         <div class="social-links">
            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
         </div>
      </div>

      <div class="contact-form">
         <h3>Send us a Message</h3>
         <form id="contactForm">
            <input type="text" placeholder="Your Name" name="name" required>
            <input type="email" placeholder="Email Address" name="email" required>
            <input type="tel" placeholder="Phone Number" name="phone">
            <input type="text" placeholder="Subject" name="subject" required>
            <textarea placeholder="Your Message" name="message" required></textarea>
            <button type="submit">Send Message</button>
         </form>
      </div>
   </section>

   <section class="map">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.8158915800166!2d36.78151241111129!3d-1.285653899999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x182f17e0d0000001%3A0x0!2sNairobi%2C%20Kenya!5e0!3m2!1sen!2s!4v1234567890" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
   </section>

   <?php include "stat/footer.html"; ?>
   <script src="js/contact.js"></script>
</body>
</html>
