<style>
    body {
        background-color: #e9ecef
    }

    .card {
        position: relative;
        display: flex;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        border: 1px solid rgba(0, 0, 0, .05);
        background-color: #fff;
        background-clip: border-box;
        border-radius: 20px;
        padding-top: 10px;
        padding: 30px;
    }

    .mb-5 {
        margin-bottom: 5px !important;
    }

    .text-danger-m1 {
        color: #dd4949 !important;
    }

    .text-primary-m1 {
        color: #4087d4 !important;
    }

    .priceTag {
        color: #777;
    }

    .extars ul {
        padding-left: 0;
    }

    .btn {
        font-size: .875rem;
        position: relative;
        transition: all .15s ease;
        letter-spacing: .025em;
        text-transform: none;
        will-change: transform;
    }

    table.customTable.invoiceTable th {
        background-color: #5e72e4 !important;
        color: #fff !important;
    }

    td p {
        text-transform: capitalize;
    }

    .orderQr img {
        height: 100px;
        width: 100px;
        margin: 0 auto;
        text-align: center;
        margin-top: 5px;
        margin-left: 28px;
    }

    .orderQr {
        width: 100%;
        margin: 0 auto;
        text-align: center;
        margin-top: -37px;
    }

    @media print {
        a[href]:after {
            content: none !important;
        }

    }

    @page {
        size: auto;
        margin: 0;
    }
</style>
<?php $shop_id = $order_info['shop_id']; ?>
<div class="container">
    <div class="page-content">
        <div class="page-header text-blue-d2">
            <h1 class="display-4"></h1>
            <div class="page-tools">

                <div class="action-buttons no-print">
                    <?php if (check() == 1) : ?>
                        <a href="javascript:;" id="pos-print" class="btn bg-white btn-light mx-1px text-95" data-title="Print">
                            <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                            <?= !empty(lang('pos_print')) ? lang('pos_print') : "POS Print"; ?>
                        </a>
                    <?php endif; ?>

                    <a href="javascript:;" id="printBtn" class="btn bg-white btn-light mx-1px text-95" data-title="Print">
                        <i class="mr-1 fa fa-print text-primary-m1 text-120 w-2"></i>
                        <?= !empty(lang('print')) ? lang('print') : "Print"; ?>
                    </a>
                    <?php if (check() == 1) : ?>
                        <a id="exportBtn" href="javascript:;" class="btn bg-white btn-light mx-1px text-95" data-title="PDF">
                            <i class="mr-1 fa fa-file-pdf-o text-danger-m1 text-120 w-2"></i>
                            <?= !empty(lang('export')) ? lang('export') : "Export"; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="card" id="print_area">
            <div class="row mt-4">
                <div class="col-12 col-lg-12">

                    <div class="row">
                        <div class="col-8">
                            <h4><?= lang('order_id'); ?> #<?= $order_info['uid']; ?> </h4>
                            <?php if ($order_info['is_payment'] == 1) : ?>
                                <span class="badge badge-success"><?= lang('paid'); ?></span>
                            <?php else : ?>
                                <span class="badge badge-danger"><?= lang('unpaid'); ?></span>
                            <?php endif; ?>

                            &nbsp; <small class="text-muted"><?= full_time($order_info['created_at']); ?></small><br />
                            <small class="text-muted"><?= lang('order_type'); ?>:
                                <?= order_type($order_info['order_type']); ?>
                                <?php if ($order_info['order_type'] == 4) : ?>
                                    ,
                                    <?= cl_format(!empty($order_info['pickup_date']) ? $order_info['pickup_date'] : $order_info['created_at']); ?>
                                    : <?= slot_time_format($order_info['pickup_time'], $shop_id); ?>
                                <?php endif; ?>
                            </small>

                            <?php if (isset($order_info['table_no']) && !empty($order_info['table_no'])) : ?>
                                <small class="text-muted">
                                    &nbsp; <?= lang('table'); ?> :
                                    <?= __table($order_info['table_no'])->area_name; ?> /
                                    <?= __table($order_info['table_no'])->name; ?>
                                </small>
                            <?php endif; ?>

                            <?php if (isset($order_info['order_type']) && $order_info['order_type'] == 8) : ?>
                                <?php if (isset($order_info['hotel_id']) && !empty($order_info['hotel_id'])) : ?>
                                    <p class="ml-0">
                                        <small class="text-muted">
                                            <?= lang('hotel_name') ?> :
                                            <b><?= single_select_by_id($order_info['hotel_id'], 'hotel_list')['hotel_name'];; ?></b>
                                            &nbsp;
                                            <?= lang('room_number') ?> : <b><?= $order_info['room_number'] ?></b>
                                        </small>
                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-4">
                            <div class="orderQr">
                                <?php if (!empty($order_info['qr_link'])) : ?>
                                    <img src="<?= base_url($order_info['qr_link']); ?>" alt="">
                                <?php endif ?>
                            </div>
                        </div>

                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="customerInfo">
                                <p class="text-muted">
                                    <b><?= !empty(lang('customer_info')) ? lang('customer_info') : "Customer Info"; ?></b>
                                </p>
                                <?php if ($order_info['order_type'] == 7) : ?>
                                    <p><?= lang('walk_in_customer'); ?></p>
                                <?php else : ?>

                                    <?php if ($order_info['is_guest_login'] == 1) : ?>
                                        <p><b><?= lang('walk_in_customer'); ?></b></p>
                                    <?php else : ?>

                                        <h3><?= $order_info['name']; ?></h3>
                                        <p><?= lang('phone'); ?> : <?= $order_info['phone']; ?></p>
                                        <?php if (!empty($order_info['address'])) : ?>
                                            <p><?= lang('address'); ?> : <?= $order_info['address']; ?></p>
                                        <?php endif; ?>
                                        <?php if ($order_info['order_type'] == 1) : ?>
                                            <?php $shpping_info = $this->common_m->delivery_area_by_shop_id($order_info['shipping_id'], $shop_id); ?>
                                            <?php if (isset($shpping_info['area']) && !empty($shpping_info['area'])) : ?>
                                                <p><?= lang('delivery_area'); ?> : <span style="color:rebeccapurple;"><?= $shpping_info['area']; ?></span></p>
                                            <?php endif; ?>
                                            <?php if (!empty($order_info['delivery_area'])) : ?>
                                                <p><?= lang('shipping_address'); ?> : <span style="color: purple;"><?= $order_info['delivery_area']; ?></span></p>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <!-- order_type -->
                                    <?php endif; ?>
                                    <!-- is_guest_login -->
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- /.col -->

                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">

                            <div class="customerInfo">
                                <h2><?= !empty($shop->name) ? $shop->name : $shop->username; ?></h2>
                                <p>
                                    <?= lang('phone'); ?> : <?= !empty($shop->phone) ? $shop->phone : ""; ?>
                                    <br />
                                    <?= lang('email'); ?> : <?= !empty($shop->email) ? $shop->email : ""; ?>
                                    <br />
                                    <?php if (!empty($shop->address)) : ?>
                                        <?= lang('address'); ?> : <?= !empty($shop->address) ? $shop->address : ""; ?>
                                        <br />
                                    <?php endif; ?>
                                    <?php if (isset($shop->tax_number) && !empty($shop->tax_number)) : ?>
                                        <?= lang('tax_number'); ?> : <?= !empty($shop->tax_number) ? $shop->tax_number : ""; ?>
                                        <br />
                                    <?php endif; ?>
                                </p>
                            </div>


                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="mt-4">

                        <div class="table-responsive">
                            <?php if ($order_info['order_type'] == 7) : ?>
                                <?php $get_dinein_items = $this->admin_m->get_dinin_items($order_info['dine_id']); ?>
                                <table class="table customTable invoiceTable" id="example1">
                                    <thead>
                                        <tr class="text-600 text-white bgc-default-tp1 py-25">
                                            <th>#</th>
                                            <th><?= lang('name'); ?></th>
                                            <th><?= lang('items'); ?></th>
                                            <th><?= lang('qty'); ?></th>
                                            <th><?= !empty(lang('unit_price')) ? lang('unit_price') : "Unit Price"; ?></th>
                                            <th><?= !empty(lang('amount')) ? lang('amount') : "Amount"; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($get_dinein_items as $key => $package) : ?>
                                            <tr>
                                                <td><?= $key + 1; ?></td>
                                                <td><?= $package['package_name']; ?></td>
                                                <td>
                                                    <ul class="p-0">
                                                        <?php foreach ($package['all_items'] as $key => $item) : ?>
                                                            <li class="space-between"><span>1 x <?= $item['title']; ?> </span>
                                                                <span><?= currency_position($item['item_price'], $shop_id); ?></span>
                                                            </li>
                                                        <?php endforeach; ?>

                                                    </ul>
                                                </td>
                                                <td>1</td>
                                                <td><?= currency_position($order_info['total'], $shop_id); ?></td>
                                                <td><?= currency_position($order_info['total'], $shop_id); ?></td>

                                            </tr>
                                        <?php endforeach; ?>
                                        <tr>
                                            <td colspan="4">
                                                <?php if ($order_info['use_payment'] == 1 && !empty($order_info['payment_by'])) : ?>
                                                    <p><?= lang('digital_payment'); ?>
                                                        (<b><?= lang($order_info['payment_by']); ?></b>)</p>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <p><?= lang('sub_total'); ?></p>

                                                <p><b><?= lang('total'); ?></b></p>
                                            </td>
                                            <td>
                                                <p><b><?= currency_position($order_info['total'], $shop_id); ?></b></p>

                                                <p><b><?= currency_position($order_info['total'], $shop_id); ?></b></p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            <?php else : ?>

                                <table class="table customTable invoiceTable" id="example1">
                                    <thead>
                                        <tr class="text-600 text-white bgc-default-tp1 py-25">
                                            <th>#</th>
                                            <th><?= lang('name'); ?></th>
                                            <th><?= lang('qty'); ?></th>
                                            <th><?= !empty(lang('unit_price')) ? lang('unit_price') : "Unit Price"; ?></th>
                                            <th><?= !empty(lang('amount')) ? lang('amount') : "Amount"; ?></th>
                                        </tr>
                                    </thead>


                                    <tbody>


                                        <?php $p = __order($order_info, $item_list); ?>

                                        <?php foreach ($item_list as $key => $row) : ?>
                                            <tr>
                                                <td><?= $key + 1; ?></td>
                                                <td>
                                                    <p><?= $row['is_package'] == 1 ? $row['package_name'] : $row['name']; ?>

                                                        <?php if ($row['is_size'] == 1) : ?>
                                                            <span class="badge sizeTag"><?= variants($row['size_slug'], $row['item_id'], $row['shop_id']); ?></span>
                                                        <?php endif; ?>
                                                    </p>
                                                    <?= __taxStatus(shop($shop_id)->is_tax, $row); ?>

                                                    <?php if (isset($row['is_extras']) && $row['is_extras'] == 1) : ?>
                                                        <div class="extars">
                                                            <?= extraList($row['extra_id'], $row['extra_qty'], $row['item_id'], $shop_id); ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if (isset($row['item_comments']) && !empty($row['item_comments'])) : ?>
                                                        <?php if (isset(pos_config($shop_id)->is_comments) && pos_config($shop_id)->is_comments == 1) : ?>
                                                            <p style="margin-top:5px; color:#777; width:60%;"><?= $row['item_comments']; ?></p>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $row['qty']; ?></td>
                                                <td><?= currency_position($row['item_price'], $shop_id); ?> </td>
                                                <td><?= currency_position($row['item_price'] * $row['qty'], $shop_id); ?></td>
                                            </tr>
                                        <?php endforeach ?>


                                        <tr>
                                            <td colspan="3">
                                                <?php if ($order_info['use_payment'] == 1 && !empty($order_info['payment_by'])) : ?>
                                                    <p><?= lang('digital_payment'); ?>
                                                        (<b><?= lang($order_info['payment_by']); ?></b>)</p>
                                                <?php endif; ?>
                                            </td>


                                            <td>
                                                <p><?= lang('sub_total'); ?></p>
                                                <p><?= lang('shipping'); ?></p>
                                                <?php if (!empty($p->tax_details)) : ?>
                                                    <?php foreach ($p->tax_details as  $key => $tax) : ?>
                                                        <p><?= lang('tax'); ?> <span class="text-sm"><?= tax($tax['percent'], $tax['tax_status']); ?></span>
                                                        </p>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <?php if ($p->tax_fee != 0) : ?>
                                                        <p><?= lang('tax'); ?></p>
                                                    <?php endif ?>
                                                <?php endif; ?>

                                                <?php if ($p->tips != 0) : ?>
                                                    <p><?= lang('tips'); ?></p>
                                                <?php endif ?>

                                                <?php if ($p->discount != 0) : ?>
                                                    <p><?= lang('discount'); ?></p>
                                                <?php endif ?>

                                                <?php if ($p->coupon_percent != 0) : ?>
                                                    <p><?= lang('coupon_discount'); ?></p>
                                                <?php endif ?>

                                                <?php if ($p->service_charge != 0) : ?>
                                                    <p><?= lang('service_charge'); ?></p>
                                                <?php endif ?>

                                                <p><b><?= lang('total'); ?></b></p>
                                            </td>
                                            <td>
                                                <p><b><?= currency_position($p->subtotal, $shop_id); ?></b></p>

                                                <p><?= $p->shipping == 0 ? currency_position(0, $shop_id) : currency_position($p->shipping, $shop_id); ?>
                                                </p>



                                                <?php if (!empty($p->tax_details)) : ?>
                                                    <?php foreach ($p->tax_details as  $key => $tax) : ?>
                                                        <p><?= currency_position($tax['total_price'], $shop_id); ?> </p>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <?php if ($p->tax_fee != 0) : ?>
                                                        <p><?= currency_position($p->tax_fee, $shop_id); ?> </p>
                                                    <?php endif ?>
                                                <?php endif; ?>

                                                <?php if ($p->tips != 0) : ?>
                                                    <p><?= currency_position($p->tips, $shop_id); ?> </p>
                                                <?php endif; ?>

                                                <?php if ($p->discount != 0) : ?>
                                                    <p><?= currency_position($p->discount, $shop_id); ?> </p>
                                                <?php endif; ?>

                                                <?php if ($p->coupon_percent != 0) : ?>
                                                    <p><?= currency_position($p->coupon_percent, $shop_id); ?> </p>
                                                <?php endif; ?>
                                                <?php if ($p->service_charge != 0) : ?>
                                                    <p><?= currency_position($p->service_charge, $shop_id); ?> </p>
                                                <?php endif; ?>

                                                <p><b><?= currency_position($p->grand_total, $shop_id); ?></b> </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php if (isset($order_info['comments']) && !empty($order_info['comments'])) : ?>
                                        <?php if (isset(pos_config($shop_id)->is_comments) && pos_config($shop_id)->is_comments == 1) : ?>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5"> <?= $order_info['comments'] ?></td>
                                                </tr>
                                            </tfoot>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif ?>
                                </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>