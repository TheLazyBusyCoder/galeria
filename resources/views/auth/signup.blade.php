<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>
    <table width="100%" height="100%" border="0">
        <tr>
            <td align="center" valign="middle">
                <h1>Signup</h1>
                <form method="POST" action="/signup">
                    @csrf
                    <table border="0" cellpadding="5">
                        <tr>
                            <td><input type="text" name="name" placeholder="Name" required></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="username" placeholder="Username" required></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="password" placeholder="Password" required></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="password_confirmation" placeholder="Confirm Password" required></td>
                        </tr>
                        <tr>
                            <td align="center">
                                <button type="submit">Signup</button>
                            </td>
                        </tr>
                    </table>
                </form>
                <p>Already have an account? <a href="/login">Login here</a></p>
            </td>
        </tr>
    </table>
</body>
</html>
