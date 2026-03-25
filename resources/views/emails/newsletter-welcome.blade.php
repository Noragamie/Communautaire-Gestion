<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
    <h2 style="color: #2d6a4f;">Bienvenue dans notre newsletter !</h2>
    <p>Merci de vous être abonné à notre newsletter.</p>
    <p>Vous recevrez désormais nos dernières actualités économiques directement dans votre boîte mail.</p>
    <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
    <p style="font-size: 12px; color: #666;">
        Vous ne souhaitez plus recevoir nos emails ?
        <a href="{{ route('newsletter.unsubscribe', $unsubscribeToken) }}" style="color: #666;">Se désabonner</a>
    </p>
</body>
</html>
