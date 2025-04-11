<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $phoneNumber = $_POST['phoneNumber'];
    $address = $_POST['address'];

    // Email settings
    $to = "lamarramy.sh@gmail.com";
    $subject = "New Order from Seera Custom Photo Magnet";
    $headers = "From: no-reply@seera.com";

    // Create email body
    $message = "Name: $name\nPhone: $phoneNumber\nAddress: $address\n\n";

    // Process uploaded images and attach them to the email
    $attachments = [];
    foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
        $file = $_FILES['photos']['tmp_name'][$key];
        $file_name = $_FILES['photos']['name'][$key];
        $file_type = $_FILES['photos']['type'][$key];
        $file_content = chunk_split(base64_encode(file_get_contents($file)));

        $attachments[] = "--boundary\r\n".
                         "Content-Type: $file_type; name=\"$file_name\"\r\n".
                         "Content-Disposition: attachment; filename=\"$file_name\"\r\n".
                         "Content-Transfer-Encoding: base64\r\n\r\n".
                         "$file_content\r\n";
    }

    // Combine all parts and send the email
    $boundary = "----boundary";
    $headers .= "\r\nContent-Type: multipart/mixed; boundary=\"$boundary\"\r\n";
    $message = "--$boundary\r\nContent-Type: text/plain; charset=UTF-8\r\n\r\n$message\r\n";
    $message .= implode("\r\n", $attachments);
    $message .= "--$boundary--";

    // Send email
    mail($to, $subject, $message, $headers);

    // Redirect to the thank you page
    echo "<script>window.location.href = 'thank_you_page.html';</script>";
}
?>
