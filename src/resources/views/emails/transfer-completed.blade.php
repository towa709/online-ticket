<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>入金確認のお知らせ</title>
</head>
<body>

<p>{{ $order->name }} 様</p>

<p>
この度は<br>
「{{ $order->schedule->event->title }}」へ<br>
ご予約いただき、誠にありがとうございます。
</p>

<hr>

<p><strong>■ ご予約確定のお知らせ</strong></p>

<p>
ご入金を確認いたしました。<br>
ご予約が正式に確定いたしました。
</p>

<ul>
  <li>公演日時：
    {{ $order->schedule->start_time->format('Y/m/d H:i') }}
  </li>
  <li>会場：
    {{ $order->schedule->venue }}
  </li>
  <li>ご予約枚数：
    {{ $order->ticket_quantity }} 枚
  </li>
</ul>

<p>
当日は受付にて<br>
お名前をお伝えください。
</p>

<p>
それでは、会場でお会いできるのを<br>
楽しみにしております。
</p>

</body>
</html>