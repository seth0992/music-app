<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/music-app/global.php");
require_once(__CLS_PATH . "cls_html.php");
require_once(__CTR_PATH . "ctr_notifications.php");
require_once(__CTR_PATH . "ctr_auth.php");

$HTML = new cls_Html();
$ctr_Notifications = new ctr_Notifications();
$ctr_Auth = new ctr_Auth();

// Verificar autenticación
if (!$ctr_Auth->is_authenticated()) {
    header("Location: " . __SITE_PATH);
    exit;
}

// Marcar notificación como leída
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $notification_id = (int)$_GET['mark_read'];
    $ctr_Notifications->mark_as_read($notification_id, $ctr_Auth->get_user_id());
    header("Location: " . __SITE_PATH . "/app_core/views/notifications.php");
    exit;
}

// Obtener notificaciones del usuario actual
$notifications = $ctr_Notifications->get_notifications($ctr_Auth->get_user_id());
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        // Incluir jQuery y jQuery UI
        echo $HTML->html_js_header("https://code.jquery.com/jquery-3.6.0.min.js");
        echo $HTML->html_js_header("https://code.jquery.com/ui/1.13.2/jquery-ui.min.js");
        
        // Incluir iconos Font Awesome
        echo $HTML->html_css_header("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css", "screen");
        
        // Incluir archivo de funciones JS propio
        echo $HTML->html_js_header(__JS_PATH . "functions.js");
        
        // Incluir CSS
        echo $HTML->html_css_header(__CSS_PATH . "style.css", "screen");
    ?>
    <title>Notificaciones - MusicApp</title>
    <style>
        .notification-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            background: #f9f9f9;
            transition: all 0.3s;
        }
        
        .notification-item:hover {
            background: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .notification-unread {
            border-left: 4px solid #8e44ad;
            background: #f0e6f5;
        }
        
        .notification-date {
            color: #777;
            font-size: 0.8em;
            margin-top: 5px;
            text-align: right;
        }
        
        .notification-actions {
            text-align: right;
            margin-top: 10px;
        }
        
        .notification-content {
            margin: 10px 0;
        }
        
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            color: #8e44ad;
            text-decoration: none;
        }
        
        .back-button:hover {
            text-decoration: underline;
        }
        
        .no-notifications {
            text-align: center;
            padding: 30px;
            color: #777;
        }
    </style>
</head>
<body>
    <div id="main_box">
        <header>
            <h1 class="site-title">Music<span class="hub-text">App</span></h1>
            <p class="site-description">Tus notificaciones</p>
            
            <div class="user-info">
                <span>Bienvenido, <?php echo $ctr_Auth->get_full_name(); ?></span>
                <form method="post" action="<?php echo __SITE_PATH; ?>" class="logout-form">
                    <button type="submit" name="btn_logout" class="link-button"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
                </form>
            </div>
        </header>
        
        <div style="width: 90%; margin: 20px auto;">
            <a href="<?php echo __SITE_PATH; ?>" class="back-button"><i class="fas fa-arrow-left"></i> Volver al inicio</a>
            
            <h2 class="section-title">Tus notificaciones</h2>
            
            <?php if (empty($notifications)): ?>
                <div class="no-notifications">
                    <i class="fas fa-bell-slash" style="font-size: 3em; margin-bottom: 15px;"></i>
                    <p>No tienes notificaciones aún.</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="notification-item <?php echo ($notification['is_read'] == 0) ? 'notification-unread' : ''; ?>">
                        <div class="notification-content">
                            <?php echo nl2br(htmlspecialchars($notification['message'])); ?>
                        </div>
                        <div class="notification-date">
                            <?php 
                                $date = new DateTime($notification['created_at']);
                                echo $date->format('d/m/Y H:i:s'); 
                            ?>
                        </div>
                        <?php if ($notification['is_read'] == 0): ?>
                            <div class="notification-actions">
                                <a href="?mark_read=<?php echo $notification['id']; ?>" class="button">Marcar como leída</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <footer>
            <p>MusicHub &copy; <?php echo date('Y'); ?> - Todos los derechos reservados</p>
        </footer>
    </div>
</body>
</html>