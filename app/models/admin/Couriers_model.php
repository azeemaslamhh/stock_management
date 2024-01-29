<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Couriers_model extends CI_Model {

    private $api_key;
    private $api_password;
    private $api_url;

    public function __construct() {
        parent::__construct();
        $this->api_key = 'C2027AB2E6D1601DE7BC73B7DEECA906';
        $this->api_password = 'BIGBASKET@045';
        $this->api_url = 'http://new.leopardscod.com/webservice';
    }

    public function getLeopardsPaymentStatus($cn_numbers, $order_id) {
        try {

            $url = $this->api_url . "/getPaymentDetails/format/json/?api_key=";
            $url = $url . $api_key . "&api_password=" . $api_password . "&cn_numbers=" . $cn_numbers;
            $res = $this->getFrequest($url);
            $resArray = json_decode($res, true);
            if ($resArray['error'] == 0 && $resArray['status'] == 1) {
                $payment_list = $resArray['payment_list'];
                if ($payment_list[0]['status'] == 'Paid') {
                    $query = $this->db->get_where('sales', $condition);
                    /* $row = $query->row();
                      $saleRow = sale::find($order_id);
                     */
                    /* $this->db->where(array('id'=>$order_id));
                      $data = array(

                      );
                      $this->db->update('sales', $data);
                      sale::where('id', $order_id)->update(array(
                      'payment_status' => 4,
                      'paid_amount' => $saleRow->grand_total
                      ));
                     */
                    // Log::info("order id= ".$order_id);
                } else {
                    /* sale::where('id', $order_id)->update(array(
                      'payment_status' => 1,
                      'paid_amount' => 0,
                      ));
                     */
                }
            }
            return $resArray;
        } catch (Exception $e) {

            Log::info("Caught exception in getLeopardsPaymentStatus:" . $e->getMessage());
        }
    }

    public function getLeopardscodStatus($order_id, $reference_no, $courier_id, $trackingNO = "") {
        // Log::info('woocommerce order id:'.$order_id);
        $api_key = 'C2027AB2E6D1601DE7BC73B7DEECA906'; //config('couriers.leopardscod.api_key');
        $api_password = 'BIGBASKET@045'; //config('couriers.leopardscod.api_password');
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->api_url . '/getShipmentDetailsByOrderID/format/json/');  // Write here Production Link
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
            'api_key' => $this->api_key,
            'api_password' => $this->api_password,
            'shipment_order_id' => array($order_id), // E.g. array('Order Id') OR  array('Order Id-1', 'Order Id-2', 'Order Id-3')
        )));
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $resArray = json_decode($buffer, true);
        //Log::info("resArray");
        //Log::info($resArray);
        if (isset($resArray['data'][0])) {
            $orderDetail = $resArray['data'][0];
            $shipment_address = isset($orderDetail['shipment_address']) ? $orderDetail['shipment_address'] : "N/A";

            if ($orderDetail['booked_packet_status'] == 'Delivered') {


                //$this->updateDeliveredOrderStatus($order_id, $reference_no, $courier_id, $shipment_address, $trackingNO);
            } else if ($orderDetail['booked_packet_status'] == 'Returned to shipper') {
                //$this->returnOrder($order_id);
            }
            if (isset($orderDetail['booking_date'])) {
                /* $booking_date = $orderDetail['booking_date'];
                  $booking_date = date("Y-m-d", strtotime($booking_date));
                  $rsOrder = DB::table('sales')->where('woocommerce_order_id', $order_id);
                  if ($rsOrder->count() > 0) {
                  $kioretailOrderID = $rsOrder->value('id');
                  $rsDelivered = DB::table('deliveries')->where('sale_id', $kioretailOrderID);
                  if ($rsDelivered->count() > 0) {
                  Delivery::where('sale_id', $kioretailOrderID)->update(array(
                  'booking_date' => $booking_date
                  ));
                  } else {
                  $objDelivery = new Delivery();
                  $objDelivery->fill(array(
                  'reference_no' => $reference_no,
                  'sale_id' => $kioretailOrderID,
                  'user_id' => 1,
                  'courier_id' => $courier_id,
                  'address' => $shipment_address,
                  'tracking_no' => $trackingNO,
                  'booking_date' => $booking_date
                  ));
                  $objDelivery->save();
                  }
                  }
                 */
                //Delivery::
            }
        }
        return $resArray;
    }

    public function getLeopardsDeliveryStatus($order_id) {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->api_url . '/getShipmentDetailsByOrderID/format/json/');  // Write here Production Link
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
            'api_key' => $this->api_key,
            'api_password' => $this->api_password,
            'shipment_order_id' => array($order_id), // E.g. array('Order Id') OR  array('Order Id-1', 'Order Id-2', 'Order Id-3')
        )));
        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $resArray = json_decode($buffer, true);
        return $resArray;
    }

    public function BookedLeopardscodCourier($order_id) {
        $order = $this->getSingleRow('sales', array('id' => $order_id));
        $addressRow = $this->getSingleRow('addresses', array('id' => $order->address_id));
        $customer = $this->getSingleRow('companies', array('id' => $order->customer_id));

        $qty = $this->getTotalItemsByOrderId($order_id);
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $this->api_url . '/bookPacket/format/json/');  // Write here Test or Production Link
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);

        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
            'api_key' => $this->api_key,
            'api_password' => $this->api_password,
            'booked_packet_weight' => 100, // Weight should in 'Grams' e.g. '2000'
            'booked_packet_no_piece' => $qty, // No. of Pieces should an Integer Value
            'booked_packet_collect_amount' => $order->grand_total, // Collection Amount on Delivery
            'booked_packet_order_id' => $order_id, // Optional Filed, (If any) Order ID of Given Product
            'origin_city' => "Lahore",
            'destination_city' => $addressRow->city,
            'shipment_id' => 1,
            'shipment_name_eng' => 'self', // Params: 'self' or 'Type any other Name here', If 'self' will used then Your Company's Name will be Used here
            'shipment_email' => 'self', // Params: 'self' or 'Type any other Email here', If 'self' will used then Your Company's Email will be Used here
            'shipment_phone' => 'self', // Params: 'self' or 'Type any other Phone Number here', If 'self' will used then Your Company's Phone Number will be Used here
            'shipment_address' => 'string', // Params: 'self' or 'Type any other Address here', If 'self' will used then Your Company's Address will be Used here
            'consignment_name_eng' => $customer->name, // Type Consignee Name here
            'consignment_email' => $customer->email, // Optional Field (You can keep it empty), Type Consignee Email here
            'consignment_phone' => $customer->phone, // Type Consignee Phone Number here
            'consignment_address' => $customer->address, // Type Consignee Address here
            'special_instructions' => $customer->address, // Type any instruction here regarding booked packet
        )));

        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $status = 'fail';
        $res = json_decode($buffer, true);
        if (isset($res['status']) && $res['status'] == 1) {
            if (isset($res['track_number']) && $res['track_number'] != "") {
                $this->db->where(array('id' => $order_id));
                $data = array(
                    'booked_date' => date("Y-m-d"),
                    'tracking_no' => $res['track_number'],
                );
                $this->db->update('sales', $data);
                $status = 'success';
            }
        }
        return $status;
    }

    public function cancelLeopardOrderBooking($order_id) {
        $order = $this->getSingleRow('sales', array('id' => $order_id));
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $this->api_url . '/cancelBookedPackets/format/json/');  // Write here Test or Production Link
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_POST, 1);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
            'api_key' => $this->api_key,
            'api_password' => $this->api_password,
            'cn_numbers' => $order->tracking_no, // E.g. 'XXYYYYYYYY' OR 'XXYYYYYYYY,XXYYYYYYYY,XXYYYYYY' 10 Digits each number
        )));

        $buffer = curl_exec($curl_handle);
        curl_close($curl_handle);
        $res = json_decode($buffer, true);
        $status = 'fail';
        if (isset($res['status']) && $res['status'] == 1) {
            $this->db->where(array('id' => $order_id));
            $data = array(
                'booked_date' => NULL,
                'tracking_no' => "",
            );
            $this->db->update('sales', $data);
            $status = 'success';
        }
        return $status;
    }

    public function getSingleRow($table, $condition) {
        $query = $this->db->get_where($table, $condition);
        return $query->row();
    }

    public function getTotalItemsByOrderId($order_id) {
        $this->db->select_sum('quantity'); // Assuming you have a 'quantity' column for items in your order
        $this->db->where('sale_id', $order_id);
        $query = $this->db->get('sale_items'); // Change 'order_items' to your actual table name

        $result = $query->row();
        return $result->quantity;
    }

}
