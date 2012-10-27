<?php

require_once dirname(dirname(__FILE__)) . '/lib/bootstrap.php';

// Get the values from the GET/POST
$graphTime = !empty($_GET['time']) ? $_GET['time'] : "1h";
$graphSize = !empty($_GET['size']) ? $_GET['size'] : "700x450";
$sizeArray = Dashboard::getWidthHeight();
$graphWidth = $sizeArray[$graphSize][0];
$graphHeight = $sizeArray[$graphSize][1];

$title = "MQ Queue Length";
$template = new GraphContainer($graphTime, $title);
$template->setGraphTime($graphTime);

    
/*
 * <h1>Web Cluster (<?php echo Dashboard::displayTime($time) ?>)</h1>
 */
        
{
    $graphName = "king-prod-mq01 queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=*,host=sac-prod-mq-01.unix.newokl.com}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-mq02 queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=*,host=sac-prod-mq-02.unix.newokl.com}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-mq01 email queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-email-changed,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-share-product,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-share-curated-event,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-e-gift-card,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-password-changed,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-return-request,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-share-sales-event,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-reset-password,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-invite-accepted,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-vendor-preview-notify,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-order-confirmation,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-invite-email,host=sac-prod-mq-01.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-manual-return-request,host=sac-prod-mq-01.unix.newokl.com}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "king-prod-mq02 email queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-email-changed,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-share-product,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-share-curated-event,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-e-gift-card,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-password-changed,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-return-request,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-share-sales-event,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-reset-password,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-invite-accepted,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-vendor-preview-notify,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-order-confirmation,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-invite-email,host=sac-prod-mq-02.unix.newokl.com}');
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=email_send-manual-return-request,host=sac-prod-mq-02.unix.newokl.com}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "prod_backoffice queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=prod_backoffice,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


{
    $graphName = "catalog_item-insert queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=catalog_item-insert,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "salesevent_event-update queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=salesevent_event-update,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "salesevent_manage-period queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=salesevent_manage-period,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "account_customer-create queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=account_customer-create,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}

{
    $graphName = "account_customer-update queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=account_customer-update,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}


{
    $graphName = "order_order-insert queue length";
    $tsd = new Tsd($graphTime);
    $tsd->addMetric('sum:1m-sum:queue.rabbitmq.length{cluster=mq,queue=order_order-insert,host=*}');
    $template->addGraph($tsd->getDashboardHTML($graphWidth, $graphHeight), $graphName);
}








$template->render();