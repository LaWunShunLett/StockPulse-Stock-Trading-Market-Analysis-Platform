document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const message = document.getElementById('message').value;
    
    if (name && email && message) {
        Email.send({
            Host: "smtp.yourisp.com",
            Username: "your-email@example.com",
            To: 'support@example.com',
            From: email,
            Subject: "Contact Form Submission",
            Body: `Name: ${name} <br> Email: ${email} <br> Message: ${message}`
        }).then(
            message => alert("Message sent successfully!")
        ).catch(
            error => alert("Error sending message!")
        );
        this.reset();
    } else {
        alert('Please fill out all fields.');
    }
});
