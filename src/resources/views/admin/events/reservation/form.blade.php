{{-- =========================
  予約フォーム 共通パーツ
  ========================= --}}
<form
  method="POST"
  action="{{ route('reservations.store', $event) }}"
>
  @csrf

  <div class="reservation-form">

    {{-- =========================
      予約フォーム上部
    ========================= --}}
    <div class="reservation-header">

      <h2 class="reservation-title">
        {{ $event->title }}<br>
        チケット予約フォーム
      </h2>

    {{-- チラシ画像 --}}
@if (!empty($event->reservation_image_1) || !empty($event->reservation_image_2))
  <div class="flyer-wrapper">

    @if (!empty($event->reservation_image_1))
      <div class="flyer-area">
        <img src="{{ asset('storage/' . $event->reservation_image_1) }}" alt="チラシ画像1">
      </div>
    @endif

    @if (!empty($event->reservation_image_2))
      <div class="flyer-area">
        <img src="{{ asset('storage/' . $event->reservation_image_2) }}" alt="チラシ画像2">
      </div>
    @endif

  </div>
@endif

    </div>

    {{-- =========================
      予約入力部分
    ========================= --}}
    <div class="reservation-body">

      {{-- お名前 --}}
      <div class="form-row">
        <label class="form-label">
          お名前 <span class="required">*</span>
        </label>
        <input type="text" name="name" class="text-input" placeholder="フルネームでご記入下さい">
      </div>

      {{-- ふりがな --}}
      <div class="form-row">
        <label class="form-label">ふりがな</label>
        <input type="text" name="kana" class="text-input" placeholder="ひらがなでご記入下さい">
      </div>

      {{-- 公演日時 --}}
<div class="form-row schedule-row">
  <label class="form-label">
    公演日時 <span class="required">*</span>
  </label>

  <select name="schedule_id" class="select-input schedule-select" required>
    <option value="">選択してください</option>
    @foreach ($event->schedules as $schedule)
      <option value="{{ $schedule->id }}">
        {{ \Carbon\Carbon::parse($schedule->start_time)->format('Y/m/d H:i') }}
        （{{ $schedule->venue }}）
      </option>
    @endforeach
  </select>
</div>

      {{-- 券種・枚数 --}}
      <div class="form-row">
        <label class="form-label">
          券種・枚数 <span class="required">*</span>
        </label>

        <div class="ticket-type-list">
          @foreach ($ticketTypes as $ticketType)
            <div class="ticket-type-row">
              <div class="ticket-type-name">
                {{ $ticketType->type }}
                <span class="ticket-price">（¥{{ number_format($ticketType->price) }}）</span>
              </div>

              <div class="ticket-quantity">
                <select name="tickets[{{ $ticketType->id }}]" class="quantity-select">
                  @for ($i = 0; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }} 枚</option>
                  @endfor
                </select>
              </div>
            </div>
          @endforeach
        </div>
      </div>
{{-- 支払方法 --}}
<div class="form-row">
  <label class="form-label">
    支払方法 <span class="required">*</span>
  </label>

  @if($event->allow_cash)
    <label class="radio-item">
      <input type="radio" name="payment_method" value="cash" required>
      当日精算
    </label>
  @endif

  @if($event->allow_transfer)
    <label class="radio-item">
      <input type="radio" name="payment_method" value="transfer" required>
      振込
    </label>
  @endif
</div>

      {{-- メールアドレス --}}
      <div class="form-row">
        <label class="form-label">
          メールアドレス <span class="required">*</span>
        </label>
        <input type="email" name="email" class="text-input" placeholder="example@example.com">
        <p class="help-text">※ 予約内容の確認メールをお送りします。</p>
      </div>

      {{-- 備考 --}}
      <div class="form-row">
        <label class="form-label">備考</label>
        <textarea name="note" class="textarea-input" rows="4"
          placeholder="ご要望などがありましたらご記入ください"></textarea>
      </div>

      {{-- 送信 --}}
      <div class="form-actions">
        <button type="submit" class="submit-button">予約する</button>
      </div>

    </div>

    {{-- =========================
      予約フォーム下部
    ========================= --}}
    <div class="reservation-footer">
      @if (!empty($event->reservation_bottom_message))
        <div class="reservation-message">
          {!! nl2br(e($event->reservation_bottom_message)) !!}
        </div>
      @endif
    </div>

  </div>
</form>
