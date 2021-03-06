<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class ControllerDashboardOrder extends Controller {
    public function index() {
        $this->load->language('dashboard/order');

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_view'] = $this->language->get('text_view');

        $data['token'] = $this->session->data['token'];

        // Total Orders
        $this->load->model('sale/order');
        
        $today = $this->model_sale_order->getTotalOrders(array('filter_date_added' => date('Y-m-d', strtotime('-1 day'))));

        $yesterday = $this->model_sale_order->getTotalOrders(array('filter_date_added' => date('Y-m-d', strtotime('-2 day'))));

        $difference = $today - $yesterday;

        if ($difference && $today) {
            $data['percentage'] = round(($difference / $today) * 100);
        } else {
            $data['percentage'] = 0;
        }
        
        $order_total = $this->model_sale_order->getTotalOrders();
        
        if ($order_total > 1000000000000) {
            $data['total'] = round($order_total / 1000000000000, 1) . $this->language->get('trillion_suffix');
        } elseif ($order_total > 1000000000) {
            $data['total'] = round($order_total / 1000000000, 1) . $this->language->get('billion_suffix');
        } elseif ($order_total > 1000000) {
            $data['total'] = round($order_total / 1000000, 1) . $this->language->get('million_suffix');
        } elseif ($order_total > 1000) {
            $data['total'] = round($order_total / 1000, 1) . $this->language->get('thousand_suffix');
        } else {
            $data['total'] = $order_total;
        }
        
        $data['order'] = $this->url->link('sale/order', 'token=' . $this->session->data['token'], 'SSL');

        return $this->load->view('dashboard/order.tpl', $data);
    }
}