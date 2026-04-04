<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>予約キャンセルのお知らせ</title>
</head>
<body>

<p>{{ $order->name }} 様</p>

<p>
ご予約はキャンセルされました。
</p>

<hr>

<p><strong>■ キャンセル内容</strong></p>

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
またのご利用をお待ちしております。
</p>

</body>
</html>