<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title', config('blog.title'))</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
@yield('content')
</div>
</body>
</html>