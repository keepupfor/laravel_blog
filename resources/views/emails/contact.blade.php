<p>
    网站发送来了一封新邮件</p><p>
    细节如下:
</p>
<ul>
    <li>姓名: <strong>{{ $name }}</strong></li>
    <li>邮箱: <strong>{{ $email }}</strong></li>
    <li>电话: <strong>{{ $phone }}</strong></li>
</ul>
<hr>
<p>
    @foreach ($messageLines as $messageLine)
        {{ $messageLine }}<br>
    @endforeach
</p>
<hr>