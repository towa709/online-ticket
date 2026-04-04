<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>予約変更のお知らせ</title>
</head>
<body>

<p>{{ $order->name }} 様</p>

<p>
ご予約内容が変更されました。
</p>

<hr>

<p><strong>■ 最新のご予約内容</strong></p>

<ul>
<li>公演日時：
{{ $order->schedule->start_time->format('Y/m/d H:i') }}
</li>

<li>会場：
{{ $order->schedule->venue }}
</li>

<li>枚数：
{{ $order->ticket_quantity }} 枚
</li>
</ul>

<p>
ご不明点がございましたらお問い合わせください。
</p>

</body>
</html>