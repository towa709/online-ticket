<p>{{ $order->name }} 様</p>

<p>
ご予約ありがとうございます。<br>
現在、ご入金待ちの状態です。
</p>

@if($order->payment_method === 'bank')
<h3>【銀行振込】</h3>
<p>
銀行名：◯◯銀行<br>
支店名：◯◯支店<br>
口座番号：1234567<br>
名義：◯◯劇団
</p>
@endif

@if($order->payment_method === 'convenience')
<h3>【コンビニ振込】</h3>
<p>
受付番号：{{ $order->id }}<br>
店頭端末で「インターネット支払い」を選択してください。
</p>
@endif

<p>
振込期限：{{ optional($order->transfer_due_at)->format('Y/m/d H:i') }}
</p>

<p>
ご入金確認後、予約確定メールをお送りします。
</p>