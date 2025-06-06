
<div class="col-md-12">
    <div class="paymentOrder">
        <ul class="paymentUl">
            <?php $p = price_details($payment['shop_id'], $payment) ?>
            <?php if (sizeof($this->cart->contents()) > 0) : ?>
                <?php $i = 1;
                foreach ($this->cart->contents() as $key => $row) : ?>

                    <?php $shop_id = $row['shop_id']; ?>
                    <li>
                        <div class="pay_size_top">
                            <div class="">
                                <span><?= html_escape($row['qty']); ?></span> x <span><?= html_escape($row['name']); ?> <span class="bg-primary-soft badge sizeText"><?= isset($row['sizes']['size_slug']) && !empty($row['sizes']['size_slug']) ? variants($row['sizes']['size_slug'], $row['item_id'], $row['shop_id']) : ''; ?></span>
                                </span>
                            </div>
                            <div class="sizes">
                                <span class="extrasTitleCard">
                                    <?php if (isset($row['is_extras']) && $row['is_extras'] == 1 && !empty($row['extra_id'])) : ?>
                                        <?php foreach (json_decode($row['extra_id'], true) as $ex) : ?>
                                            <?php echo $extras_name =  rtrim('+' . $this->common_m->get_extras_name($ex, $row['item_id']) . '+', '+'); ?>
                                        <?php endforeach ?>
                                    <?php endif; ?>

                                </span>
                            </div>
                        </div>
                        <div class="">
                            <span><?= currency_position($this->cart->format_number($row['subtotal']), $shop_id); ?></span>
                        </div>
                    </li>
                <?php $i++;
                endforeach; ?>
            <?php endif; ?>
            <?php $shop_info = $this->common_m->shop_info($shop_id); ?>

        </ul>
        <ul class="paymentSummary">
            <li>
                <div class="pleft">
                    <?= lang('sub_total'); ?>
                </div>
                <div class="pright">
                    <?= currency_position($p->subtotal, $shop_id); ?> </span>
                </div>
            </li>

            <li>
                <div class="pleft">
                    <?= lang('shipping'); ?>
                </div>
                <div class="pright">
                    <?php if ($p->shipping != 0) : ?>

                        <p> <span class="d_charge"><?= currency_position($p->shipping, $shop_id); ?></span></p>
                    <?php else : ?>
                        <p> <span class="d_charge"><?= !empty(lang('Free')) ? lang('Free') : 'Free'; ?></span></p>
                    <?php endif; ?>
                </div>
            </li>

            <?php if (!empty($p->tax_details)) : ?>
                <?php foreach ($p->tax_details as  $key => $tax) : ?>
                    <li>
                        <div class="pleft">
                            <?= lang('tax'); ?><span class="text-sm"> <?= tax($tax['percent'], $tax['tax_status']); ?>
                            </span> :
                        </div>
                        <div class="pright">
                            <p> <span class="d_charge"><?= currency_position($tax['total_price'], $shop_id); ?></span>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>

            <?php else : ?>
                <li>
                    <div class="pleft">
                        <?= lang('tax'); ?>
                    </div>
                    <div class="pright">
                        <p> <span class="d_charge"><?= currency_position($p->tax_fee, $shop_id); ?></span>
                        </p>
                    </div>
                </li>
            <?php endif; ?>

            <?php if ($p->tips != 0) : ?>
                <li>
                    <div class="pleft">
                        <?= lang('tips'); ?>
                    </div>
                    <div class="pright">
                        <p> <span class="d_charge"><?= currency_position($p->tips, $shop_id); ?></span></p>
                    </div>
                </li>
            <?php endif; ?>

            <?php if ($p->discount != 0) : ?>
                <li>
                    <div class="pleft">
                        <?= lang('discount'); ?>
                    </div>
                    <div class="pright">
                        <p> <span class="d_charge"><?= currency_position($p->discount, $shop_id); ?></span>
                        </p>
                    </div>
                </li>
            <?php endif; ?>


            <?php if ($p->coupon_discount != 0) : ?>
                <li>
                    <div class="pleft">
                        <?= lang('coupon_discount'); ?>
                    </div>
                    <div class="pright">
                        <p> <span class="d_charge"><?= currency_position($p->coupon_discount, $shop_id); ?></span>
                        </p>
                    </div>
                </li>
            <?php endif; ?>

            <?php if ($p->service_charge != 0) : ?>
                <li>
                    <div class="pleft">
                        <?= lang('service_charge'); ?>
                    </div>
                    <div class="pright">
                        <p> <span class="d_charge"><?= currency_position($p->service_charge, $shop_id); ?></span>
                        </p>
                    </div>
                </li>
            <?php endif; ?>



            <li class="pt-7 bb-top">
                <div class="pleft">
                    <strong><?= lang('total'); ?></strong>
                </div>
                <div class="pright">
                    <?= currency_position($p->grand_total, $shop_id); ?> </span>
                </div>
            </li>
        </ul>

    </div><!-- paymentOrder -->
</div><!-- col-12 -->