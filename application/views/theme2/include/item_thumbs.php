 <div class="homeSingle_item <?= is_image($row['shop_id']); ?> style__2">
 	<?php if (is_image($row['shop_id']) == 0) : ?>
 		<div class="homeSingleImg menu-img img bg_loader" data-src="<?= get_img($row['thumb'], $row['img_url'], $row['img_type']); ?>" style="background: url(<?= img_loader(); ?>);">
 			<?php if (isset($row['veg_type']) && $row['veg_type'] != 0) : ?> <i class="fa fa-circle veg_type <?= $row['veg_type'] == 1 ? 'c_green' : 'c_red'; ?> style2" data-placement="top" data-toggle="tooltip" title="<?= veg_type($row['veg_type']); ?>"></i><?php endif; ?>
 		</div>
 	<?php endif; ?>

 	<div class="homeItemDetails list_view is_size_0">
 		<div class="homeItem_left">
 			<div class="topTitle">
 				<?php if ($row['is_size'] == 0) : ?>
 					<p class="mb-4 is_size_0"><?= currency_position($row['price'], $shop_id); ?> </p>
 				<?php endif; ?>
 				<h4><?= html_escape($row['title']); ?></h4>
 			</div>
 			<div class="price_section">
 				<p>
 					<?php if (isJson($row['allergen_id'])) : ?>
 						<span class="capital fz-13"><?= !empty(lang('allergens')) ? lang('allergens') : 'allregens'; ?>: <?= is_array(json_decode($row['allergen_id'])) ? allergens(json_decode($row['allergen_id'])) : ''; ?></span>
 					<?php endif; ?>
 				</p>
 				<p class="details">
 					<?= character_limiter(html_escape($row['overview']), 120); ?>
 				</p>

 			</div>
 			<div class="port_d_flex home_view">
 				<a href="javascript:;" class="quick_view" data-id="<?= html_escape($row['item_id']); ?>" data-placement="top" data-toggle="" title="Quick View"><i class="icofont-eye-open"></i> <?= lang('details'); ?></a>

 				<?php if (shop($row['shop_id'])->is_cart == 1) : ?>
 					<?php $extra = $this->common_m->get_item_extras($row['item_id']); ?>
 					<?php if (shop($row['shop_id'])->stock_status == 1) : ?>

 						<?php if ($row['in_stock'] > $row['remaining']) : ?>
 							<a href="javascript:;" class="<?= (isset($extra->is_extra) && $extra->is_extra == 1) || $row['is_size'] == 1 ? "quick_view" : "add_to_cart"; ?>" data-id="<?= html_escape($row['item_id']); ?>" data-type="item" data-placement="top" data-toggle="" title="Add to Cart"><i class="icofont-ui-cart"></i> <?= lang('order_now'); ?></a>
 						<?php endif; ?>

 					<?php else : ?>
 						<a href="javascript:;" class="<?= (isset($extra->is_extra) && $extra->is_extra == 1) || $row['is_size'] == 1 ? "quick_view" : "add_to_cart"; ?>" data-id="<?= html_escape($row['item_id']); ?>" data-type="item" data-placement="top" data-toggle="" title="Add to Cart"><i class="icofont-ui-cart"></i> <?= lang('order_now'); ?></a>

 					<?php endif; ?>
 				<?php endif; ?>
 			</div>
 		</div>
 		<div class="homeItem_right is_size_0">
 			<?php if ($row['is_size'] == 0) : ?>
 				<p><?= currency_position($row['price'], $shop_id); ?> </p>
 			
 			<?php endif; ?>
 		</div>

 	</div>


 </div>