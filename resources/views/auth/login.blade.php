<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <table width="100%" height="100%" border="0">
        <tr>
            <td align="center" valign="middle">
                <h1>Login</h1>
                <form method="POST" action="/login">
                    @csrf
                    <table border="0" cellpadding="5">
                        <tr>
                            <td><input type="text" name="username" placeholder="Username" required></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="password" placeholder="Password" required></td>
                        </tr>
                        <tr>
                            <td align="center">
                                <button type="submit">Login</button>
                            </td>
                        </tr>
                    </table>
                </form>
                <p>Don't have an account? <a href="/signup">Signup here</a></p>
            </td>
        </tr>
    </table>
</body>
</html>
