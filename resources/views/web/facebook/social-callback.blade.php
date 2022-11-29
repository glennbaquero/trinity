<head>
    <meta charset="utf-8">
    <title>Redirecting...</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<br><br>
<div style="text-align: center;">
    <p>You have successfully login via {{ ucwords($provider) }}</p>
    <p>Redirecting please wait...</p>
    <span id="social_id" style="opacity: 0;">{{ $user }}</span>
</div>

<br><br>

<script>
document.addEventListener("DOMContentLoaded", function(event) {
    setTimeout(() => {
        window.location.href = '/';
    }, 1000);
});
</script>