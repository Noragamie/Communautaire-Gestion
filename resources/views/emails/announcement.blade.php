<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <h2 style="color: #2d6a4f;">{{ $announcement->title }}</h2>
    <div style="line-height: 1.6; color: #333;">{!! \App\Support\Markdown::toHtml($announcement->content) !!}</div>
    @if($announcement->image)
        <img src="{{ image_url($announcement, 'image', 'image_data') }}" alt="{{ $announcement->title }}" style="max-width: 100%; margin: 20px 0;">
    @endif
    <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
    <p style="font-size: 12px; color: #666;">
        Vous recevez cet email car vous êtes membre validé de notre plateforme.
    </p>
</body>
</html>
