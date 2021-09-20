<?php

namespace Chuckbe\ChuckcmsModuleOrderForm\Chuck;

use ChuckSite;
use Illuminate\Http\Request;
use Chuckbe\Chuckcms\Models\Module;

class SettingsRepository
{
    public function __construct()
    {

    }

    /**
     *  Get all the settings
     *  @var string
     */
    public function get()
    {

    }

    public function update(Request $values)
    {
        $module = Module::where('slug', 'chuckcms-module-order-form')->firstOrFail();
        $json = $module->json;
        $settings = [];

        $settings['form']['display_images'] = (bool)$values->input('form.display_images');
        $settings['form']['display_description'] = (bool)$values->input('form.display_description');
        $settings['form']['page'] = $values->input('form.page');

        $settings['cart']['use_ui'] = (bool)$values->input('cart.use_ui');

        $settings['order']['has_minimum_order_price'] = (bool)$values->input('order.has_minimum_order_price');
        $settings['order']['minimum_order_price'] = (int)$values->input('order.minimum_order_price');
        $settings['order']['legal_text'] = $values->input('order.legal_text');
        $settings['order']['promo_check'] = (bool)$values->input('order.promo_check');
        $settings['order']['promo_text'] = $values->input('order.promo_text');
        $settings['order']['payment_upfront'] = (bool)$values->input('order.payment_upfront');
        $settings['order']['payment_description'] = $values->input('order.payment_description');
        $settings['order']['redirect_url'] = $values->input('order.redirect_url');

        $settings['emails']['send_confirmation'] = (bool)$values->input('emails.send_confirmation');
        $settings['emails']['confirmation_subject'] = $values->input('emails.confirmation_subject');
        $settings['emails']['send_notification'] = (bool)$values->input('emails.send_notification');
        $settings['emails']['from_email'] = $values->input('emails.from_email');
        $settings['emails']['from_name'] = $values->input('emails.from_name');
        $settings['emails']['to_email'] = $values->input('emails.to_email');
        $settings['emails']['to_name'] = $values->input('emails.to_name');
        $settings['emails']['to_cc'] = is_null($values->input('emails.to_cc')) ? false : $values->input('emails.to_cc');
        $settings['emails']['to_bcc'] = is_null($values->input('emails.to_bcc')) ? false : $values->input('emails.to_bcc');

        $settings['delivery']['same_day'] = (bool)$values->input('delivery.same_day');
        $settings['delivery']['same_day_until_hour'] = (int)$values->input('delivery.same_day_until_hour');
        $settings['delivery']['next_day'] = (bool)$values->input('delivery.next_day');
        $settings['delivery']['next_day_until_hour'] = (int)$values->input('delivery.next_day_until_hour');
        $settings['delivery']['google_maps_api_key'] = $values->input('delivery.google_maps_api_key');

        $settings['pos']['ticket_logo'] = $values->input('pos.ticket_logo');

        $json['admin']['settings'] = $settings;
        $module->json = $json;
        $module->update();

        return $module;
    }
}