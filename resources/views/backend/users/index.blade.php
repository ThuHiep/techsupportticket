<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách User</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
<h1>Danh sách người dùng</h1>
<table>
    <thead>
    <tr>
        <th>User ID</th>
        <th>Username</th>
        <th>Password</th>
        <th>Email</th>
        <th>OTP</th>
        <th>OTP Expiration Time</th>
        <th>OTP Validation</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->user_id }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->password }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->otp }}</td>
            <td>{{ $user->otp_expiration_time }}</td>
            <td>{{ $user->otp_validation ? 'Valid' : 'Invalid' }}</td>
            <td>{{ $user->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>

