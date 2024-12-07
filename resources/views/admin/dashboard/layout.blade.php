<!DOCTYPE html>
<html>

<head>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @include('admin.dashboard.component.header')
</head>

<body>
<div id="wrapper">
    @include('admin.dashboard.component.sidebar')
    <div id="page-wrapper" class="gray-bg">
        @include('admin.dashboard.component.nav')
        @include($template)

    </div>
</div>
@include('admin.dashboard.component.script')
</body>
</html>



