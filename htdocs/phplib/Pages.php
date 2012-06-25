<?php

require_once "TimingUtils.php";

class Pages {
    const PERFORMANCE_EXPECTATION = 800;

    const FAST_PERFORMANCE_EXPECTATION = 400;

    const STATIC_PERFORMANCE_EXPECTATION = 200;

    const WEB_PREFIX = "page";

    const ATLAS_PREFIX = "page_atlas";

    /** @var array */
    public static $INVALID_METHODS = array();

    /** @var array */
    public static $RETIRED_METHODS = array();

    /** @var array */
    public static $CATEGORIZED_METHODS = array(
        'About' => array(
            'about.index',
            'about.team',
            'corporate.about',
        ),
        'Activity' => array(
            'your.activity.index',
            'your.activity.list',
            'your.activity.similar_stories',
            'your.activity.your_circle_grid',
            'your.activity.check',
            'your.activity.comment',
            'your.activity.delete_comment',
            'your.activity.subjects_overlay',
            'your.activity.suppress',
            'your.activity.suppress_for_user',
        ),
        'Convos' => array(
            'conversations.contacts_autocomplete',
            'conversations.convo_recent_usernames_ajax',
            'conversations.index',
            'conversations.js_error',
            'conversations.new',
            'conversations.view',
            'convos_util.all',
            'convos_util.get_convo_image',
        ),
        'Help' => array(
            'help.article',
            'help.contact',
            'help.index',
            'help.search',
            'help.topics',
            'help.attachment',
            'help.test',
        ),
        'Listing' => array(
            'listing',
            'categories.index',
        ),
        'Profile' => array(
            'people.profile',
        ),
        'Search' => array(
            'search_results',
            'search_results_shops',
        ),
        'Search Ads' => array(
            'search_ads.create_campaign',
        ),
        'Seller Onboarding' => array(
            'your.shops.create',
            'your.shops.select_currency',
            'your.shops.preview',
            'your.shops.open',
            'register_seller1',
            'register_seller2',
            'register_seller_confirm',
        ),
        'Shops' => array(
            'shop.available',
            'shop.sold',
            'shop.searchresults',
            'shop.policy',
            'shop.report',
            'shop.rss',
        ),
        'Shop Stats' => array(
            'your.shops.stats.overview',
            'your.shops.stats.listing',
            'your.shops.stats.section',
            'your.shops.stats.sources',
            'your.shops.stats.searches',
            'your.shops.stats.ads',
        ),
        'Teams: General' => array(
            'teams.beta',
            'teams.calendar',
            'teams.create',
            'teams.grant',
            'teams.home',
            'teams.list',
            'teams.migrate',
            'teams.search',
            'teams.searchRedirect',
            'teams.validate',
            'teams.fellowships',
        ),
        'Teams: Team' => array(
            'teams.team.delete',
            'teams.team.edit',
            'teams.team.flag',
            'teams.team.forum_board',
            'teams.team.forum_new_thread',
            'teams.team.forum_search',
            'teams.team.forum_thread',
            'teams.team.invite',
            'teams.team.inviteMigrated',
            'teams.team.member.action',
            'teams.team.member.member',
            'teams.team.members',
            'teams.team.respond',
            'teams.team.restore',
            'teams.team.subscribe',
            'teams.team.team',
        ),
        'Top Five' => array(
            'listing',
            'shop.available',
            'people.profile',
            'search_results',
            'index',
        ),
        'Treasuries' => array(
            'treasurysouth.index',
            'people.treasury',
        ),
        'Your Account' => array(
            'your.account.communication',
            'your.purchases.index',
            'your.account.addresses',
            'your.account.cards',
            'your.account.gift-card-balance',
            'your.account.payments',
            'your.account.preferences',
            'your.account.privacy',
            'your.account.settings',
            'your.account.connections.auth',
            'your.account.connections.callback',
            'your.account.connections.info',
            'your.account.connections.remove',
            'your.profile.index',
            'your.profile.make_favorites_public',
        ),
        'Your Account: Billing' => array(
            'billing_main',
            'your.account.billing',
            'billing_all_statements',
            'billing_how_billing_works',
            'billing_make_payment',
            'billing_make_payment_paypal',
            'billing_payment_confirmation',
            'billing_payment_error',
            'billing_paypal_error',
            'billing_paypal_pending',
            'billing_paypal_unverified',
            'billing_statement',
            'billing_statement_csv',
        ),
        'Your Account: Cases' => array(
            'your.cases.case',
            'your.cases.cases',
            'your.cases.case.messages',
            'your.cases.image_upload',
            'your.cases.reported_against',
            'your.cases.reported_by_you',
            'your.cases.reported_closed',
            'your.cases.reported_open',
            'your.cases.view_attachment',
        ),
        'Your Account: Payments' => array(
            'your.account.payments.disburse',
            'your.account.payments.page',
            'your.account.payments.payment',
            'your.account.payments.ssn',
            'your.account.payments.tou',
            'your.account.payments.disbursements.bank',
            'your.account.payments.disbursements.bank_verify',
            'your.account.payments.onboarding.address',
            'your.account.payments.onboarding.bank',
            'your.account.payments.onboarding.tou',
            'your.account.payments.onboarding.verify',
        ),
        'Your Shop' => array(
            'your.etsy_mini',
            'your.shops.analytics',
            'your.shops.appearance',
            'your.shops.clear_stale_translation_nag',
            'your.shops.coupons',
            'your.shops.create',
            'your.shops.currency',
            'your.shops.custshop',
            'your.shops.custshop_customize',
            'your.shops.custshop_update',
            'your.shops.export',
            'your.shops.languages',
            'your.shops.name',
            'your.shops.open',
            'your.shops.options',
            'your.shops.payments',
            'your.shops.policies',
            'your.shops.preview',
            'your.shops.select_currency',
            'your.shops.status',
            'your.shops.syndication',
            'your.shops.tax',
            'your.shops.tax_redirect',
            'your.shops.vacation',
            'shop.rearrange',
            'shop.rearrange_listings',
            'shop.rearrange_save',
        ),
        'Your Shop: Listings' => array(
            'your.listings.index',
            'your.listings.draft',
            'your.listings.inactive',
            'your.listings.expired',
            'your.listings.sold',
            'your.listings.featured',
            'your.listings.create',
            'your.listings.copy',
            'your.listings.edit',
            'your.listings.renew_sold',
            'your.shops.sections',
            'your.sections.contents',
        ),
        'Your Shop: Orders' => array(
            'your.orders.sold',
            'your.orders.sold_csv',
            'your.orders.cancel',
            'your.orders.cancel_confirm',
            'your.orders.receipt',
            'your.orders.refund',
            'your.orders.report',
        ),
        'Your Shop: Shipping' => array(
            'your.shops.shipping',
            'your.orders.submit_tracking',
            'your.orders.shipping_notification_preview',
            'your.orders.validate_provider',
            'your.orders.validate_tracking',
            'your.orders.tracking',
            'your.shops.shipping.batch',
            'your.shops.shipping.create',
            'your.shops.shipping.edit',
            'your.shipping.view',
        ),
    );

    /** @var array */
    public static $CATEGORIZED_ATLAS_METHODS = array(
        'Compass' => array(
            'compass.assign',
            'compass.draft',
            'compass.flash_message.index',
            'compass.get_bodies',
            'compass.inbox.index',
            'compass.message',
            'compass.send',
            'compass.state_change',
            'compass.tasktool.broker',
        ),
        'Index' => array(
            'index'
        ),
        'Funnels' => array(
            'funnels'
        ),
        'Members' => array(
            'members.connected_accounts.delete',
            'members.connected_accounts.index',
            'members.connections.extended',
            'members.connections.index',
            'members.connections.prints',
            'members.notes.index',
            'members.notes.note',
            'members.payments.disable',
            'members.payments.enable',
            'members.payments.identity_verification',
            'members.payments.shop_payment_account',
            'members.payments.unlink_bank_account',
            'members.shipping.panel',
            'members.shipping.rebuild_metric',
            'members.shipping.rebuild_score',
            'members.ab_tests',
            'members.account_changes',
            'members.action_history',
            'members.api_key_tokens',
            'members.api_tokens',
            'members.bill_summary',
            'members.billing_admin_statement',
            'members.billing_information',
            'members.buyer_settings',
            'members.carts',
            'members.cases',
            'members.circle',
            'members.coupons',
            'members.creditcards',
            'members.devices',
            'members.dismissed_notices',
            'members.email_delivery_status',
            'members.email_status',
            'members.emails',
            'members.feedback',
            'members.flags',
            'members.force_logout',
            'members.forums_and_blogs_mute',
            'members.gavel',
            'members.geo',
            'members.giftcards',
            'members.index',
            'members.listings',
            'members.loginas',
            'members.mailing_lists',
            'members.member',
            'members.newsfeed',
            'members.objects',
            'members.payments',
            'members.payments_log',
            'members.realname',
            'members.refresh_buy_count',
            'members.reset_password',
            'members.sales_tax',
            'members.shard',
            'members.shipping_templates',
            'members.shop_stats',
            'members.switch_email',
            'members.syndication',
            'members.threads',
            'members.threads_lookup',
            'members.transactions',
        ),
        'Shipping' => array(
            'shipping.email.lookup',
            'shipping.labels.admin_history',
            'shipping.labels.audit',
            'shipping.labels.block',
            'shipping.labels.dashboard',
            'shipping.labels.index',
            'shipping.labels.quotas',
            'shipping.labels.refund_label',
            'shipping.labels.update_image_expiration',
            'shipping.labels.view',
            'shipping.labels.view_all',
            'shipping.labels.void_label',
            'shipping.metric.lookup',
            'shipping.metric.segments',
            'shipping.score.lookup',
            'shipping.buyer_undelivered',
            'shipping.events',
            'shipping.providers',
            'shipping.scores',
            'shipping.tracking',
        ),
        'Shops' => array(
            'shops.close',
            'shops.events',
            'shops.name',
            'shops.namechanges',
            'shops.refresh_listing_count',
            'shops.refresh_sold_count',
            'shops.shard_check',
            'shops.shops',
        ),
    );

    /**
     * @param string $prefix
     * @param string $parent
     * @return array
     */
    public static function getAllMethods($prefix = self::WEB_PREFIX, $parent = '') {
        $methods = GraphiteHelper::fetchChildMetrics($parent ? "stats.timers.$prefix.$parent." : "stats.timers.$prefix.");

        $full_methods = array();

        if (in_array("all", $methods)) {
            if ($parent && !in_array($parent, array_merge(self::$INVALID_METHODS, self::$RETIRED_METHODS))) {
                $full_methods[] = $parent;
            }
        } else {
            foreach ($methods as $method) {
                $full_method = $parent ? "$parent.$method" : $method;
                $full_methods = array_merge($full_methods, self::getAllMethods($prefix, $full_method));
            }
        }

        return $full_methods;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public static function getAllAjaxMethods($prefix = self::WEB_PREFIX) {
        $methods = self::getAllMethods($prefix);

        $full_methods = array();

        foreach ($methods as $method) {
            $child_methods = GraphiteHelper::fetchChildMetrics("stats.timers.$prefix.$method.");

            if (in_array("ajax", $child_methods)) {
                if ($method && !in_array($method, array_merge(self::$INVALID_METHODS, self::$RETIRED_METHODS))) {
                    $full_methods[] = $method;
                }
            }
        }

        return $full_methods;
    }

    /**
     * @return array
     */
    public static function getUncategorizedMethods() {
        return TimingUtils::getUncategorizedMethods(self::getAllMethods(self::WEB_PREFIX), self::$CATEGORIZED_METHODS);
    }

    /**
     * @return array
     */
    public static function getUncategorizedAtlasMethods() {
        return TimingUtils::getUncategorizedMethods(self::getAllMethods(self::ATLAS_PREFIX), self::$CATEGORIZED_ATLAS_METHODS);
    }

    /**
     * @param string $prefix
     * @param array $methods
     * @param string $average
     * @param float $expectation
     * @param array $children
     * @return array
     */
    public static function buildAllPerformanceGraphs($prefix, $methods, $average, $expectation, $children = array()) {
        $performance_graphs = array();

        foreach ($methods as $method) {
            $title = str_replace('.', '/', $method) . '.php';
            $performance_graphs[] = TimingUtils::buildPie(
                "$title<br/>Device Type",
                $prefix,
                array($method),
                GraphConstants::SEVEN_GRAPH_WIDTH,
                array('desktop', 'mobile'));
            $performance_graphs[] = TimingUtils::buildPie(
                "$title<br/>User Type",
                $prefix,
                array($method),
                GraphConstants::SEVEN_GRAPH_WIDTH,
                array('logged_in', 'logged_out', 'admin'));
            $performance_graphs[] = TimingUtils::buildPie(
                "$title<br/>Method",
                $prefix,
                array($method),
                GraphConstants::SEVEN_GRAPH_WIDTH,
                array('GET', 'POST', 'HEAD'));
            $performance_graphs[] = TimingUtils::buildCountGraph(
                $title,
                $prefix,
                array($method),
                GraphConstants::FOUR_GRAPH_WIDTH,
                $children);
            $performance_graphs[] = TimingUtils::buildAverageGraph(
                $title,
                $prefix,
                array($method),
                $average,
                $expectation,
                GraphConstants::FOUR_GRAPH_WIDTH,
                $children);
        }

        return $performance_graphs;
    }

    /**
     * @param array $methods
     * @param string $average
     * @param float $expectation
     * @return array
     */
    public static function buildAllAtlasPerformanceGraphs($methods, $average, $expectation) {
        $prefix = self::ATLAS_PREFIX;

        $performance_graphs = array();

        foreach ($methods as $method) {
            $title = str_replace('.', '/', $method) . '.php';
            $performance_graphs[] = TimingUtils::buildCountGraph(
                $title,
                $prefix,
                array($method),
                GraphConstants::FOUR_GRAPH_WIDTH);
            $performance_graphs[] = TimingUtils::buildAverageGraph(
                $title,
                $prefix,
                array($method),
                $average,
                $expectation,
                GraphConstants::FOUR_GRAPH_WIDTH);
            $performance_graphs[] = TimingUtils::buildPie(
                "HTTP Method",
                $prefix,
                array($method),
                GraphConstants::FOUR_GRAPH_WIDTH,
                array('GET', 'POST', 'HEAD'));
            $performance_graphs[] =
                array(
                    'type' => 'graphite_percentage',
                    'title' => 'AJAX Calls',
                    'numerator_metrics' => array("stats.timers.$prefix.$method.ajax.count"),
                    'denominator_metrics' => array("stats.timers.$prefix.$method.all.count"),
                    'height' => GraphConstants::HEIGHT + 86,
                    'width' => GraphConstants::FOUR_GRAPH_WIDTH,
                );
        }

        return $performance_graphs;
    }

    /**
     * @param array $methods
     * @return array
     */
    private static function buildParameterGraphs($methods) {
        $performance_graphs = array();

        foreach ($methods as $method) {
            $title = str_replace('.', '/', $method) . '.php';
            $get_params = GraphiteHelper::fetchChildMetrics("stats.page_params.$method.get.*");

            $get_metrics = array();

            foreach ($get_params as $get_param) {
                $get_metrics[] = "stats.page_params.$method.get.$get_param";
            }

            $post_params = GraphiteHelper::fetchChildMetrics("stats.page_params.$method.post.*");

            $post_metrics = array();

            foreach ($post_params as $post_param) {
                $post_metrics[] = "stats.page_params.$method.post.$post_param";
            }

            $performance_graphs[] = TimingUtils::buildGraphitePie(
                "$title<br/>GET Params",
                $get_metrics,
                $get_params,
                GraphConstants::FOUR_GRAPH_WIDTH);
            $performance_graphs[] = TimingUtils::buildGraphite(
                "$title<br/>GET Params",
                $get_metrics,
                $get_params,
                GraphConstants::FOUR_GRAPH_WIDTH);
            $performance_graphs[] = TimingUtils::buildGraphitePie(
                "$title<br/>POST Params",
                $post_metrics,
                $post_params,
                GraphConstants::FOUR_GRAPH_WIDTH);
            $performance_graphs[] = TimingUtils::buildGraphite(
                "$title<br/>POST Params",
                $post_metrics,
                $post_params,
                GraphConstants::FOUR_GRAPH_WIDTH);
        }

        return $performance_graphs;
    }
}
