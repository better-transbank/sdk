<html>
<head>
    <title>Redireccionando a Webpay...</title>
</head>
<body>
<form action="<?= $url ?>" id="webpay-form-<?= $rand ?>" method="POST">
    <input type="hidden" name="token_ws" value="<?= $token ?>" />
</form>
<script>
    document.getElementById("webpay-form-<?= $rand ?>").submit();
</script>
</body>
</html>