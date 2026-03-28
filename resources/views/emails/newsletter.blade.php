<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>{{ $actuality->title }}</h2>
    <div style="line-height: 1.6; color: #333;">{!! \App\Support\Markdown::toHtml($actuality->content) !!}</div>
    @if($actuality->image)
        <img src="{{ asset('storage/'.$actuality->image) }}" alt="{{ $actuality->title }}" style="max-width: 100%; margin: 20px 0;">
    @endif
    <hr style="margin: 30px 0;">
    <p style="font-size: 12px; color: #666;">
        <a href="{{ route('newsletter.unsubscribe', $unsubscribeToken) }}">Se désabonner</a>
    </p>
</body>
</html>
