<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Share on Facebook</title>

    <!-- Open Graph Protocol meta tags -->
    <meta property="og:title" content="My Saving Post">
    <meta property="og:description" content="im reducing my carbon footprint and fuel costs. Check out my savings with the POWRBANK battery energy storage system. #POWR2">
    <meta property="og:image" content="http://3.108.245.156/theme-asset/test.png">
    <meta property="og:url" content="http://3.108.245.156/testing">
    <meta property="og:type" content="Article">
</head>
<body>
    <h1>Test Page Content</h1>
    <p>This is a test page for sharing on Facebook.</p>
    <!-- Facebook share button -->
    <a href="#" onclick="shareOnFacebook(); return false;">
        Share on Facebook
    </a>

    <!-- JavaScript function to share on Facebook -->
    <script>
        function shareOnFacebook() {
            var urlToShare = encodeURIComponent("http://3.108.245.156/testing");
            var shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${urlToShare}`;
            window.open(shareUrl, '_blank');
        }
    </script>
</body>
</html>
