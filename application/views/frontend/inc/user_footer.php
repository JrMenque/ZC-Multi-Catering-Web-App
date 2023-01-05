<?php $user_settings = u_settings($id); ?>
  <div class="footer_area">
  <?php if (isset($user_settings['is_footer']) && $user_settings['is_footer'] == 0) : ?>

    <div class="top_footer">
      <div class="container restaurant-container">
        <div class="row">

          <div class=" col-lg-4 col-sm-4 col-xs-12">
            <div class="left_footer">
              <div class="qrCode">
                <img src="<?= base_url(!empty($user['qr_link']) ? $user['qr_link'] : ''); ?>" alt="">
              </div>
              <h4><?= html_escape(!empty(restaurant($id)->name) ? restaurant($id)->name : restaurant($id)->username); ?></h4>

              <div class="shopFooterInfo">
                <?php if (isset($shop['phone']) && !empty($shop['phone'])) : ?>
                  <p class="phone mb-5"><i class="fas fa-phone fa-flip-horizontal phone mr-5 "></i> <a href="<?= redirect_url($shop['phone'], 'phone', $shop['dial_code']); ?>"><?= $shop['dial_code']; ?> <?= html_escape($shop['phone']); ?></a></p>
                <?php endif; ?>

                <?php if (!empty(restaurant($id)->location)) : ?>
                  <p class="address"><i class="icofont-google-map"></i> <a href="<?= redirect_url(restaurant($id)->location, 'google'); ?>"><?= restaurant($id)->address; ?></a></p>
                <?php endif; ?>


                <p><a href="<?= redirect_url(!empty(u_settings($id)['smtp_mail']) ? u_settings($id)['smtp_mail'] : html_escape($shop['email']), 'email'); ?>"><i class="icofont-email"></i> &nbsp;<?= strtolower(!empty(u_settings($id)['smtp_mail']) ? u_settings($id)['smtp_mail'] : html_escape($shop['email'])); ?></a></p>
              </div>
              <ul class="">

                <?php if (!empty($social['facebook'])) : ?>
                  <li><a href="<?= redirect_url($social['facebook'], 'facebook'); ?>"><i class="fa fa-facebook facebook"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($social['instagram'])) : ?>
                  <li><a href="<?= redirect_url($social['instagram'], 'instagram'); ?>"><i class="fa fa-instagram instagram"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($social['whatsapp'])) : ?>
                  <li><a href="<?= redirect_url($social['whatsapp'], 'whatsapp', $shop['dial_code'], url(restaurant($id)->username)); ?>"><i class="fa fa-whatsapp whatsapp"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($social['twitter'])) : ?>
                  <li><a href="<?= redirect_url($social['twitter'], 'twitter'); ?>"><i class="fa fa-twitter twitter"></i></a></li>
                <?php endif; ?>

                <?php if (!empty($social['youtube'])) : ?>
                  <li><a href="<?= $social['youtube']; ?>" class="venobox" data-autoplay="true" data-vbtype="video"><i class="fa fa-youtube youtube"></i></a></li>
                <?php endif; ?>
              </ul>
            </div>
          </div>



          <?php $days = get_days(); ?>
          <?php if (isset($days) && !empty($days)) : ?>
            <div class="col-12 col-sm-4 col-lg-4">
              <div class="left_footer">
                <h4><a href="#availableDays" class="f-color mr-10" data-toggle="modal"> <i class="fa fa-calendar-minus-o"></i></a> <?= !empty(lang('available_days')) ? lang('available_days') : "available days"; ?> </h4>
                <ul class="row_ul availableDays">
                  <?php $i = 0;
                  foreach ($days as $key => $day) : ?>
                    <?php $my_days = $this->common_m->get_single_appoinment($key, restaurant($id)->id); ?>
                    <?php $getTimes = $this->admin_m->get_time_config(@$my_days['id']); ?>
                    <?php if (isset($my_days['is_24']) && html_escape($my_days['is_24']) == 1) : ?>
                      <li>
                        <div class="flexTime">
                          <div class="timeLeft">
                            <span><?= !empty(lang($day)) ? lang($day) : $day; ?></span>
                          </div>
                          <div class="timeRight">
                            <div class="timeRightTop">
                              <span class="timeFormat"><i class="icofont-wall-clock"></i> &nbsp;<?= lang('open_24_hours'); ?></span>
                            </div>
                          </div>
                        </div>
                        <div class="timeRightBottom">
                          <?php if (isset($getTimes) && !empty($getTimes)) : ?>
                            <?php foreach ($getTimes as  $keys => $times) : ?>
                               <span class="breakTime"> <i class="icofont-close-line text-danger "></i> <span class="stime"><?= time_format($times->start_time, $shop_id); ?> </span> <i class="icofont-long-arrow-right"></i> <span class="etime"><?= time_format($times->end_time, $shop_id); ?></span></span>
                            <?php endforeach; ?>
                          <?php endif; ?> <!-- getTimes -->
                        </div>
                      </li>
                    <?php else : ?>
                      <?php if (isset($my_days['days']) && html_escape($my_days['days']) == $key) : ?>

                        <li>
                          <div class="flexTime">
                            <div class="timeLeft">
                              <span><?= !empty(lang($day)) ? lang($day) : $day; ?></span>
                            </div>
                            <div class="timeRight">
                              <div class="timeRightTop">
                                <span class="timeFormat"><i class="icofont-wall-clock"></i> &nbsp; <span class="stime"><span><?= time_format($my_days['start_time'], restaurant($id)->id); ?></span> <span class="icofont-long-arrow-right timeArrow"></span> <span><?= time_format($my_days['end_time'], restaurant($id)->id); ?></span></span></span>
                              </div>
                            </div>
                          </div>
                          <div class="timeRightBottom">
                            <?php if (isset($getTimes) && !empty($getTimes)) : ?>
                              <?php foreach ($getTimes as  $keys => $times) : ?>
                                <span class="breakTime"> <i class="icofont-close-line text-danger "></i> <span class="stime"><?= time_format($times->start_time, $shop_id); ?> </span> <i class="icofont-long-arrow-right"></i> <span class="etime"><?= time_format($times->end_time, $shop_id); ?></span></span>
                              <?php endforeach; ?>
                            <?php endif; ?> <!-- getTimes -->
                          </div>
                        </li>
                      <?php else : ?>

                        <li>
                          <div class="flexTime">
                            <div class="timeLeft">
                              <span><?= !empty(lang($day)) ? lang($day) : $day; ?></span>
                            </div>
                            <div class="timeRight">
                              <div class="timeRightTop">
                                <span class="timeFormat"><i class="icofont-close-line c_red"></i> &nbsp; <?= lang('close'); ?></span>
                              </div>
                            </div>
                          </div>
                          <div class="timeRightBottom">
                          </div>
                        </li>

                      <?php endif; ?>
                    <?php endif; ?>
                  <?php $i++;
                  endforeach; ?>
                </ul>
              </div>
            </div>
          <?php endif; ?>


          <div class=" col-6 col-sm-4 col-lg-4 sm-p-5">
            <div class="left_footer">
              <h4><?= !empty(lang('quick_links')) ? lang('quick_links') : "Quick Links"; ?></h4>
              <ul class="row_ul">
                <?php __menu($shop_id, '-', true); ?>

                <li><a href="<?= url($slug); ?>"><i class="icofont-simple-right"></i> <?= !empty(lang('home')) ? lang('home') : "home"; ?></a></li>

                <?php if (is_feature($id, 'reservation') == 1 && is_active($id, 'reservation')) : ?>
                  <li><a href="<?= url('reservation/' . $slug); ?>"><i class="icofont-simple-right"></i> <?= !empty(lang('reservation')) ? lang('reservation') : "reservation"; ?></a></li>
                <?php endif; ?>

                <li><a href="<?= url('about-us/' . $slug); ?>"><i class="icofont-simple-right"></i> <?= !empty(lang('about_us')) ? lang('about_us') : "About Us"; ?></a></li>

                <li><a href="<?= url('track-order/' . $slug); ?>"><i class="icofont-simple-right"></i> <?= !empty(lang('track_order')) ? lang('track_order') : "track order"; ?></a></li>

                <?php if (is_feature($id, 'contacts') == 1 && is_active($id, 'contacts')) : ?>
                  <li><a href="<?= url('shop-contacts/' . $slug); ?>"><i class="icofont-simple-right"></i> <?= !empty(lang('contacts')) ? lang('contacts') : "contacts"; ?></a></li>
                <?php endif; ?>

                <?php if (isset(restaurant($id)->is_review) && restaurant($id)->is_review == 1) : ?>
                  <li><a href="<?= url('shop-reviews/' . $slug); ?>"><i class="icofont-simple-right"></i> <?= !empty(lang('reviews')) ? lang('reviews') : "reviews"; ?></a></li>
                <?php endif; ?>

                <li><a href="<?= base_url('staff-login/customer'); ?>"><i class="icofont-simple-right"></i> <?= !empty(lang('login')) ? lang('login') : "Login"; ?></a></li>


              </ul>
            </div>
          </div>


        </div>
      </div>

    </div>
    <div class="footer_bottom text-center">
      <?php if (isset(restaurant($id)->is_branding) && restaurant($id)->is_branding == 1) : ?>
        <p class="created_by"><img src="<?= avatar(st()->logo, 'item'); ?>" alt=""> <?= lang('created_by'); ?> <a href="<?= base_url(""); ?>"><?= $this->settings['site_name']; ?></a></p>
      <?php else : ?>
        <p>© <?= html_escape(!empty(restaurant($id)->name) ? restaurant($id)->name : restaurant($id)->username); ?> </p>
      <?php endif; ?>
    </div>

<?php endif ?>
  </div>


