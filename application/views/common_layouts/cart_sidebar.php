<?php $time = $this->common_m->get_single_appoinment(__week(), isset($shop_id) ? $shop_id : 0); ?>
<div class="cart_heading">
    <h4><?= lang('my_cart'); ?> </h4>
    <span class="cartItemList cartActive"><a class="" href="javascript:;"><i class="icofont-close-line-squared fa-2x c_red"></i></a></span>
</div><!-- cart_heading -->
<div class="cart_body">
    <?php if (isset($time) && !empty($time)) : ?>
        <?php if (is_feature($id, 'order') == 1 && is_active($id, 'order')) : ?>
            <?php
            $total = $this->common_m->count_table_shop_id($shop['id'], 'order_user_list');
            $limit = limit($id, 0);
            ?>
            <?php if ($limit != 0 && $total >= $limit) :  ?>
                <div class="top_cart_order">
                    <div class="limit_msg">
                        <i class="fa fa-frown fa-3x"></i>
                        <h4><?= lang('maximum_order_alert'); ?></h4>
                        <a href="<?= url('contacts/' . $slug); ?>" class="btn btn-info custom_btn mt-15"><?= lang('contact_us'); ?></a>
                    </div>
                </div>
            <?php else : ?>
                <?php if (check_shop_open_status($shop_id) == 1) : ?>
              
                    <?php $isActive = 1; ?>
                    <div class="cart_item_list">
                        <?php if (__cart()->shop_id == $shop_id) : ?>
                            <ul class="cartItems">
                                <?php include APPPATH . 'views/layouts/ajax_cart_item.php'; ?>
                            </ul>
                        <?php else : ?>
                            <div class="conflictText">
                                <h4><?= lang('start_new_cart'); ?></h4>
                                <p><?= lang('your_cart_alreay_contains_items_from'); ?>
                                    <?php if (isset($shop)) : ?>

                                        <?= $shop['name'] ?? $shop['usernae']; ?>
                                    <?php else : ?>
                                        <?= lang('others'); ?>

                                    <?php endif ?>
                                </p>
                                <p><?= lang('would_you_like_to_clear_the_cart'); ?></p>
                            </div>

                        <?php endif; ?>

                    </div>
                <?php else : ?>
                    <div class="top_cart_order">
                        <div class="limit_msg">
                            <i class="fa fa-frown fa-3x"></i>
                            <h4><?= lang('today_remaining_off'); ?></h4>
                            <div class="offCartBtn">
                                <a href="#availableDays" data-toggle="modal" class="btn btn-info  mt-15"> <i class="fa fa-calendar-minus-o"> </i> <?= lang('available_days'); ?></a>
                                <a href="<?= url('contacts/' . $slug); ?>" class="btn btn-secondary mt-15"><?= lang('contact_us'); ?></a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><!-- check_shop_open_status -->
            <?php endif; ?><!-- limit -->
        <?php else : ?>
            <div class="top_cart_order">
                <div class="limit_msg">
                    <i class="fa fa-frown fa-3x"></i>
                    <h4><?= lang('sorry_cant_take_order'); ?></h4>
                    <a href="<?= url('contacts/' . $slug); ?>" class="btn btn-info custom_btn mt-15"><?= lang('contact_us'); ?></a>
                </div>
            </div>
        <?php endif; ?><!-- isFeature -->
    <?php else : ?>
        <div class="top_cart_order">
            <div class="limit_msg">
                <i class="fa fa-frown fa-3x"></i>
                <h4><?= lang('today_remaining_off'); ?></h4>
                <a href="<?= url('contacts/' . $slug); ?>" class="btn btn-info custom_btn mt-15"><?= lang('contact_us'); ?></a>
            </div>
        </div>
    <?php endif; ?> <!-- sset($time) && !empty($time) -->
</div><!-- cart_body -->
<?php if (isset($isActive) && $isActive == 1) : ?>
    <div class="cart_footer">
        <?php if (__cart()->shop_id == $shop_id) : ?>
            <div class="sub_total_list">
                <h4>
                    <div class="sidebarQty"> <span class="cart_count"><?= $this->cart->total_items(); ?></span>
                    </div>

                    <div class="sidebarAmoutn">
                        <?= lang('price'); ?> <span class="total_price"><?= currency_position($this->cart->total(), $shop['id']); ?></span>
                    </div>
                </h4>
            </div>

            <a href="<?= url('checkout/' . $slug); ?>" class="btn btn-info btn-block order-btn"><?= !empty(lang('checkout')) ? lang('checkout') : 'Checkout'; ?></a>
        <?php else : ?>
            <a href="<?= base_url("profile/clear_cart"); ?>" class="btn btn-info btn-block order-btn"><?= !empty(lang('new_cart')) ? lang('new_cart') : 'New Cart'; ?></a>
        <?php endif; ?>
    </div>
<?php endif; ?>